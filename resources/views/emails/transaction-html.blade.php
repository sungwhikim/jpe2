<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sample Transaction</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <style>

        .header-image {
            margin: 20px 0 40px 0;
        }

        .tags {
            padding: 0;
        }
        .tags li {
            float: left;
            display: table-cell;
            vertical-align: middle;
            padding: 0 10px;
            border-radius: 4px;
            margin: 0 15px 10px 0;
        }
        .tag-default {
            color: #fff;
            background-color: #777;
            white-space: nowrap;
        }
        .tags .tag-variant {
            padding: 0 10px;
            margin: 5px 10px 5px 0;
        }

        .table > tbody > tr > td{
            vertical-align: middle !important;
        }

        footer {
            margin-top: 50px;
        }

        .panel-heading {
            padding: 1px 15px;
        }
        .panel-body {
            min-height: 110px;
        }

        .panel {
            width: 350px;
            margin-right: 15px;
            float: left;
        }


    </style>
</head>

<body>
<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <a href="http://jpent.com">
                <img src="/image/jpe-logo-popup.jpg" class="header-image">
            </a>
        </div>
        <div class="col-xs-12" style="padding-right: 20px;">
            <div class="alert alert-info">The transaction was created</div>
        </div>
    </div>

    <div class="row" style="padding-left: 15px;">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h4>ASN Shipping</h4>
                </div>
                <div class="panel-body">
                    <p>Transaction Date: 6-1-2016</p>
                    <p>PO Number: aaa000</p>
                    Status: Active
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Ship From Warehouse</h4>
                </div>
                <div class="panel-body">
                    <p>1290</p>
                    123 any street<br>
                    Mississauga, ON 000AAA
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Ship To Customer</h4>
                </div>
                <div class="panel-body">
                    <p>Walmart</p>
                    123 any street<br>
                    Mississauga, ON 000AAA
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Client</h4>
                </div>
                <div class="panel-body">
                    <p>Dave & Co</p>
                    123 any street<br>
                    Mississauga, ON 000AAA
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Carrier & Note</h4>
                </div>
                <div class="panel-body">
                    <p>Carrier: Jones Shipping</p>
                    <p>Tracking Number: 998888ZZZZ11111</p>
                    Notes: My Note
                </div>
            </div>
    </div>
    <!-- / end client details section -->
    <table class="table table-striped" id="items">
        <thead>
        <tr>
            <th>
                <h4>SKU</h4>
            </th>
            <th>
                <h4>Name</h4>
            </th>
            <th>
                <h4>Variants</h4>
            </th>
            <th>
                <h4>Quantity</h4>
            </th>
            <th>
                <h4>Inventory</h4>
            </th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>AAA000</td>
            <td>Test1</td>
            <td>
                <ul class="tags">
                    <li class="tag-default tag-variant">
                        <strong>DLOT : </strong>5555
                    </li>
                </ul>
            </td>
            <td>5</td>
            <td>10</td>
        </tr>
        <tr>
            <td>BBB111</td>
            <td>Test2</td>
            <td></td>
            <td>10300</td>
            <td>45000</td>
        </tr>
        <tr>
            <td>CCC222</td>
            <td>Test2</td>
            <td></td>
            <td>1000</td>
            <td>3200</td>
        </tr>
        </tbody>
    </table>
</div>
<footer>
        <p class="text-muted text-center">JP Enterprises, 2016</p>
</footer>
</body>
</html>