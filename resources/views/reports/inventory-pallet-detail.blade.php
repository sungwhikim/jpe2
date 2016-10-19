@extends('reports.report');

@section('report-criteria')
        @include('forms.report-warehouse')
        @include('forms.report-client')
        @include('forms.report-product-search-select')
        @include('forms.report-date', ['title' => 'As of Date',
                                       'name' => 'as_of_date'])
        @include('forms.report-toggle', ['title' => 'Show zero inventory items',
                                         'name' => 'zero_inventory_items'])
@stop

@section('js-data')
    <script>
        var reportCriteria = { name : 'inventory-pallet-detail',
                               warehouse_id : null,
                               client_id : null,
                               product_id: null,
                               as_of_date : new Date(),
                               zero_inventory_items : false };
    </script>
@stop
