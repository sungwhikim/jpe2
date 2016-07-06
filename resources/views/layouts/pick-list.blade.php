@extends('layouts.popup-ship')

@section('head')

@endsection

@section('body')

    <img src="/image/jp-logo-popup.jpg" border="0" />
    <h2>Ship To Pick List</h2>
    <table>
        <tr>
            <td>

                <table width="100%" class="dataGrid">
                    <tr class="rowHeader">
                        <td>Ship From Warehouse</td>
                        <td>Client</td>
                        <td>To Customer</td>
                        <td>P.O./REF</td>
                        <td>Date</td>
                    </tr>
                    <tr>
                        <td>
                            {{ $data->company_short_name }}<br>
                            {{ $data->warehouse->name }} Warehouse<br>
                            {{ $data->warehouse->address1 }}<br>
                            @if( $data->warehouse->address2 != null )
                                {{ $data->warehouse->address2 }}<br>
                            @endif
                            {{ $data->warehouse->city }}, {{ $data->warehouse->province_code }} {{ $data->warehouse->postal_code }}
                        </td>
                        <td>
                            {{ $data->client->name }}<br>
                            {{ $data->client->address1 }}<br>
                            @if( $data->client->address2 != null )
                                {{ $data->client->address2 }}<br>
                            @endif
                            {{ $data->client->city }}, {{ $data->client->province_code }} {{ $data->client->postal_code }}
                        </td>
                        <td>
                            <strong>{{ $data->customer->name }}</strong><br>
                            {{ $data->customer->address1 }}<br>
                            @if( $data->customer->address2 != null )
                                {{ $data->customer->address2 }}<br>
                            @endif
                            {{ $data->customer->city }}, {{ $data->customer->province_code }} {{ $data->customer->postal_code }}
                        </td>
                        <td style="text-align:center">{{ $data->po_number }}</td>
                        <?php $tx_date = new DateTime($data->tx_date) ?>
                        <td style="text-align:center">{{ $tx_date->format('m-d-Y') }}</td>
                    </tr>

                    <tr class="rowHeader">
                        <td colspan=5>Note</td>
                    </tr>
                    <tr>
                        <td colspan=5>{{ $data->note }}</td>
                    </tr>


                </table>

            </td>
        </tr>
        <tr><td><br /></td></tr>
        <tr>
            <td>
                <table width="100%" cellpadding="10" cellspacing="1" class="dataGrid">
                    <tr class="rowHeader">
                        <td width="150">Product</td>
                        <td width="230">Description</td>
                        <td>UOM</td>
                        <td width='50'>Total Qty</td>
                        <td align='center' width='70'>Variants</td>
                        <td width="50">Aisle</td>
                        <td width="50">Section</td>
                        <td width="50">Tier</td>
                        <td width="50">Position</td>
                        <td width="80">QTY to Pick</td>
                        <td width="120">Receive Date</td>

                    </tr>
                    @foreach( $data->items as $item )
                        <?php $is_first = true; ?>
                        @foreach( $item->bins as $bin )
                            <tr>
                                @if( $is_first === true )
                                    <?php $is_first = false; ?>
                                    <td>{{ $item->product->sku }}</td>
                                    <td>{{ $item->product->name }}</td>
                                    <td>{{ $item->selectedUomName }}</td>
                                    <td>{{ $item->quantity / $item->selectedUomMultiplierTotal }}</td>
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
                                @else
                                    <td colspan="5"></td>
                                @endif
                                <td align='center'>{{ $bin->aisle }}</td>
                                <td align='center'>{{ $bin->section }}</td>
                                <td align='center'>{{ $bin->tier }}</td>
                                <td align='center'>{{ $bin->position }}</td>
                                <td align='center'>{{ $bin->quantity / $item->selectedUomMultiplierTotal }}</td>
                                <?php $receive_date = new DateTime($bin->receive_date) ?>
                                <td align='center'>{{ $receive_date->format('m-d-Y') }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                </table>
            </td>
        </tr>

        <tr><td><br /></td></tr>
        <tr>
            <td>
                <table width="100%" cellpadding="2" cellspacing="1"  border="0">
                    <tr >
                        <td class=nonGrid" width="50%" valign="top" style="line-height:20px;">
                            <strong>Products Picked</strong><br />
                            <div style="margin-left:15px">
                                No. of Each's _________<br />
                                No. of Packs  _________<br />
                                No. of Cases  _________<br />
                                No. of Full Pallets Picked _________<br /><br />

                                JP Pallets Used  _________<br />
                                Labels Applied   _________<br />
                                No. of Pallets Shrink Wrapped  _________<br />
                            </div>
                        </td>
                        <td class=nonGrid" width="50%" valign="top" style="line-height:20px;">
                            <strong>Shipment Summary</strong><br />
                            <div style="margin-left:15px">
                                Total Cartons _________<br />
                                Total Pallets _________<br />
                                Weight _________<br />
                                Shipment Method _________<br /><br />
                            </div>
                            Picked By ________________________<br /><br />
                            Verified By ________________________
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr><td><br /></td></tr>
        <tr><td><a href="#" onclick="window.print();">Print</a> | <a href="#" onclick="window.close();">Close</a></td></tr>
    </table>

@endsection