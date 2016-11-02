@for( $i = 2; $i <= $uom_count; $i++ )

    @if( $row['uom' . $i] != null && $row['uom' . $i . '_multiplier'] != null  )
        <td>{{ ucwords($row['uom' . $i]) }}</td>
        <td>{{ $row['uom' . $i . '_multiplier'] }}</td>
    @else
        <td></td>
        <td></td>
    @endif

@endfor