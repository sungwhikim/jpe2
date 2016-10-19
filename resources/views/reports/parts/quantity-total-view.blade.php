@if( $report_data['body']->currentPage() == $report_data['body']->lastPage() )
    @include('reports.parts.quantity-total', ['colspan1' => $colspan1, 'colspan2' => $colspan2])
@endif