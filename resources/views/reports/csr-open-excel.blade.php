@extends('reports.report-excel')

@section('report-data')
    <table class="table table-report">
        <thead>
        <tr>
            <th>Client</th>
            <th>Date</th>
            <th>PO</th>
            <th>Status</th>
        </tr>
        </thead>
        <tbody>
        @foreach( $report_data['body'] as $row )
            <tr>
                <td>{{ $row->client_short_name }}</td>
                <td>{{ $row->tx_date }}</td>
                <td>{{ $row->po_number }}</td>
                <td>{{ $row->tx_status_name }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@stop

