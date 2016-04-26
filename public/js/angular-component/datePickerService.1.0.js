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


/* THIS IS NOT WORKING BECAUSE OF ISSUE OF COLLISION OF SETTING THE MODEL VALUE WHEN NEW SELECTION IS MADE */
/* Need to resolve not setting the model date value on focus if it is already set. */
        function setInitialDate() {
            //chop date to date in correct format
            var currentDate = new Date();
            var day = currentDate.getDate();
            var month = currentDate.getMonth() + 1;
            var year = currentDate.getFullYear();
            var current_date = month + "-" + day + "-" + year;

            //we may not need to all the processing above to remove the time and reformat.  It depends on the back end.
            //however, it would be simpler to leave it as is so only the date gets passed back.  Re-check later after.
            //save function is done.
            //model = new Date(current_date);
            return new Date(current_date);

        }
    });