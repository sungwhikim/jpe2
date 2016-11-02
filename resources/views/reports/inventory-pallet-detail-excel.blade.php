@extends('reports.report-excel')

{{-- get the largest number of uoms used --}}
<?php $uom_count = $report_model::getUomCount($report_data['body']); ?>

@section('report-data')
    <table class="table table-responsive table-report">
        <thead>
        <tr>
            <th>SKU</th>
            <th>Name</th>
            <th>Active</th>
            @include('reports.parts.header-uom')
            <th>Variants</th>
            <th>Bin</th>
            @include('reports.parts.header-on-hand')
        </tr>
        </thead>
        <tbody>
        <?php $previous_product_id = ''; ?>
        <?php $product_total = []; ?>
        <?php $row_count = 1; ?>
        @foreach( $report_data['body'] as $row )
            <tr>
                @if( $previous_product_id != $row->product_id )
                    <td>{{ $row->sku }}</td>
                    <td>{{ $row->name }}</td>
                    <td>@include('reports.parts.active-flag')</td>
                    @include('reports.parts.uom-multiplier-excel')
                @else
                    <td colspan="{{ (($uom_count - 1) * 2) + 3 }}"></td>
                @endif

                <td>@include('reports.parts.variant-list')</td>

                <td class="text-nowrap">{{ $row->aisle }} {{ strlen($row->section) > 1 ? $row->section : '0' . $row->section }}
                    {{ strlen($row->tier) > 1 ? $row->tier : '0' . $row->tier }} {{ strlen($row->position) > 1 ? $row->position : '0' . $row->position }}
                </td>

                {{-- We are taking this code out of the sub view because we need to calculate product totals and performance reasons as we can't pass
                variables back up a view without some issues.--}}
                    <?php $uom_multiplier = 1; ?>
                    @for( $i = 1; $i <= $uom_count; $i++ )

                        @if( $row['uom' . $i] != null && $row['uom' . $i . '_multiplier'] != null  )
                            <?php $uom_multiplier *= $row['uom' . $i . '_multiplier'] ?>
                            <td>{{ ucwords($row['uom' . $i]) }}</td>
                            <td>{{ ceil($row->quantity / $uom_multiplier) }}</td>

                            {{-- Create product totals --}}
                            @if( isset($product_total) )
                                {{-- If the UOM has not been set, set it now so the addition doesn't break with undefined --}}
                                @if( !isset($product_total[$row['uom' . $i]]) ) <?php $product_total[$row['uom' . $i]] = 0; ?> @endif

                                {{-- Add to UOM total --}}
                                <?php $product_total[$row['uom' . $i]] += $row->quantity / $uom_multiplier ?>
                            @endif
                        @else
                            <td></td>
                            <td></td>
                        @endif

                    @endfor
            </tr>

            {{-- Create product total row --}}
            @if( $row_count == count($report_data['body']) || $row->product_id != $report_data['body'][$row_count]->product_id )
                <tr style="font-weight: bold;">
                    <td colspan="{{ (($uom_count - 1) * 2) + 5 }}" style="text-align: right">Total</td>
                    @foreach( $product_total as $key => $value )
                        <td>{{ ucwords($key) }}</td>
                        <td>{{ ceil($value) }}</td>
                    @endforeach
                </tr>

                {{-- add spacer row--}}
                <tr><td colspan="{{ (($uom_count - 1) * 4) + 2 }}"></td></tr>

                {{-- reset product total array --}}
                <?php $product_total = []; ?>
            @endif

            <?php $row_count++ ?>
            <?php $previous_product_id = $row->product_id; ?>
        @endforeach

        @include('reports.parts.quantity-total-excel', ['colspan1' => (($uom_count -1) * 2) + 5, 'colspan2' => $uom_count * 2, 'uom_count' => $uom_count])
        </tbody>
    </table>
@stop

