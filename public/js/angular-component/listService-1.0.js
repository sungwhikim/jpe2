/* THERE ARE TWO DEPENDENT VARIABLES THAT MUST BE SET FOR THIS SERVICE TO WORK
    1.  appData = The main data for the app in JSON
    2.  appURL  = The path to the server to make the AJAX calls to
    3.  myName  = The name of the list to be used to identiy itself in messages
 */

angular.module('listService', [])
    .factory('listService', function () {

    //SET SOME GLOBALS HERE FOR THE CONTROLLER
    var ListService = this;
    ListService.myName = myName;

    /* SET THE DATA */
    ListService.items = appData; //add data is always set in the @section('js-data') in the blade template
    ListService.displayItems = [].concat(ListService.items); //for smart table component
    resetModel(); //make copy of the data for reset

    /* ADD NEW ITEM */
    ListService.new = function(form) {
        //set the data - we need to convert it to JSON as for some reason the model does not do it automatically
        var newData = {code:ListService.newItem.code, name:ListService.newItem.name};

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
            //do an error check to see if this was a duplicate or something
            if( response.data.errorMsg ) {
                //set alert
                alertService.add('danger', getAlertMsg(ListService.newItem.name, 'added', response.data.errorMsg));
            } else {
                var id = response.data.id;

                //need to check for valid id - there could be situations where this gets screwed up
                if( !id || isNaN(id) ) {
                    alertService.add('danger', getAlertMsg(ListService.newItem.name, 'added', 'New id was not returned. Please verify your data and try again.'));
                } else {
                    //set alert
                    alertService.add('success', getAlertMsg(ListService.newItem.name, 'added', ''));

                    //add to model
                    ListService.items.push({
                        id: response.data.id,
                        code: ListService.newItem.code,
                        name: ListService.newItem.name
                    });

                    //reset original model
                    ListService.resetModel();
                }
            }
        }, function errorCallback(response) {
            //set alert
            alertService.add('danger', getAlertMsg(ListService.newItem.name, 'added', response.statusText));
        });
    };

    /* SAVE THE DATA */
    ListService.save = function(curItem) {
        //clear alerts
        alertService.clear();

        //run ajax update
        $http({
            method: 'POST',
            url: appUrl + '/update',
            data: curItem
        }).then(function successCallback(response) {
            //set alert
            alertService.add('success', getAlertMsg(curItem.name, 'updated', ''));

            //reset original model
            ListService.resetModel();
        }, function errorCallback(response) {
            //set alert
            alertService.add('danger', getAlertMsg(curItem.name, 'updated', response.statusText));
        });
    };

    /* DELETE CONFIRMATION DIALOG */
    ListService.deleteConfirm = function(index) {
        modalService.showModal({
            templateUrl: "/js/angular-component/modalService-delete.html",
            controller: "YesNoController"
        }).then(function(modal) {
            modal.element.modal();
            modal.close.then(function(result) {
                if( result === true ) { ListService.delete(index); }
            });
        });
    };

    /* DELETE THE DATA */
    ListService.delete = function(index) {
        //clear alerts
        alertService.clear();

        //run ajax delete
        $http({
            method: 'PUT',
            url: appUrl + '/delete/' + ListService.displayItems[index].id
        }).then(function successCallback(response) {
            //set alert
            alertService.add('success', getAlertMsg(ListService.displayItems[index].name, 'deleted', ''));

            //remove item from model
            ListService.displayItems.splice(index, 1);
            ListService.items = angular.copy(ListService.displayItems);

            //reset original model
            ListService.resetModel();
        }, function errorCallback(response) {
            //set alert
            alertService.add('danger', getAlertMsg(ListService.displayItems[index].name, 'deleted', response.statusText));
        });
    };

    /* RESET TO ORIGINAL DATA */
    ListService.resetData = function() {
        //clear alerts
        alertService.clear();

        //reset to original data
        ListService.items = angular.copy(ListService.org);
    };

    /* RESET THE ORIGINAL DATA TO CURRENT UPDATED DATA */
    ListService.resetModel = resetModel; //this is set this way so this function can be called on init
    function resetModel() {
        //reset existing data
        ListService.org = angular.copy(ListService.items);

        //reset new data
        ListService.newItem = [];
    };

    /* SET THE ALERT MESSAGE */
    function getAlertMsg(name, action, error)
    {
        if( error.length == 0 ) {
            return 'The ' + ListService.myName + ' ' + name + ' was ' + action + '.';
        } else {
            return 'The ' + ListService.myName + ' ' + name + ' was not ' + action + '.' + ' ERROR: ' + error;
        }
    }
});