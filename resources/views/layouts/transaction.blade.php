@extends('main')

@section('css-head')
@stop

@section('nav')
    @include('layouts.nav')
@stop

@section('body')
    <div class="container main-content home-page tx-container" ng-cloak ng-controller="TransactionController as txCtrl">
        <form class="form-horizontal tx-form" id="txForm" name="txForm" novalidate>

            <div class="row col-lg-10 col-md-12 center-block no-float">
                <h1>{{ $tx_title }}</h1>
                <div class="panel panel-default panel-nav box-shadow--2dp">
                    <div class="panel-heading panel-heading-tx">
                        <button type="button" class="btn btn-success" ng-show="txCtrl.txSetting.new == true"
                                ng-click="txCtrl.saveTransaction(true, txForm)">Save & New</button>
                        <button type="button" class="btn btn-success" style="margin-right: 50px;"
                                ng-show="txCtrl.txSetting.edit == true"
                                ng-click="txCtrl.saveTransaction(false, txForm)">Save</button>
                        <button type="reset" class="btn btn-default btn-cancel-tx" ng-show="txCtrl.txSetting.new == true"
                                ng-click="txCtrl.resetTransaction(txForm);">Clear</button>
                        <button type="button" class="btn btn-default"
                                ng-show="txCtrl.txSetting.new == false && txCtrl.txSetting.edit == true"
                                ng-click="txCtrl.voidTransaction();">Void</button>
                        @if( !empty($main_data->converted_tx_id) )
                        <a class="btn btn-primary" href="/transaction/{{ $tx_setting['direction'] }}/{{ $main_data->converted_tx_id }}">View Converted Transaction</a>
                        @endif

                        @yield('tx-buttons')

                        <div class="tx-status-container pull-right text-right">
                            <span class="label label-primary label-status">@{{ txCtrl.txData.tx_status_name }}</span><br>
                            <span class="label label-default label-user">@{{ txCtrl.txData.user_name }}</span>
                        </div>
                        <div class="pull-right tx-wc-header">
                            <span ng-bind="txCtrl.txData.warehouse_name"></span> /
                            <span ng-bind="txCtrl.txData.client_short_name"></span>
                        </div>
                    </div>
                    <div class="panel-body tx-panel">
                        <div class="row">
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                @yield('tx-main-input')
                            </div>
                            @include('forms.tx-note')
                        </div>
                        <div class="row" id="tx-product-input-container" ng-show="txCtrl.txSetting.edit == true">
                            @yield('tx-product-input')

                            @include('forms.tx-product-add-button')
                        </div>
                        <div class="row col-lg-12 col-md-12 col-sm-12 col-xs-12" id="tx-product-container">
                            <table class="table table-form">
                                <thead>
                                <tr>
                                    @yield('tx-product-table-header')
                                </tr>
                                </thead>
                                <tbody>
                                <tr ng-repeat="item in txCtrl.txData.items">
                                    @yield('tx-product-table')
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </form>
    </div>
@stop

@section('js-footer')
    <script>
        var txSetting = {!! $tx_setting !!};
        var appData = {!! $main_data !!};
        var productData = {!! $product_data !!};
        var carrierData = {!! $carrier_data !!};
    </script>

    @yield('tx-inline-javascript')
    @include('layouts.angular-js')
    @include('layouts.transaction-js')
@stop