angular.module('checkBoxService', [])
    .factory('checkBoxService', function () {
        var service = {
                toggleCheckBox: toggleCheckBox,
                allCheckBoxes: allCheckBoxes,
                noneCheckBoxes: noneCheckBoxes
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

        function allCheckBoxes(selectedList, masterList) {
            //this is because re-assignment of the array elements breaks the variable reference to its parent/child
            selectedList.length = 0;
            for( i = 0; i < masterList.length; i++ ) {
                selectedList.push(masterList[i].id);
            }
        }

        function noneCheckBoxes(selectedList) {
            selectedList.length = 0;
        }
    });