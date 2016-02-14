<!-- app/views/page/country.blade.php -->

@extends('main')

@section('nav')
    @include('layouts.nav')
@stop

@section('body')
    <div class="container main-content" ng-controller="ListController as mainList" st-table="mainList.displayItems" st-safe-src="mainList.items">
        <div class="row content-header">
            <div class="col-lg-7 col-md-6 col-sm-5">
                <h1>Countries</h1>
            </div>
            @include('layouts.search-bar', ['criterias' => ['code' => 'Code', 'name' => 'Name']])
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
                                <form class="form-horizontal" id="formNew" name="formNew" ng-submit="formNew.$valid && mainList.add(formNew)" novalidate>
                                    <fieldset>
                                        @include('forms.new-code', ['size' => 2])
                                        @include('forms.new-name', ['size' => 100])
                                        @include('forms.new-text', ['title' => 'Currency Name',
                                                                    'name' => 'currency_name',
                                                                    'size' => 30,
                                                                    'required' => 'required'])
                                        @include('forms.new-text', ['title' => 'Currency Prefix',
                                                                    'name' => 'currency_prefix',
                                                                    'size' => 30,
                                                                    'required' => 'required'])
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
                                        @include('forms.text', ['title' => 'Currency Name',
                                                                'name' => 'currency_name',
                                                                'size' => 30,
                                                                'required' => 'required'])
                                        @include('forms.text', ['title' => 'Currency Prefix',
                                                                    'name' => 'currency_prefix',
                                                                    'size' => 30,
                                                                    'required' => 'required'])
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
    @include('layouts.js-lists');
@stop