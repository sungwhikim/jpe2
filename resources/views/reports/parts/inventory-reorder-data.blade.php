<table class="table table-responsive table-striped table-report">
    <thead>
    <tr>
        <th>SKU</th>
        <th>Name</th>
        <th>UOM</th>
        <th>R/O Level</th>
        <th>On Hand</th>
        <th>To Order</th>
    </tr>
    </thead>
    <tbody>
    @foreach( $report_data['body'] as $row )
        <tr>
            <td>{{ $row->sku }}</td>
            <td>{{ $row->name }}</td>
            <td class="text-nowrap">@include('reports.parts.uom-multiplier')</td>
            <td>{{ $row->reorder_level }}</td>
            <td class="text-nowrap">{{ $row->quantity }}</td>
            <td class="text-nowrap">{{ $row->reorder_level - $row->quantity }}</td>
        </tr>
    @endforeach
    </tbody>
</table>