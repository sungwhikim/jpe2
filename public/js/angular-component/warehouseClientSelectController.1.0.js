/**
 *
 *  THE EXTERNAL VARIABLES NEED FOR THIS CONTROLLER FOR WORK ARE:
 *  1) warehouseClientCurrent - The currently selected data.
 *  2) warehouseClientData - The list of warehouses and clients available to this user.
 *  3) baseUrl - The base url of the app.
 */

app.controller('WarehouseClientSelectController', function($scope, warehouseClientSelectService) {
    var WarehouseClientSelectController = this;

    /* SET THE DATA FOR THE SERVICE */
    warehouseClientSelectService.selectedData = warehouseClientCurrent;
    warehouseClientSelectService.listData = warehouseClientData;
    warehouseClientSelectService.baseUrl = baseUrl;

    /* MAP PASS THROUGH FUNCTIONS AND PROPERTIES*/
    WarehouseClientSelectController.updateData = warehouseClientSelectService.updateData;
    WarehouseClientSelectController.updateName = warehouseClientSelectService.updateName;
    WarehouseClientSelectController.selectedData = warehouseClientSelectService.selectedData;
    WarehouseClientSelectController.listData = warehouseClientSelectService.listData;
});