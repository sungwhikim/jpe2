@extends('reports.report-excel')

@section('report-data')
    @include('reports.parts.transaction-yearly-data', ['paginate' => false])
@stop

