<table class="table table-responsive table-striped table-hover table-report">
    <thead>
    <tr>
        <th></th>
        @for($month = 1; $month <= 12; $month++ )
            <th colspan="2">{{ date("M", mktime(0, 0, 0, $month, 10)) }}</th>
        @endfor
        <th class="total" colspan="2">Totals</th>
    </tr>
    <tr>
        <th>Client</th>
        @for($month = 1; $month <= 12; $month++ )
            <th>Trans</th>
            <th>Units</th>
        @endfor
        <th class="total">Trans</th>
        <th class="total">Units</th>
    </tr>
    </thead>
    <tbody>
    <?php $column_total = [] ?>
    @foreach( $report_data['body'] as $row )
        <tr>
            <td class="text-nowrap">{{ $row->client_short_name }}</td>
            <?php $line_total = ['tx_count' => 0, 'units' => []] ?>
            @foreach( $row->tx_data as $tx_data )
                <td>{{ $tx_data['tx_count'] }}</td>
                <td>
                    @if( count($tx_data['units']) == 0 )
                        {{ 0 }}
                    @else
                        <ul class="tags tags-report-quantity">
                        @foreach( $tx_data['units'] as $key => $value )
                            <li class="tag-report-quantity">{{ ucwords($key) }}: {{ number_format($value) }}</li>

                            {{-- update unit line total --}}
                            @if( !isset($line_total['units'][$key]) )<?php $line_total['units'][$key] = 0; ?>@endif
                            <?php $line_total['units'][$key] += $value ?>
                        @endforeach
                        </ul>
                    @endif
                </td>

                {{-- update tx count line total --}}
                <?php $line_total['tx_count'] += $tx_data['tx_count']; ?>
            @endforeach
            <td class="total">{{ number_format($line_total['tx_count']) }}</td>
            <td class="total">
                <ul class="tags tags-report-quantity">
                    @foreach( $line_total['units'] as $key => $value )
                        <li class="tag-report-quantity">{{ ucwords($key) }}: {{ number_format($value) }}</li>
                    @endforeach
                </ul>
            </td>
        </tr>
    @endforeach

    {{-- Only show totals on last page --}}
    @if( $paginate === false || $report_data['body']->currentPage() == $report_data['body']->lastPage() )
        <tr class="total">
            <td>Totals</td>
            <?php $line_total = ['tx_count' => 0, 'units' => []] ?>
            @foreach( $report_data['total'] as $total_data )
                <td>{{ $total_data['tx_count'] }}</td>
                <td>
                    @if( count($total_data['units']) == 0 )
                        {{ 0 }}
                    @else
                        <ul class="tags tags-report-quantity">
                            @foreach( $total_data['units'] as $key => $value )
                                <li class="tag-report-quantity">{{ ucwords($key) }}: {{ number_format($value) }}</li>

                                {{-- update unit line total --}}
                                @if( !isset($line_total['units'][$key]) )<?php $line_total['units'][$key] = 0; ?>@endif
                                <?php $line_total['units'][$key] += $value ?>
                            @endforeach
                        </ul>
                    @endif
                </td>

                {{-- update tx count line total --}}
                <?php $line_total['tx_count'] += $total_data['tx_count']; ?>
            @endforeach
            <td>{{ number_format($line_total['tx_count']) }}</td>
            <td>
                <ul class="tags tags-report-quantity">
                    @foreach( $line_total['units'] as $key => $value )
                        <li class="tag-report-quantity">{{ ucwords($key) }}: {{ number_format($value) }}</li>
                    @endforeach
                </ul>
            </td>
        </tr>
    @endif
    </tbody>
</table>