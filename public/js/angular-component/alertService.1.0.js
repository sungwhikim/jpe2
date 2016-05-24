angular.module('alertService', [])
    .factory('alertService', function () {
        var service = {
                add: add,
                closeAlert: closeAlert,
                clear: clear,
                get: get
            },
            alerts = [];

        return service;

        function add(type, msg, clear) {
            //clear other alerts
            if( clear === true ) service.clear();

            return alerts.push({
                type: type,
                msg: msg,
                close: function() {
                    return closeAlert(this);
                }
            });
        }

        function closeAlert(index) {
            return alerts.splice(index, 1);
        }

        function clear(){
            alerts.splice(0, alerts.length);
        }

        function get() {
            return alerts;
        }
    });