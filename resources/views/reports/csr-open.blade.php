@extends('reports.report');

@section('report-criteria')
        @include('forms.report-warehouse-sole')
@stop

@section('js-data')
    <script>
        var reportCriteria = { name : 'csr-open',
                               warehouse_id : null };
    </script>
@stop
