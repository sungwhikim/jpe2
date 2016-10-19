@extends('reports.report');

@section('report-criteria')
    @include('forms.report-warehouse-sole')
    @include('forms.report-year', ['num_years' => 6])
@stop

@section('js-data')
    <script>
        var reportCriteria = { name : 'shipping',
                               warehouse_id : null,
                               year : '{{ date('Y') }}' };
    </script>
@stop
