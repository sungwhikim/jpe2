@extends('reports.bin-location-detail');

@section('report-data')
    @include('reports.parts.bin-location-detail-data', ['total_view' => 'quantity-total-view'])
    @include('reports.parts.pagination')
@stop

@section('js-data')
    <script>
        var reportCriteria = {!! collect($report['criteria']) !!}
    </script>
@stop
