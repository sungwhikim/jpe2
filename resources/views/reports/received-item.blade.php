@extends('reports.report');

@section('report-criteria')
        @include('forms.report-warehouse')
        @include('forms.report-client')
        @include('forms.report-date', ['title' => 'From Date',
                                       'name' => 'from_date'])
        @include('forms.report-date', ['title' => 'To Date',
                                       'name' => 'to_date',
                                       'margin_left' => '30px'])
@stop

@section('js-data')
    <script>
        var reportCriteria = {
            name : 'received-item',
            warehouse_id : null,
            client_id : null,
            from_date : null,
            to_date : new Date()
        };
    </script>
@stop
