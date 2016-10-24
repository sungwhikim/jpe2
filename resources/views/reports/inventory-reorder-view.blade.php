@extends('reports.inventory-reorder');

@section('report-data')
    @include('reports.parts.inventory-reorder-data')
    @include('reports.parts.pagination')
@stop

@section('js-data')
    <script>
        var reportCriteria = {!! collect($report['criteria']) !!}
    </script>
@stop
