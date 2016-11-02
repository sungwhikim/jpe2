@extends('reports.report-excel')

{{-- get the largest number of uoms used --}}
<?php $uom_count = $report_model::getUomCount($report_data['body']); ?>

@section('report-data')
    <table class="table table-report">
        <thead>
            <tr>
                <th>SKU</th>
                <th>Name</th>
                <th>Variants</th>
                @include('reports.parts.header-uom')
                @include('reports.parts.header-on-hand')
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
                    @include('reports.parts.uom-multiplier-excel', ['uom_count' => $uom_count])
                    @include('reports.parts.uom-quantities-excel', ['uom_count' => $uom_count])
                    <td>@include('reports.parts.active-flag')</td>
                </tr>
            @endforeach

            @include('reports.parts.quantity-total-excel', ['colspan1' => 1 + ($uom_count * 2 ),
                                                            'colspan2' => $uom_count * 2,
                                                            'uom_count' => $uom_count])
        </tbody>
    </table>
@stop

