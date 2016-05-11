angular.module('searchSelectService', [])
    .factory('searchSelectService', function () {
        var service = {
                selectItem: selectItem,
                search: search,
                selectCallBack: selectCallBack,
                compare: compare,
                clear: clear
            },
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

        function search() {
            service.displayItems.length = 0;
            service.displayItems = [];

            for (var item in service.items) {
                if (!service.items.hasOwnProperty(item)) {
                    //The current property is not a direct property of the master
                    continue;
                }

                //add item by calling an over loadable function. This is so the particular search can be tailored
                //to each situation
                service.compare(service.items[item]);
            }
        }

        /* THIS IS MEANT TO BE OVERLOADED TO SEARCH ON THE PARTICULAR FIELD(S) OF THE CONTROL.  IT IS SET FOR
           SKU FOR PRODUCT SEARCH, BUT CAN BE CHANGED TO ANYTHING YOU WANT.  JUST MAKE SURE THE service.displayItems
           IS USED AS THE MODEL LIST.
         */
        function compare(item) {
            if( item.sku.search(new RegExp(service.searchTerm, "i")) !== -1 ) service.displayItems.push(item);
        }

        /* THIS IS MEANT TO BE OVERLOADED FOR ADDITIONAL PROCESSING AFTER AN ITEM IS SELECTED IN THE CONTROLLER */
        function selectCallBack(item) {}

        function clear() {
            service.selectedItem = {};
            service.searchTerm = '';
        }
    });