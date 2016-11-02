<tr class="report-total" style="font-weight: bold;">
    <td colspan="{{ $colspan1 }}" style="text-align: right">Grand Total</td>

    {{-- If the count of the total uoms are equal to the uom_count, then we can do normal total column.  If not
         then we can't do normal totals and we need to just display them inline--}}
    @if( count($report_data['total']) == $uom_count )
        @foreach( $report_data['total'] as $key => $value )
            <td>{{ ucwords($key) }}</td>
            <td>{{ ceil($value) }}</td>
        @endforeach
    @else
        <td colspan="{{ $colspan2 }}" class="text-nowrap">
            <ul class="tags tags-report-quantity">

                @foreach( $report_data['total'] as $key => $value )
                    <li class="tag-report-quantity">{{ ucwords($key) }}: {{ ceil($value) }}</li>
                @endforeach

            </ul>
        </td>
    @endif
</tr>

