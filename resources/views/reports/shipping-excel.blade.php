@extends('reports.report-excel')

@section('report-data')
    @include('reports.parts.shipping-data', ['paginate' => false])
@stop

