<!-- app/views/page/country.blade.php -->

@extends('main')

@section('nav')
    @include('layouts.nav')
@stop

@section('body')
    <div class="container main-content" ng-controller="ListController as mainList" st-table="mainList.displayItems" st-safe-src="mainList.items">
        <div class="row content-header">
            <div class="col-lg-7 col-md-6 col-sm-5">
                <h1>Product Types</h1>
            </div>
            @include('layouts.search-bar', ['criterias' => ['name' => 'Name',
                                                            'variants' => 'Variants',
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
                        <th class="sort-header" st-sort="name" st-sort-default="true">Name</th>
                        <th class="sort-header" st-sort="variants">Variants</th>
                        <th class="sort-header" st-sort="active">Active</th>
                    </tr>
                    <tr>
                        <td colspan="4" class="td-form">
                            <div class="collapse" id="new-item">
                                <form class="form-horizontal" id="formNew" name="formNew" ng-submit="formNew.$valid && mainList.add(formNew)" novalidate>
                                    <fieldset>
                                        @include('forms.new-name', ['size' => 50])
                                        @include('forms.new-description', ['size' => 250])

                                        @include('forms.divider', ['title' => 'Variants'])
                                        @include('forms.new-product-variant', ['number' => 1])
                                        @include('forms.new-product-variant', ['number' => 2])
                                        @include('forms.new-product-variant', ['number' => 3])
                                        @include('forms.new-product-variant', ['number' => 4])

                                        @include('forms.divider', ['title' => 'Unit Of Measure'])
                                        @include('forms.new-product-uom', ['number' => 1])
                                        @include('forms.new-product-uom', ['number' => 2])
                                        @include('forms.new-product-uom', ['number' => 3])
                                        @include('forms.new-product-uom', ['number' => 4])
                                        @include('forms.new-product-uom', ['number' => 5])
                                        @include('forms.new-product-uom', ['number' => 6])
                                        @include('forms.new-product-uom', ['number' => 7])
                                        @include('forms.new-product-uom', ['number' => 8])

                                        @include('forms.divider')
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
                        <td ng-bind="item.variants"></td>
                        <td><span ng-bind="item.active" ng-class="{'badge': item.active===true}"></span></td>
                    </tr>
                    <tr>
                        <td colspan="4" class="td-form">
                            <div class="collapse col-lg-12" id="item-@{{ item.id }}">
                                <form class="form-horizontal" name="dataForm" ng-submit="dataForm.$valid && mainList.save(item)" novalidate>
                                    <input class="item-id" type="hidden" ng-model="item.id" name="id">
                                    <div class="col-lg-12">
                                        <div class="alert alert-dismissible alert-info">
                                            <span class="glyphicon glyphicon-info"></span>&nbsp;
                                            <span>
                                                Please be careful before editing any of the values if this product type has been or bring used.
                                                Any changes here could affect many things such as reporting, billing, and transaction screens.
                                                If in doubt, create a new product type if you need something different.
                                                <br><br><strong>Changing a value will affect all products using this type!</strong>
                                            </span>
                                        </div>
                                    </div>
                                    <fieldset>
                                        @include('forms.name', ['size' => 50])
                                        @include('forms.description', ['size' => 250])

                                        @include('forms.divider', ['title' => 'Variants'])
                                        @include('forms.product-variant', ['number' => 1])
                                        @include('forms.product-variant', ['number' => 2])
                                        @include('forms.product-variant', ['number' => 3])
                                        @include('forms.product-variant', ['number' => 4])

                                        @include('forms.divider', ['title' => 'Unit Of Measure'])
                                        @include('forms.product-uom', ['number' => 1])
                                        @include('forms.product-uom', ['number' => 2])
                                        @include('forms.product-uom', ['number' => 3])
                                        @include('forms.product-uom', ['number' => 4])
                                        @include('forms.product-uom', ['number' => 5])
                                        @include('forms.product-uom', ['number' => 6])
                                        @include('forms.product-uom', ['number' => 7])
                                        @include('forms.product-uom', ['number' => 8])

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
     </script>
@stop

@section('js-footer')
    @include('layouts.angular-js')
    @include('layouts.js-lists')
@stop