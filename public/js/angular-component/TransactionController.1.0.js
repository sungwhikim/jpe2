/* THERE ARE THREE EXTERNAL DEPENDENT VARIABLES THAT MUST BE SET FOR THIS CONTROLLER TO WORK
    1.  appUrl = The path to the server to make the AJAX calls to
    2.  txType = The type of the transaction - one of the 4 main types
    3.  txDirection = receive/ship - the direction of movement of goods
    4.  txMode = Whether it is a new transaction or not and whether it is editable
 */

/* app is instantiated in the myApp.js file */

app.controller('TransactionController', function($http, ListService, alertService, checkBoxService, modalMessageService,
                                               warehouseClientSelectService, modalService, datePickerService) {
    //set object to variable to prevent self reference collisions
    var TransactionController = this;

    //set reference back to the service so the model variable scope can get passed back
    //for some strange reason, the variable gets disconnected by reference assignment.
    ListService.mainCtl = TransactionController;

    /* SET PASS THROUGH PROPERTIES */
    ListService.myName = txType;
    ListService.baseUrl = baseUrl;
    ListService.appUrl = appUrl;

    /* SET DATA PROPERTIES */
    TransactionController.txMode = txMode;
    TransactionController.txType = txType;
    TransactionController.txDirection = txDirection;
    TransactionController.txData = appData;
    TransactionController.products = productData;
    TransactionController.selectedWarehouseClient = warehouseClientSelectService.selectedData;

    /* ---- SET DATA TO BE USED FOR SELECT LISTS---- */
    if( typeof warehouseClientData != "undefined" ) { TransactionController.warehouse_client = warehouseClientData; }
    if( typeof carrierData != "undefined" ) { TransactionController.carriers = carrierData; }

    /* INIT DATA */
    ListService.resetModel();

    /* SET MEMBER METHODS */
    TransactionController.newTransaction = newTransaction;
    TransactionController.saveTransaction = saveTransaction;
    TransactionController.selectProduct = selectProduct;
    TransactionController.deleteItem = deleteItem;
    TransactionController.addItem = addItem;
    TransactionController.checkPoNumber = checkPoNumber;
    TransactionController.selectUom = selectUom;

    /* CREATE PASS THROUGH FUNCTIONS */
    TransactionController.add = ListService.add;
    TransactionController.save = ListService.save;
    TransactionController.deleteConfirm = ListService.deleteConfirm;
    TransactionController.resetData = ListService.resetData;
    TransactionController.closeAlert = ListService.closeAlert;
    TransactionController.toggleCheckBox = checkBoxService.toggleCheckBox;
    TransactionController.allCheckBoxes = checkBoxService.allCheckBoxes;
    TransactionController.noneCheckBoxes = checkBoxService.noneCheckBoxes;

    /* ASSIGN SERVICES TO ALLOW DIRECT ACCESS FROM TEMPLATE TO SERVICE */
    TransactionController.modalService = modalService;
    TransactionController.datePicker = datePickerService;

    /* OVERLOAD REFRESH DATA FUNCTION IN WAREHOUSE CLIENT SELECTOR */
    warehouseClientSelectService.refreshData = changeClientWarehouse;

    /* SET THE WAREHOUSE AND CLIENT ID IN THE txData OBJECT FOR NEW TRANSACTIONS */
    setWarehouseClientTxData();

    /* Clears the form and loads the product list */
    function changeClientWarehouse() {
        /* only allow changes the data if it is in new mode */
        if( TransactionController.txMode.new === true ) {
            //update product and carrier lists
            updateProductList();
            updateCarrierList(TransactionController.txDirection);

            //update warehouse id and client id in txData
            setWarehouseClientTxData();
        }
    }

    /* Updates the product list data when warehouse/client is changed */
    function updateProductList() {
        //make ajax call to get new data
        $http({
            method: 'GET',
            url: ListService.baseUrl + '/transaction/product-list'
        }).then(function successCallback(response) {
            //replace data
            TransactionController.products = 0;
            TransactionController.products = response.data;
            //clear out the products already added and reset new item model
            TransactionController.newItem = {};
            TransactionController.txData.items.length = 0;
            TransactionController.txData.items = [];
        }, function errorCallback(response) {
            //set alert
            modalMessageService.showModalMessage('danger', 'The following error occurred in loading the data: ' + response.statusText);
        });
    }

    /* Updates the carrier when warehouse/client is changed */
    function updateCarrierList(type) {
        //make ajax call to get new data
        $http({
            method: 'GET',
            url: ListService.baseUrl + '/carrier/list-by-wc/' + type
        }).then(function successCallback(response) {
            //replace data
            TransactionController.carriers = 0;
            TransactionController.carriers = response.data;
        }, function errorCallback(response) {
            //set alert
            modalMessageService.showModalMessage('danger', 'The following error occurred in loading the data: ' + response.statusText);
        });
    }

    /* Verifies that the PO number is unique */
    function checkPoNumber() {
        //assign to a local variable
        var po_number = TransactionController.txData.po_number;

        //check for valid value
        if( !po_number || po_number.length == 0 ) {
            modalMessageService.showModalMessage('danger', 'Please enter a valid PO Number.');
            return false;
        }

        //build data
        var sendData = getWarehouseClientId();
        sendData.po_number = po_number;

        //make ajax call
        $http({
            method: 'POST',
            url: ListService.baseUrl + '/transaction/check-po-number/' + TransactionController.txType,
            data: sendData
        }).then(function successCallback(response) {
            //Captured error in processing
            if( response.data.errorMsg ) {
                modalMessageService.showModalMessage('danger', response.data.errorMsg);
            }
            //success
            else {
                var isValidPoNumber = response.data.valid_po_number;

                if( isValidPoNumber !== true ) {
                    modalMessageService.showModalMessage('danger', 'You entered a duplicate PO number.');
                }

                return isValidPoNumber;
            }
        }, function errorCallback(response) {
            //set alert
            modalMessageService.showModalMessage('danger', 'The following error occurred in checking the PO Number: ' + response.statusText);
        });
    }

    /* Get the current inventory for the product and update the model */
    function selectProduct(product) {
        //go get the product inventory and other data
        $http({
            method: 'GET',
            url: ListService.baseUrl + '/product/tx-detail/' + product
        }).then(function successCallback(response) {
            //replace data
            TransactionController.newItem.uoms = response.data.uoms;
            TransactionController.newItem.variants = response.data.variants;
            TransactionController.newItem.selectedUom = response.data.selectedUom;
            TransactionController.newItem.product = getObjectById(TransactionController.products, product);

            //clear out the variant data
            clearVariants(TransactionController.newItem);
        }, function errorCallback(response) {
            //set alert
            modalMessageService.showModalMessage('danger', 'The following error occurred in loading the data: ' + response.statusText);
        });
    }

    /* Add a line item */
    function addItem() {
        var newItem = TransactionController.newItem;

        //don't add if product and quantity was not selected
        if( isNaN(newItem.product_id) === true || isNaN(newItem.quantity) === true ) {
            modalMessageService.showModalMessage('info', 'Please select a product and enter a valid quantity');
            return;
        }

        //add to model
        TransactionController.txData.items.push(TransactionController.newItem);

        //clear out the new item object
        TransactionController.newItem = {};
    }

    /* Delete a line item */
    function deleteItem(index) {
        TransactionController.txData.items.splice(index, 1);
    }

    /* Save the transaction data */
    function saveTransaction() {}
    function newTransaction(reset, form) {
        //set form submitted to true here since we are not using form submission to process saving of data
        //as we require different parameters to be passed in by different buttons.
        form.$submitted = true;

        //validate data manually again to prevent bad data being sent to the back end.(although another check will be done in the back end)
        if( validateData() !== true ) { return false; }

        //run ajax save
        $http({
            method: 'POST',
            url: ListService.appUrl + '/new',
            data: TransactionController.txData
        }).then(function successCallback(response) {
            if( response.data.errorMsg ) {
                //set alert
                modalMessageService.showModalMessage('danger', response.data.errorMsg);
            } else {
                //set success message
                modalMessageService.showModalMessage('info', 'The transaction has been saved');

                //reset form submit status
                form.$submitted = false;

                //reset transaction
                if( reset === true ) resetTx();
            }
        }, function errorCallback(response) {
            //set alert
            modalMessageService.showModalMessage('danger', 'The following error occurred in saving the data: ' + response.statusText);
        });
    }

    /* Clear out the variant data when switching products */
    function clearVariants(model_item) {
        if ( model_item.variant1_value ) {
            model_item.variant1_value = null;
        }
        if ( model_item.variant2_value ) {
            model_item.variant2_value = null;
        }
        if ( model_item.variant3_value ) {
            model_item.variant3_value = null;
        }
        if ( model_item.variant4_value ) {
            model_item.variant4_value = null;
        }
    }

    /* Reset the transaction */
    function resetTx() {
        TransactionController.txData = {};
        TransactionController.txData.items = [];
        TransactionController.newItem = {};
    }

    /* Selects the unit of measure(uom) */
    function selectUom(model, uom) {
        //clear out current quantity
        model.quantity = null;

        //set selected Uom
        model.selectedUom = uom.key;
    }

    /* Main data validation function.  Case switch for different transaction types */
    function validateData() {
        //check basic items
        if( validateDataBasic() !== true ) { return false; }

        //check additional items by tx type
        switch( TransactionController.txType) {
            //asn shipping
            case 'asn_ship':
                break;

            //receiving
            case 'receive':
                break;

            //shipping
            case 'ship':
                break;
        }

        //check line items
        if( validateLineItems() !== true ) { return false; }

        //if we got here, all is good
        return true;
    }

    /* The basic data in all transactions which need to be verified */
    function validateDataBasic() {
        //validate date
        if( !TransactionController.txData.tx_date )
        {
            modalMessageService.showModalMessage('danger', 'Please enter a valid date.');
            return false;
        }

        /* validate PO number */
        if( checkPoNumber() !== true ) {
            return false;
        }

        //make sure at least one line item was added
        if( TransactionController.txData.items.length == 0 ) {
            modalMessageService.showModalMessage('danger', 'Please enter the product data.');
            return false;
        }

        //if we got here, everything checked out
        return true;
    }

    /* Verify the line items */
    function validateLineItems() {
        //go through each item
        var items = TransactionController.txData.items;
        for( i = 0; i < items.length; i++ ) {
            //check for valid quantity
            if( items[i].quantity == '' || isNaN(items[i].quantity) === true ) {
                modalMessageService.showModalMessage('danger', 'Please verify that all items have a valid quantity.');
                return false;
            }
        }

        //if we got here, everything checked out
        return true;
    }

    function getWarehouseClientId() {
        var data = {};
        data.warehouse_id = TransactionController.txData.warehouse_id;
        data.client_id = TransactionController.txData.client_id;

        return data;
    }

    function setWarehouseClientTxData() {
        //only set if this is a new transaction
        if( TransactionController.txMode.new === true ) {
            TransactionController.txData.warehouse_id = TransactionController.selectedWarehouseClient.warehouse_id;
            TransactionController.txData.client_id = TransactionController.selectedWarehouseClient.client_id;
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