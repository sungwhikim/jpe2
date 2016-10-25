@extends('reports.report-excel')

@section('report-data')
    <table class="table table-report">
        <thead>
            <tr>
                <th>SKU</th>
                <th>Name</th>
                <th>Variants</th>
                <th colspan="4">UOM</th>
                <th>On Hand</th>
                <th>Active</th>
            </tr>
        </thead>
        <tbody>
            <?php $uom_count = count($report_data['total']); ?>
            @foreach( $report_data['body'] as $row )
                <tr>
                    <td>{{ $row->sku }}</td>
                    <td>{{ $row->name }}</td>
                    <td>
                        @include('reports.parts.variant-list')
                    </td>
                    @include('reports.parts.uom-multiplier-excel')
                    <td>@include('reports.parts.uom-quantities')</td>
                    <td>@include('reports.parts.active-flag')</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@stop

