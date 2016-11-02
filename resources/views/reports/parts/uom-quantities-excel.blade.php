<?php $uom_multiplier = 1; ?>
@for( $i = 1; $i <= $uom_count; $i++ )

    @if( $row['uom' . $i] != null && $row['uom' . $i . '_multiplier'] != null  )
        <?php $uom_multiplier *= $row['uom' . $i . '_multiplier'] ?>
        <td>{{ ucwords($row['uom' . $i]) }}</td>
        <td>{{ ceil($row->quantity / $uom_multiplier) }}</td>
    @else
        <td></td>
        <td></td>
    @endif

@endfor

