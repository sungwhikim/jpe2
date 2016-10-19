<table class="table table-responsive table-report">
    <thead>
    <tr>
        <th>SKU</th>
        <th>Name</th>
        <th>UOM</th>
        <th>Variants</th>
        <th>Bin</th>
        <th>On Hand</th>
    </tr>
    </thead>
    <tbody>
    <?php $previous_product_id = ''; ?>
    @foreach( $report_data['body'] as $row )
        <tr>
            @if( $previous_product_id != $row->product_id )
                <td>{{ $row->sku }}</td>
                <td>{{ $row->name }}</td>
                <td class="text-nowrap">@include('reports.parts.uom-multiplier')</td>
            @else
                <td colspan="3"></td>
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
                        @endif

                    @endfor
                </ul>
            </td>
        </tr>

        <?php $previous_product_id = $row->product_id; ?>
    @endforeach

    @include('reports.parts.' . $total_view, ['colspan1' => 5, 'colspan2' => 1])
    </tbody>
</table>