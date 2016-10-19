<table class="table table-responsive table-striped table-report">
    <thead>
    <tr>
        <th>SKU</th>
        <th>Name</th>
        <th>Variants</th>
        <th>Tx Date On Zero</th>
        <th>Inv Date On Zero</th>
    </tr>
    </thead>
    <tbody>
    @foreach( $report_data['body'] as $row )
        <tr>
            <td>{{ $row->sku }}</td>
            <td>{{ $row->name }}</td>
            <td>
                @include('reports.parts.variant-list')
            </td>
            <td>{{ $row->tx_date != null ? date_format(date_create($row->tx_date), 'm-d-Y') : '' }}</td>
            <td>{{ $row->inv_date != null ? date_format(date_create($row->inv_date), 'm-d-Y') : '' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>