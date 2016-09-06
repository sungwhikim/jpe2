angular.module('searchSelectService', [])
    .factory('searchSelectService', function ($http) {
        var service = {
                selectItem: selectItem,
                search: search,
                selectCallBack: selectCallBack,
                clear: clear,
                loadDefaultList: loadDefaultList
            },
            isRunningSearch = false;
            baseUrl = '',
            items = [],
            displayItems = [],
            selectedItem = {},
            searchTerm = '';
        return service;

        function selectItem(item) {
            //select item
            service.selectedItem = item;

            //run the callback for processing
            service.selectCallBack(item);
        }

        /* loads the full list of products again */
        function loadDefaultList() {
            //don't reload list every time.  Only load if the number of items is different and if no search term is selected
            if( !service.searchTerm && service.items.length !== service.displayItems.length ) {
                service.displayItems.length = 0;
                service.displayItems = angular.copy(service.items);
            }
        }

        /* Go get the updated list of products based on search term */
        function search() {
            //don't do if nothing is entered or if the lock is set
            if( service.isRunningSearch ) { return; }

            //set running search flag so no other ajax call can be make while waiting for this one
            service.isRunningSearch = true;

            //set a delay to finish the typing before submitting search
            setTimeout(function() {
                console.log(service.searchTerm);
                //if the search term is blank now, the user just wants the full list loaded
                if( !service.searchTerm ) {
                    //load the full list
                    loadDefaultList();

                    //reset search flag
                    service.isRunningSearch = false;

                    //don't make ajax call to get new data
                    return;
                }

                //make ajax call to get new data
                $http({
                    method: 'GET',
                    url: service.baseUrl + '/products/search/' + encodeURI(service.searchTerm)
                }).then(function successCallback(response) {
                    //replace data
                    service.displayItems.length = 0;
                    service.displayItems = response.data;
                }, function errorCallback(response) {
                    //set alert
                    alert('The following error occurred in loading the data: ' + response.statusText);
                });

                //reset search flag
                service.isRunningSearch = false;
            }, 600);
        }

        /* THIS IS MEANT TO BE OVERLOADED FOR ADDITIONAL PROCESSING AFTER AN ITEM IS SELECTED IN THE CONTROLLER */
        function selectCallBack(item) {}

        function clear() {
            service.selectedItem = {};
            service.searchTerm = '';
        }
    });