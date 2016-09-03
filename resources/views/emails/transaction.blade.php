<div style='padding: 25px; margin-right: auto;margin-left: auto;font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;font-size: 16px;'>
    <div style="padding-right: 20px;">
        <div style="position: relative;min-height: 1px;float: left;width: 100%;">
            <a href="{{ $data->host }}" style="background-color: transparent;color: #337ab7;text-decoration: underline;">
                <img src="{{ $data->host }}/image/jpe-logo-popup.jpg" class="header-image" style="border: 0;vertical-align: middle;page-break-inside: avoid;margin: 0 0 40px 0;max-width: 100%;">
            </a>
        </div>
        <div style="padding-right: 20px;position: relative;min-height: 1px;float: left;width: 100%;">
            <div style="max-width: 657px;min-width: 305px;padding: 15px;margin-bottom: 20px;border: 1px solid transparent;border-radius: 4px;color: #31708f;background-color: #d9edf7;border-color: #bce8f1;">{{ $data->tx_status_message }}</div>
        </div>
    </div>

    <div style="margin-bottom: 20px;border: 1px solid transparent;border-radius: 4px;width: 335px;margin-right: 15px;float: left;border-color: #337ab7;">
        <div style="padding: 1px 15px;border-bottom: 1px solid transparent;border-top-left-radius: 3px;border-top-right-radius: 3px;color: #fff;background-color: #337ab7;border-color: #337ab7;">
            <h4 style="font-weight: 500;line-height: 1.1;color: inherit;margin-top: 10px;margin-bottom: 10px;font-size: 18px;">{{ $data->tx_title }}</h4>
        </div>
        <div style="padding: 15px;min-height: 85px;">
            <p style="margin: 0 0 10px;">Transaction Date: {{ $data->tx_date }}</p>
            <p style="margin: 0 0 10px;">PO Number: {{ $data->po_number }}</p>
            <span style="text-transform: capitalize;">Status: {{ $data->tx_status_name }}</span>
        </div>
    </div>
    <div style="margin-bottom: 20px;border: 1px solid transparent;border-radius: 4px;width: 335px;margin-right: 15px;float: left;border-color: #ddd;">
        <div style="padding: 1px 15px;border-bottom: 1px solid transparent;border-top-left-radius: 3px;border-top-right-radius: 3px;color: #333;background-color: #f5f5f5;border-color: #ddd;">
            <h4 style="font-weight: 500;line-height: 1.1;color: inherit;margin-top: 10px;margin-bottom: 10px;font-size: 18px;">{{ $data->warehouse_title }} Warehouse</h4>
        </div>
        <div style="padding: 15px;min-height: 85px;">
            <p style="margin: 0 0 10px;">{{ $data->warehouse->name }}</p>
            {{ $data->warehouse->address1 }}<br>
            @if( strlen($data->warehouse->address2) > 0 ) {{ $data->warehouse->address2 }}<br> @endif
            {{ $data->warehouse->city }}, {{ $data->warehouse->province->code }} {{ $data->warehouse->postal_code }}
        </div>
    </div>
    @if( $data->tx_type == 'ship' || $data->tx_type == 'csr')
    <div style="margin-bottom: 20px;border: 1px solid transparent;border-radius: 4px;width: 335px;margin-right: 15px;float: left;border-color: #ddd;">
        <div style="padding: 1px 15px;border-bottom: 1px solid transparent;border-top-left-radius: 3px;border-top-right-radius: 3px;color: #333;background-color: #f5f5f5;border-color: #ddd;">
            <h4 style="font-weight: 500;line-height: 1.1;color: inherit;margin-top: 10px;margin-bottom: 10px;font-size: 18px;">Ship To Customer</h4>
        </div>
        <div style="padding: 15px;min-height: 85px;">
            <p style="margin: 0 0 10px;">{{ $data->customer->name }}</p>
            {{ $data->customer->address1 }}<br>
            @if( strlen($data->customer->address2) > 0 ) {{ $data->customer->address2 }}<br> @endif
            {{ $data->customer->city }}, {{ $data->customer->province->code }} {{ $data->customer->postal_code }}
        </div>
    </div>
    @endif
    <div style="margin-bottom: 20px;border: 1px solid transparent;border-radius: 4px;width: 335px;margin-right: 15px;float: left;border-color: #ddd;">
        <div style="padding: 1px 15px;border-bottom: 1px solid transparent;border-top-left-radius: 3px;border-top-right-radius: 3px;color: #333;background-color: #f5f5f5;border-color: #ddd;">
            <h4 style="font-weight: 500;line-height: 1.1;color: inherit;margin-top: 10px;margin-bottom: 10px;font-size: 18px;">Client</h4>
        </div>
        <div style="padding: 15px;min-height: 85px;">
            <p style="margin: 0 0 10px;">{{ $data->client->name }}</p>
            {{ $data->client->address1 }}<br>
            @if( strlen($data->client->address2) > 0 ) {{ $data->client->address2 }}<br> @endif
            {{ $data->client->city }}, {{ $data->client->province->code }} {{ $data->client->postal_code }}
        </div>
    </div>
    <div style="margin-bottom: 20px;border: 1px solid transparent;border-radius: 4px;width: 335px;margin-right: 15px;float: left;border-color: #ddd;">
        <div style="padding: 1px 15px;border-bottom: 1px solid transparent;border-top-left-radius: 3px;border-top-right-radius: 3px;color: #333;background-color: #f5f5f5;border-color: #ddd;">
            <h4 style="font-weight: 500;line-height: 1.1;color: inherit;margin-top: 10px;margin-bottom: 10px;font-size: 18px;">Carrier & Note</h4>
        </div>
        <div style="padding: 15px;min-height: 85px;">
            <p style="margin: 0 0 10px;">Carrier: @if( $data->carrier_id != null ) {{ $data->carrier->name }} @endif</p>
            <p style="margin: 0 0 10px;">Tracking Number: {{ $data->tracking_number }}</p>
            Notes: {{ $data->note }}
        </div>
    </div>

    <table id="items" style="border-spacing: 0;border-collapse: collapse;background-color: transparent;width: 100%;max-width: 100%;margin-bottom: 20px;font-size: 16px;">
        <thead style="display: table-header-group;">
        <tr>
            <th style="padding: 8px;text-align: left;line-height: 1.42857143;vertical-align: bottom;border-top: 0;border-bottom: 2px solid #ddd;">
                <h4 style="font-weight: 500;line-height: 1.1;color: inherit;margin-top: 10px;margin-bottom: 10px;font-size: 18px;">SKU</h4>
            </th>
            <th style="padding: 8px;text-align: left;line-height: 1.42857143;vertical-align: bottom;border-top: 0;border-bottom: 2px solid #ddd;">
                <h4 style="font-weight: 500;line-height: 1.1;color: inherit;margin-top: 10px;margin-bottom: 10px;font-size: 18px;">Name</h4>
            </th>
            <th style="padding: 8px;text-align: left;line-height: 1.42857143;vertical-align: bottom;border-top: 0;border-bottom: 2px solid #ddd;">
                <h4 style="font-weight: 500;line-height: 1.1;color: inherit;margin-top: 10px;margin-bottom: 10px;font-size: 18px;">Variants</h4>
            </th>
            <th style="padding: 8px;text-align: left;line-height: 1.42857143;vertical-align: bottom;border-top: 0;border-bottom: 2px solid #ddd;">
                <h4 style="font-weight: 500;line-height: 1.1;color: inherit;margin-top: 10px;margin-bottom: 10px;font-size: 18px;">UOM</h4>
            </th>
            <th style="padding: 8px;text-align: left;line-height: 1.42857143;vertical-align: bottom;border-top: 0;border-bottom: 2px solid #ddd;">
                <h4 style="font-weight: 500;line-height: 1.1;color: inherit;margin-top: 10px;margin-bottom: 10px;font-size: 18px;">Quantity</h4>
            </th>
            @if( $data->tx_type == 'ship' || $data->tx_type == 'csr')
            <th style="padding: 8px;text-align: left;line-height: 1.42857143;vertical-align: bottom;border-top: 0;border-bottom: 2px solid #ddd;">
                <h4 style="font-weight: 500;line-height: 1.1;color: inherit;margin-top: 10px;margin-bottom: 10px;font-size: 18px;">Inventory</h4>
            </th>
            @endif
        </tr>
        </thead>
        <tbody>
        <?php $row_color = 'background-color: #f9f9f9;'; ?>
        @foreach( $data->items as $item )
            <tr style="<?php echo $row_color ?>">
                <td style="padding: 8px;line-height: 1.42857143;vertical-align: middle;border-top: 1px solid #ddd;">{{ $item->sku }}</td>
                <td style="padding: 8px;line-height: 1.42857143;vertical-align: middle;border-top: 1px solid #ddd;">{{ $item->name }}</td>
                <td style="padding: 8px;line-height: 1.42857143;vertical-align: middle;border-top: 1px solid #ddd;">
                    <ul class="tags" style="margin-top: 0;margin-bottom: 10px;padding: 0;">
                        @if( strlen($item->variant1_value) > 0  )
                            <li class="tag-default tag-variant" style="color: #fff;background-color: #777;white-space: nowrap;float: left;display: table-cell;vertical-align: middle;padding: 0 10px;border-radius: 4px;margin: 5px 10px 5px 0;">
                                <strong style="font-weight: bold;">{{ $item->variant1_name }} : </strong>{{ $item->variant1_value }}
                            </li>
                        @endif
                        @if( strlen($item->variant2_value) > 0  )
                            <li class="tag-default tag-variant" style="color: #fff;background-color: #777;white-space: nowrap;float: left;display: table-cell;vertical-align: middle;padding: 0 10px;border-radius: 4px;margin: 5px 10px 5px 0;">
                                <strong style="font-weight: bold;">{{ $item->variant2_name }} : </strong>{{ $item->variant2_value }}
                            </li>
                        @endif
                        @if( strlen($item->variant3_value) > 0  )
                            <li class="tag-default tag-variant" style="color: #fff;background-color: #777;white-space: nowrap;float: left;display: table-cell;vertical-align: middle;padding: 0 10px;border-radius: 4px;margin: 5px 10px 5px 0;">
                                <strong style="font-weight: bold;">{{ $item->variant3_name }} : </strong>{{ $item->variant3_value }}
                            </li>
                        @endif
                        @if( strlen($item->variant4_value) > 0  )
                            <li class="tag-default tag-variant" style="color: #fff;background-color: #777;white-space: nowrap;float: left;display: table-cell;vertical-align: middle;padding: 0 10px;border-radius: 4px;margin: 5px 10px 5px 0;">
                                <strong style="font-weight: bold;">{{ $item->variant4_name }} : </strong>{{ $item->variant4_value }}
                            </li>
                        @endif
                    </ul>
                </td>
                <td style="padding: 8px;line-height: 1.42857143;vertical-align: middle;border-top: 1px solid #ddd;text-transform: capitalize;">{{ $item->uom_name }}</td>
                <td style="padding: 8px;line-height: 1.42857143;vertical-align: middle;border-top: 1px solid #ddd;">{{ $item->quantity / $item->uom_multiplier }}</td>
                @if( $data->tx_type == 'ship' || $data->tx_type == 'csr')
                <td style="padding: 8px;line-height: 1.42857143;vertical-align: middle;border-top: 1px solid #ddd;">{{ $item->inventory }}</td>
                @endif
            </tr>
            <?php $row_color = ( $row_color == '' ) ? 'background-color: #f9f9f9;' : ''; ?>
        @endforeach
        </tbody>
    </table>
</div>
<footer style='display: block;margin-top: 50px;font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;'>
    <p class="text-muted text-center" style="margin: 0 0 10px;text-align: center;color: #777;">JP Enterprises, <?php echo date('Y'); ?></p>
</footer>
