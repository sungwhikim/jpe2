/* THERE ARE THREE EXTERNAL DEPENDENT VARIABLES THAT MUST BE SET FOR THIS CONTROLLER TO WORK
    1.  myName  = The name of the list to be used in messages
    2.  appData = The main data for the app in JSON
    3.  appUrl  = The path to the server to make the AJAX calls to
 */

/* app is instantiated in the myApp.js file */

app.controller('InventoryController', function($http, ListService, alertService, checkBoxService,
                                               warehouseClientSelectService, modalService, datePickerService) {
    //set object to variable to prevent self reference collisions
    var InventoryController = this;

    //set reference back to the service so the model variable scope can get passed back
    //for some strange reason, the variable gets disconnected by reference assignment.
    ListService.mainCtl = InventoryController;

    /* SET PASS THROUGH PROPERTIES */
    ListService.myName = myName;
    ListService.appUrl = appUrl;
    ListService.alerts = alertService.get();

    /* SET DATA PROPERTIES */
    InventoryController.items = appData;
    InventoryController.displayItems = [].concat(appData);
    InventoryController.newItem = {};
    InventoryController.alerts = ListService.alerts;
    InventoryController.products = productData;
    InventoryController.displayProducts = [].concat(productData);

    /* ---- SET DATA TO BE USED FOR SELECT LISTS---- */
    if( typeof warehouseClientData != "undefined" ) { InventoryController.warehouse_client = warehouseClientData; }

    /* CREATE OVER-LOADED FUNCTION TO RESET THE DATA */
    /* -- This is mostly to initialize the new item model members -- */
    ListService.resetModelPublic = function (mainController) {
        //the mainController is a circular reference back to ListController, but needs to be so due to scope reasons in JavaScript
        mainController.newItem.active = true;
    };

    /* INIT DATA */
    ListService.resetModel();

    /* SET MEMBER METHODS */
    InventoryController.selectProduct = updateMainData;
    InventoryController.saveConfirmInventory = saveConfirmInventory;
    InventoryController.saveInventory = saveInventory;
    InventoryController.addBin = addBin;
    InventoryController.deleteConfirmBin = deleteConfirmBin;
    InventoryController.deleteBin = deleteBin;
    InventoryController.newBinItem = newBinItem;

    /* CREATE PASS THROUGH FUNCTIONS */
    InventoryController.add = ListService.add;
    InventoryController.save = ListService.save;
    InventoryController.deleteConfirm = ListService.deleteConfirm;
    InventoryController.resetData = ListService.resetData;
    InventoryController.closeAlert = ListService.closeAlert;
    InventoryController.toggleCheckBox = checkBoxService.toggleCheckBox;
    InventoryController.allCheckBoxes = checkBoxService.allCheckBoxes;
    InventoryController.noneCheckBoxes = checkBoxService.noneCheckBoxes;

    /* ASSIGN DATE PICKER SERVICE AND DO ANY OVERLOADING HERE */
    InventoryController.datePicker = datePickerService;

    /* OVERLOAD REFRESH DATA FUNCTION IN WAREHOUSE CLIENT SELECTOR */
    warehouseClientSelectService.refreshData = updateProductList;

    /* Used to dynamically show the uom and variants when a new product type is selected */
    function setProductType(model_item) {
        //get and set the selected product type object
        model_item.product_type = getObjectById(InventoryController.product_types, model_item.product_type_id);
    }

    /* Updates the product list when warehouse/client is changed */
    function updateProductList() {
        //sends "processing" message to user
        ListService.setProcessingAlert();

        //clear data
        InventoryController.displayProducts.length = 0;

        //run ajax add
        $http({
            method: 'GET',
            url: ListService.appUrl + '/product-list'
        }).then(function successCallback(response) {
            //replace data
            InventoryController.products = 0;
            InventoryController.products = response.data;
            InventoryController.displayProducts = [].concat(response.data);

            //clear out main data
            InventoryController.items.length = 0;
            InventoryController.displayItems.length = 0;

            //reset original model
            ListService.resetModel();

            //clear alerts
            alertService.clear();

            //set label
            InventoryController.selectedProduct = {};
            InventoryController.selectedProduct.sku = '- select a product';
        }, function errorCallback(response) {
            //put data back
            InventoryController.displayProducts = [].concat(InventoryController.items);

            //set alert
            alertService.add('danger', 'The following error occurred in loading the data: ' + response.statusText);
        });
    }

    /* Used to update the main data when an event triggers need to refresh the main data */
    function updateMainData(product) {
        //sends "processing" message to user
        ListService.setProcessingAlert();

        //clear data
        InventoryController.displayItems.length = 0;

        //run ajax call
        $http({
            method: 'GET',
            url: ListService.appUrl + '/product-inventory/' + product.id
        }).then(function successCallback(response) {
            //replace data
            loadMainData(response.data);

            //reset original model
            ListService.resetModel();

            //clear alerts
            alertService.clear();

            //set label
            InventoryController.selectedProduct = product;
        }, function errorCallback(response) {
            //put data back
            InventoryController.displayItems = [].concat(InventoryController.items);

            //set alert
            alertService.add('danger', 'The following error occurred in loading the data: ' + response.statusText);
        });
    }

    /* PRIVATE FUNCTION USED TO UPDATE MAIN DATA */
    function loadMainData(data) {
        InventoryController.items = 0;
        InventoryController.items = data;
        InventoryController.displayItems = [].concat(data);
    }

    /* SAVE CONFIRMATION DIALOG */
    function saveConfirmInventory() {
        modalService.showModal({
            templateUrl: "/js/angular-component/modalService-save.html",
            controller: "YesNoController"
        }).then(function(modal) {
            modal.element.modal();
            modal.close.then(function(result) {
                if( result === true ) { InventoryController.saveInventory(); }
            });
        });
    }

    /* Save the inventory data */
    function saveInventory() {
        //sends "processing" message to user
        ListService.setProcessingAlert();

        //make sure product id was set.  Just in case we are in a weird state
        if( !InventoryController.selectedProduct.id ) {
            alertService.add('danger', 'A valid product was not selected.  Please select a product and try again.', true);
            return false;
        }

        //make sure only one default bin is selected
        var defaultCount = 0;
        for( var counter = 0; counter < InventoryController.items.length; counter++ ) {
            if( InventoryController.items[counter].default === true ) {
                defaultCount++;

                //if more than one default item is selected, show error
                if( defaultCount > 1 ) {
                    alertService.add('danger', 'Only one default bin can be set', true);
                    return false;
                }
            }
        }

        //since a default bin was not found, show error
        if( defaultCount == 0 ) {
            alertService.add('danger', 'Please select a default bin.', true);
            return false;
        }

        //validate the data

        //run ajax save
        $http({
            method: 'POST',
            url: ListService.appUrl + '/save/' + InventoryController.selectedProduct.id,
            data: InventoryController.items
        }).then(function successCallback(response) {
            if( response.data.errorMsg ) {
                //set alert
                alertService.clear();
                alertService.add('danger', response.data.errorMsg);
            } else {
                //load data
                loadMainData(response.data);

                //reset original model
                ListService.resetModel();

                //clear alerts
                alertService.clear();

                //set success message
                alertService.add('success', 'The inventory data has been saved');
            }
        }, function errorCallback(response) {
            //clear alerts
            alertService.clear();

            //set alert
            alertService.add('danger', 'The following error occurred in saving the data: ' + response.statusText);
        });
    }

    /* ADD NEW BIN */
    function addBin(form) {
        //reset item name
        var itemName = 'Bin';

        //set the data
        var newData = InventoryController.newItem;
        newData.product_id = InventoryController.selectedProduct.id;

        //reset form validation
        form.$setPristine();
        form.$setUntouched();

        //sends "processing" message to user
        ListService.setProcessingAlert();

        //run ajax add
        $http({
            method: 'POST',
            url: ListService.appUrl + '/new-bin',
            data: newData
        }).then(function successCallback(response) {
            //do an error check to see if this was a duplicate or something
            if( response.data.errorMsg ) {
                //set alert
                ListService.sendAlert('danger', ListService.getAlertMsg(itemName, 'added', response.data.errorMsg));
            } else {
                var id = response.data.id;

                //need to check for valid id - there could be situations where this gets screwed up
                if( !id || isNaN(id) ) {
                    ListService.sendAlert('danger', ListService.getAlertMsg(itemName, 'added', 'New id was not returned. Please verify your data and try again.'));
                } else {
                    //set alert
                    ListService.sendAlert('success', ListService.getAlertMsg(itemName, 'added', ''));

                    //add variants - we could add them in sending back the data from the backend, but
                    //it is available here.  Same difference either way, but one less db query.
                    response.data.variant1 = InventoryController.selectedProduct.variant1;
                    response.data.variant2 = InventoryController.selectedProduct.variant2;
                    response.data.variant3 = InventoryController.selectedProduct.variant3;
                    response.data.variant4 = InventoryController.selectedProduct.variant4;

                    //if it is a new default bin, change all other bins to not be the default
                    if( newData.default === true ) resetDefaultBin(response.data);

                    //add to model
                    ListService.mainCtl.items.unshift(response.data);

                    //reset original model
                    ListService.resetModel();
                }
            }
        }, function errorCallback(response) {
            console.log(response);
            //set alert
            ListService.sendAlert('danger', ListService.getAlertMsg(itemName, 'added', response.statusText));
        });
    }

    /* NEW BIN ITEM */
    function newBinItem(item) {
        //create empty new item data object
        var new_item = {
            'variant1_value' : null,
            'variant2_value' : null,
            'variant3_value' : null,
            'variant4_value' : null,
            'receive_date'   : new Date(),
            'quantity'       : 0,
            'quantity_new'   : null
        };

        //create the new bin items array if not already created to avoid error in next step.
        if( !item.new_bin_items ) { item.new_bin_items = []; }

        //add to new bin items array
        item.new_bin_items.push(new_item);
    }

    /* DELETE BIN CONFIRMATION DIALOG */
    function deleteConfirmBin(index) {
        modalService.showModal({
            templateUrl: "/js/angular-component/modalService-delete.html",
            controller: "YesNoController"
        }).then(function(modal) {
            modal.element.modal();
            modal.close.then(function(result) {
                if( result === true ) { InventoryController.deleteBin(index); }
            });
        });
    }

    /* DELETE THE DATA */
    function deleteBin(index) {
        //sends "processing" message to user
        ListService.setProcessingAlert();

        //run ajax delete
        $http({
            method: 'PUT',
            url: ListService.appUrl + '/bin/delete/' + ListService.mainCtl.displayItems[index].id
        }).then(function successCallback(response) {
            if( response.data.errorMsg ) {
                //set alert
                ListService.sendAlert('danger', ListService.getAlertMsg('Bin', 'deleted', response.data.errorMsg));
            } else {
                //set alert
                ListService.sendAlert('success', 'The bin was deleted.');

                //remove item from model
                ListService.mainCtl.displayItems.splice(index, 1);
                ListService.mainCtl.items = angular.copy(ListService.mainCtl.displayItems);

                //reset original model
                ListService.resetModel();
            }
        }, function errorCallback(response) {
            //set alert
            ListService.sendAlert('danger', ListService.getAlertMsg('Bin', 'deleted', response.statusText));
        });
    }

    /* RESETS THE DEFAULT BIN */
    function resetDefaultBin(defaultItem) {
        //main list
        for( var counter = 0; counter < InventoryController.items.length; counter++ ) {
            //don't change the selected default bin
            if( InventoryController.items[counter].id !== defaultItem.id ) {
                InventoryController.items[counter].default = false;
            }
        }

        //display list
        for( counter = 0; counter < InventoryController.displayItems.length; counter++ ) {
            //don't change the selected default bin
            if( InventoryController.displayItems[counter].id !== defaultItem.id ) {
                InventoryController.displayItems[counter].default = false;
            }
        }
    }

    /* Helper function to get object by id value */
    function getObjectById(data, id) {
        for( var i = 0; i < data.length; i++ ) {
            if( data[i].id == id ) { return data[i]; }
        }
        return {};
    }
});