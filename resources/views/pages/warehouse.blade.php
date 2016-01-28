<!-- app/views/page/country.blade.php -->

@extends('main')

@section('body')
    <div class="container main-content" ng-controller="WarehouseListController as mainList" st-table="mainList.displayItems" st-safe-src="mainList.items">
        <div class="row content-header">
            <div class="col-lg-7 col-md-6 col-sm-5">
                <h1>Warehouses</h1>
            </div>
            <div class="col-lg-5 col-md-6 col-sm-7 col-xs-10" ng-cloak>
                <span class="item-total-container"><span id="item-total" class="badge" ng-bind="mainList.items.length"></span>  items</span>
                <form class="">
                    <div class="form-group">
                        <label class="control-label"></label>
                        <div class="input-group">
                        <span class="search-criteria">
                            <select class="form-control" ng-model="mainList.searchCriteria">
                                <option value="">All</option>
                                <option value="short_name">Short Name</option>
                                <option value="name">Name</option>
                                <option value="city">City</option>
                                <option value="active">Active</option>
                            </select>
                        </span>
                            <input placeholder="Search" st-search="@{{ mainList.searchCriteria }}" class="form-control" type="search"/>
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
                        <th class="sort-header" st-sort="short_name" st-sort-default="true">Short Name</th>
                        <th class="sort-header" st-sort="name">Name</th>
                        <th class="sort-header" st-sort="city">City</th>
                        <th class="sort-header" st-sort="active">Active</th>
                    </tr>
                    <tr>
                        <td colspan="5" class="td-form">
                            <div class="collapse" id="new-item">
                                <form class="form-horizontal" id="formNew" name="formNew" ng-submit="formNew.$valid && mainList.new(formNew)" novalidate>
                                    <fieldset>
                                        <div class="row col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group"
                                             ng-class="{ 'has-error': (formNew.short_name.$touched || formNew.$submitted) && formNew.short_name.$invalid }">
                                            <label class="col-lg-3 control-label text-right">Short Name <span class="required-field glyphicon glyphicon-asterisk" /></label>
                                            <div class="col-lg-8">
                                                <input type="text" class="form-control" name="short_name" placeholder="Short Name"
                                                       ng-model="mainList.newItem.short_name" ng-maxlength="20"
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
                                            <label class="col-lg-3 control-label text-right">Name <span class="required-field glyphicon glyphicon-asterisk" /></label>
                                            <div class="col-lg-8">
                                                <input type="text" class="form-control" name="name" placeholder="Name"
                                                       ng-model="mainList.newItem.name" ng-maxlength="50"
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
                                            <label class="col-lg-3 control-label text-right">Address 1 <span class="required-field glyphicon glyphicon-asterisk" /></label>
                                            <div class="col-lg-8">
                                                <input type="text" class="form-control" name="address1" placeholder="Address 1"
                                                       ng-model="mainList.newItem.address1" ng-maxlength="100"
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
                                            <label class="col-lg-3 control-label text-right">Address 2</label>
                                            <div class="col-lg-8">
                                                <input type="text" class="form-control" name="address2" placeholder="Address 2"
                                                       ng-model="mainList.newItem.address2" ng-maxlength="100"
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
                                            <label class="col-lg-3 control-label text-right">City <span class="required-field glyphicon glyphicon-asterisk" /></label>
                                            <div class="col-lg-8">
                                                <input type="text" class="form-control" name="city" placeholder="City"
                                                       ng-model="mainList.newItem.city" ng-maxlength="50"
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
                                            <label class="col-lg-3 control-label text-right">Postal Code <span class="required-field glyphicon glyphicon-asterisk" /></label>
                                            <div class="col-lg-8">
                                                <input type="text" class="form-control" name="postal_code" placeholder="Postal Code"
                                                       ng-model="mainList.newItem.postal_code" ng-maxlength="30"
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
                                            <label class="col-lg-3 control-label text-right">Country <span class="required-field glyphicon glyphicon-asterisk" /></label>
                                            <div class="col-lg-8">
                                                <select class="form-control" name="country_id" ng-model="mainList.newItem.country_id"
                                                        ng-change="formNew.province_id=null" ng-options="country.id as country.name for country in mainList.countries" required>
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
                                            <label class="col-lg-3 control-label text-right">Province/State <span class="required-field glyphicon glyphicon-asterisk" /></label>
                                            <div class="col-lg-8">
                                                <select class="form-control" name="province_id" ng-model="mainList.newItem.province_id" required
                                                        ng-options="province.id as province.name for province in (mainList.countries | filter:{id:mainList.newItem.country_id})[0].provinces">
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
                                                <div class="col-lg-3 control-label text-right"></div>
                                                <div class="col-lg-5 col-md-8 col-sm-7 col-xs-6 text-left">
                                                    <button class="btn btn-default" type="reset" data-toggle="collapse" data-target="#new-item">Cancel</button>
                                                    <button type="submit" class="btn btn-success">Add</button>
                                                </div>
                                                <div class="col-lg-3 col-md-4 col-sm-5 col-xs-4">
                                                    <div class="checkbox checkbox-slider--b checkbox-slider-md">
                                                        <label>
                                                            <input type="checkbox" name="active" value="true" ng-model="mainList.newItem.active"><span>Active</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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
                        <td ng-bind="item.short_name"></td>
                        <td ng-bind="item.name"></td>
                        <td ng-bind="item.city"></td>
                        <td><span ng-bind="item.active" ng-class="{'badge': item.active===true}"></span></td>
                    </tr>
                    <tr>
                        <td colspan="5" class="td-form">
                            <div class="collapse col-lg-12" id="item-@{{ item.id }}">
                                <form class="form-horizontal" name="dataForm" ng-submit="dataForm.$valid && mainList.save(item)" novalidate>
                                    <input class="item-id" type="hidden" ng-model="item.id" name="id">
                                    <fieldset>
                                        <div class="row col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group"
                                             ng-class="{ 'has-error': (dataForm.short_name.$touched || dataForm.$submitted) && dataForm.short_name.$invalid }">
                                            <label class="col-lg-3 control-label text-right">Short Name <span class="required-field glyphicon glyphicon-asterisk" /></label>
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
                                            <label class="col-lg-3 control-label text-right">Name <span class="required-field glyphicon glyphicon-asterisk" /></label>
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
                                            <label class="col-lg-3 control-label text-right">Address 1 <span class="required-field glyphicon glyphicon-asterisk" /></label>
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
                                            <label class="col-lg-3 control-label text-right">Address 2</label>
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
                                            <label class="col-lg-3 control-label text-right">City <span class="required-field glyphicon glyphicon-asterisk" /></label>
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
                                            <label class="col-lg-3 control-label text-right">Postal Code <span class="required-field glyphicon glyphicon-asterisk" /></label>
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
                                            <label class="col-lg-3 control-label text-right">Country <span class="required-field glyphicon glyphicon-asterisk" /></label>
                                            <div class="col-lg-8">
                                                <select class="form-control" name="country_id" ng-model="item.country_id" ng-change="item.province_id=null"
                                                        ng-options="country.id as country.name for country in mainList.countries">
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group"
                                             ng-class="{ 'has-error': (dataForm.province_id.$touched || dataForm.$submitted) && dataForm.province_id.$invalid }">
                                            <label class="col-lg-3 control-label text-right">Province/State <span class="required-field glyphicon glyphicon-asterisk" /></label>
                                            <div class="col-lg-8">
                                                <select class="form-control" name="province_id" ng-model="item.province_id" required
                                                        ng-options="province.id as province.name for province in (mainList.countries | filter:{id:item.country_id})[0].provinces">
                                                </select>
                                                <!-- ngMessages goes here -->
                                                <div class="help-block ng-messages" ng-messages="dataForm.province_id.$error"
                                                     ng-if="dataForm.$submitted || dataForm.province_id.$touched">
                                                    @include('layouts.validate-message')
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row col-lg-12 col-md-12 col-sm-12">
                                            <div class="row col-lg-6 col-md-6 col-sm-7 col-xs-12 form-group button-group">
                                                <div class="col-lg-3 control-label text-right"></div>
                                                <div class="col-lg-5 col-md-8 col-sm-7 col-xs-6 text-left">
                                                    <button class="btn btn-default" ng-click="mainList.resetData()">Cancel</button>
                                                    <button type="submit" class="btn btn-success">Save</button>
                                                    <a ng-click="mainList.deleteConfirm($index)"
                                                       class="btn glyphicon glyphicon-trash btn-delete"
                                                       data-toggle="tooltip" title="Delete"></a>
                                                </div>
                                                <div class="col-lg-3 col-md-4 col-sm-5 col-xs-3">
                                                    <div class="checkbox checkbox-slider--b checkbox-slider-md">
                                                        <label>
                                                            <input type="checkbox" name="active" value="true" ng-model="item.active"><span>Active</span>
                                                        </label>
                                                    </div>
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