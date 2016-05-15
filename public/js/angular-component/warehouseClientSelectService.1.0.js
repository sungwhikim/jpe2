/**
 * This service is to control the warehouse and client selector.  The controller warehouseClientController.1.0.js is
 * used to control the service.
 */

angular.module('warehouseClientSelectService', [])
    .factory('warehouseClientSelectService', function ($http) {
        var service = {
                updateName: updateName,
                updateData: updateData,
                refreshData: refreshData
            },
            selectedData = [],
            listData = [],
            errorMessage = '',
            baseUrl = '';

        return service;

        /* THIS IS TO BE OVERLOADED BY THE CONTROLLER IF DATA NEEDS TO BE REFRESHED AFTER A WAREHOUSE/CLIENT CHANGE */
        function refreshData() {}

        /* Updates the name of the warehouse and client */
        function updateName() {
            //update warehouse
            var warehouseObj = getValueById(this.listData, this.selectedData.warehouse_id);
            this.selectedData.warehouse_name = warehouseObj ? warehouseObj.name : '';

            //update client
            var clientObj = getValueById(warehouseObj.clients, this.selectedData.client_id);
            this.selectedData.client_short_name = clientObj ? clientObj.short_name : '';
            this.selectedData.show_barcode_client = clientObj ? clientObj.show_barcode_client : '';
        }

        /* Updates the data on the database of the currently selected warehouse/client */
        function updateData() {
            //set local variables
            var warehouse_id = this.selectedData.warehouse_id;
            var client_id = this.selectedData.client_id;

            //check to see if warehouse and client was selected
            if( warehouse_id === null || warehouse_id.length == 0 ||
                    client_id === null || client_id.length == 0 ) {
                service.errorMessage = 'Please select a valid Warehouse and Client';
                alert(service.errorMessage);
                return false;
            }

            //go save the current selection data
            $http({
                method: 'PUT',
                url: service.baseUrl + '/user/update-warehouse-client/' + warehouse_id + '/' + client_id
            }).then(function successCallback(response) {
                //call stub function to refresh the data
                service.refreshData();
            }, function errorCallback(response) {
                //set alert
                //sendAlert('danger', getAlertMsg(curItem.name, 'updated', response.statusText));
                alert('Changing warehouse and client failed');
                console.log(response);
            });
        }

        /* Helper function to get object by id value */
        function getValueById(data, id) {
            for( var i = 0; i < data.length; i++ ) {
                if( data[i].id == id ) { return data[i]; }
            }
        }
    });