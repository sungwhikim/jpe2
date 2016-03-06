/* THERE ARE THREE EXTERNAL DEPENDENT VARIABLES THAT MUST BE SET FOR THIS CONTROLLER TO WORK
    1.  myName  = The name of the list to be used in messages
    2.  appData = The main data for the app in JSON
    3.  appUrl  = The path to the server to make the AJAX calls to
 */

/* app is instantiated in the myApp.js file */

app.controller('InventoryController', function($http, ListService, alertService, checkBoxService, warehouseClientSelectService) {
    //set object to variable to prevent self reference collisions
    var InventoryController = this;

    //set reference back to the service so the model variable scope can get passed back
    //for some strange reason, the variable gets disconnected by reference assignment.
    ListService.mainCtl = InventoryController;

    /* SET DATA PROPERTIES */
    ListService.myName = myName;
    ListService.appUrl = appUrl;
    ListService.alerts = alertService.get();

    /* SET PASS THROUGH PROPERTIES */
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

    /* CREATE PASS THROUGH FUNCTIONS */
    InventoryController.add = ListService.add;
    InventoryController.save = ListService.save;
    InventoryController.deleteConfirm = ListService.deleteConfirm;
    InventoryController.resetData = ListService.resetData;
    InventoryController.closeAlert = ListService.closeAlert;
    InventoryController.toggleCheckBox = checkBoxService.toggleCheckBox;
    InventoryController.allCheckBoxes = checkBoxService.allCheckBoxes;
    InventoryController.noneCheckBoxes = checkBoxService.noneCheckBoxes;

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
            url: ListService.appUrl + '/products'
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

        //run ajax add
        $http({
            method: 'GET',
            url: ListService.appUrl + '/product/' + product.id
        }).then(function successCallback(response) {
            //replace data
            InventoryController.items = 0;
            InventoryController.items = response.data;
            InventoryController.displayItems = [].concat(response.data);

            //reset original model
            ListService.resetModel();

            //clear alerts
            alertService.clear();
        }, function errorCallback(response) {
            //put data back
            InventoryController.displayItems = [].concat(InventoryController.items);

            //set alert
            alertService.add('danger', 'The following error occurred in loading the data: ' + response.statusText);
        });
    }

    /* Helper function to get object by id value */
    function getObjectById(data, id) {
        for( var i = 0; i < data.length; i++ ) {
            if( data[i].id == id ) { return data[i]; }
        }
        return {};
    }
});