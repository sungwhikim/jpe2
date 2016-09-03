@extends('layouts.transaction')

@section('tx-buttons')
    <button type="button" class="btn btn-info" style="margin-left: 100px;"
            ng-show="txCtrl.txSetting.edit == true"
            ng-click="txCtrl.pickList(txForm)">Pick List</button>
    <button type="button" class="btn btn-info" style=""
            ng-show="txCtrl.txSetting.edit == true"
            ng-click="txCtrl.shippingMemo(txForm)">Shipping Memo</button>
@endsection

@section('tx-main-input')
    @include('forms.tx-date')
    @include('forms.tx-po-number')
    @include('forms.tx-customer')
    @include('forms.tx-carrier', ['size' => '4'])
    @include('forms.tx-tracking-number-ship')
@endsection

@section('tx-product-input')
    @include('forms.tx-product-search-select')
    @include('forms.tx-product-variant-ship', ['controller' => 'txCtrl'])
    @include('forms.tx-product-quantity-ship', ['controller' => 'txCtrl'])
@endsection

@section('tx-product-table-header')
    <th class="col-lg-3 col-md-3 col-sm-3 col-xs-3">SKU</th>
    <th class="col-lg-3 col-md-3 col-sm-3 col-xs-3">Name</th>
    <th class="col-lg-2 col-md-2 col-sm-3 col-xs-3">Variants</th>
    <th class="col-lg-4 col-md-4 col-sm-5 col-xs-5" style="padding-left: 25px;">Quantity</th>
@endsection

@section('tx-product-table')
    <td ng-bind="item.product.sku"></td>
    <td ng-bind="item.product.name"></td>
    <td>@include('forms.tx-product-variant-list')</td>
    <td style="white-space: nowrap; padding-left: 25px;">
        @include('forms.tx-product-quantity-ship-raw',['model_object' => 'item', 'controller' => 'txCtrl', 'show_ship_bin' => true])
        @include('forms.tx-delete-button')
    </td>
@endsection

@section('tx-inline-javascript')
<script>
    var customerData = {!! $customer_data !!};
    var countryData = {!! $country_data !!};
</script>
@endsection
