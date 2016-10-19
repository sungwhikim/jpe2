@extends('reports.shipping');

@section('report-data')
    @include('reports.parts.shipping-data')
    @include('reports.parts.pagination')
@stop

@section('js-data')
    <script>
        var reportCriteria = {!! collect($report['criteria']) !!}
    </script>
@stop
