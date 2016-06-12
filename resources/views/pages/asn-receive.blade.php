@extends('layouts.transaction')

@section('tx-buttons')
    @include('forms.tx-button-convert')
@endsection

@section('tx-main-input')
    @include('forms.tx-date')
    @include('forms.tx-po-number')
    @include('forms.tx-carrier', ['size' => '5'])
    @include('forms.tx-tracking-number')
@endsection

@section('tx-product-input')
    @include('forms.tx-product-search-select')
    @include('forms.tx-product-quantity', ['controller' => 'txCtrl'])
    @include('forms.tx-product-variant', ['controller' => 'txCtrl'])
@endsection

@section('tx-product-table-header')
    <th class="col-lg-3 col-md-3">SKU</th>
    <th>Name</th>
    <th class="col-lg-2 col-md-2">Variants</th>
    <th class="col-lg-2 col-md-2">Quantity</th>
    <th class="col-lg-1 col-md-1"></th>
@endsection

@section('tx-product-table')
    <td ng-bind="item.product.sku"></td>
    <td ng-bind="item.product.name"></td>
    <td>@include('forms.tx-product-variant-list')</td>
    <td>
        @include('forms.tx-product-quantity-raw',['model_object' => 'item', 'controller' => 'txCtrl'])
    </td>
    <td>
        <a ng-click="txCtrl.deleteItem($index)"
           class="btn glyphicon glyphicon-trash btn-delete"
           data-toggle="tooltip" title="Delete"></a>
    </td>
@endsection

@section('tx-inline-javascript')

@endsection
