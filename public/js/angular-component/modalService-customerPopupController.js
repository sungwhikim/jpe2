app.controller('CustomerPopupController', ['$scope', 'ListService', 'alertService', 'modalService', 'baseUrl', 'customerList',
    'newItem', '$http', 'close', '$element', function($scope, ListService, alertService, modalService, baseUrl, customerList, newItem, $http, close, $element) {

    /* SET SERVICE DATA PROPERTIES */
    ListService.myName = 'customer';
    ListService.alerts = alertService.get();

    /* SET PROPERTIES AND METHODS*/
    $scope.newItem = newItem;
    $scope.newItem.active = true;

    /* CREATE PASS THROUGH FUNCTIONS */
    $scope.alerts = ListService.alerts;
    $scope.closeAlert = ListService.closeAlert;

    /* CLEAR ANY ALERTS LEFT OVER FROM LAST INSTANTIATION */
    alertService.clear();

    /* INIT DATA */
    $scope.countries = countryData;

    /* Overload the save method in ListService as we are going to process things */
    $scope.add = function() {
        //set the data
        var newData = $scope.newItem;
        var itemName = ListService.myName;

        //sends "processing" message to user
        ListService.setProcessingAlert();

        //run ajax add
        $http({
            method: 'POST',
            url: baseUrl + '/customer/new',
            data: newData
        }).then(function successCallback(response) {
            //do an error check to see if this was a duplicate or something
            if( response.data.errorMsg ) {
                //set alert
                ListService.sendAlert('danger', ListService.getAlertMsg(itemName, 'added', response.data.errorMsg));
            } else {
                var customer_id = response.data.id;

                //need to check for valid id - there could be situations where this gets screwed up
                if( !customer_id || isNaN(customer_id) ) {
                    ListService.sendAlert('danger', ListService.getAlertMsg(itemName, 'added', 'New id was not returned. Please verify your data and try again.'));
                } else {
                    //set alert
                    ListService.sendAlert('success', ListService.getAlertMsg(newData.name, 'added', ''));

                    //create return object
                    var customer = {
                        id: customer_id,
                        name: newData.name
                    };

                    //add new customer to list
                    customerList.unshift(customer);

                    //Manually hide the modal.
                    $element.modal('hide');

                    //close the window
                    close(customer_id, 500);
                }
            }
        }, function errorCallback(response) {
            //set alert
            ListService.sendAlert('danger', ListService.getAlertMsg(itemName, 'added', response.statusText));
        });
    }
}]);