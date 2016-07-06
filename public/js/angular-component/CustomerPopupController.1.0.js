app.controller('CustomerPopupController', ['$scope', 'ListService', 'alertService', 'modalService', 'test',
                    function($scope, ListService, alertService, modalService, test) {
    //set object to variable to prevent self reference collisions
    var CustomerPopupController = this;

    //set reference back to the service so the model variable scope can get passed back
    //for some strange reason, the variable gets disconnected by reference assignment.
    ListService.mainCtl = CustomerPopupController;

    /* SET SERVICE DATA PROPERTIES */
    ListService.myName = 'customer';
    ListService.appUrl = baseUrl + '/customer';
    ListService.alerts = alertService.get();

    /* SET PROPERTIES AND METHODS*/
    CustomerPopupController.newItem = {};

    /* CREATE PASS THROUGH FUNCTIONS */
    CustomerPopupController.add = ListService.add;
    CustomerPopupController.resetData = ListService.resetData;
    CustomerPopupController.alerts = ListService.alerts;
    CustomerPopupController.closeAlert = ListService.closeAlert;

    /* INIT DATA */
    //reset new data
    ListService.mainCtl.newItem = {};

    //set active flag to true for new item as a default so the checkbox toggles correctly upon load
    ListService.mainCtl.newItem.active = true;

    /* Overload the save method in ListService as we are going to process things */
    ListService.add = function add() {
        //set the data
        var newData = ListService.mainCtl.newItem;
        var itemName = ListService.myName;

        //sends "processing" message to user
        setProcessingAlert();

        //run ajax add
        $http({
            method: 'POST',
            url: ListService.appUrl + '/new',
            data: newData
        }).then(function successCallback(response) {
            //do an error check to see if this was a duplicate or something
            if( response.data.errorMsg ) {
                //set alert
                sendAlert('danger', getAlertMsg(itemName, 'added', response.data.errorMsg));
            } else {
                var id = response.data.id;

                //need to check for valid id - there could be situations where this gets screwed up
                if( !id || isNaN(id) ) {
                    sendAlert('danger', getAlertMsg(itemName, 'added', 'New id was not returned. Please verify your data and try again.'));
                } else {
                    //set alert
                    sendAlert('success', getAlertMsg(itemName, 'added', ''));

                    //create return object
                    var customer = {
                        id: response.data.id,
                        name: newData.name
                    };

                    //set return values
                    modelItem = response.data.id;
                    customerList.push(newData);

                    //close the window
                    modalService.close(false);
                }
            }
        }, function errorCallback(response) {
            //set alert
            sendAlert('danger', getAlertMsg(itemName, 'added', response.statusText));
        });
    }
}]);