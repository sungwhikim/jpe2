<ul class="tags">
    @if( $row->variant1_name != null )
        <li class="tag-default tag-variant-report">
            {{ $row->variant1_name }} : {{ $row->variant1_value }}
        </li>
    @endif
    @if( $row->variant2_name != null )
        <li class="tag-default tag-variant-report">
            {{ $row->variant2_name }} : {{ $row->variant2_value }}
        </li>
    @endif
    @if( $row->variant3_name != null )
        <li class="tag-default tag-variant-report">
            {{ $row->variant3_name }} : {{ $row->variant3_value }}
        </li>
    @endif
    @if( $row->variant4_name != null )
        <li class="tag-default tag-variant-report">
            {{ $row->variant4_name }} : {{ $row->variant4_value }}
        </li>
    @endif

</ul>

