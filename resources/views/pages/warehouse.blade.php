<!-- app/views/page/country.blade.php -->

@extends('main')

@section('body')
    <div class="container main-content" ng-controller="WarehouseListController as warehouseList" st-table="warehouseList.displayItems" st-safe-src="warehouseList.items">
        <div class="row content-header">
            <div class="col-lg-7 col-md-6 col-sm-5">
                <h1>Warehouses</h1>
            </div>
            <div class="col-lg-5 col-md-6 col-sm-7 col-xs-10">
                <span class="item-total-container"><span id="item-total" class="badge" ng-bind="warehouseList.items.length"></span>  items</span>
                <form class="">
                    <div class="form-group">
                        <label class="control-label"></label>
                        <div class="input-group">
                        <span class="search-criteria">
                            <select class="form-control" ng-model="warehouseList.searchCriteria">
                                <option value="">All</option>
                                <option value="short_name">Short Name</option>
                                <option value="name">Name</option>
                                <option value="city">city</option>
                            </select>
                        </span>
                            <input placeholder="Search" st-search="@{{ warehouseList.searchCriteria }}" class="form-control" type="search"/>
                        <span class="input-group-btn">
                          <button class="btn btn-default" type="button"><span class="glyphicon glyphicon-search"></span></button>
                        </span>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="status-row">
            <div class="col-lg-12">
                <div class="alert alert-dismissible alert-@{{ item.type }}" ng-repeat="item in warehouseList.alerts">
                    <button type="button" class="close" data-dismiss="item" ng-click="warehouseList.closeAlert($index)">x</button>
                    <div ng-bind="item.msg"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <table class="table table-striped table-hover table-form">
                    <thead>
                    <tr>
                        <th class="td-button"><button class="btn btn-warning btn-sm" data-toggle="collapse" data-target="#new-item">New</button></th>
                        <th class="sort-header" st-sort="short_name" st-sort-default="true">Short Name</th>
                        <th class="sort-header" st-sort="name">Name</th>
                        <th class="sort-header" st-sort="city">City</th>
                    </tr>
                    <tr>
                        <td colspan="4" class="td-form">
                            <div class="collapse" id="new-item">
                                <form class="form-horizontal" id="formNew" name="formNew" ng-submit="formNew.$valid && warehouseList.new(formNew)" novalidate>
                                    <fieldset>
                                        <div class="row col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group"
                                             ng-class="{ 'has-error': (formNew.short_name.$touched || formNew.$submitted) && formNew.short_name.$invalid }">
                                            <label class="col-lg-2 control-label">Short Name <span class="required-field glyphicon glyphicon-asterisk" /></label>
                                            <div class="col-lg-8">
                                                <input type="text" class="form-control" name="short_name" placeholder="Short Name"
                                                       ng-model="warehouseList.newItem.short_name" ng-maxlength="20"
                                                       ng-model-options="{ updateOn: 'blur' }" required>
                                                <!-- ngMessages goes here -->
                                                <div class="help-block ng-messages" ng-messages="formNew.short_name.$error"
                                                     ng-if="formNew.$submitted || formNew.short_name.$touched">
                                                    @include('layouts.validate-message')
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group"
                                             ng-class="{ 'has-error': (formNew.name.$touched || formNew.$submitted) && formNew.name.$invalid }">
                                            <label class="col-lg-2 control-label">Name <span class="required-field glyphicon glyphicon-asterisk" /></label>
                                            <div class="col-lg-8">
                                                <input type="text" class="form-control" name="name" placeholder="Name"
                                                       ng-model="warehouseList.newItem.name" ng-maxlength="50"
                                                       ng-model-options="{ updateOn: 'blur' }" required>
                                                <!-- ngMessages goes here -->
                                                <div class="help-block ng-messages" ng-messages="formNew.name.$error"
                                                     ng-if="formNew.$submitted || formNew.name.$touched">
                                                    @include('layouts.validate-message')
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group"
                                             ng-class="{ 'has-error': (formNew.address1.$touched || formNew.$submitted) && formNew.address1.$invalid }">
                                            <label class="col-lg-2 control-label">Address 1 <span class="required-field glyphicon glyphicon-asterisk" /></label>
                                            <div class="col-lg-8">
                                                <input type="text" class="form-control" name="address1" placeholder="Address 1"
                                                       ng-model="warehouseList.newItem.address1" ng-maxlength="100"
                                                       ng-model-options="{ updateOn: 'blur' }" required>
                                                <!-- ngMessages goes here -->
                                                <div class="help-block ng-messages" ng-messages="formNew.address1.$error"
                                                     ng-if="formNew.$submitted || formNew.address1.$touched">
                                                    @include('layouts.validate-message')
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group"
                                             ng-class="{ 'has-error': (formNew.address2.$touched || formNew.$submitted) && formNew.address2.$invalid }">
                                            <label class="col-lg-2 control-label">Address 2</label>
                                            <div class="col-lg-8">
                                                <input type="text" class="form-control" name="address2" placeholder="Address 2"
                                                       ng-model="warehouseList.newItem.address2" ng-maxlength="100"
                                                       ng-model-options="{ updateOn: 'blur' }">
                                                <!-- ngMessages goes here -->
                                                <div class="help-block ng-messages" ng-messages="formNew.address2.$error"
                                                     ng-if="formNew.$submitted || formNew.address2.$touched">
                                                    @include('layouts.validate-message')
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group"
                                             ng-class="{ 'has-error': (formNew.city.$touched || formNew.$submitted) && formNew.city.$invalid }">
                                            <label class="col-lg-2 control-label">City <span class="required-field glyphicon glyphicon-asterisk" /></label>
                                            <div class="col-lg-8">
                                                <input type="text" class="form-control" name="city" placeholder="City"
                                                       ng-model="warehouseList.newItem.city" ng-maxlength="50"
                                                       ng-model-options="{ updateOn: 'blur' }" required>
                                                <!-- ngMessages goes here -->
                                                <div class="help-block ng-messages" ng-messages="formNew.city.$error"
                                                     ng-if="formNew.$submitted || formNew.city.$touched">
                                                    @include('layouts.validate-message')
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group"
                                             ng-class="{ 'has-error': (formNew.postal_code.$touched || formNew.$submitted) && formNew.postal_code.$invalid }">
                                            <label class="col-lg-2 control-label">Postal Code <span class="required-field glyphicon glyphicon-asterisk" /></label>
                                            <div class="col-lg-8">
                                                <input type="text" class="form-control" name="postal_code" placeholder="Postal Code"
                                                       ng-model="warehouseList.newItem.postal_code" ng-maxlength="30"
                                                       ng-model-options="{ updateOn: 'blur' }" required>
                                                <!-- ngMessages goes here -->
                                                <div class="help-block ng-messages" ng-messages="formNew.postal_code.$error"
                                                     ng-if="formNew.$submitted || formNew.postal_code.$touched">
                                                    @include('layouts.validate-message')
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group"
                                             ng-class="{ 'has-error': (formNew.country_id.$touched || formNew.$submitted) && formNew.country_id.$invalid }">
                                            <label class="col-lg-2 control-label">Country <span class="required-field glyphicon glyphicon-asterisk" /></label>
                                            <div class="col-lg-8">
                                                <select class="form-control" name="country_id" ng-model="warehouseList.newItem.country_id"
                                                        ng-change="formNew.province_id=null" ng-options="country.id as country.name for country in warehouseList.countries" required>
                                                    <option value="">-- select a country --</option>
                                                </select>
                                                <!-- ngMessages goes here -->
                                                <div class="help-block ng-messages" ng-messages="formNew.country_id.$error" ng-if="formNew.$submitted || formNew.country_id.$touched">
                                                    @include('layouts.validate-message')
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group"
                                             ng-class="{ 'has-error': (formNew.province_id.$touched || formNew.$submitted) && formNew.province_id.$invalid }">
                                            <label class="col-lg-2 control-label">Province/State <span class="required-field glyphicon glyphicon-asterisk" /></label>
                                            <div class="col-lg-8">
                                                <select class="form-control" name="province_id" ng-model="warehouseList.newItem.province_id" required
                                                        ng-options="province.id as province.name for province in (warehouseList.countries | filter:{id:warehouseList.newItem.country_id})[0].provinces">
                                                    <option value="">-- select a province/state --</option>
                                                </select>
                                                <!-- ngMessages goes here -->
                                                <div class="help-block ng-messages" ng-messages="formNew.province_id.$error"
                                                     ng-if="formNew.$submitted || formNew.province_id.$touched">
                                                    @include('layouts.validate-message')
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row col-lg-12 col-md-12 col-sm-12">
                                            <div class="row col-lg-6 col-md-6 col-sm-6 col-xs-12 form-group button-group">
                                                <div class="col-lg-2 control-label"></div>
                                                <div class="col-lg-6 col-md-8 col-sm-7 col-xs-5 text-left">
                                                    <button class="btn btn-default" type="reset" data-toggle="collapse" data-target="#new-item">Cancel</button>
                                                    <button type="submit" class="btn btn-success">Add</button>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                </form>
                            </div>
                        </td>
                    </tr>
                    </thead>
                    <tbody dir-paginate="item in warehouseList.displayItems | itemsPerPage: 10" class="tbody-form">
                    <tr>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-primary btn-sm" data-toggle="collapse" data-target="#item-@{{ item.id }}">Edit</button>
                            </div>
                        </td>
                        <td ng-bind="item.short_name"></td>
                        <td ng-bind="item.name"></td>
                        <td ng-bind="item.city"></td>
                    </tr>
                    <tr>
                        <td colspan="4" class="td-form">
                            <div class="collapse col-lg-12" id="item-@{{ item.id }}">
                                <form class="form-horizontal" name="dataForm" ng-submit="warehouseList.save(item)" novalidate>
                                    <input class="item-id" type="hidden" ng-model="item.id" name="id">
                                    <fieldset>
                                        <div class="row col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group"
                                             ng-class="{ 'has-error': (dataForm.short_name.$touched || dataForm.$submitted) && dataForm.short_name.$invalid }">
                                            <label class="col-lg-2 control-label">Short Name <span class="required-field glyphicon glyphicon-asterisk" /></label>
                                            <div class="col-lg-8">
                                                <input type="text" class="form-control" name="short_name" placeholder="Short Name"
                                                       ng-model="item.short_name" ng-maxlength="20"
                                                       ng-model-options="{ updateOn: 'blur' }" required>
                                                <!-- ngMessages goes here -->
                                                <div class="help-block ng-messages" ng-messages="dataForm.short_name.$error"
                                                     ng-if="dataForm.$submitted || dataForm.short_name.$touched">
                                                    @include('layouts.validate-message')
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group"
                                             ng-class="{ 'has-error': (dataForm.name.$touched || dataForm.$submitted) && dataForm.name.$invalid }">
                                            <label class="col-lg-2 control-label">Name <span class="required-field glyphicon glyphicon-asterisk" /></label>
                                            <div class="col-lg-8">
                                                <input type="text" class="form-control" name="name" placeholder="Name"
                                                       ng-model="item.name" ng-maxlength="50"
                                                       ng-model-options="{ updateOn: 'blur' }" required>
                                                <!-- ngMessages goes here -->
                                                <div class="help-block ng-messages" ng-messages="dataForm.name.$error"
                                                     ng-if="dataForm.$submitted || dataForm.name.$touched">
                                                    @include('layouts.validate-message')
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group"
                                             ng-class="{ 'has-error': (dataForm.address1.$touched || dataForm.$submitted) && dataForm.address1.$invalid }">
                                            <label class="col-lg-2 control-label">Address 1 <span class="required-field glyphicon glyphicon-asterisk" /></label>
                                            <div class="col-lg-8">
                                                <input type="text" class="form-control" name="address1" placeholder="Address 1"
                                                       ng-model="item.address1" ng-maxlength="100"
                                                       ng-model-options="{ updateOn: 'blur' }" required>
                                                <!-- ngMessages goes here -->
                                                <div class="help-block ng-messages" ng-messages="dataForm.address1.$error"
                                                     ng-if="dataForm.$submitted || dataForm.address1.$touched">
                                                    @include('layouts.validate-message')
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group"
                                             ng-class="{ 'has-error': (dataForm.address2.$touched || dataForm.$submitted) && dataForm.address2.$invalid }">
                                            <label class="col-lg-2 control-label">Address 2</label>
                                            <div class="col-lg-8">
                                                <input type="text" class="form-control" name="address2" placeholder="Address 2"
                                                       ng-model="item.address2" ng-maxlength="100"
                                                       ng-model-options="{ updateOn: 'blur' }">
                                                <!-- ngMessages goes here -->
                                                <div class="help-block ng-messages" ng-messages="dataForm.address2.$error"
                                                     ng-if="dataForm.$submitted || dataForm.address2.$touched">
                                                    @include('layouts.validate-message')
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group"
                                             ng-class="{ 'has-error': (dataForm.city.$touched || dataForm.$submitted) && dataForm.city.$invalid }">
                                            <label class="col-lg-2 control-label">City <span class="required-field glyphicon glyphicon-asterisk" /></label>
                                            <div class="col-lg-8">
                                                <input type="text" class="form-control" name="city" placeholder="City"
                                                       ng-model="item.city" ng-maxlength="50"
                                                       ng-model-options="{ updateOn: 'blur' }" required>
                                                <!-- ngMessages goes here -->
                                                <div class="help-block ng-messages" ng-messages="dataForm.city.$error"
                                                     ng-if="dataForm.$submitted || dataForm.city.$touched">
                                                    @include('layouts.validate-message')
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group"
                                             ng-class="{ 'has-error': (dataForm.postal_code.$touched || dataForm.$submitted) && dataForm.postal_code.$invalid }">
                                            <label class="col-lg-2 control-label">Postal Code <span class="required-field glyphicon glyphicon-asterisk" /></label>
                                            <div class="col-lg-8">
                                                <input type="text" class="form-control" name="postal_code" placeholder="Postal Code"
                                                       ng-model="item.postal_code" ng-maxlength="30"
                                                       ng-model-options="{ updateOn: 'blur' }" required>
                                                <!-- ngMessages goes here -->
                                                <div class="help-block ng-messages" ng-messages="dataForm.postal_code.$error"
                                                     ng-if="dataForm.$submitted || dataForm.postal_code.$touched">
                                                    @include('layouts.validate-message')
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group">
                                            <label class="col-lg-2 control-label">Country <span class="required-field glyphicon glyphicon-asterisk" /></label>
                                            <div class="col-lg-8">
                                                <select class="form-control" name="country_id" ng-model="item.country_id" ng-change="item.province_id=null"
                                                        ng-options="country.id as country.name for country in warehouseList.countries">
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group"
                                             ng-class="{ 'has-error': (dataForm.province_id.$touched || dataForm.$submitted) && dataForm.province_id.$invalid }">
                                            <label class="col-lg-2 control-label">Province/State <span class="required-field glyphicon glyphicon-asterisk" /></label>
                                            <div class="col-lg-8">
                                                <select class="form-control" name="province_id" ng-model="item.province_id" required
                                                        ng-options="province.id as province.name for province in (warehouseList.countries | filter:{id:item.country_id})[0].provinces">
                                                </select>
                                                <!-- ngMessages goes here -->
                                                <div class="help-block ng-messages" ng-messages="dataForm.province_id.$error"
                                                     ng-if="dataForm.$submitted || dataForm.province_id.$touched">
                                                    @include('layouts.validate-message')
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row col-lg-12 col-md-12 col-sm-12">
                                            <div class="row col-lg-6 col-md-6 col-sm-6 col-xs-12 form-group button-group">
                                                <div class="col-lg-2 control-label"></div>
                                                <div class="col-lg-6 col-md-8 col-sm-7 col-xs-5 text-left">
                                                    <button class="btn btn-default" ng-click="warehouseList.resetData()">Cancel</button>
                                                    <button type="submit" class="btn btn-success">Save</button>
                                                </div>
                                                <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
                                                    <a ng-click="warehouseList.deleteConfirm($index)"
                                                       class="btn glyphicon glyphicon-trash btn-delete"
                                                       data-toggle="tooltip" title="Delete"></a>
                                                </div>
                                            </div>
                                        </div>
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
         var countryData = {!! $country_data !!};
     </script>
@stop

@section('js-footer')
    @include('layouts.angular-js')

    <!-- open source components -->
    <script src="/js/angular-library/dirPaginate.js"></script>
    <script src="/js/angular-library/smart-table.js"></script>

    <!-- custom components -->
    <script src="/js/angular-component/modalService-1.0.js"></script>
    <script src="/js/angular-component/modalService-yesnocontroller.js"></script>
    <script src="/js/angular-component/alertService-1.0.js"></script>
    <script src="/js/angular-component/{{ $my_name }}Controller-1.0.js"></script>
@stop