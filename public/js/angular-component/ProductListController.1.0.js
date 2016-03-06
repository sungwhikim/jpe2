/* THERE ARE THREE EXTERNAL DEPENDENT VARIABLES THAT MUST BE SET FOR THIS CONTROLLER TO WORK
    1.  myName  = The name of the list to be used in messages
    2.  appData = The main data for the app in JSON
    3.  appUrl  = The path to the server to make the AJAX calls to
 */

/* app is instantiated in the myApp.js file */

app.controller('ProductListController', function($http, ListService, alertService, checkBoxService, warehouseClientSelectService) {
    //set object to variable to prevent self reference collisions
    var ProductListController = this;

    //set reference back to the service so the model variable scope can get passed back
    //for some strange reason, the variable gets disconnected by reference assignment.
    ListService.mainCtl = ProductListController;

    /* SET DATA PROPERTIES */
    ListService.myName = myName;
    ListService.appUrl = appUrl;
    ListService.alerts = alertService.get();

    /* SET PASS THROUGH PROPERTIES */
    ProductListController.items = appData;
    ProductListController.displayItems = [].concat(appData);
    ProductListController.newItem = {};
    ProductListController.alerts = ListService.alerts;

    /* ---- SET DATA TO BE USED FOR SELECT LISTS---- */
    if( typeof warehouseClientData != "undefined" ) { ProductListController.warehouse_client = warehouseClientData; }
    if( typeof productTypeData != "undefined" ) { ProductListController.product_types = productTypeData; }

    /* CREATE OVER-LOADED FUNCTION TO RESET THE DATA */
    /* -- This is mostly to initialize the new item model members -- */
    ListService.resetModelPublic = function (mainController) {
        //the mainController is a circular reference back to ListController, but needs to be so due to scope reasons in JavaScript
        mainController.newItem.active = true;
        mainController.newItem.product_type = {};
        mainController.newItem.uom1 = 1;
        mainController.newItem.oversized_pallet = false;
    };

    /* INIT DATA */
    ListService.resetModel();

    /* SET MEMBER METHODS */
    ProductListController.setProductType = setProductType;
    ProductListController.updateMainData = updateMainData;

    /* CREATE PASS THROUGH FUNCTIONS */
    ProductListController.add = ListService.add;
    ProductListController.save = ListService.save;
    ProductListController.deleteConfirm = ListService.deleteConfirm;
    ProductListController.resetData = ListService.resetData;
    ProductListController.closeAlert = ListService.closeAlert;
    ProductListController.toggleCheckBox = checkBoxService.toggleCheckBox;
    ProductListController.allCheckBoxes = checkBoxService.allCheckBoxes;
    ProductListController.noneCheckBoxes = checkBoxService.noneCheckBoxes;

    /* OVERLOAD REFRESH DATA FUNCTION IN WAREHOUSE CLIENT SELECTOR */
    warehouseClientSelectService.refreshData = updateMainData;

    /* Used to dynamically show the uom and variants when a new product type is selected */
    function setProductType(model_item) {
        //get and set the selected product type object
        model_item.product_type = getObjectById(ProductListController.product_types, model_item.product_type_id);
    }

    /* Used to update the main data when an event triggers need to refresh the main data */
    function updateMainData() {
        //sends "processing" message to user
        ListService.setProcessingAlert();

        //delete old data
        ProductListController.items = 0;

        //run ajax add
        $http({
            method: 'GET',
            url: ListService.appUrl + '/list'
        }).then(function successCallback(response) {
            //replace data
            ProductListController.items = response.data;
            ProductListController.displayItems.length = 0;
            ProductListController.displayItems = [].concat(response.data);

            //reset original model
            ListService.resetModel();

            //clear alerts
            alertService.clear();
        }, function errorCallback(response) {
            //put data back
            ProductListController.displayItems = [].concat(ProductListController.items);

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