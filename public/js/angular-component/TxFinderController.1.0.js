/* THERE ARE THREE EXTERNAL DEPENDENT VARIABLES THAT MUST BE SET FOR THIS CONTROLLER TO WORK
    1.  myName  = The name of the list to be used in messages
    2.  appData = The main data for the app in JSON
    3.  appUrl  = The path to the server to make the AJAX calls to
 */

/* app is instantiated in the myApp.js file */

app.controller('TxFinderController', function($http, ListService, alertService, modalMessageService,
                                              warehouseClientSelectService) {
    //set object to variable to prevent self reference collisions
    var TxFinderController = this;

    //set reference back to the service so the model variable scope can get passed back
    //for some strange reason, the variable gets disconnected by reference assignment.
    ListService.mainCtl = TxFinderController;

    /* SET PASS THROUGH PROPERTIES */
    ListService.myName = myName;
    ListService.appUrl = appUrl;
    ListService.alerts = alertService.get();

    /* CREATE PASS THROUGH FUNCTIONS */
    TxFinderController.deleteConfirm = ListService.deleteConfirm;
    TxFinderController.resetData = ListService.resetData;
    TxFinderController.closeAlert = ListService.closeAlert;

    /* SET PROPERTIES */
    TxFinderController.items = appData;
    TxFinderController.displayItems = [].concat(appData);
    TxFinderController.newItem = {};
    TxFinderController.alerts = ListService.alerts;
    TxFinderController.txType = txType;

    /* SET MEMBER METHODS */
    TxFinderController.changeTxType = changeTxType;

    function changeTxType() {
        //make ajax call to refresh transaction list
        $http({
            method: 'GET',
            url: ListService.appUrl + '/find-type/' + TxFinderController.txType
        }).then(function successCallback(response) {
            //Captured error in processing
            if( response.data.errorMsg ) {
                modalMessageService.showModalMessage('danger', response.data.errorMsg);
                return false;
            }
            //success
            else {
                TxFinderController.items = response.data;
                TxFinderController.displayItems = angular.copy(response.data);
            }
        }, function errorCallback(response) {
            //set alert
            modalMessageService.showModalMessage('danger', 'The following error occurred: ' + response.statusText);
            return false;
        });
    }
});