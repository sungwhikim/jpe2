@extends('reports.report-excel')

@section('report-data')
    <table class="table table-report">
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
            @foreach( $report['body'] as $row )
                <tr>
                    <td>{{ $row->sku }}</td>
                    <td>{{ $row->name }}</td>
                    <td>
                        @include('reports.parts.variant-list')
                    </td>
                    <td>@include('reports.parts.uom-multiplier')</td>
                    <td>@include('reports.parts.uom-quantities')</td>
                    <td>@include('reports.parts.active-flag')</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@stop

