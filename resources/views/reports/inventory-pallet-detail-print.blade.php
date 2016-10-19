@extends('reports.report-print')

@section('report-data')
    @include('reports.parts.inventory-pallet-detail-data', ['total_view' => 'quantity-total'])
@stop

