@extends('reports.report');

@section('report-criteria')
        @include('forms.report-warehouse')
        @include('forms.report-client')
        @include('forms.report-product-search-select')
        @include('forms.report-tx-type')
        @include('forms.report-date', ['title' => 'From Date',
                                       'name' => 'from_date'])
        @include('forms.report-date-2', ['title' => 'To Date',
                                           'name' => 'to_date'])
@stop

@section('js-data')
    <script>
        var reportCriteria = { name : 'transaction-chronological',
                               warehouse_id : null,
                               client_id : null,
                               product_id: null,
                               tx_type : null,
                               from_date : new Date('1/1/2010'),
                               to_date : new Date()};
    </script>
@stop
