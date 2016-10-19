@extends('reports.inventory-zero');

@section('report-data')
    @include('reports.parts.inventory-zero-data')
    @include('reports.parts.pagination')
@stop

@section('js-data')
    <script>
        var reportCriteria = {!! collect($report['criteria']) !!}
    </script>
@stop
