@extends('layouts.popup-ship')

@section('head')

@endsection

@section('body')

    <img src="/image/jp-logo-popup.jpg" border="0" />
    <h2>Pick and Pack</h2>
    <table>
        <tr>
            <td>
                <table width="100%" cellpadding="10" cellspacing="1" class="dataGrid">
                    <tr class="rowHeader">
                        <td width="50">Aisle</td>
                        <td width="50">Section</td>
                        <td width="50">Tier</td>
                        <td width="50">Position</td>
                        <td width="150">Product</td>
                        <td width="230">Description</td>
                        <td align='center' width='70'>Variants</td>
                        <td>UOM</td>
                        <td width="80">QTY to Pick</td>
                        <td width="120">Receive Date</td>

                    </tr>
                    @foreach( $items as $item )
                        <tr>
                            <td align='center'>{{ $item->aisle }}</td>
                            <td align='center'>{{ $item->section }}</td>
                            <td align='center'>{{ $item->tier }}</td>
                            <td align='center'>{{ $item->position }}</td>
                            <td>{{ $item->sku }}</td>
                            <td>{{ $item->name }}</td>
                            <td>
                                @if( $item->variant1_value != null )
                                    <span class="badge">{{ $item->variant1_name }} : {{ $item->variant1_value }}</span>
                                @endif
                                @if( $item->variant2_value != null )
                                    <span class="badge">{{ $item->variant2_name }} : {{ $item->variant2_value }}</span>
                                @endif
                                @if( $item->variant3_value != null )
                                    <span class="badge">{{ $item->variant3_name }} : {{ $item->variant3_value }}</span>
                                @endif
                                @if( $item->variant4_value != null )
                                    <span class="badge">{{ $item->variant4_name }} : {{ $item->variant4_value }}</span>
                                @endif
                            </td>
                            <td>{{ $item->uom_name }}</td>
                            <td align='right'>{{ $item->quantity / $item->uom_multiplier }}</td>
                            <?php $receive_date = new DateTime($item->receive_date) ?>
                            <td align='center'>{{ $receive_date->format('m-d-Y') }}</td>
                        </tr>
                    @endforeach
                </table>
            </td>
        </tr>
        <tr><td><br /></td></tr>
        <tr><td><a href="#" onclick="window.print();">Print</a> | <a href="#" onclick="window.close();">Close</a></td></tr>
    </table>

@endsection