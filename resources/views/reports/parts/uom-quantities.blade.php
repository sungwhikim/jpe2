{{-- The reason this is in php and not in blade is that blade doesn't allow explicit assignment of variables and
     we need to assign the name of the column as we loop through it. --}}
<?php $uom_multiplier = 1; ?>
<?php for( $i = 2; $i <= count($report['body']->uom_data); $i++ ) { ?>

    <?php $name = 'uom' . $i . '_multiplier'; ?>

    <?php if( $row[$name] != null ) { ?>
        <?php $uom_multiplier *= $row[$name] ?>
        <td>{{ ceil($row->quantity / $uom_multiplier) }}</td>
    <?php } else { ?>
        <td></td>
    @<?php } ?>

<?php } ?>