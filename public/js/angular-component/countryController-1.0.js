/* THERE ARE TWO DEPENDENT VARIABLES THAT MUST BE SET FOR THIS CONTROLLER TO WORK
    1.  appData = The main data for the app in JSON
    2.  appURL  = The path to the server to make the AJAX calls to
 */

var app = angular.module('myApp', ['alertService',
    'ui.bootstrap',
    'angularModalService',
    'ngAnimate',
    'angularUtils.directives.dirPagination',
    'smart-table',
    'ngMessages']);

app.controller('CountryListController', function(alertService, modalService, $http) {
    var ListController = this;

    /* SET THE DATA */
    ListController.items = appData; //add data is always set in the @section('js-data') in the blade template
    ListController.displayItems = [].concat(ListController.items); //for smart table component
    resetModel(); //make copy of the data for reset

    var newId = ListController.items.length; //remove after testing

    /* ADD NEW ITEM */
    ListController.new = function(form) {
        //set the data - we need to convert it to JSON as for some reason the model does not do it automatically
        var newData = {code:ListController.newItem.code, name:ListController.newItem.name};

        //reset form validation
        form.$setPristine();
        form.$setUntouched();

        //clear alerts
        alertService.clear();

        //run ajax add
        $http({
            method: 'POST',
            url: appUrl + '/new',
            data: newData
        }).then(function successCallback(response) {
            console.log(response);
            //set alert
            alertService.add('success', 'Item Added');

            //add to model
            ListController.items.push({
                id: response.data.id,
                code: ListController.newItem.code,
                name: ListController.newItem.name });

            //reset original model
            ListController.resetModel();
        }, function errorCallback(response) {
            //set alert
            alertService.add('danger', 'The item was not added.  ERROR: ' + response.statusText);
        });
    };

    /* SAVE THE DATA */
    ListController.save = function(curItem) {
        //clear alerts
        alertService.clear();

        //run ajax update
        $http({
            method: 'POST',
            url: appUrl + '/update',
            data: curItem
        }).then(function successCallback(response) {
            //set alert
            alertService.add('success', 'Item Updated');

            //reset original model
            ListController.resetModel();
        }, function errorCallback(response) {
            //set alert
            alertService.add('danger', 'The item was not updated. ERROR: ' + response.statusText);
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
        //clear alerts
        alertService.clear();

        //run ajax delete
        $http({
            method: 'PUT',
            url: appUrl + '/delete/' + ListController.displayItems[index].id
        }).then(function successCallback(response) {
            //set alert
            alertService.add('success', 'Item Deleted!');

            //remove item from model
            ListController.displayItems.splice(index, 1);
            ListController.items = angular.copy(ListController.displayItems);

            //reset original model
            ListController.resetModel();
        }, function errorCallback(response) {
            //set alert
            alertService.add('danger', 'The item was not deleted. ERROR: ' + response.statusText);
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
        ListController.newItem = [];
    };

    /* INITIALIZE THE ALERT SERVICE PASS THROUGH ASSIGNMENTS */
    ListController.alerts = alertService.get();
    ListController.closeAlert = function(index) {
        alertService.closeAlert(index);
    };
});