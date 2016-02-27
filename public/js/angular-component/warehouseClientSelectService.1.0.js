angular.module('warehouseClientSelectService', [])
    .factory('warehouseClientSelectService', function () {
        var service = {
                openSelectDialog: openSelectDialog,
                selectWC: selectWC,
                get: get
            },
            data = [],
            warehouse_id = null,
            warehouse_name = null,
            client_id = null,
            client_name = null;

        return service;

        function openSelectDialog() {

        }

        function selectWC() {
            console.log('select function');
        }

        function get() {

        }
    });