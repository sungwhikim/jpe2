/* THERE ARE THREE EXTERNAL DEPENDENT VARIABLES THAT MUST BE SET FOR THIS CONTROLLER TO WORK
    1.  myName  = The name of the list to be used in messages
    2.  appData = The main data for the app in JSON
    3.  appUrl  = The path to the server to make the AJAX calls to
 */

var app = angular.module('myApp', ['listService', 'checkBoxService']);

app.controller('ListController', function(ListService, alertService, checkBoxService) {
    //set object to variable to prevent self reference collisions
    var ListController = this;

    //set reference back to the service so the model variable scope can get passed back
    //for some strange reason, the variable gets disconnected by reference assignment.
    ListService.mainCtl = ListController;

    /* SET DATA PROPERTIES */
    ListService.myName = myName;
    ListService.appUrl = appUrl;
    ListService.alerts = alertService.get();

    /* SET PASS THROUGH PROPERTIES */
    ListController.items = appData;
    ListController.displayItems = [].concat(appData);
    ListController.newItem = {};
    ListController.alerts = ListService.alerts;

    /* ---- SET ADDITIONAL DATA LISTS ---- */
    if( typeof countryData != "undefined" ) { ListController.countries = countryData; }
    if( typeof categoryData != "undefined" ) { ListController.categories = categoryData; }
    if( typeof userGroupData != "undefined" ) { ListController.user_groups = userGroupData; }

    /* INIT DATA */
    ListService.resetModel();

    /* CREATE PASS THROUGH FUNCTIONS */
    ListController.add = ListService.add;
    ListController.save = ListService.save;
    ListController.deleteConfirm = ListService.deleteConfirm;
    ListController.resetData = ListService.resetData;
    ListController.closeAlert = ListService.closeAlert;
    ListController.toggleCheckBox = checkBoxService.toggleCheckBox;
});