@extends('reports.inventory-pallet-detail');

@section('report-data')
    @include('reports.parts.inventory-pallet-detail-data', ['total_view' => 'quantity-total-view'])
    @include('reports.parts.pagination')
@stop

@section('js-data')
    <script>
        var reportCriteria = {!! collect($report['criteria']) !!}
    </script>
@stop
