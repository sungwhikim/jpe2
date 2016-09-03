@extends('main')

@section('nav')
    @include('layouts.nav')
@stop

@section('body')
    <div class="container main-content" ng-controller="ReportController as mainList" st-table="mainList.displayItems" st-safe-src="mainList.items">
        <div class="row content-header">
            <div class="col-lg-12">
                <h1>@yield('title')</h1>
            </div>
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
                <div class="panel panel-default">
                    <div class="panel-heading" style="height: 54px;">
                        <div class="col-xs-3">
                            <select class="form-control" name="tx_type" ng-change="mainList.updateData()" ng-model="mainList.txType">
                                <option value="asn" ng-selected="mainList.txType == 'asn'">ASN - Advanced Shipping Notice</option>
                                <option value="csr" ng-selected="mainList.txType == 'csr'">CSR - Client Stock Release</option>
                                <option value="receive" ng-selected="mainList.txType == 'receive'">Receiving</option>
                                <option value="ship" ng-selected="mainList.txType == 'ship'">Shipping</option>
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-3 col-xs-3 pull-right">
                            <div class="checkbox checkbox-slider--b checkbox-slider-md" style="margin: 0;">
                                <label>
                                    <input type="checkbox" name="showAllStatus" value="true"
                                           ng-model="mainList.showAllStatus" ng-change="mainList.changeToggle(mainList.showAllStatus)">
                                    <span>All Statuses</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-3 col-xs-3 pull-right">
                            <div class="checkbox checkbox-slider--b checkbox-slider-md" style="margin: 0;">
                                <label>
                                    <input type="checkbox" name="showAllStatus" value="true"
                                           ng-model="mainList.showAllDates" ng-change="mainList.changeToggle(mainList.showAllDates)">
                                    <span>All Dates</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">

                    </div>
                </div>
                <div id="main-data">
                    <h2>No Data Found!</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center pagination-container">

    </div>
@stop

@section('js-data')
     <script>
         var myName  = '{{ $my_name }}';
         var appUrl  = '{{ $url }}';
         var txType  = '{{ $tx_type }}';
         var showFlag = {!! $show_flag !!};
         var appData = {!! $main_data !!};
     </script>
@stop

@section('js-footer')
    @include('layouts.angular-js')
    @include('layouts.transaction-finder-js');
@stop