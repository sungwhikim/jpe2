@extends('layouts.transaction')

@section('transaction-input')
    <div class="container main-content home-page tx-container" ng-cloak ng-controller="TransactionController as txCtrl">
        <form class="form-horizontal tx-form" id="txForm" name="txForm" novalidate>

            <div class="row col-lg-10 col-md-12 center-block no-float">
                <h1>ASN - Receiving</h1>
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
                                @include('forms.tx-product', ['controller' => 'txCtrl'])
                                @include('forms.tx-product-quantity', ['controller' => 'txCtrl'])
                                @include('forms.tx-product-variant', ['controller' => 'txCtrl'])

                                @include('forms.tx-product-add-button')
                        </div>
                        <div class="row col-lg-12 col-md-12 col-sm-12 col-xs-12" id="tx-product-container">
                            <table class="table table-form">
                                <thead>
                                <tr>
                                    <th class="col-lg-3 col-md-3">SKU</th>
                                    <th>Name</th>
                                    <th class="col-lg-2 col-md-2">Variants</th>
                                    <th class="col-lg-2 col-md-2">Quantity</th>
                                    <th class="col-lg-1 col-md-1"></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr ng-repeat="item in txCtrl.txData.items">
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
        var txMode = {'new': true,
                      'edit': true}; //This sets the status of controller behavior and is important to set correctly for each tx type.
        var txDirection = '{{ $tx_direction }}';
        var txType = '{{ $tx_type }}';
        var appData = {!! $main_data !!};
        var productData = {!! $product_data !!};
        var carrierData = {!! $carrier_data !!};
    </script>
@endsection
