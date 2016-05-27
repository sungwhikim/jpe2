/* THERE ARE THREE EXTERNAL DEPENDENT VARIABLES THAT MUST BE SET FOR THIS CONTROLLER TO WORK
    1.  appUrl = The path to the server to make the AJAX calls to
    2.  txSetting = The transaction settings like type and direction and mode
 */

/* app is instantiated in the myApp.js file */
app.controller('TransactionController', function($http, checkBoxService, modalMessageService, warehouseClientSelectService,
                                                 modalService, datePickerService, searchSelectService) {
    //set object to variable to prevent self reference collisions
    var TransactionController = this;

    /* SET PASS THROUGH PROPERTIES */
    /* CREATE PASS THROUGH FUNCTIONS */

    /* SET DATA PROPERTIES */
    TransactionController.baseUrl = baseUrl;
    TransactionController.appUrl = appUrl;
    TransactionController.txSetting = txSetting;
    TransactionController.newItem = {};
    TransactionController.txData = appData;
    TransactionController.txData.txType = txSetting.type; //we need to do this to verify po number again upon save
    TransactionController.txData.txSetting = txSetting; //this is so we can tell what the state of the tx is when saving
    TransactionController.products = productData;
    TransactionController.selectedWarehouseClient = warehouseClientSelectService.selectedData;
    TransactionController.currentBinData = {};

    /* SET SEARCH SELECT PROPERTIES */
    TransactionController.SearchSelectProduct = searchSelectService;
    TransactionController.SearchSelectProduct.items = productData;
    TransactionController.SearchSelectProduct.displayItems = angular.copy(TransactionController.SearchSelectProduct.items);
    TransactionController.SearchSelectProduct.selectCallBack = selectProduct;
    TransactionController.SearchSelectProduct.clear = searchSelectService.clear;
    TransactionController.SearchSelectProduct.searchTerm = searchSelectService.searchTerm;

    /* ---- SET DATA TO BE USED FOR SELECT LISTS---- */
    if( typeof warehouseClientData != "undefined" ) { TransactionController.warehouse_client = warehouseClientData; }
    if( typeof carrierData != "undefined" ) { TransactionController.carriers = carrierData; }

    /* SET MEMBER METHODS */
    TransactionController.saveTransaction = saveTransaction;
    TransactionController.convertTransaction = convertTransaction;
    TransactionController.voidTransaction = voidTransaction;
    TransactionController.voidTransactionCallback = voidTransactionCallback;
    TransactionController.selectProduct = selectProduct;
    TransactionController.deleteItem = deleteItem;
    TransactionController.addItem = addItem;
    TransactionController.checkPoNumber = checkPoNumber;
    TransactionController.selectUom = selectUom;
    TransactionController.resetTransaction = resetTransaction;
    TransactionController.clearProductInput = clearProductInput;
    TransactionController.showBin = showBin;
    TransactionController.checkBarcodeClient = checkBarcodeClient;

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
        if( TransactionController.txSetting.new === true ) {
            //update product and carrier lists
            updateProductList();
            updateCarrierList(TransactionController.txSetting.type);

            //update warehouse id and client id in txData
            setWarehouseClientTxData();
        }
    }

    /* Updates the product list data when warehouse/client is changed */
    function updateProductList() {
        //make ajax call to get new data
        $http({
            method: 'GET',
            url: TransactionController.baseUrl + '/transaction/product-list'
        }).then(function successCallback(response) {
            //replace data
            TransactionController.products = 0;
            TransactionController.products = response.data;
            TransactionController.SearchSelectProduct.items = response.data;
            TransactionController.SearchSelectProduct.displayItems = angular.copy(response.data);
            TransactionController.SearchSelectProduct.clear();

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
            url: TransactionController.baseUrl + '/carrier/list-by-wc/' + type
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
            url: TransactionController.baseUrl + '/transaction/check-po-number/' + TransactionController.txSetting.type,
            data: sendData
        }).then(function successCallback(response) {
            //Captured error in processing
            if( response.data.errorMsg ) {
                modalMessageService.showModalMessage('danger', response.data.errorMsg);
                return false;
            }
            //success
            else {
                var isValidPoNumber = response.data.valid_po_number;

                if( isValidPoNumber !== true ) {
                    modalMessageService.showModalMessage('danger', 'The PO Number is a duplicate.');
                }

                return isValidPoNumber;
            }
        }, function errorCallback(response) {
            //set alert
            modalMessageService.showModalMessage('danger', 'The following error occurred in checking the PO Number: ' + response.statusText);
            return false;
        });
    }

    /* Get the current inventory for the product and update the model */
    function selectProduct(product) {
        //update the input box
        TransactionController.SearchSelectProduct.searchTerm = product.sku;

        //set get inventory flag
        var txType = TransactionController.txSetting.type;
        var getInventory = (txType == 'receive' || txType == 'ship') ? '/true' : '';

        //go get the product inventory and other data
        $http({
            method: 'GET',
            url: TransactionController.baseUrl + '/product/tx-detail/' + product.id + getInventory
        }).then(function successCallback(response) {
            TransactionController.newItem = response.data;
            TransactionController.newItem.product_id = product.id;
            TransactionController.newItem.product = product;
            TransactionController.newItem.barcode_client = product.barcode_client;

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
console.log(TransactionController.txData);
        //reset
        TransactionController.newItem = {};
        TransactionController.SearchSelectProduct.clear();
    }

    /* Delete a line item */
    function deleteItem(index) {
        TransactionController.txData.items.splice(index, 1);
    }

    /* Clear the product input */
    function clearProductInput() {
        //reset the model
        TransactionController.newItem = {};

        //reset the search service
        TransactionController.SearchSelectProduct.clear();
    }

    /* Save the transaction data */
    function saveTransaction(reset, form) {
        //set form submitted to true here since we are not using form submission to process saving of data
        //as we require different parameters to be passed in by different buttons.
        form.$submitted = true;

        //validate data manually again to prevent bad data being sent to the back end.(although another check will be done in the back end)
        if( validateData() !== true ) { return false; }

        //set route for new or update
        var route = ( TransactionController.txSetting.new === true ) ? 'new' : 'update';

        //run ajax save
        $http({
            method: 'POST',
            url: TransactionController.appUrl + '/' + route,
            data: TransactionController.txData
        }).then(function successCallback(response) {
            if( response.data.errorMsg ) {
                //set alert
                modalMessageService.showModalMessage('danger', response.data.errorMsg);
            } else {
                //set success message
                modalMessageService.showModalMessage('info', 'The transaction has been saved');

                //reset transaction
                if( reset === true ) resetTransaction(form);
            }
        }, function errorCallback(response) {
            //set alert
            modalMessageService.showModalMessage('danger', 'The following error occurred in saving the data: ' + response.statusText);
        });
    }

    /* Convert the ASN Transaction */
    function convertTransaction() {

    }

    /* Void transaction dialog box */
    function voidTransaction() {

    }

    /* Void transaction callback */
    function voidTransactionCallback() {

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
    function resetTransaction(form) {
        //reset form
        form.$submitted = false;
        form.$setPristine();
        form.$setUntouched();

        //reset properties/data
        TransactionController.txData = {};
        TransactionController.txData.items = [];
        TransactionController.newItem = {};
        TransactionController.SearchSelectProduct.clear(); //clear out product select box
        TransactionController.txData.txType = txSetting.type;
        setWarehouseClientTxData(); //add back warehouse_id and client_id
    }

    /* Brings up the bin popup */
    function showBin(item) {
        //don't allow bin dialog if they haven't selected a product yet
        if( !item.product_id ) {
            modalMessageService.showModalMessage('danger', 'Please select a product first.');
            return;
        }

        //don't allow bin dialog if the quantity is not set
        if( !item.quantity || isNaN(item.quantity) === true ) {
            modalMessageService.showModalMessage('danger', 'Please enter a quantity first.');
            return;
        }

        //show bin dialog box
        modalService.showModal({
            templateUrl: "/js/angular-component/modalService-bin.html",
            controller: "BinController",
            inputs: {
                item: item
            }
        }).then(function(modal) {
            modal.element.modal();
            modal.close.then(function(result) {
            });
        });
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
        switch( TransactionController.txSetting.type) {
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
        /* MOVED TO THE BACKEND AS CHECKING IT HERE WILL REQUIRE A CALL BACK AND HAVE TO REDO SOME OF THE LOGIC FLOW */

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
        if( TransactionController.txSetting.new === true ) {
            TransactionController.txData.warehouse_id = TransactionController.selectedWarehouseClient.warehouse_id;
            TransactionController.txData.client_id = TransactionController.selectedWarehouseClient.client_id;
        }
    }

    function checkBarcodeClient() {
        var barcode = Number(TransactionController.newItem.barcode_client);

        //if nothing is selected, then just clear out the selected item and return
        if( isNaN(barcode) === true ) {
            TransactionController.txData.selectedProduct = {};
            return false;
        }

        //find the entered bar code
        var data = TransactionController.products;

        for( var counter = 0; counter < data.length; counter++ ) {
            var product = data[counter];
            if( barcode === Number(product.barcode_client) ) {
                TransactionController.txData.selectedProduct = product;
                TransactionController.selectProduct(product);
                return true;
            }
        }

        //show an error dialog since if we got here, then the barcode was not found
        TransactionController.SearchSelectProduct.clear();
        modalMessageService.showModalMessage('danger', 'The client barcode was not found.');
    }
});