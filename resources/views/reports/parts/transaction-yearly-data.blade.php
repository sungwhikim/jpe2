<table class="table table-responsive table-striped table-hover table-report">
    <thead>
    <tr>
        <th></th>
        @for($month = 1; $month <= 12; $month++ )
            <th colspan="2">{{ jdmonthname($month, 0) }}</th>
        @endfor
        <th>Totals</th>
    </tr>
    <tr>
        <th>Client</th>
        @for($month = 1; $month <= 13; $month++ )
            <th>Trans</th>
            <th>Units</th>
        @endfor
    </tr>
    </thead>
    <tbody>
    @foreach( $report_data['body'] as $row )
        <tr>
            <td>{{ $row->client_short_name }}</td>
            <td>{{ $row->tx_date }}</td>
            <td>{{ $row->uom_name }} : {{ $row->quantity }}</td>
        </tr>
    @endforeach
    </tbody>
</table>