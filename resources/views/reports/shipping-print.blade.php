@extends('reports.report-print')

@section('report-data')
    @include('reports.parts.shipping-data', ['paginate' => false])
@stop

