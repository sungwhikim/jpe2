/* THERE ARE THREE EXTERNAL DEPENDENT VARIABLES THAT MUST BE SET FOR THIS CONTROLLER TO WORK
    1.  txSetting = The transaction settings like type and direction and mode
    2.  appData = The tx data
    3.  productData = The product select list
    4.  carrierData = The carrier select list
    5.  warehouseClientData = The warehouse/client list - usually loaded in the header for the top warehouse/client select list
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
    TransactionController.txSetting = txSetting;
    TransactionController.txData = appData;
    TransactionController.txData.txSetting = txSetting; //this is so we can tell what the state of the tx is when saving
    TransactionController.products = productData;
    TransactionController.carriers = carrierData;
    TransactionController.selectedWarehouseClient = warehouseClientSelectService.selectedData;
    TransactionController.currentBinData = {};
    TransactionController.variantNone = { value:"-- none --", id:null }; //this is used to bring back inventory of items without variant selected
    initTxDate(); //sets up the initial date
    resetNewItem(); //sets the initial newItem model object properties

    /* SET SEARCH SELECT PROPERTIES */
    TransactionController.SearchSelectProduct = searchSelectService;
    TransactionController.SearchSelectProduct.items = productData;
    TransactionController.SearchSelectProduct.displayItems = angular.copy(TransactionController.SearchSelectProduct.items);
    TransactionController.SearchSelectProduct.selectCallBack = selectProduct;
    TransactionController.SearchSelectProduct.clear = searchSelectService.clear;
    TransactionController.SearchSelectProduct.searchTerm = searchSelectService.searchTerm;

    /* ---- SET DATA TO BE USED FOR SELECT LISTS---- */
    if( typeof warehouseClientData != "undefined" ) { TransactionController.warehouse_client = warehouseClientData; }
    if( typeof customerData != "undefined" ) { TransactionController.customers = customerData; }
    if( typeof countryData != "undefined" ) { TransactionController.countries = countryData; }

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
    TransactionController.selectVariantShip = selectVariantShip;
    TransactionController.pickList = pickList;
    TransactionController.shippingMemo = shippingMemo;
    TransactionController.newCustomer = newCustomer;

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
            updateCustomerList();

            //update warehouse id and client id in txData
            setWarehouseClientTxData();
        }

        //if the user has tried to change it when in edit mode, throw an error
        else {
            modalMessageService.showModalMessage('danger', 'Please do not change the warehouse/client when editing an existing transaction.');
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
            resetNewItem();
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

    /* Updates the customer list when warehouse/client is changed */
    function updateCustomerList() {
        //make ajax call to get new data
        $http({
            method: 'GET',
            url: TransactionController.baseUrl + '/customer/list-by-wc'
        }).then(function successCallback(response) {
            //replace data
            TransactionController.customers = 0;
            TransactionController.customers = response.data;
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
        /* Send txType now.  This is because the return data varies more by type than anything else
        var txType = TransactionController.txSetting.type;
        var getInventory = (txType == 'receive' || txType == 'ship') ? '/true' : '';
        */

        //go get the product inventory and other data
        $http({
            method: 'GET',
            url: TransactionController.baseUrl + '/product/tx-detail/' + product.id + '/' + TransactionController.txSetting.type
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
        //do data validation for shipping transaction item. Don't do for ASN Ship as we can request more than
        //what we have in stock at the moment.
        if( TransactionController.txSetting.type == 'ship' && validateNewItemShip() === false ) { return; }

        var newItem = TransactionController.newItem;

        //don't add if product and quantity was not selected or less than 0
        if( isNaN(newItem.product_id) === true || isNaN(newItem.quantity) === true || newItem.quantity < 1 ) {
            modalMessageService.showModalMessage('info', 'Please select a product and enter a valid quantity');
            return;
        }

        //add to model
        TransactionController.txData.items.push(TransactionController.newItem);
console.log(TransactionController.txData);
        //reset
        resetNewItem();
        TransactionController.SearchSelectProduct.clear();
    }

    /* Delete a line item */
    function deleteItem(index) {
        TransactionController.txData.items.splice(index, 1);
    }

    /* Clear the product input */
    function clearProductInput() {
        //reset the model
        resetNewItem();

        //reset the search service
        TransactionController.SearchSelectProduct.clear();
    }

    /* Save the transaction data */
    function saveTransaction(reset, form, callback) {
        //set form submitted to true here since we are not using form submission to process saving of data
        //as we require different parameters to be passed in by different buttons.
        form.$submitted = true;

        //validate data manually again to prevent bad data being sent to the back end.(although another check will be done in the back end)
        if( validateData() !== true ) { return false; }

        //set route for new or update
        var route = ( TransactionController.txSetting.new === true ) ? 'new' : 'update/' + TransactionController.txData.id;

        //run ajax save
        $http({
            method: 'POST',
            url: getTransactionUrl(route),
            data: TransactionController.txData
        }).then(function successCallback(response) {
            if( response.data.errorMsg ) {
                //set alert
                modalMessageService.showModalMessage('danger', response.data.errorMsg);
            } else {
                //set success message
                modalMessageService.showModalMessage('info', 'The transaction has been saved');

                //set id
                if( response.data.tx_id ) { TransactionController.txData.id = response.data.tx_id; }

                //reset transaction
                if( reset === true ) { resetTransaction(form); }

                //if on a new transaction, it wasn't set to reset, then we want to change the status so any future
                //saves the existing transaction and not create a new one;
                else{ TransactionController.txSetting.new = false; }

                //set the convert flag since if it was saved, then it was already converted
                TransactionController.txSetting.convert = false;

                //update status name to active only if the user wants to stay on the current transaction
                if( reset !== true ) { TransactionController.txData.tx_status_name = 'active'; }

                //go to callback if set - this is to process pick list and shipping memo
                if( callback ) { callback(); }
            }
        }, function errorCallback(response) {
            //set alert
            modalMessageService.showModalMessage('danger', 'The following error occurred in saving the data: ' + response.statusText);
        });
    }

    /* Convert the ASN Transaction */
    function convertTransaction() {
        //build the url
        var url = TransactionController.baseUrl + '/transaction/' + TransactionController.txSetting.direction +
                '/convert/' + TransactionController.txData.id;

        //reroute
        window.location = url;
    }

    /* Void transaction dialog box */
    function voidTransaction() {
        modalService.showModal({
            templateUrl: "/js/angular-component/modalService-transaction-void.html",
            controller: "YesNoController"
        }).then(function(modal) {
            modal.element.modal();
            modal.close.then(function(result) {
                if( result === true ) { voidTransactionCallback(); }
            });
        });
    }

    /* Void transaction callback */
    function voidTransactionCallback() {
        //make ajax call to void tx
        $http({
            method: 'PUT',
            url: getTransactionUrl('void/' + TransactionController.txData.id)
        }).then(function successCallback(response) {
            if( response.data.errorMsg ) {
                //set alert
                modalMessageService.showModalMessage('danger', response.data.errorMsg);
            } else {
                //set success message
                modalMessageService.showModalMessage('info', 'The transaction has been voided');

                //update tx settings
                TransactionController.txSetting.edit = false;
                TransactionController.txData.tx_status_name = 'voided';
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

    /* Reset the newItem model values */
    function resetNewItem() {
        //we are adding in the two properties on creation for usage in shipping transactions so the initial
        //inventory quantity is set to zero.
        TransactionController.newItem = {
            inventoryTotal: 0,
            selectedUomMultiplierTotal: 1
        };
    }

    /* Reset the transaction */
    function resetTransaction(form) {
        //reset form
        form.$submitted = false;
        form.$setPristine();
        form.$setUntouched();

        //get user name - save it so we can add it back
        var userName = TransactionController.txData.user_name;

        //reset properties/data
        TransactionController.txData = {};
        TransactionController.txData.user_name = userName;
        TransactionController.txData.tx_status_name = null;
        TransactionController.txData.items = [];
        TransactionController.SearchSelectProduct.clear(); //clear out product select box
        TransactionController.txData.txSetting = txSetting;
        setWarehouseClientTxData(); //add back warehouse_id and client_id
        initTxDate(); //set tx date back to today's date
        resetNewItem();
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
        model.selectedUomMultiplierTotal = uom.multiplier_total;
        model.selectedUomName = uom.name;
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

    /* Verify new shipping line item */
    function validateNewItemShip() {
        var newItem = TransactionController.newItem;

        //check for inventory quantity
        if( newItem.quantity > newItem.inventoryTotal / newItem.selectedUomMultiplierTotal ) {
            modalMessageService.showModalMessage('danger', 'You entered a quantity greater than is available in the inventory');
            return false;
        }

        //check for variants
        for( i = 1; i < 5; i++ ) {
            //set name
            var baseName = 'variant' + i;
            var active = baseName + '_active';
            var value = baseName + '_value';
            var name = baseName + '_name';
            var error = baseName + '_error';

            //reset error flag
            newItem[error] = false;

            //check each item
            if( newItem.variants[active] === true && !newItem[value] ) {
                //set error flag
                newItem[error] = true;

                //send back error message
                modalMessageService.showModalMessage('danger', 'Please select a value for ' + newItem.variants[name]);
                return false;
            }
        }

        //if we got here, it passed all validations so return true
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

            //if it is shipping transaction, also check the inventory level
            if( TransactionController.txSetting.type == 'ship' &&
                    items[i].quantity > items[i].inventoryTotal / items[i].selectedUomMultiplierTotal ) {
                modalMessageService.showModalMessage('danger', 'You entered a quantity greater than is available in the inventory.');
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
            TransactionController.txData.warehouse_name = TransactionController.selectedWarehouseClient.warehouse_name;
            TransactionController.txData.client_short_name = TransactionController.selectedWarehouseClient.client_short_name;
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

    function initTxDate() {
        var txDate = TransactionController.txData.tx_date;

        /* Set differently whether it is a new transaction or a date is already set */
        //date not set
        if( txDate == null ) {
            TransactionController.txData.tx_date = new Date();
        }

        //date set so init as date object
        else {
            TransactionController.txData.tx_date = new Date(txDate);
        }
    }

    /* Process selection of variant for shipping transactions.  This is different than from receiving as the
       inventory needs to be dynamically found.
     */
    function selectVariantShip(model, variantNumber ,variant) {
        //clear any errors
        model[variantNumber + '_error'] = false;

        //add selected variant value and id
        model[variantNumber + '_value'] = variant.value;
        model[variantNumber + '_id'] = variant.id;

        //need to account for undefined to null
        var variant1_id = ( model.variant1_id ) ? model.variant1_id : null;
        var variant2_id = ( model.variant2_id ) ? model.variant2_id : null;
        var variant3_id = ( model.variant3_id ) ? model.variant3_id : null;
        var variant4_id = ( model.variant4_id ) ? model.variant4_id : null;

        //build json data to get inventory data
        var data = {product_id : model.product_id,
                    variant1_id : variant1_id,
                    variant2_id : variant2_id,
                    variant3_id : variant3_id,
                    variant4_id : variant4_id};

        //run ajax query to get inventory data
        $http({
            method: 'POST',
            url: TransactionController.baseUrl + '/inventory/variant-total',
            data: data
        }).then(function successCallback(response) {
            if( response.data.errorMsg ) {
                //set alert
                modalMessageService.showModalMessage('danger', response.data.errorMsg);
            } else {
                //set data
                model.inventoryTotal = response.data.inventory_total;
            }
        }, function errorCallback(response) {
            //set alert
            modalMessageService.showModalMessage('danger', 'The following error occurred in getting the variant inventory: ' + response.statusText);
        });
    }

    /* builds the transaction route */
    function getTransactionUrl(route) {
        return TransactionController.baseUrl + '/transaction/' + TransactionController.txSetting.type.replace('_', '/') + '/' + route;
    }

    /* pick list popup */
    function pickList(form) {
        //if transaction has not been saved yet or in a pre-covert stage, then popup up save confirm dialog
        if( !TransactionController.txData.id || TransactionController.txSetting.convert ) {
            modalService.showModal({
                templateUrl: "/js/angular-component/modalService-ship-popup-save.html",
                controller: "YesNoController"
            }).then(function(modal) {
                modal.element.modal();
                modal.close.then(function(result) {
                    if( result === true ) { TransactionController.saveTransaction(false, form, pickListCallback); }
                });
            });
        }

        //just route to callback function to process pick list
        else {
            pickListCallback();
        }
    }

    /* generate pick list */
    function pickListCallback() {
        //set new popup window settings
        var url = TransactionController.baseUrl + '/transaction/ship/pick-list/' + TransactionController.txData.id;

        //open window
        popupWindow(url, 'Pick List', 900, 600);
    }

    /* shipping memo popup */
    function shippingMemo() {
        //if transaction has not been saved yet or in a pre-covert stage, then popup up save confirm dialog
        if( !TransactionController.txData.id || TransactionController.txSetting.convert ) {
            modalService.showModal({
                templateUrl: "/js/angular-component/modalService-ship-popup-save.html",
                controller: "YesNoController"
            }).then(function(modal) {
                modal.element.modal();
                modal.close.then(function(result) {
                    if( result === true ) { TransactionController.saveTransaction(false, form, shippingMemoCallback); }
                });
            });
        }

        //just route to callback function to process pick list
        else {
            shippingMemoCallback();
        }
    }

    /* generate shipping memo */
    function shippingMemoCallback() {
        //set new popup window settings
        var url = TransactionController.baseUrl + '/transaction/ship/shipping-memo/' + TransactionController.txData.id;

        //open window
        popupWindow(url, 'Pick List', 800, 600);
    }

    function newCustomer() {
        //we have to do this if no customer was selected because if an undefined variable gets passed, then even
        //if set, it doesn't come back
        if( !TransactionController.txData.customer_id ) { TransactionController.txData.customer_id = null; }
        console.log(TransactionController.txData.customer_id);
        /* new customer modal window */
        modalService.showModal({
            templateUrl: "/customer/new-popup",
            controller: "CustomerPopupController",
            inputs: {
                baseUrl: TransactionController.baseUrl,
                customerList: TransactionController.customers,
                newItem: {
                    warehouse_id: TransactionController.selectedWarehouseClient.warehouse_id,
                    client_id: TransactionController.selectedWarehouseClient.client_id
                }
            }
        }).then(function(modal) {
            modal.element.modal();
            modal.close.then(function(result) {
                TransactionController.txData.customer_id = result;
            });
        });
    }
});