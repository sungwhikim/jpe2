@extends('layouts.transaction')

@section('tx-buttons')
    <button type="button" class="btn btn-success" ng-show="txCtrl.txSetting.new == true && txCtrl.txSetting.convert == false"
            ng-click="txCtrl.saveTransaction(true, txForm)">Save & New</button>
    <button type="button" class="btn btn-success" ng-disabled="txCtrl.txSetting.edit == false"
            ng-click="txCtrl.saveTransaction(false, txForm)">Save</button>
    <button type="reset" class="btn btn-default btn-cancel-tx"
            ng-show="txCtrl.txSetting.new == true && txCtrl.txSetting.convert == false"
            ng-click="txCtrl.resetTransaction(txForm);">Clear</button>
@endsection

@section('tx-main-input')
    @include('forms.tx-date')
    @include('forms.tx-po-number')
    @include('forms.tx-carrier')
    @include('forms.tx-tracking-number', ['size' => '8'])
@endsection

@section('tx-product-input')
    @include('forms.tx-barcode')
    @include('forms.tx-product-search-select')
    @include('forms.tx-product-quantity-bin', ['model_object' => 'txCtrl.newItem'])
    @include('forms.tx-product-variant', ['controller' => 'txCtrl'])
@endsection

@section('tx-product-table-header')
    <th ng-show="txCtrl.selectedWarehouseClient.show_barcode_client">Client Barcode</th>
    <th class="col-lg-2 col-md-2">SKU</th>
    <th>Name</th>
    <th>Variants</th>
    <th class="col-lg-3 col-md-3">Quantity</th>
    <th class="col-lg-1 col-md-1"></th>
@endsection

@section('tx-product-table')
    <td ng-show="txCtrl.selectedWarehouseClient.show_barcode_client" ng-bind="item.product.barcode_client"></td>
    <td ng-bind="item.product.sku" class="text-nowrap"></td>
    <td ng-bind="item.product.name"></td>
    <td>@include('forms.tx-product-variant-list')</td>
    <td>
        @include('forms.tx-product-quantity-bin-raw', ['model_object' => 'item'])
    </td>
    <td>
        <a ng-click="txCtrl.deleteItem($index)"
           class="btn glyphicon glyphicon-trash btn-delete"
           data-toggle="tooltip" title="Delete"></a>
    </td>
@endsection

@section('tx-inline-javascript')
@endsection
