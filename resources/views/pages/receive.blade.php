@extends('layouts.transaction')

@section('transaction-input')
    <div class="container main-content home-page tx-container" ng-cloak ng-controller="TransactionController as txCtrl">
        <form class="form-horizontal tx-form" id="txForm" name="txForm" novalidate>

            <div class="row col-lg-10 col-md-12 center-block no-float">
                <h1>Receiving</h1>
                <div class="panel panel-default panel-nav box-shadow--2dp">
                    <div class="panel-heading">
                        <button type="button" class="btn btn-success" ng-click="txCtrl.newTransaction(true, txForm)">Save & New</button>
                        <button type="button" class="btn btn-success" ng-click="txCtrl.newTransaction(false, txForm)">Save</button>
                        <button type="reset" class="btn btn-default btn-cancel-tx" >Clear</button>
                        <div class="pull-right tx-wc-header">
                                <span ng-bind="txCtrl.selectedWarehouseClient.warehouse_name"></span> /
                                <span ng-bind="txCtrl.selectedWarehouseClient.client_short_name"></span>
                        </div>
                    </div>
                    <div class="panel-body tx-panel">
                        <div class="row">
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                @include('forms.tx-date')
                                @include('forms.tx-po-number')
                                @include('forms.tx-carrier')
                                @include('forms.tx-tracking-number', ['size' => '8'])
                            </div>
                            @include('forms.tx-note')
                        </div>
                        <div class="row" id="tx-product-input-container">
                                @include('forms.tx-barcode')
                                @include('forms.tx-product-search-select')
                                @include('forms.tx-product-quantity-bin', ['model_object' => 'txCtrl.newItem'])
                                @include('forms.tx-product-variant', ['controller' => 'txCtrl'])

                                @include('forms.tx-product-add-button')
                        </div>
                        <div class="row col-lg-12 col-md-12 col-sm-12 col-xs-12" id="tx-product-container">
                            <table class="table table-form">
                                <thead>
                                <tr>
                                    <th ng-show="txCtrl.selectedWarehouseClient.show_barcode_client">Client Barcode</th>
                                    <th class="col-lg-2 col-md-2">SKU</th>
                                    <th>Name</th>
                                    <th class="col-lg-2 col-md-2">Variants</th>
                                    <th class="col-lg-3 col-md-3">Quantity</th>
                                    <th class="col-lg-1 col-md-1"></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr ng-repeat="item in txCtrl.txData.items">
                                    <td ng-show="txCtrl.selectedWarehouseClient.show_barcode_client" ng-bind="item.barcode_client"></td>
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
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </form>
    </div>
@endsection

@section('tx-inline-javascript')
    <script>
        var appUrl = '{{ $url }}';
        var txSetting = {!! $tx_setting !!};
        var appData = {!! $main_data !!};
        var productData = {!! $product_data !!};
        var carrierData = {!! $carrier_data !!};
    </script>
@endsection