@extends('reports.transaction-chronological');

@section('report-data')
    @include('reports.parts.transaction-chronological-data')
    @include('reports.parts.pagination')
@stop

@section('js-data')
    <script>
        var reportCriteria = {!! collect($report['criteria']) !!}
    </script>
@stop
