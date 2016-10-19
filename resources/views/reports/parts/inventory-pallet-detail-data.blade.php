<table class="table table-responsive table-report">
    <thead>
    <tr>
        <th>SKU</th>
        <th>Name</th>
        <th>Active</th>
        <th>UOM</th>
        <th>Variants</th>
        <th>Bin</th>
        <th>On Hand</th>
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
                <td class="text-nowrap">@include('reports.parts.uom-multiplier')</td>
            @else
                <td colspan="4"></td>
            @endif

            <td>@include('reports.parts.variant-list')</td>

            <td class="text-nowrap">{{ $row->aisle }} {{ strlen($row->section) > 1 ? $row->section : '0' . $row->section }}
                {{ strlen($row->tier) > 1 ? $row->tier : '0' . $row->tier }} {{ strlen($row->position) > 1 ? $row->position : '0' . $row->position }}
            </td>

            <td class="text-nowrap">
                {{-- We are taking this code out of the sub view because we need to calculate product totals and performance reasons as we can't pass
                variables back up a view without some issues.--}}
                <ul class="tags tags-report-quantity">
                    <?php $uom_multiplier = 1; ?>
                    @for( $i = 1; $i <= 8; $i++ )

                        @if( $row['uom' . $i] != null && $row['uom' . $i . '_multiplier'] != null  )
                            <?php $uom_multiplier *= $row['uom' . $i . '_multiplier'] ?>
                            <li class="tag-report-quantity">{{ ucwords($row['uom' . $i]) }}: {{ number_format(ceil($row->quantity / $uom_multiplier)) }}</li>

                            {{-- Create product totals --}}
                            @if( isset($product_total) )
                                {{-- If the UOM has not been set, set it now so the addition doesn't break with undefined --}}
                                @if( !isset($product_total[$row['uom' . $i]]) ) <?php $product_total[$row['uom' . $i]] = 0; ?> @endif

                                {{-- Add to UOM total --}}
                                <?php $product_total[$row['uom' . $i]] += $row->quantity / $uom_multiplier ?>
                            @endif
                        @endif

                    @endfor
                </ul>
            </td>
        </tr>

        {{-- Create product total row --}}
        @if( $row_count == count($report_data['body']) || $row->product_id != $report_data['body'][$row_count]->product_id )
            <tr class="report-product-total">
                <td colspan="6" class="text-right">Total</td>
                <td colspan="1" class="text-nowrap">
                    <ul class="tags tags-report-quantity">

                        @foreach( $product_total as $key => $value )
                            <li class="tag-report-quantity">{{ ucwords($key) }}: {{ number_format(ceil($value)) }}</li>
                        @endforeach

                    </ul>
                </td>
            </tr>

            {{-- reset product total array --}}
            <?php $product_total = []; ?>
        @endif

        <?php $row_count++ ?>
        <?php $previous_product_id = $row->product_id; ?>
    @endforeach

    @include('reports.parts.' . $total_view, ['colspan1' => 6, 'colspan2' => 1])
    </tbody>
</table>