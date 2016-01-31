<!-- app/views/page/country.blade.php -->

@extends('main')

@section('nav')
    @include('layouts.nav')
@stop

@section('body')
    <div class="container main-content" ng-controller="CountryListController as mainList" st-table="mainList.displayItems" st-safe-src="mainList.items">
        <div class="row content-header">
            <div class="col-lg-7 col-md-6 col-sm-5">
                <h1>Countries</h1>
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
                                <option value="code">Code</option>
                                <option value="name">Name</option>
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
                <div class="alert alert-processing text-center" ng-hide="mainList"><strong>Loading Data...</strong></div>
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
                        <th class="sort-header" st-sort="code" st-sort-default="true">Code</th>
                        <th class="sort-header" st-sort="name">Name</th>
                    </tr>
                    <tr>
                        <td colspan="3" class="td-form">
                            <div class="collapse" id="new-item">
                                <form class="form-horizontal" id="formNew" name="formNew" ng-submit="formNew.$valid && mainList.new(formNew)" novalidate>
                                    <fieldset>
                                        @include('forms.new-code', ['size' => 2])
                                        @include('forms.new-name', ['size' => 100])
                                        @include('forms.new-edit-buttons')
                                    </fieldset>
                                </form>
                            </div>
                        </td>
                    </tr>
                    </thead>
                    <tbody dir-paginate="item in mainList.displayItems | itemsPerPage: 10" class="tbody-form">
                    <tr>
                        <td>
                            <button class="btn btn-primary btn-sm" data-toggle="collapse" data-target="#item-@{{ item.id }}">Edit</button>
                        </td>
                        <td ng-bind="item.code"></td>
                        <td ng-bind="item.name"></td>
                    </tr>
                    <tr>
                        <td colspan="3" class="td-form">
                            <div class="collapse" id="item-@{{ item.id }}">
                                <form class="form-horizontal" name="dataForm" ng-submit="dataForm.$valid && mainList.save(item)" novalidate>
                                    <fieldset>
                                        @include('forms.code', ['size' => 2])
                                        @include('forms.name', ['size' => 100])
                                        @include('forms.edit-buttons')
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