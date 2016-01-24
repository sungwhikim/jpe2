var app = angular.module('myApp', ['alertService',
    'ui.bootstrap',
    'angularModalService',
    'ngAnimate',
    'angularUtils.directives.dirPagination',
    'smart-table',
    'ngMessages']);

app.controller('WarehouseListController', function(alertService, modalService) {
    var ListController = this;

    /* INITIALIZE THE ALERT SERVICE PASS THROUGH ASSIGNMENTS */
    ListController.alerts = alertService.get();
    ListController.closeAlert = function(index) {
        alertService.closeAlert(index);
    };

    /* GET THE COUNTRY DATA */
    ListController.countries = [
        {id:1, code:'CA', name:'Canada', provinces:[
            {id:1, name:'Ontario'},
            {id:2, name:'BC'},
            {id:3, name:'QB'}
        ]},
        {id:2, code:'US', name:'USA', provinces:[
            {id:1, name:'Texas'},
            {id:2, name:'Washington'},
            {id:3, name:'Oregon'},
            {id:4, name:'Florida'}
        ]},
        {id:3, code:'DE', name:'Germany'},
        {id:4, code:'BR', name:'Brazil'},
        {id:5, code:'AU', name:'Australia'},
        {id:6, code:'CN', name:'China'},
        {id:7, code:'GR', name:'Greece'},
        {id:8, code:'IE', name:'Ireland'},
        {id:9, code:'KE', name:'Kenya'},
        {id:10, code:'MG', name:'Madagascar'},
        {id:11, code:'NZ', name:'New Zealand'},
        {id:12, code:'NO', name:'Norway'},
        {id:13, code:'ES', name:'Spain'},
        {id:14, code:'TW', name:'Taiwan'}
    ];

    /* GET THE MAIN DATA */
    ListController.items = [
        {id:1, code:'1290', name:'Miss', country_id:1, province_id:1},
        {id:2, code:'TC-DAL', name:'Dallas', country_id:2, province_id:1},
        {id:3, code:'Van', name:'Vancouver', country_id:1, province_id:2}
    ];

    /* COPY THE DATA FOR SMART TABLE COMPONENT */
    ListController.displayItems = [].concat(ListController.items);

    var newId = ListController.items.length; //remove after testing

    /* MAKE COPY OF OLD DATA FOR RESET */
    resetModel();

    /* RESET TO ORIGINAL DATA */
    ListController.resetData = function() {
        //clear alerts
        alertService.clear();

        //reset to original data
        ListController.items = angular.copy(ListController.org);
    };

    /* ADD NEW ITEM */
    ListController.new = function(form) {
        //reset form validation
        form.$setPristine();
        form.$setUntouched();

        //clear alerts
        alertService.clear();

        //run ajax add

        alertService.add('success', 'Item Added!');

        newId++; //remove after testing

        //add to model
        ListController.items.push({ id:newId,
            code: ListController.newItem.code,
            name: ListController.newItem.name,
            country_id: ListController.newItem.country_id,
            province_id: ListController.newItem.province_id
        });

        //reset original model
        ListController.resetModel();
    };

    /* SAVE THE DATA */
    ListController.save = function(curItem) {
        //clear alerts
        alertService.clear();

        //assign item
        //var curItem = ListController.displayItems[index];

        alertService.add('success', 'Item Saved!');

        //reset original model
        ListController.resetModel();
    };

    /* DELETE CONFIRMATION DIALOG */
    ListController.deleteConfirm = function(index) {
        modalService.showModal({
            templateUrl: "js/angular-component/modalService-delete.html",
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

        alertService.add('success', 'Item Deleted!');

        //remove item from model
        ListController.displayItems.splice(index, 1);
        ListController.items = angular.copy(ListController.displayItems);

        //reset original model
        ListController.resetModel();
    };

    /* RESET THE ORIGINAL DATA TO CURRENT UPDATED DATA */
    ListController.resetModel = resetModel; //this is set this way so this function can be called on init
    function resetModel() {
        //reset existing data
        ListController.org = angular.copy(ListController.items);

        //reset new data
        ListController.newItem = [];
    };

});