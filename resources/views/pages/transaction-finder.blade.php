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
        <div class="row">
            <div class="col-lg-12">
                <table class="table table-striped table-hover table-form" ng-cloak>
                    <thead>
                        <tr>
                            <th class="col-lg-2 col-md-2 col-sm-2">
                                <select class="form-control" name="tx_type" ng-change="mainList.changeTxType()" ng-model="mainList.txType">
                                    <option value="asn_receive" ng-selected="mailList.txType == 'asn_receive'">ASN - Receiving</option>
                                    <option value="receive" ng-selected="mailList.txType == 'receive'">Receiving</option>
                                </select>
                            </th>
                            {{--<th class="sort-header" st-sort="tx_type">Type</th>--}}
                            <th class="sort-header" st-sort="tx_date">Date</th>
                            <th class="sort-header" st-sort="po_number">PO Number</th>
                            <th class="sort-header" st-sort="tx_status">Status</th>
                        </tr>
                    </thead>
                    <tbody dir-paginate="item in mainList.displayItems | itemsPerPage: 10" class="tbody-form">
                        <tr>
                            <td>
                                <a class="btn btn-primary btn-sm" href="{{ $url }}/@{{ item.tx_type | UnderScoreToForwardSlash }}/@{{ item.id }}">View / Edit</a>
                            </td>
                            {{--<td ng-bind="item.tx_type"></td>--}}
                            <td ng-bind="item.tx_date"></td>
                            <td ng-bind="item.po_number"></td>
                            <td><span ng-bind="item.tx_status" class="badge"></span></td>
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
         var txType  = '{{ $tx_type }}';
         var appData = {!! $main_data !!};
     </script>
@stop

@section('js-footer')
    @include('layouts.angular-js')
    @include('layouts.transaction-finder-js');
@stop