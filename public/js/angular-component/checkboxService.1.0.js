angular.module('checkBoxService', [])
    .factory('checkBoxService', function () {
        var service = {
                toggleCheckBox: toggleCheckBox
            };
        return service;

        function toggleCheckBox(selectedList, id) {
            var idx = selectedList.indexOf(id);

            // is currently selected
            if (idx > -1) {
                selectedList.splice(idx, 1);
            }

            // is newly selected
            else {
                selectedList.push(id);
            }
        }
    });