@extends('layouts.popup-ship')

@section('body')

    <img src="/image/jp-logo-popup.jpg" border="0" />
    <h2>Shipping memo No.</h2>
    <table>
        <tr>
            <td>

                <table class="dataGrid" style="width: 100%">
                    <tr bgcolor="#9db9b9">
                        <td width="33%">
                            {{ $data->client->name }}<br>
                            {{ $data->client->address1 }}<br>
                            @if( $data->client->address2 != null )
                                {{ $data->client->address2 }}<br>
                            @endif
                            {{ $data->client->city }}, {{ $data->client->province_code }} {{ $data->client->postal_code }}
                        </td>
                        <td width="33%" style="text-align:center" colspan="2">P.O./REF: {{ $data->po_number }}</td>
                        <?php $tx_date = new DateTime($data->tx_date) ?>
                        <td width="33%" style="text-align:center">Date: {{ $tx_date->format('m-d-Y') }}</td>
                    </tr>
                    <tr bgcolor="#9db9b9">
                        <td width="50%" colspan="2">
                            <strong>Ship To:</strong>
                            <strong>{{ $data->customer->name }}</strong><br>
                            {{ $data->customer->address1 }}<br>
                            @if( $data->customer->address2 != null )
                                {{ $data->customer->address2 }}<br>
                            @endif
                            {{ $data->customer->city }}, {{ $data->customer->province_code }} {{ $data->customer->postal_code }}
                        </td>
                        <td width="50%" colspan="2">
                            From: {{ $data->company_short_name }}<br>
                            {{ $data->warehouse->name }} Warehouse<br>
                            {{ $data->warehouse->address1 }}<br>
                            @if( $data->warehouse->address2 != null )
                                {{ $data->warehouse->address2 }}<br>
                            @endif
                            {{ $data->warehouse->city }}, {{ $data->warehouse->province_code }} {{ $data->warehouse->postal_code }}
                        </td>
                    </tr>
                    <tr bgcolor="#9db9b9">
                        <td width="50%" colspan="2">Carrier: {{ $data->carrier_name }}</td>
                        <td width="50%" colspan="2">Tracking No: {{ $data->tracking_number }}</td>
                    </tr>
                </table>

                <p></p>
                <strong>Memo</strong>
                <table width="100%" class="dataGrid">
                    <tr>
                        <td>{{ $data->note }}</td>
                    </tr>
                </table>

            </td>
        </tr>
        <tr><td><br /></td></tr>

        <tr>
            <td>
                <table width="100%" class="dataGrid">
                    <tr class="rowHeader">
                        <td width="150">Product</td>
                        <td width="200">Description</td>
                        <td align='center' width='100'>Variants</td>
                        <td width="50">UOM</td>
                        <td width='50'>Total Qty</td>
                    </tr>
                    <?php $total = 0; ?>
                    @foreach( $data->items as $item )
                        <?php $total += $item->quantity / $item->selectedUomMultiplierTotal ; ?>
                        <tr>
                            <td>{{ $item->product->sku }}</td>
                            <td>{{ $item->product->name }}</td>
                            <td align="center" >
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
                            <td>{{ $item->product->uom_name }}</td>
                            <td align='right'>{{ $item->quantity / $item->selectedUomMultiplierTotal }}</td>
                        </tr>
                    @endforeach
                    <tr><td colspan="4" align="right">Total: </td><td align="right">{{ $total }}</td></tr>

                </table>
            </td>
        </tr>

        <tr><td><br /></td></tr>
        <tr>
            <td>
                <table width="100%" cellpadding="2" cellspacing="1"  border="0">
                    <tr ><td class=nonGrid">No. of Cases _______________________ No. of Pallets _______________________  Weight ___________________</td></tr>
                    <tr ><td class=nonGrid">&nbsp;</td></tr>
                    <tr ><td class=nonGrid">Receiver's/Driver's Signature ______________________________________________________________________</td></tr>
                </table>
            </td>
        </tr>

        <tr><td><br /></td></tr>
        <tr><td><a href="#" onclick="window.print();">Print</a> | <a href="#" onclick="window.close();">Close</a></td></tr>

    </table>

@endsection


