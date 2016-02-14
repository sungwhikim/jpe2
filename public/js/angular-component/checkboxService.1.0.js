angular.module('checkBoxService', [])
    .factory('checkBoxService', function () {
        var service = {
                toggleCheckBox: toggleCheckBox,
                allCheckBoxes: allCheckBoxes,
                noneCheckBoxes: noneCheckBoxes
            },
            items = [];
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

        function allCheckBoxes(selectedList) {
            //this is because direct assignments of array by reference does not work in js.
            for( i = 0; i < service.items.length; i++ ) {
                if( selectedList.indexOf(service.items[i]) == -1 ) {
                    selectedList.push(service.items[i]);
                }
            }
        }

        function noneCheckBoxes(selectedList) {
            selectedList.length = 0;
        }
    });