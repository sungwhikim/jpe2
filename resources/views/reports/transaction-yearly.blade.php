@extends('reports.report');

@section('report-criteria')
        @include('forms.report-warehouse')
        @include('forms.report-client')
        @include('forms.report-product-search-select')
        @include('forms.report-year', ['num_years' => 6])
@stop

@section('js-data')
    <script>
        var reportCriteria = { name : 'transaction-yearly',
                               warehouse_id : null,
                               client_id : null,
                               product_id : null,
                               year : '{{ date('Y') }}' };
    </script>
@stop
