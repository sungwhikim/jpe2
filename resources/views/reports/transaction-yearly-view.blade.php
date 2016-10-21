@extends('reports.transaction-yearly');

@section('report-data')
    @include('reports.parts.transaction-yearly-data', ['paginate' => true])
    @include('reports.parts.pagination')
@stop

@section('js-data')
    <script>
        var reportCriteria = {!! collect($report['criteria']) !!}
    </script>
@stop
