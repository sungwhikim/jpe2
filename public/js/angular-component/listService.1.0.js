/**
 * ---------------------------------------
 * See listController.1.0.js for usage
 * ---------------------------------------
 * */

angular.module('listService',
       ['alertService',
        'ui.bootstrap',
        'angularModalService',
        'ngAnimate',
        'angularUtils.directives.dirPagination',
        'smart-table',
        'ngMessages'])
    .factory('ListService', function (alertService, modalService, $http) {

    //SET THE INTERFACE HERE
    var ListService = {
            add: add,
            save: save,
            deleteConfirm: deleteConfirm,
            deleteItem: deleteItem,
            resetData: resetData,
            resetModel: resetModel,
            resetModelPublic: resetModelPublic,
            closeAlert: closeAlert
        },
        myName  = '',
        appUrl = '',
        alerts = [],
        mainCtl = {}; //we have to set this reference back to the main controller due to the data in the
                      //model data becoming disconnected and the references not not working.  A kludge, but
                      //is the cleanest way I could find to resolve this issue.  Yes, I tried injecting a value
                      //and a service to hold the model data in a separate container.

    return ListService;

    /* ADD NEW ITEM */
    function add(form) {
        //set the data
        var newData = ListService.mainCtl.newItem;
        var itemName = newData.name;

        //reset form validation
        form.$setPristine();
        form.$setUntouched();

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

                    //add to model
                    newData.id = response.data.id;
                    ListService.mainCtl.items.push(newData);

                    //reset original model
                    ListService.resetModel();
                }
            }
        }, function errorCallback(response) {
            //set alert
            sendAlert('danger', getAlertMsg(itemName, 'added', response.statusText));
        });
    }

    /* SAVE THE DATA */
    function save(curItem) {
        //sends "processing" message to user
        setProcessingAlert();

        //run ajax update
        $http({
            method: 'POST',
            url: ListService.appUrl + '/update',
            data: curItem
        }).then(function successCallback(response) {
            //set alert
            sendAlert('success', getAlertMsg(curItem.name, 'updated', ''));

            //reset original model
            ListService.resetModel();
        }, function errorCallback(response) {
            //set alert
            sendAlert('danger', getAlertMsg(curItem.name, 'updated', response.statusText));
        });
    }

    /* DELETE CONFIRMATION DIALOG */
    function deleteConfirm(index) {
        modalService.showModal({
            templateUrl: "/js/angular-component/modalService-delete.html",
            controller: "YesNoController"
        }).then(function(modal) {
            modal.element.modal();
            modal.close.then(function(result) {
                if( result === true ) { ListService.deleteItem(index); }
            });
        });
    }

    /* DELETE THE DATA */
    function deleteItem(index) {
        //sends "processing" message to user
        setProcessingAlert();

        //run ajax delete
        $http({
            method: 'PUT',
            url: ListService.appUrl + '/delete/' + ListService.mainCtl.displayItems[index].id
        }).then(function successCallback(response) {
            //set alert
            sendAlert('success', getAlertMsg(ListService.mainCtl.displayItems[index].name, 'deleted', ''));

            //remove item from model
            //ListService.displayItems.splice(index, 1);
            ListService.mainCtl.displayItems.splice(index, 1);
            ListService.mainCtl.items = angular.copy(ListService.mainCtl.displayItems);

            //reset original model
            ListService.resetModel();
        }, function errorCallback(response) {
            //set alert
            sendAlert('danger', getAlertMsg(ListService.mainCtl.displayItems[index].name, 'deleted', response.statusText));
        });
    }

    /* RESET TO ORIGINAL DATA */
    function resetData() {
        //clear alerts
        alertService.clear();

        //reset to original data
        ListService.mainCtl.items = angular.copy(ListService.org);
        ListService.mainCtl.displayItems = ListService.mainCtl.items;
    };

    /* RESET THE ORIGINAL DATA TO CURRENT UPDATED DATA */
    function resetModel() {
        //reset existing data
        ListService.org = angular.copy(ListService.mainCtl.items);

        //reset new data
        ListService.mainCtl.newItem = {};

        //set active flag to true for new item as a default so the checkbox toggles correctly upon load
        ListService.mainCtl.newItem.active = true;

        //call over loadable function to reset model collections from the controller
        ListService.resetModelPublic(ListService.mainCtl);
    }

    /* OVER-LOADABLE FUNCTION TO RESET DATA. THIS IS NEEDED DUE TO ISSUE WITH INITIALIZING NEW OBJECT MODELS */
    function resetModelPublic(mainController) {

    }

    /* SET THE ALERT MESSAGE */
    function getAlertMsg(name, action, error)
    {
        if( error.length == 0 ) {
            return 'The ' + ListService.myName + ' ' + name + ' was ' + action + '.';
        } else {
            return 'The ' + ListService.myName + ' ' + name + ' was not ' + action + '.' + ' ERROR: ' + error;
        }
    }

    /* INITIALIZE THE ALERT SERVICE PASS THROUGH ASSIGNMENTS */
    function closeAlert(index) {
        alertService.closeAlert(index);
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