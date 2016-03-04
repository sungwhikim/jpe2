<!-- app/views/page/country.blade.php -->

@extends('main')

@section('nav')
    @include('layouts.nav')
@stop

@section('body')
    <div class="container main-content" ng-controller="ProductListController as mainList" st-table="mainList.displayItems" st-safe-src="mainList.items">
        <div class="row content-header">
            <div class="col-lg-7 col-md-6 col-sm-5">
                <h1>Products</h1>
            </div>
            @include('layouts.search-bar', ['criterias' => ['sku' => 'SKU',
                                                            'name' => 'Name',
                                                            'sku_client' => 'Client SKU',
                                                            'barcode' => 'Barcode',
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
                        <th class="sort-header" st-sort="sku" st-sort-default="true">SKU</th>
                        <th class="sort-header" st-sort="name">Name</th>
                        <th class="sort-header" st-sort="barcode">Barcode</th>
                        <th class="sort-header" st-sort="active">Active</th>
                    </tr>
                    <tr>
                        <td colspan="5" class="td-form">
                            <div class="collapse" id="new-item">
                                <form class="form-horizontal" id="formNew" name="formNew" ng-submit="formNew.$valid && mainList.add(formNew)" novalidate>
                                    <fieldset>
                                        @include('forms.new-product-type', ['product_page' => true])
                                        @include('forms.new-sku')
                                        @include('forms.new-name', ['size' => 500])
                                        @include('forms.new-text', ['title' => 'Client SKU',
                                                                    'name' => 'sku_client',
                                                                    'size' => 250,
                                                                    'required' => false])
                                        @include('forms.new-text', ['title' => 'Barcode',
                                                                    'name' => 'barcode',
                                                                    'size' => 100,
                                                                    'required' => false])
                                        @include('forms.new-text', ['title' => 'Client Barcode',
                                                                    'name' => 'barcode_client',
                                                                    'size' => 200,
                                                                    'required' => false])
                                        @include('forms.new-text', ['title' => 'RFID',
                                                                    'name' => 'rfid',
                                                                    'size' => 200,
                                                                    'required' => false])
                                        @include('forms.new-toggle-button', ['title' => 'Over Sized Pallet',
                                                                             'name' => 'oversized_pallet',
                                                                             'default' => 'false',
                                                                             'required' => false])
                                        @include('forms.new-variant-product')

                                        @include('forms.divider', ['title' => 'Unit Of Measure'])
                                        @include('forms.new-uom-product')

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
                        <td ng-bind="item.sku"></td>
                        <td ng-bind="item.name"></td>
                        <td ng-bind="item.barcode"></td>
                        <td><span ng-bind="item.active" ng-class="{'badge': item.active===true}"></span></td>
                    </tr>
                    <tr>
                        <td colspan="5" class="td-form">
                            <div class="collapse col-lg-12" id="item-@{{ item.id }}">
                                <form class="form-horizontal" name="dataForm" ng-submit="dataForm.$valid && mainList.save(item)" novalidate>
                                    <input class="item-id" type="hidden" ng-model="item.id" name="id">
                                    <fieldset>
                                        @include('forms.product-type', ['product_page' => true])
                                        @include('forms.sku')
                                        @include('forms.name', ['size' => 500])
                                        @include('forms.text', ['title' => 'Client SKU',
                                                                'name' => 'sku_client',
                                                                'size' => 250,
                                                                'required' => false])
                                        @include('forms.text', ['title' => 'JPE Barcode',
                                                                    'name' => 'barcode',
                                                                    'size' => 100,
                                                                    'required' => false])
                                        @include('forms.text', ['title' => 'Client Barcode',
                                                                'name' => 'barcode_client',
                                                                'size' => 200,
                                                                'required' => false])
                                        @include('forms.text', ['title' => 'RFID',
                                                                'name' => 'rfid',
                                                                'size' => 200,
                                                                'required' => false])
                                        @include('forms.toggle-button', ['title' => 'Over Sized Pallet',
                                                                         'name' => 'oversized_pallet',
                                                                         'required' => false])
                                        @include('forms.variant-product')

                                        @include('forms.divider', ['title' => 'Unit Of Measure'])
                                        @include('forms.uom-product')

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
         var productTypeData = {!! $product_type_data !!};
     </script>
@stop

@section('js-footer')
    @include('layouts.angular-js')
    @include('layouts.product-list-js')
@stop