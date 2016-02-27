app.controller('WarehouseClientSelectController', function($scope, warehouseClientSelectService) {
    var WarehouseClientSelectController = this;

    /* MAP PASS THROUGH FUNCTIONS */
    WarehouseClientSelectController.openSelectDialog = warehouseClientSelectService.openSelectDialog;

    warehouseClientSelectService.client_name = 'Hailing';
    warehouseClientSelectService.warehouse_name = 'TC Dallas';
    WarehouseClientSelectController.warehouse_name = warehouseClientSelectService.warehouse_name;
    WarehouseClientSelectController.client_name = warehouseClientSelectService.client_name;

});