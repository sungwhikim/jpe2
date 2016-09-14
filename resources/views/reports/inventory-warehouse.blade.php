@extends('reports.report');

@section('report-criteria')
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-3">
            <label class="control-label">Warehouse</label>
            <select class="form-control" name="warehouse_id" ng-model="wcc.selectedData.warehouse_id" ng-change="wcc.selectedData.client_id=null;wcc.updateName()"
                    ng-options="warehouse.id as warehouse.name for warehouse in wcc.listData">
                <option value="">-- select --</option>
            </select>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-3">
            <label class="control-label">Client</label>
            <select class="form-control" name="client_id" ng-model="wcc.selectedData.client_id" ng-change="wcc.updateName();wcc.updateData();"
                    ng-options="client.id as client.short_name for client in (wcc.listData | filter:{id:wcc.selectedData.warehouse_id})[0].clients">
                <option value="">-- select --</option>
            </select>
        </div>
@stop

@section('report-data')
    <h2>This is the report</h2>
@stop