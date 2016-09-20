<ul class="tags">
    @if( $row->variant1_name != null )
        <li class="tag-default tag-variant-report">
            <strong>{{ $row->variant1_name }} : </strong>{{ $row->variant1_value }}
        </li>
    @endif
    @if( $row->variant2_name != null )
        <li class="tag-default tag-variant-report">
            <strong>{{ $row->variant2_name }} : </strong>{{ $row->variant2_value }}
        </li>
    @endif
    @if( $row->variant3_name != null )
        <li class="tag-default tag-variant-report">
            <strong>{{ $row->variant3_name }} : </strong>{{ $row->variant3_value }}
        </li>
    @endif
    @if( $row->variant4_name != null )
        <li class="tag-default tag-variant-report">
            <strong>{{ $row->variant4_name }} : </strong>{{ $row->variant4_value }}
        </li>
    @endif

</ul>

