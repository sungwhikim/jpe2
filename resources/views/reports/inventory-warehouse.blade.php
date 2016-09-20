@extends('reports.report');

@section('report-criteria')
        @include('forms.report-warehouse')
        @include('forms.report-client')
        @include('forms.report-date', ['title' => 'As of Date',
                                       'name' => 'end_date'])
        @include('forms.report-toggle', ['title' => 'Show zero inventory',
                                         'name' => 'zero_inventory_items'])
@stop

@section('js-data')
    <script>
        var reportCriteria = { name : 'inventory-warehouse',
                               warehouse_id : null,
                               client_id : null,
                               end_date : new Date(),
                               zero_inventory_items : false };
    </script>
@stop
