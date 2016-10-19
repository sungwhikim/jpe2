<ul class="tags tags-report-quantity">
    @for( $i = 2; $i <= 8; $i++ )

        @if( $row['uom' . $i] != null && $row['uom' . $i . '_multiplier'] != null  )
            <li class="tag-report-quantity">{{ ucwords($row['uom' . $i]) }}: {{ $row['uom' . $i . '_multiplier'] }}</li>
        @endif

    @endfor
</ul>