angular.module('warehouseClientSelectService', [])
    .factory('warehouseClientSelectService', function () {
        var service = {
                updateName: updateName,
                updateData: updateData
            },
            selectedData = [],
            listData = [];

        return service;

        function updateName() {
            //update warehouse
            var warehouseObj = getValueById(this.listData, this.selectedData.warehouse_id);
            this.selectedData.warehouse_name = warehouseObj ? warehouseObj.name : '' ;

            //update client
            var clientObj = getValueById(warehouseObj.clients, this.selectedData.client_id);
            this.selectedData.client_short_name = clientObj ? clientObj.short_name : '' ;
        }

        function updateData() {
            //set local variables
            var warehouse_id = this.selectedData.warehouse_id;
            var client_id = this.selectedData.client_id;

            //check to see if warehouse and client was selected
            if( isNaN(warehouse_id) || isNaN(client_id) ) {
                this.errorMsg = 'Please select a valid Warehouse and Client';
                return;
            }

            //go save the current selection data

        }

        function getValueById(data, id) {
            for( var i = 0; i < data.length; i++ ) {
                if( data[i].id == id ) { return data[i]; }
            }
        }
    });