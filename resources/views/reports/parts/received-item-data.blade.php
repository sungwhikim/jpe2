<table class="table table-responsive table-striped table-report">
    <thead>
        <tr>
            <th>PO Number</th>
            <th>Received Date</th>
            <th>SKU</th>
            <th>Name</th>
            <th>Variants</th>
            <th>Quantity</th>
        </tr>
    </thead>
    <tbody>

    @foreach( $report_data['body'] as $row )
        <tr>
            <td>{{ $row->po_number }}</td>
            <td>{{ date_format(date_create($row->tx_date), 'm-d-Y') }}</td>
            <td>{{ $row->sku }}</td>
            <td>{{ $row->name }}</td>
            <td>
                @include('reports.parts.variant-list')
            </td>
            <td class="text-nowrap">{{ ucwords($row->uom_name) }}: {{ number_format($row->quantity) }}</td>
        </tr>
    @endforeach

    </tbody>
</table>