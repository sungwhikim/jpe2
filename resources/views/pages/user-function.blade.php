@extends('main')

@section('nav')
    @include('layouts.nav')
@stop

@section('body')
    <div class="container main-content" ng-controller="ListController as mainList" st-table="mainList.displayItems" st-safe-src="mainList.items">
        <div class="row content-header">
            <div class="col-lg-7 col-md-6 col-sm-5">
                <h1>User Functions</h1>
            </div>
            @include('layouts.search-bar', ['criterias' => ['name' => 'Name', 'url' => 'Url', 'category_name' => 'Category', 'active' => 'Active']])
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
                        <th class="sort-header" st-sort="name" st-sort-default="true">Name</th>
                        <th class="sort-header" st-sort="category_name">Category</th>
                        <th class="sort-header" st-sort="sort_order">Sort Order</th>
                        <th class="sort-header" st-sort="active">Active</th>
                    </tr>
                    <tr>
                        <td colspan="5" class="td-form">
                            <div class="collapse" id="new-item">
                                <form class="form-horizontal" id="formNew" name="formNew" ng-submit="formNew.$valid && mainList.add(formNew)" novalidate>
                                    <fieldset>
                                        @include('forms.new-name', ['size' => 50])
                                        @include('forms.new-url')
                                        @include('forms.new-category')
                                        @include('forms.new-sort-order')
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
                            <button class="btn btn-primary btn-sm" data-toggle="collapse" data-target="#item-@{{ item.id }}">Edit</button>
                        </td>
                        <td ng-bind="item.name"></td>
                        <td ng-bind="item.category_name"></td>
                        <td ng-bind="item.sort_order"></td>
                        <td><span ng-bind="item.active" ng-class="{'badge': item.active===true}"></span></td>
                    </tr>
                    <tr>
                        <td colspan="5" class="td-form">
                            <div class="collapse" id="item-@{{ item.id }}">
                                <form class="form-horizontal" name="dataForm" ng-submit="dataForm.$valid && mainList.save(item)" novalidate>
                                    <fieldset>
                                        @include('forms.name', ['size' => 50])
                                        @include('forms.url')
                                        @include('forms.category')
                                        @include('forms.sort-order')
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
         var categoryData = {!! $category_data !!};
     </script>
@stop

@section('js-footer')
    @include('layouts.angular-js')
    @include('layouts.list-js');
@stop