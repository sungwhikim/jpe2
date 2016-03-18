angular.module('datePickerService', [])
    .factory('datePickerService', function () {
        var service = {
                clear: clear,
                open: open,
                dateFormat: 'MM-dd-yyyy',
                popupOpened: false,
                dateOptions: {
                    showWeeks: false,
                    startingDay: 1
                }
            };
        return service;

        function open() {
            service.popupOpened= true;
        }

        function clear() {
            service.dt = null;
        }
    });