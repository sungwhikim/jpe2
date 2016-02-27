<div class="row col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group">
    <label class="col-lg-3 control-label text-right">Default Warehouse</label>
    <div class="col-lg-8">
        <select class="form-control" name="warehouse_id" ng-model="item.default_warehouse_id" ng-change="mainList.newItem.client_id=null"
                ng-options="warehouse.id as warehouse.name for warehouse in mainList.warehouse_client">
            <option value="">-- select a warehouse --</option>
        </select>
    </div>
</div>
<div class="row col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group">
    <label class="col-lg-3 control-label text-right">Default Client</label>
    <div class="col-lg-8">
        <select class="form-control" name="client_id" ng-model="mainList.newItem.default_client_id"
                ng-options="client.id as client.short_name for client in (mainList.warehouse_client | filter:{id:mainList.newItem.default_warehouse_id})[0].clients">
            <option value="">-- select a client --</option>
        </select>
    </div>
</div>