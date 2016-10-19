@extends('reports.csr-open');

@section('report-data')
    <table class="table table-responsive table-striped table-hover table-report">
        <thead>
            <tr>
                <th></th>
                <th>Client</th>
                <th>Date</th>
                <th>PO</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach( $report_data['body'] as $row )
                <tr>
                    <td style="width: 50px"><a href="/transaction/csr/{{ $row->tx_id }}" class="btn btn-primary btn-sm" target="_blank">View</a></td>
                    <td>{{ $row->client_short_name }}</td>
                    <td>{{ $row->tx_date }}</td>
                    <td>{{ $row->po_number }}</td>
                    <td>{{ $row->tx_status_name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @include('reports.parts.pagination')
@stop

@section('js-data')
    <script>
        var reportCriteria = {!! collect($report['criteria']) !!}
    </script>
@stop
