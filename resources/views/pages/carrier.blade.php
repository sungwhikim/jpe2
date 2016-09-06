@extends('main')

@section('nav')
    @include('layouts.nav')
@stop

@section('body')
    <div class="container main-content" ng-controller="CarrierController as mainList" st-table="mainList.displayItems" st-safe-src="mainList.items">
        <div class="row content-header">
            <div class="col-lg-7 col-md-6 col-sm-5">
                <h1>Carriers</h1>
            </div>
            @include('layouts.search-bar', ['criterias' => ['name' => 'Name',
                                                            'client_warehouse.warehouse_name' => 'Warehouse',
                                                            'client_warehouse.client_name' => 'Client',
                                                            'client_warehouse.receive' => 'Receiving',
                                                            'client_warehouse.ship' => 'Ship',
                                                            'active' => 'Active']])
        </div>
        <div class="status-row">
            <div class="col-lg-12">
                <div class="alert alert-processing text-center" ng-hide="mainList"><strong>Loading Data....</strong></div>
                <div class="alert alert-dismissible alert-@{{ item.type }}" ng-repeat="item in mainList.alerts" ng-cloak>
                    <span class="glyphicon glyphicon-@{{ item.type }}"></span>&nbsp;
                    <button type="button" class="close" data-dismiss="item" ng-click="mainList.closeAlert($index)">x</button>
                    <span ng-bind="item.msg"></span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <table class="table table-striped table-hover table-form" ng-cloak>
                    <thead>
                    <tr>
                        <th class="td-button"><button class="btn btn-warning btn-sm" data-toggle="collapse" data-target="#new-item">New</button></th>
                        <th class="sort-header" st-sort="name" st-sort-default="true"><span>Name</span></th>
                        <th class="sort-header" st-sort="active"><span>Active</span></th>
                    </tr>
                    <tr>
                        <td colspan="3" class="td-form">
                            <div class="collapse" id="new-item">
                                <form class="form-horizontal" id="formNew" name="formNew" ng-submit="formNew.$valid && mainList.add(formNew)" novalidate>
                                    <input type="hidden" name="remember_current_warehouse_client" value="true">
                                    <fieldset>
                                        @include('forms.new-name', ['size' => 100])

                                        @include('forms.new-edit-buttons', ['active_flag' => true])
                                    </fieldset>
                                </form>
                            </div>
                        </td>
                    </tr>
                    </thead>
                    <tbody dir-paginate="item in mainList.displayItems | itemsPerPage: 10" class="tbody-form">
                    <tr>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-primary btn-sm" data-toggle="collapse" data-target="#item-@{{ item.id }}">Edit</button>
                            </div>
                        </td>
                        <td ng-bind="item.name"></td>
                        <td><span ng-bind="item.active" ng-class="{ 'tag-status-list' : item.active === true}"></span></td>
                    </tr>
                    <tr>
                        <td colspan="3" class="td-form">
                            <div class="collapse col-lg-12" id="item-@{{ item.id }}">
                                <form class="form-horizontal" name="dataForm" ng-submit="dataForm.$valid && mainList.save(item)" novalidate>
                                    <input class="item-id" type="hidden" ng-model="item.id" name="id">
                                    <fieldset>
                                        @include('forms.name', ['size' => 100])

                                        <div class="col-lg-11">
                                            <table class="table table-striped table-hover table-form">
                                                <tr>
                                                    <th class="col-lg-1 col-md-1 col-sm-1">
                                                        <button class="btn btn-warning" type="button" ng-click="mainList.newClientWarehouse(item)">New</button>
                                                    </th>
                                                    <th>Warehouse</th>
                                                    <th>Client</th>
                                                    <th class="col-lg-2 col-md-2 col-sm-2">Receiving</th>
                                                    <th class="col-lg-2 col-md-2 col-sm-2">Shipping</th>
                                                    <th class="col-lg-1 col-md-1 col-sm-1"></th>
                                                </tr>
                                                <tr ng-repeat="ccw_new in item.client_warehouse_new">
                                                    <td></td>
                                                    <td>
                                                        <select class="form-control" name="warehouse_id" ng-model="ccw_new.warehouse_id"
                                                                ng-options="warehouse.id as warehouse.name for warehouse in mainList.warehouseData">
                                                            <option value="">-- select a warehouse --</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select class="form-control" name="client_id" ng-model="ccw_new.client_id"
                                                                ng-options="client.id as client.short_name for client in mainList.clientData">
                                                            <option value="">-- select a client --</option>
                                                        </select>
                                                    </td>
                                                    <td>@include('forms.toggle-button-min', ['name' => 'ccw_new.receive'])</td>
                                                    <td>@include('forms.toggle-button-min', ['name' => 'ccw_new.ship'])</td>
                                                    <td>
                                                        <a ng-click="mainList.deleteClientWarehouse(item.client_warehouse_new, $index)"
                                                           class="btn glyphicon glyphicon-trash btn-delete"
                                                           data-toggle="tooltip" title="Delete"></a>
                                                    </td>
                                                </tr>
                                                <tr ng-repeat="ccw in item.client_warehouse">
                                                    <td></td>
                                                    <td ng-bind="ccw.warehouse_name"></td>
                                                    <td ng-bind="ccw.client_name"></td>
                                                    <td>@include('forms.toggle-button-min', ['name' => 'ccw.receive'])</td>
                                                    <td>@include('forms.toggle-button-min', ['name' => 'ccw.ship'])</td>
                                                    <td>
                                                        <a ng-click="mainList.deleteClientWarehouse(item.client_warehouse, $index)"
                                                           class="btn glyphicon glyphicon-trash btn-delete"
                                                           data-toggle="tooltip" title="Delete"></a>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>

                                        @include('forms.divider')
                                        @include('forms.edit-buttons', ['active_flag' => true])

                                    </fieldset>
                                </form>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="text-center pagination-container">
        <dir-pagination-controls boundary-links="true"></dir-pagination-controls>
    </div>
@stop

@section('js-data')
     <script>
         var myName  = '{{ $my_name }}';
         var appUrl  = '{{ $url }}';
         var appData = {!! $main_data !!};
         var warehouseData = {!! $warehouse_data !!};
         var clientData = {!! $client_data !!};
     </script>
@stop

@section('js-footer')
    @include('layouts.angular-js')
    @include('layouts.carrier-js')
@stop