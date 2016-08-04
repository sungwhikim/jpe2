angular.module('datePickerService', [])
    .factory('datePickerService', function () {
        var service = {
                clear: clear,
                open: open,
                setInitialDate: setInitialDate,
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

        /* WE DON'T NEED THIS ANY MORE AS WE CAN JUST CREATE A NEW DATE OBJECT UPON STARTUP.  THIS ALSO HAS A BUG
           WHERE THE DATE IS INVALID ON ANYTHING OTHER THAN OSX
         */
        function setInitialDate() {
            //chop date to date in correct format
            var currentDate = new Date();
            var day = currentDate.getDate();
            var month = currentDate.getMonth() + 1;
            var year = currentDate.getFullYear();
            var current_date = month + "/" + day + "/" + year;

            //we may not need to all the processing above to remove the time and reformat.  It depends on the back end.
            //however, it would be simpler to leave it as is so only the date gets passed back.  Re-check later after.
            return new Date(current_date);
        }
    });