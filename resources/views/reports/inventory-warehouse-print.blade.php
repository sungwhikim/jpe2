@extends('reports.report-print')

@section('report-data')
    <table class="table table-responsive table-striped table-report">
        <thead>
            <tr>
                <th>SKU</th>
                <th>Name</th>
                <th>Variants</th>
                <th>UOM</th>
                <th>On Hand</th>
                <th>Active</th>
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
                    <td class="text-nowrap">@include('reports.parts.uom-multiplier')</td>
                    <td class="text-nowrap">@include('reports.parts.uom-quantities')</td>
                    <td>@include('reports.parts.active-flag')</td>
                </tr>
            @endforeach

            @include('reports.parts.quantity-total', ['colspan1' => 4, 'colspan2' => 2])
        </tbody>
    </table>
@stop

