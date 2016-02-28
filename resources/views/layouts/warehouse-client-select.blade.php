<form class="form-group" name="formWarehouseClient" novalidate>
    <div class="container nav-warehouse-client">
        <div class="row" style="margin-bottom: 15px;">
            <label class="control-label">Warehouse</label>
                <select class="form-control" name="warehouse_id" ng-model="wcc.selectedData.warehouse_id" ng-change="wcc.selectedData.client_id=null;wcc.updateName()"
                        ng-options="warehouse.id as warehouse.name for warehouse in wcc.listData">
                </select>
        </div>
        <div class="row">
            <label class="control-label">Client</label>
                <select class="form-control" name="client_id" ng-model="wcc.selectedData.client_id" ng-change=";wcc.updateName();wcc.updateData();"
                        ng-options="client.id as client.short_name for client in (wcc.listData | filter:{id:wcc.selectedData.warehouse_id})[0].clients">
                    <option value="">-- select a client --</option>
                </select>
        </div>
    </div>
</form>
