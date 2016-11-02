@extends('reports.report-excel')

{{-- get the largest number of uoms used --}}
<?php $uom_count = $report_model::getUomCount($report_data['body']); ?>

@section('report-data')
    <table class="table table-responsive table-report">
        <thead>
        <tr>
            <th>SKU</th>
            <th>Name</th>
            @include('reports.parts.header-uom')
            <th>Variants</th>
            <th>Bin</th>
            @include('reports.parts.header-on-hand')
        </tr>
        </thead>
        <tbody>
        <?php $previous_product_id = ''; ?>
        @foreach( $report_data['body'] as $row )
            <tr>
                @if( $previous_product_id != $row->product_id )
                    <td>{{ $row->sku }}</td>
                    <td>{{ $row->name }}</td>
                    @include('reports.parts.uom-multiplier-excel')
                @else
                    <td colspan="{{ (($uom_count - 1) * 2) + 2 }}"></td>
                @endif

                <td>@include('reports.parts.variant-list')</td>

                <td class="text-nowrap">{{ $row->aisle }} {{ strlen($row->section) > 1 ? $row->section : '0' . $row->section }}
                    {{ strlen($row->tier) > 1 ? $row->tier : '0' . $row->tier }} {{ strlen($row->position) > 1 ? $row->position : '0' . $row->position }}
                </td>

                @include('reports.parts.uom-quantities-excel')

            </tr>

            <?php $previous_product_id = $row->product_id; ?>
        @endforeach

        @include('reports.parts.quantity-total-excel', ['colspan1' => 4 + (($uom_count - 1) * 2), 'colspan2' => $uom_count * 2, 'uom_count' => $uom_count])
        </tbody>
    </table>
@stop

