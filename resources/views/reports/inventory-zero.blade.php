@extends('reports.report');

@section('report-criteria')
        @include('forms.report-warehouse')
        @include('forms.report-client')
@stop

@section('js-data')
    <script>
        var reportCriteria = { name : 'inventory-zero',
                               warehouse_id : null,
                               client_id : null };
    </script>
@stop
