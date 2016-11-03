@extends('reports.received-item');

@section('report-data')
    @include('reports.parts.received-item-data')
    @include('reports.parts.pagination')
@stop

@section('js-data')
    <script>
        var reportCriteria = {!! collect($report['criteria']) !!}
    </script>
@stop
