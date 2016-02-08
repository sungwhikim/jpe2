/* THERE ARE TWO DEPENDENT VARIABLES THAT MUST BE SET FOR THIS CONTROLLER TO WORK
 1.  myName  = The name of the list to be used in messages
 2.  appData = The main data for the app in JSON
 3.  appURL  = The path to the server to make the AJAX calls to

 ---- specific to this controller ----
 4.  countryData = The country data with the list of provinces attached
 */

var app = angular.module('myApp', ['alertService',
    'ui.bootstrap',
    'angularModalService',
    'ngAnimate',
    'angularUtils.directives.dirPagination',
    'smart-table',
    'ngMessages']);

app.controller('WarehouseListController', function(alertService, modalService, $http) {
    //SET SOME GLOBALS HERE FOR THE CONTROLLER
    var ListController = this;
    ListController.myName = myName;

    /* ---- SET DATA SPECIFIC TO THIS CONTROLLER ---- */
    ListController.countries = countryData;

    /* SET THE DATA */
    ListController.items = appData; //add data is always set in the @section('js-data') in the blade template
    ListController.displayItems = [].concat(ListController.items); //for smart table component
    resetModel(); //make copy of the data for reset

    /* ADD NEW ITEM */
    ListController.new = function(form) {
        //set the data - we need to convert it to JSON as for some reason the model does not do it automatically
        var newData = ListController.newItem;

        //reset form validation
        form.$setPristine();
        form.$setUntouched();

        //sends "processing" message to user
        setProcessingAlert();

        //run ajax add
        $http({
            method: 'POST',
            url: appUrl + '/new',
            data: newData
        }).then(function successCallback(response) {
            //do an error check to see if this was a duplicate or something
            if( response.data.errorMsg ) {
                //set alert
                alertService.add('danger', getAlertMsg(ListController.newItem.name, 'added', response.data.errorMsg));
            } else {
                var id = response.data.id;

                //need to check for valid id - there could be situations where this gets screwed up
                if( !id || isNaN(id) ) {
                    sendAlert('danger', getAlertMsg(ListController.newItem.name, 'added', 'New id was not returned. Please verify your data and try again.'));
                } else {
                    //set alert
                    sendAlert('success', getAlertMsg(ListController.newItem.name, 'added', ''));

                    //add to model
                    newData.id = response.data.id;
                    ListController.items.push(newData);

                    //reset original model
                    ListController.resetModel();
                }
            }
        }, function errorCallback(response) {
            //set alert
            sendAlert('danger', getAlertMsg(ListController.newItem.name, 'added', response.statusText));
        });
    };

    /* SAVE THE DATA */
    ListController.save = function(curItem) {
        //sends "processing" message to user
        setProcessingAlert();

        //run ajax update
        $http({
            method: 'POST',
            url: appUrl + '/update',
            data: curItem
        }).then(function successCallback(response) {
            //set alert
            sendAlert('success', getAlertMsg(curItem.name, 'updated', ''));

            //reset original model
            ListController.resetModel();
        }, function errorCallback(response) {
            //set alert
            sendAlert('danger', getAlertMsg(curItem.name, 'updated', response.statusText));
        });
    };

    /* DELETE CONFIRMATION DIALOG */
    ListController.deleteConfirm = function(index) {
        modalService.showModal({
            templateUrl: "/js/angular-component/modalService-delete.html",
            controller: "YesNoController"
        }).then(function(modal) {
            modal.element.modal();
            modal.close.then(function(result) {
                if( result === true ) { ListController.delete(index); }
            });
        });
    };

    /* DELETE THE DATA */
    ListController.delete = function(index) {
        //sends "processing" message to user
        setProcessingAlert();

        //run ajax delete
        $http({
            method: 'PUT',
            url: appUrl + '/delete/' + ListController.displayItems[index].id
        }).then(function successCallback(response) {
            //set alert
            sendAlert('success', getAlertMsg(ListController.displayItems[index].name, 'deleted', ''));

            //remove item from model
            ListController.displayItems.splice(index, 1);
            ListController.items = angular.copy(ListController.displayItems);

            //reset original model
            ListController.resetModel();
        }, function errorCallback(response) {
            //set alert
            sendAlert('danger', getAlertMsg(ListController.displayItems[index].name, 'deleted', response.statusText));
        });
    };

    /* RESET TO ORIGINAL DATA */
    ListController.resetData = function() {
        //clear alerts
        alertService.clear();

        //reset to original data
        ListController.items = angular.copy(ListController.org);
    };

    /* RESET THE ORIGINAL DATA TO CURRENT UPDATED DATA */
    ListController.resetModel = resetModel; //this is set this way so this function can be called on init
    function resetModel() {
        //reset existing data
        ListController.org = angular.copy(ListController.items);

        //reset new data
        ListController.newItem = {};

        //set active flag to true for new item as a default so the checkbox toggles correctly upon load
        ListController.newItem.active = true;
    };

    /* INITIALIZE THE ALERT SERVICE PASS THROUGH ASSIGNMENTS */
    ListController.alerts = alertService.get();
    ListController.closeAlert = function(index) {
        alertService.closeAlert(index);
    };

    /* SET THE ALERT MESSAGE */
    function getAlertMsg(name, action, error) {
        if( error.length == 0 ) {
            return 'The ' + ListController.myName + ' ' + name + ' was ' + action + '.';
        } else {
            return 'The ' + ListController.myName + ' ' + name + ' was not ' + action + '.' + ' ERROR: ' + error;
        }
    }

    /* CLEAR ALERTS AND SETS THE PROCESSING MESSAGE */
    function setProcessingAlert() {
        alertService.clear();
        alertService.add('processing', 'Data is being updated.... ')
    }

    /* SENDS A SINGLE RESPONSE ALERT TO USER AND CLEARS OUT OLD MESSAGE */
    function sendAlert(type, message) {
        alertService.clear();
        alertService.add(type, message);
    }
});