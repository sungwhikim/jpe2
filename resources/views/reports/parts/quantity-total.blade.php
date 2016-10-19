<tr class="report-total">
    <td colspan="{{ $colspan1 }}" class="report-total-label text-right">Grand Total</td>
    <td colspan="{{ $colspan2 }}" class="text-nowrap">
        <ul class="tags tags-report-quantity">

            @foreach( $report_data['total'] as $key => $value )
                <li class="tag-report-quantity">{{ ucwords($key) }}: {{ number_format(ceil($value)) }}</li>
            @endforeach

        </ul>
    </td>
</tr>

