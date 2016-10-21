<table class="table table-responsive table-striped table-hover table-report">
    <thead>
    <tr>
        <th colspan="3"></th>
        @for($month = 1; $month <= 12; $month++ )
            <th colspan="2">{{ date("M", mktime(0, 0, 0, $month, 10)) }}</th>
        @endfor
        <th class="total" colspan="2">Totals</th>
    </tr>
    <tr>
        <th>SKU</th>
        <th>Name</th>
        <th>Variants</th>
        @for($month = 1; $month <= 12; $month++ )
            <th>Rec</th>
            <th>Ship</th>
        @endfor
        <th class="total">Rec</th>
        <th class="total">Ship</th>
    </tr>
    </thead>
    <tbody>
    <?php $column_total = [] ?>
    @foreach( $report_data['body'] as $row )
        <tr>
            <td class="text-nowrap">{{ $row->sku }}</td>
            <td>{{ $row->product_name }}</td>
            <td>@include('reports.parts.variant-list')</td>
            <?php $line_total = ['receive_units' => [], 'ship_units' => []] ?>
            @foreach( $row->tx_data as $tx_data )
                <td>
                    @if( count($tx_data['receive_units']) == 0 )
                        {{ 0 }}
                    @else
                        <ul class="tags tags-report-quantity">
                            @foreach( $tx_data['receive_units'] as $key => $value )
                                <li class="tag-report-quantity">{{ ucwords($key) }}: {{ number_format($value) }}</li>

                                {{-- update unit line total --}}
                                @if( !isset($line_total['receive_units'][$key]) )<?php $line_total['receive_units'][$key] = 0; ?>@endif
                                <?php $line_total['receive_units'][$key] += $value ?>
                            @endforeach
                        </ul>
                    @endif
                </td>
                <td>
                    @if( count($tx_data['ship_units']) == 0 )
                        {{ 0 }}
                    @else
                        <ul class="tags tags-report-quantity">
                            @foreach( $tx_data['ship_units'] as $key => $value )
                                <li class="tag-report-quantity">{{ ucwords($key) }}: {{ number_format($value) }}</li>

                                {{-- update unit line total --}}
                                @if( !isset($line_total['ship_units'][$key]) )<?php $line_total['ship_units'][$key] = 0; ?>@endif
                                <?php $line_total['ship_units'][$key] += $value ?>
                            @endforeach
                        </ul>
                    @endif
                </td>
            @endforeach
            <td class="total">
                <ul class="tags tags-report-quantity">
                    @foreach( $line_total['receive_units'] as $key => $value )
                        <li class="tag-report-quantity">{{ ucwords($key) }}: {{ number_format($value) }}</li>
                    @endforeach
                </ul>
            </td>
            <td class="total">
                <ul class="tags tags-report-quantity">
                    @foreach( $line_total['ship_units'] as $key => $value )
                        <li class="tag-report-quantity">{{ ucwords($key) }}: {{ number_format($value) }}</li>
                    @endforeach
                </ul>
            </td>
        </tr>
    @endforeach

    {{-- Only show totals on last page --}}
    @if( $paginate === false || $report_data['body']->currentPage() == $report_data['body']->lastPage() )
        <tr class="total">
            <td colspan="3" style="text-align: right">Grand Total</td>
            <?php $line_total = ['receive_units' => [], 'ship_units' => []] ?>
            @foreach( $report_data['total'] as $total_data )
                <td>
                    @if( count($total_data['receive_units']) == 0 )
                        {{ 0 }}
                    @else
                        <ul class="tags tags-report-quantity">
                            @foreach( $total_data['receive_units'] as $key => $value )
                                <li class="tag-report-quantity">{{ ucwords($key) }}: {{ number_format($value) }}</li>

                                {{-- update unit line total --}}
                                @if( !isset($line_total['receive_units'][$key]) )<?php $line_total['receive_units'][$key] = 0; ?>@endif
                                <?php $line_total['receive_units'][$key] += $value ?>
                            @endforeach
                        </ul>
                    @endif
                </td>
                <td>
                    @if( count($total_data['ship_units']) == 0 )
                        {{ 0 }}
                    @else
                        <ul class="tags tags-report-quantity">
                            @foreach( $total_data['ship_units'] as $key => $value )
                                <li class="tag-report-quantity">{{ ucwords($key) }}: {{ number_format($value) }}</li>

                                {{-- update unit line total --}}
                                @if( !isset($line_total['ship_units'][$key]) )<?php $line_total['ship_units'][$key] = 0; ?>@endif
                                <?php $line_total['ship_units'][$key] += $value ?>
                            @endforeach
                        </ul>
                    @endif
                </td>
            @endforeach
            <td>
                <ul class="tags tags-report-quantity">
                    @foreach( $line_total['receive_units'] as $key => $value )
                        <li class="tag-report-quantity">{{ ucwords($key) }}: {{ number_format($value) }}</li>
                    @endforeach
                </ul>
            </td>
            <td>
                <ul class="tags tags-report-quantity">
                    @foreach( $line_total['ship_units'] as $key => $value )
                        <li class="tag-report-quantity">{{ ucwords($key) }}: {{ number_format($value) }}</li>
                    @endforeach
                </ul>
            </td>
        </tr>
    @endif
    </tbody>
</table>