<?php $row_count = 1; ?>
<?php $previous_tx_id = null; ?>
@foreach( $report_data['body'] as $row )
    @if( $previous_tx_id != $row->tx_id )
        {{-- init totals array --}}
        <?php $total = []; ?>
        <div style="margin-top: 30px;">
            <span style="font-weight: bold;">Transaction Date: </span>{{ date_format(date_create($row['tx_date']), 'm-d-Y') }} &nbsp;&nbsp;
            <span style="font-weight: bold;">PO Number: </span>{{ $row['po_number'] }} &nbsp;&nbsp;
            <span style="font-weight: bold;">Status: </span>{{ ucwords($row['tx_status_name']) }}
        </div>
        <table class="table table-responsive table-striped table-report">
            <thead>
            <tr>
                <th style="width: 25%">SKU</th>
                <th style="width: 30%">Name</th>
                <th style="width: 20%">Variants</th>
                <th style="width: 25%">Quantity</th>
            </tr>
            </thead>
            <tbody>
            @endif
            <tr>
                <td>{{ $row->sku }}</td>
                <td>{{ $row->name }}</td>
                <td>
                    @include('reports.parts.variant-list')
                </td>
                <td class="text-nowrap">{{ ucwords($row->uom_name) }}: {{ number_format($row->quantity) }}</td>
            </tr>

            {{-- calculate total --}}
            @if( !isset($total[$row->uom_name]) ) <?php $total[$row->uom_name] = 0 ?> @endif
            <?php $total[$row->uom_name] += $row->quantity; ?>

            {{-- set total and close the table after each transaction --}}
            @if( $row_count == count($report_data['body']) || $report_data['body'][$row_count]->tx_id != $row->tx_id )
                <tr class="report-total">
                    <td colspan="3" class="text-right">Total</td>
                    <td class="text-nowrap">
                        <ul class="tags tags-report-quantity">

                            @foreach( $total as $key => $value )
                                <li class="tag-report-quantity">{{ ucwords($key) }}: {{ number_format(ceil($value)) }}</li>
                            @endforeach

                        </ul>
                    </td>
                </tr>
            </tbody>
        </table>
    @endif

    {{-- increment --}}
    <?php $row_count++ ?>
    <?php $previous_tx_id = $row->tx_id ?>
@endforeach