/* THERE ARE THREE EXTERNAL DEPENDENT VARIABLES THAT MUST BE SET FOR THIS CONTROLLER TO WORK
    1.  myName  = The name of the list to be used in messages
    2.  appData = The main data for the app in JSON
    3.  appUrl  = The path to the server to make the AJAX calls to
 */

/* app is instantiated in the myApp.js file */

app.controller('TransactionController', function($http, ListService, alertService, checkBoxService,
                                               warehouseClientSelectService, modalService, datePickerService) {
    //set object to variable to prevent self reference collisions
    var TransactionController = this;

    //set reference back to the service so the model variable scope can get passed back
    //for some strange reason, the variable gets disconnected by reference assignment.
    ListService.mainCtl = TransactionController;

    /* SET PASS THROUGH PROPERTIES */
    ListService.myName = myName;
    ListService.baseUrl = baseUrl;
    ListService.appUrl = appUrl;
    ListService.alerts = alertService.get();

    /* SET DATA PROPERTIES */
    TransactionController.items = appData;
    TransactionController.displayItems = [].concat(appData);
    TransactionController.newItem = {};
    TransactionController.alerts = ListService.alerts;
    TransactionController.products = productData;
    //TransactionController.displayProducts = [].concat(productData);

    /* ---- SET DATA TO BE USED FOR SELECT LISTS---- */
    if( typeof warehouseClientData != "undefined" ) { TransactionController.warehouse_client = warehouseClientData; }

    /* CREATE OVER-LOADED FUNCTION TO RESET THE DATA */
    /* -- This is mostly to initialize the new item model members -- */
    ListService.resetModelPublic = function (mainController) {
        //the mainController is a circular reference back to ListController, but needs to be so due to scope reasons in JavaScript
        mainController.newItem.active = true;
    };

    /* INIT DATA */
    ListService.resetModel();

    /* SET MEMBER METHODS */
    TransactionController.saveTransaction = saveTransaction;
    TransactionController.selectProduct = selectProduct;
    TransactionController.deleteItem = deleteItem;
    TransactionController.addItem = addItem;

    /* CREATE PASS THROUGH FUNCTIONS */
    TransactionController.add = ListService.add;
    TransactionController.save = ListService.save;
    TransactionController.deleteConfirm = ListService.deleteConfirm;
    TransactionController.resetData = ListService.resetData;
    TransactionController.closeAlert = ListService.closeAlert;
    TransactionController.toggleCheckBox = checkBoxService.toggleCheckBox;
    TransactionController.allCheckBoxes = checkBoxService.allCheckBoxes;
    TransactionController.noneCheckBoxes = checkBoxService.noneCheckBoxes;

    /* ASSIGN DATE PICKER SERVICE AND DO ANY OVERLOADING HERE */
    TransactionController.datePicker = datePickerService;

    /* OVERLOAD REFRESH DATA FUNCTION IN WAREHOUSE CLIENT SELECTOR */
    warehouseClientSelectService.refreshData = changeClientWarehouse;

    /* Clears the form and loads the product list */
    function changeClientWarehouse() {
        updateProductList();
    }

    /* Updates the product list data when warehouse/client is changed */
    function updateProductList() {
        //sends "processing" message to user
        ListService.setProcessingAlert();

        //clear data
        TransactionController.displayProducts.length = 0;

        //run ajax add
        $http({
            method: 'GET',
            url: ListService.baseUrl + '/transaction/product-list'
        }).then(function successCallback(response) {
            //replace data
            TransactionController.products = 0;
            TransactionController.products = response.data;
            TransactionController.displayProducts = [].concat(response.data);

            //clear out main data
            TransactionController.items.length = 0;
            TransactionController.displayItems.length = 0;

            //reset original model
            ListService.resetModel();

            //clear alerts
            alertService.clear();

            //set label
            TransactionController.selectedProduct = {};
            TransactionController.selectedProduct.sku = '- select a product';
        }, function errorCallback(response) {
            //put data back
            TransactionController.displayProducts = [].concat(TransactionController.items);

            //set alert
            alertService.add('danger', 'The following error occurred in loading the data: ' + response.statusText);
        });
    }

    /* Get the current inventory for the product and update the model */
    function selectProduct(product) {
        /* SEND PROCESSING MESSAGE TO USER */

        //go get the product inventory and other data
        $http({
            method: 'GET',
            url: ListService.baseUrl + '/product/tx-detail/' + product
        }).then(function successCallback(response) {
            /* DISMISS MESSAGE */
//console.log(response.data);
            //replace data
            TransactionController.newItem.uoms = response.data.uoms;
            TransactionController.newItem.variants = response.data.variants;
            TransactionController.newItem.product = getObjectById(TransactionController.products, product);

            //clear out the variant data
            clearVariants(TransactionController.newItem);

        }, function errorCallback(response) {

            /* SEND MODAL ERROR MESSAGE */

            //set alert
            alertService.add('danger', 'The following error occurred in loading the data: ' + response.statusText);
        });
    }

    /* Add a line item */
    function addItem() {
        var newItem = TransactionController.newItem;

        //don't add if product and quantity was not selected
        if( isNaN(newItem.product_id) === true || isNaN(newItem.quantity) === true ) {
            /* CHANGE THIS TO MODAL DIALOG LATER */
            alert('Please select a product and enter a quantity');
            return;
        }

        //add to model
        TransactionController.items.push(TransactionController.newItem);

        //clear out the new item object
        TransactionController.newItem = {};
    }

    /* Delete a line item */
    function deleteItem(index) {
        TransactionController.items.splice(index, 1);
    }

    /* Save the transaction data */
    function saveTransaction() {

        //make sure at least one line item was added
        if( items.length == 0 ) {
            alertService.add('danger', 'Please enter product data.');
            return false;
        }

        //sends "processing" message to user
        ListService.setProcessingAlert();

        //validate the data

        //run ajax save
        $http({
            method: 'POST',
            url: ListService.appUrl + '/save/',
            data: TransactionController.items
        }).then(function successCallback(response) {
            if( response.data.errorMsg ) {
                //set alert
                alertService.clear();
                alertService.add('danger', response.data.errorMsg);
            } else {
                //reset transaction
                resetTx();

                //clear alerts
                alertService.clear();

                //set success message
                alertService.add('success', 'The data has been saved');
            }
        }, function errorCallback(response) {
            //clear alerts
            alertService.clear();

            //set alert
            alertService.add('danger', 'The following error occurred in saving the data: ' + response.statusText);
        });
    }

    /* Clear out the variant data when switching products */
    function clearVariants(model_item)
    {
        if( model_item.variant1_value ) { model_item.variant1_value = null; }
        if( model_item.variant2_value ) { model_item.variant2_value = null; }
        if( model_item.variant3_value ) { model_item.variant3_value = null; }
        if( model_item.variant4_value ) { model_item.variant4_value = null; }
    }

    /* Helper function to get object by id value */
    function getObjectById(data, id) {
        for( var i = 0; i < data.length; i++ ) {
            if( data[i].id == id ) { return data[i]; }
        }
        return {};
    }

    /* Reset the transaction */
    function resetTx() {
        TransactionController.items.length = 0;
        TransactionController.items = [];
        TransactionController.newItem = {};
    }


/*    /!* SAVE CONFIRMATION DIALOG *!/
    function saveConfirmInventory() {
        modalService.showModal({
            templateUrl: "/js/angular-component/modalService-save.html",
            controller: "YesNoController"
        }).then(function(modal) {
            modal.element.modal();
            modal.close.then(function(result) {
                if( result === true ) { TransactionController.saveInventory(); }
            });
        });
    }*/


});