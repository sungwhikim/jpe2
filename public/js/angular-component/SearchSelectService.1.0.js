angular.module('searchSelectService', [])
    .factory('searchSelectService', function ($http) {
        var service = {
                //methods
                selectItem: selectItem,
                search: search,
                selectCallBack: selectCallBack,
                clear: clear,
                loadDefaultList: loadDefaultList,
                initList: initList,
                getData: getData,
                getDataCallBack: getDataCallback,

                //properties
                isRunningSearch: false,
                baseUrl: '',
                items: [],
                displayItems: [],
                selectedItem: {},
                searchTerm: '',
                warehouse_id: null,
                client_id: null
        };

        return service;

        function selectItem(item) {
            //select item
            service.selectedItem = item;

            //run the callback for processing
            service.selectCallBack(item);
        }

        /* load the full list - used only for reports or where the warehouse/client selection is not linked to the one in the nav bar */
        function initList() {
            service.searchTerm = '';
            service.getData(true);
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
            //don't do if the lock is set
            if( service.isRunningSearch ) { return; }

            //set running search flag so no other ajax call can be make while waiting for this one
            service.isRunningSearch = true;

            //set a delay to finish the typing before submitting search
            setTimeout(function() {
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
                service.getData(false);

                //reset search flag
                service.isRunningSearch = false;
            }, 600);
        }

        /* This is the AJAX call the get the data */
        function getData(init) {
            $http({
                method: 'GET',
                url: service.baseUrl + '/products/search/?warehouse_id=' + service.warehouse_id + '&client_id=' + service.client_id + '&search_term=' + encodeURI(service.searchTerm)
            }).then(function successCallback(response) {
                //if init, then load the data into the main list
                if( init ) { service.items = response.data; }

                //replace data
                service.displayItems.length = 0;
                service.displayItems = response.data;

                //go to callback
                service.getDataCallBack(response.data, init);
            }, function errorCallback(response) {
                //set alert
                alert('The following error occurred in loading the data: ' + response.statusText);
            });
        }

        /* THIS IS MEANT TO BE OVERLOADED FOR ADDITIONAL PROCESSING AFTER AN ITEM IS SELECTED IN THE CONTROLLER */
        function selectCallBack(item) {}

        /* THIS FUNCTION IS MEANT TO BE OVERLOADED AFTER DATA IS BROUGHT BACK */
        function getDataCallback(data, init) {}

        function clear() {
            service.selectedItem = {};
            service.searchTerm = '';
        }
    });