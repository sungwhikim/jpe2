/* THERE ARE THREE EXTERNAL DEPENDENT VARIABLES THAT MUST BE SET FOR THIS CONTROLLER TO WORK
    1.  myName  = The name of the list to be used in messages
    2.  appData = The main data for the app in JSON
    3.  appUrl  = The path to the server to make the AJAX calls to
 */

/* app is instantiated in the myApp.js file */

app.controller('CustomerController', function(ListService, alertService, checkBoxService) {
    //set object to variable to prevent self reference collisions
    var CustomerController = this;

    //set reference back to the service so the model variable scope can get passed back
    //for some strange reason, the variable gets disconnected by reference assignment.
    ListService.mainCtl = CustomerController;

    /* SET SERVICE DATA PROPERTIES */
    ListService.myName = myName;
    ListService.appUrl = appUrl;
    ListService.alerts = alertService.get();

    /* SET PROPERTIES AND METHODS*/
    CustomerController.items = appData;
    CustomerController.displayItems = [].concat(appData);
    CustomerController.newItem = {};
    CustomerController.countries = countryData;
    CustomerController.newClientWarehouse = newClientWarehouse;
    CustomerController.deleteClientWarehouse = deleteClientWarehouse;

    /* ---- SET DATA TO BE USED FOR SELECT LISTS---- */
    if( typeof warehouseClientData != "undefined" ) { CustomerController.warehouse_client = warehouseClientData; }

    /* ----- SET DATA FOR CHECKBOX SERVICE. THE MASTER LIST OF ITEMS FOR CHECK ALL FUNCTION ---- */
    /* This needs to be set to allow Angular to have access to it in the checkbox service */
    if( typeof warehouseData != "undefined" ) { CustomerController.warehouseData = warehouseData; }
    if( typeof clientData != "undefined" ) { CustomerController.clientData = clientData; }

    /* CREATE OVER-LOADED FUNCTION TO RESET THE DATA */
    /* -- This is mostly to initialize the new item model members -- */
    ListService.resetModelPublic = function (mainController) {
        //the mainController is a circular reference back to CustomerController, but needs to be so due to scope reasons in JavaScript
        mainController.newItem.active = true;
    };

    /* INIT DATA */
    ListService.resetModel();

    /* CREATE PASS THROUGH FUNCTIONS */
    CustomerController.add = ListService.add;
    CustomerController.save = ListService.save;
    CustomerController.deleteConfirm = ListService.deleteConfirm;
    CustomerController.resetData = ListService.resetData;
    CustomerController.alerts = ListService.alerts;
    CustomerController.closeAlert = ListService.closeAlert;
    CustomerController.toggleCheckBox = checkBoxService.toggleCheckBox;
    CustomerController.allCheckBoxes = checkBoxService.allCheckBoxes;
    CustomerController.noneCheckBoxes = checkBoxService.noneCheckBoxes;

    /* ADDS A NEW CLIENT WAREHOUSE ITEM */
    function newClientWarehouse(item) {
        //create empty new item data object
        var new_item = {
            client_id : null,
            client_name : null,
            warehouse_id : null,
            warehouse_name : null
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