@extends('reports.report-excel')

@section('report-data')
    <?php $row_count = 1; ?>
    <?php $previous_tx_id = null; ?>
    <table class="table table-report">
    @foreach( $report_data['body'] as $row )
        @if( $previous_tx_id != $row->tx_id )
            {{-- init totals array --}}
            <?php $total = []; ?>
            <tr>
                <th colspan="4">
                    <span style="font-weight: bold;">Transaction Date: </span>{{ date_format(date_create($row['tx_date']), 'm-d-Y') }} &nbsp;&nbsp;
                    <span style="font-weight: bold;">PO Number: </span>{{ $row['po_number'] }} &nbsp;&nbsp;
                    <span style="font-weight: bold;">Status: </span>{{ ucwords($row['tx_status_name']) }}
                </th>
            </tr>

            <tr>
                <th>SKU</th>
                <th>Name</th>
                <th>Variants</th>
                <th>Quantity</th>
            </tr>
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
                            <tr style="font-weight: bold;">
                                <td colspan="3" style="text-align: right;">Total</td>
                                <td colspan="2">
                                    <ul class="tags tags-report-quantity">
                                        @foreach( $total as $key => $value )
                                            <li class="tag-report-quantity">{{ ucwords($key) }}: {{ number_format(ceil($value)) }}</li>
                                        @endforeach
                                    </ul>
                                </td>
                            </tr>

                            {{-- create spacer row--}}
                            <tr><td colspan="5"></td></tr>
                @endif

        {{-- increment --}}
        <?php $row_count++ ?>
        <?php $previous_tx_id = $row->tx_id ?>
    @endforeach
    </table>
@stop

