@extends('reports.report');

@section('report-criteria')
        @include('forms.report-warehouse')
        @include('forms.report-client')
        @include('forms.report-date', ['title' => 'As of Date',
                                       'name' => 'end_date'])
        @include('forms.report-toggle', ['title' => 'Show zero inventory',
                                         'name' => 'zero_inventory_items'])
@stop

@section('report-data')
    <table class="table table-responsive table-striped table-hover table-report">
        <thead>
            <tr>
                <th>SKU</th>
                <th>Name</th>
                <th>Variants</th>
                @foreach( $report['body']->uom_data as $uom)
                    <th>{{ ucwords(implode(' / ', $uom)) }} On Hand</th>
                @endforeach
                <th>Active</th>
            </tr>
        </thead>
        <tbody>
            @foreach( $report['body'] as $row )
                <tr>
                    <td>{{ $row->sku }}</td>
                    <td>{{ $row->name }}</td>
                    <td>
                        @include('reports.parts.variant-list')
                    </td>
                    <td>{{ $row->quantity }}</td>
                    @include('reports.parts.uom-quantities')
                    <td>@if( $row->active === true) True @else False @endif</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {!! $report['body']->appends($report['criteria'])->render() !!}
@stop

@section('js-data')
    <script>
        var reportCriteria = {!! collect($report['criteria']) !!}

        /* IF THE REPORT HAS DATES, MAKE SURE YOU INITIALIZE IT AS A DATE OBJECT HERE!!! */
        reportCriteria.end_date = new Date(reportCriteria.end_date);
    </script>
@stop
