/* THERE ARE THREE EXTERNAL DEPENDENT VARIABLES THAT MUST BE SET FOR THIS CONTROLLER TO WORK
    1.  myName  = The name of the list to be used in messages
    2.  appData = The main data for the app in JSON
    3.  appUrl  = The path to the server to make the AJAX calls to
 */

app.filter('UnderScoreToForwardSlash', function () {
    return function (input) {
        return input.replace(/_/g, '/');
    };
});

/* app is instantiated in the myApp.js file */

app.controller('TxFinderController', function($http, ListService, alertService, modalMessageService,
                                              warehouseClientSelectService, checkBoxService) {
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
    TxFinderController.toggleCheckBox = checkBoxService.toggleCheckBox;

    /* SET PROPERTIES */
    TxFinderController.baseUrl = baseUrl;
    TxFinderController.items = appData;
    TxFinderController.displayItems = [].concat(appData);
    TxFinderController.newItem = {};
    TxFinderController.alerts = ListService.alerts;
    TxFinderController.txType = txType;
    TxFinderController.pick_pack_tx_ids = [];

    /* SET MEMBER METHODS */
    TxFinderController.changeTxType = changeTxType;
    TxFinderController.pickAndPack = pickAndPack;

    /* OVERLOAD REFRESH DATA FUNCTION IN WAREHOUSE CLIENT SELECTOR TO REFRESH THE PAGE WHEN WAREHOUSE/CLIENT IS CHANGED */
    warehouseClientSelectService.refreshData = changeTxType;

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

    function pickAndPack() {
        //don't allow if no transactions were selected
        if( TxFinderController.pick_pack_tx_ids.length == 0 ) {
            modalMessageService.showModalMessage('info', 'Please select transaction(s).');
            return false;
        }

        //set new popup window settings
        var url = TxFinderController.baseUrl + '/transaction/ship/pick-and-pack/' + TxFinderController.pick_pack_tx_ids.join();

        //open window
        popupWindow(url, 'Pack & Pick', 925, 600);
    }
});