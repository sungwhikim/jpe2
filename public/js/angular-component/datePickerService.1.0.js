angular.module('datePickerService', [])
    .factory('datePickerService', function () {
        var service = {
                clear: clear,
                open: open,
                getDateString: getDateString,
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

        /* Returns the date as a string in format for database */
        function getDateString(date) {
            //chop date to date in correct format
            var day = date.getDate();
            var month = date.getMonth() + 1;
            var year = date.getFullYear();
            return year + "-" + month + "-" + day;
        }
    });