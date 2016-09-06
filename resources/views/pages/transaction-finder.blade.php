@extends('main')

@section('nav')
    @include('layouts.nav')
@stop

@section('body')
    <div class="container main-content" ng-controller="TxFinderController as mainList" st-table="mainList.displayItems" st-safe-src="mainList.items">
        <div class="row content-header">
            <div class="col-lg-7 col-md-6 col-sm-5">
                <h1>Transaction Finder</h1>
            </div>
            @include('layouts.search-bar', ['criterias' => ['tx_date' => 'Date', 'po_number' => 'PO Number', 'tx_status' => 'Status']])
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
        <div class="row" ng-cloak>
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
                        @include('layouts.no-items-found')
                        <table class="table table-striped table-hover" ng-show="mainList.displayItems.length > 0">
                            <thead>
                            <tr>
                                <th class="col-lg-2 col-md-2 col-sm-2"></th>
                                {{--<th class="sort-header" st-sort="tx_type">Type</th>--}}
                                <th class="sort-header" st-sort="tx_date" st-sort-default="reverse"><span>Transaction Date</span></th>
                                <th class="sort-header" st-sort="po_number"><span>PO Number</span></th>
                                <th class="sort-header" st-sort="tx_status"><span>Status</span></th>
                                <th ng-show="mainList.txType == 'ship'" style="text-align: center" class="col-lg-2 col-md-2 col-sm-2">
                                    <button type="button" class="btn btn-info btn-sm" ng-click="mainList.pickAndPack();">Pick & Pack</button>
                                </th>
                            </tr>
                            </thead>
                            <tbody dir-paginate="item in mainList.displayItems | itemsPerPage: 10" class="tbody-form">
                            <tr>
                                <td>
                                    <a class="btn btn-primary btn-sm" href="{{ $url }}/@{{ item.tx_type | UnderScoreToForwardSlash }}/@{{ item.id }}">View / Edit</a>
                                </td>
                                {{--<td ng-bind="item.tx_type"></td>--}}
                                <td ng-bind="item.tx_date | date : 'longDate'"></td>
                                <td ng-bind="item.po_number"></td>
                                <td><span ng-bind="item.tx_status" ng-class="{ 'tag-status-list' : item.tx_status == 'active' }"></span></td>
                                <td ng-show="mainList.txType == 'ship'" style="text-align: center">
                                    <input type="checkbox" name="tx_id" style="transform: scale(2, 2);"
                                           ng-click="mainList.toggleCheckBox(mainList.pick_pack_tx_ids, item.id)"
                                           ng-show="item.tx_status == 'active'">
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
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
         var txType  = '{{ $tx_type }}';
         var showFlag = {!! $show_flag !!};
         var appData = {!! $main_data !!};
     </script>
@stop

@section('js-footer')
    @include('layouts.angular-js')
    @include('layouts.transaction-finder-js');
@stop