/* THERE ARE THREE EXTERNAL DEPENDENT VARIABLES THAT MUST BE SET FOR THIS CONTROLLER TO WORK
    1.  myName  = The name of the list to be used in messages
    2.  appData = The main data for the app in JSON
    3.  appUrl  = The path to the server to make the AJAX calls to
 */

/* app is instantiated in the myApp.js file */

app.controller('CarrierController', function(ListService, alertService, checkBoxService, warehouseClientSelectService) {
    //set object to variable to prevent self reference collisions
    var CarrierController = this;

    //set reference back to the service so the model variable scope can get passed back
    //for some strange reason, the variable gets disconnected by reference assignment.
    ListService.mainCtl = CarrierController;

    /* SET SERVICE DATA PROPERTIES */
    ListService.myName = myName;
    ListService.appUrl = appUrl;
    ListService.alerts = alertService.get();

    /* SET PROPERTIES AND METHODS*/
    CarrierController.items = appData;
    CarrierController.displayItems = [].concat(appData);
    CarrierController.newItem = {};
    CarrierController.newClientWarehouse = newClientWarehouse;
    CarrierController.deleteClientWarehouse = deleteClientWarehouse;

    /* ---- SET DATA TO BE USED FOR SELECT LISTS---- */
    if( typeof warehouseClientData != "undefined" ) { CarrierController.warehouse_client = warehouseClientData; }

    /* ----- SET DATA FOR CHECKBOX SERVICE. THE MASTER LIST OF ITEMS FOR CHECK ALL FUNCTION ---- */
    /* This needs to be set to allow Angular to have access to it in the checkbox service */
    if( typeof warehouseData != "undefined" ) { CarrierController.warehouseData = warehouseData; }
    if( typeof clientData != "undefined" ) { CarrierController.clientData = clientData; }

    /* CREATE OVER-LOADED FUNCTION TO RESET THE DATA */
    /* -- This is mostly to initialize the new item model members -- */
    ListService.resetModelPublic = function (mainController) {
        //the mainController is a circular reference back to CarrierController, but needs to be so due to scope reasons in JavaScript
        mainController.newItem.active = true;
    };

    /* INIT DATA */
    ListService.resetModel();

    /* CREATE PASS THROUGH FUNCTIONS */
    CarrierController.add = ListService.add;
    CarrierController.save = ListService.save;
    CarrierController.deleteConfirm = ListService.deleteConfirm;
    CarrierController.resetData = ListService.resetData;
    CarrierController.alerts = ListService.alerts;
    CarrierController.closeAlert = ListService.closeAlert;
    CarrierController.toggleCheckBox = checkBoxService.toggleCheckBox;
    CarrierController.allCheckBoxes = checkBoxService.allCheckBoxes;
    CarrierController.noneCheckBoxes = checkBoxService.noneCheckBoxes;

    /* ADDS A NEW CLIENT WAREHOUSE ITEM */
    function newClientWarehouse(item) {
        //create empty new item data object
        var new_item = {
            client_id : null,
            client_name : null,
            warehouse_id : null,
            warehouse_name : null,
            ship : false,
            receive : false
        };

        //create the new bin items array if not already created to avoid error in next step.
        if( !item.client_warehouse_new ) { item.client_warehouse_new = []; }

        //add to new bin items array
        item.client_warehouse_new.push(new_item);
    }

    /* REMOVES A CLIENT WAREHOUSE ITEMS */
    function deleteClientWarehouse(list, index) {
        list.splice(index, 1);
    }
});