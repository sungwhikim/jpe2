<ul class="tags tags-report-quantity">
    <?php $uom_multiplier = 1; ?>
    @for( $i = 1; $i <= 8; $i++ )

        @if( $row['uom' . $i] != null && $row['uom' . $i . '_multiplier'] != null  )
            <?php $uom_multiplier *= $row['uom' . $i . '_multiplier'] ?>
            <li class="tag-report-quantity">{{ ucwords($row['uom' . $i]) }}: {{ number_format(ceil($row->quantity / $uom_multiplier)) }}</li>
        @endif

    @endfor
</ul>
