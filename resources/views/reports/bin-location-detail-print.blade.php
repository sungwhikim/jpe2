@extends('reports.report-print')

@section('report-data')
    @include('reports.parts.bin-location-detail-data', ['total_view' => 'quantity-total'])
@stop

