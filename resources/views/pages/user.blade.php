<!-- app/views/page/user.blade.php -->

@extends('main')

@section('nav')
    @include('layouts.nav')
@stop

@section('body')
    <div class="container main-content" ng-controller="ListController as mainList" st-table="mainList.displayItems" st-safe-src="mainList.items">
        <div class="row content-header">
            <div class="col-lg-7 col-md-6 col-sm-5">
                <h1>Users</h1>
            </div>
            @include('layouts.search-bar', ['criterias' => ['username' => 'User Name', 'name' => 'Name', 'email' => 'Email', 'active' => 'Active']])
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
                        <th class="sort-header" st-sort="name" st-sort-default="true">Name</th>
                        <th class="sort-header" st-sort="username">User Name</th>
                        <th class="sort-header" st-sort="email">Email</th>
                        <th class="sort-header" st-sort="active">Active</th>
                    </tr>
                    <tr>
                        <td colspan="5" class="td-form">
                            <div class="collapse" id="new-item">
                                <form class="form-horizontal" id="formNew" name="formNew" ng-submit="formNew.$valid && mainList.add(formNew)" novalidate>
                                    <input type="hidden" name="remember_current_warehouse_client" value="true">
                                    <fieldset>
                                        @include('forms.new-username')
                                        @include('forms.new-user-group')
                                        @include('forms.new-name', ['size' => 50])
                                        @include('forms.new-email', ['required' => 'required'])
                                        @include('forms.new-password')

                                        @include('forms.divider')
                                        @include('forms.new-checkbox-list', ['title' => 'Warehouses',
                                                                             'name' => 'warehouses',
                                                                             'container_class' => 'checkbox-list-left',
                                                                             'list_data' => $warehouse_data,
                                                                             'master_list' => 'mainList.warehouseData'])
                                        @include('forms.new-client-list', ['container_class' => 'checkbox-list-right'])

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
                        <td ng-bind="item.username"></td>
                        <td ng-bind="item.email"></td>
                        <td><span ng-bind="item.active" ng-class="{'badge': item.active===true}"></span></td>
                    </tr>
                    <tr>
                        <td colspan="5" class="td-form">
                            <div class="collapse col-lg-12" id="item-@{{ item.id }}">
                                <form class="form-horizontal" name="dataForm" ng-submit="dataForm.$valid && mainList.save(item)" novalidate>
                                    <input class="item-id" type="hidden" ng-model="item.id" name="id">
                                    <input type="hidden" name="remember_current_warehouse_client" value="true">
                                    <fieldset>
                                        @include('forms.username')
                                        @include('forms.user-group')
                                        @include('forms.name', ['size' => 50])
                                        @include('forms.email', ['required' => 'required'])
                                        @include('forms.password')

                                        @include('forms.divider')
                                        @include('forms.checkbox-list', ['title' => 'Warehouses',
                                                                         'name' => 'warehouses',
                                                                         'id_name' => 'warehouse_id',
                                                                         'container_class' => 'checkbox-list-left',
                                                                         'list_data' => $warehouse_data,
                                                                         'master_list' => 'mainList.warehouseData'])
                                        @include('forms.client-list', ['container_class' => 'checkbox-list-right'])

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
         var userGroupData = {!! $user_group_data !!};
         var warehouseData = {!! $warehouse_data !!};
         var clientData = {!! $client_data !!};
     </script>
@stop

@section('js-footer')
    @include('layouts.angular-js')
    @include('layouts.js-lists')
@stop