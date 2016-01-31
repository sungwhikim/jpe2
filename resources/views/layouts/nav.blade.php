<div class="col-lg-12">
    <div class="container">
        <div class="nav navbar-nav hidden-lg hidden-md hidden-sm visible-xs">
            <button class="navbar-toggle" data-target="#menu-main" data-toggle="collapse" type="button">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <ul class="nav navbar-nav navbar-collapse hidden-xs">
            <li><a href="/dashboard">Dashboard</a></li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="true">Transactions<span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="#">Transaction Finder</a></li>
                    <li class="divider"></li>
                    <li><a href="#">Receiving</a></li>
                    <li><a href="#">Shipping</a></li>
                    <li><a href="#">Advanced Receiving Notice</a></li>
                    <li><a href="#">Advanced Shipping Notice</a></li>
                    <li class="divider"></li>
                    <li><a href="#">Warehouse Transfer</a></li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="true">Reports<span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="#">Inventory - Pallet Detail Report</a></li>
                    <li><a href="#">Inventory - By Warehouse</a></li>
                    <li><a href="#">Shipping Report</a></li>
                    <li class="divider"></li>
                    <li><a href="#">CSR Report</a></li>
                    <li><a href="#">Transactions - Yearly</a></li>
                    <li><a href="#">Bin Location Details</a></li>
                    <li><a href="#">Zero Report</a></li>
                    <li><a href="#">ReOrder Report</a></li>
                    <li class="divider"></li>
                    <li><a href="#">Billing Reports</a></li>
                    <li><a href="#">Client Billing Reports</a></li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="true">Lists<span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="#">Products</a></li>
                    <li><a href="#">Bin Locations</a></li>
                    <li class="divider"></li>
                    <li><a href="#">Clients</a></li>
                    <li><a href="/warehouses">Warehouses</a></li>
                    <li><a href="/customers">Customers</a></li>
                    <li><a href="#">Carriers</a></li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="true">Site<span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="#">Fees</a></li>
                    <li><a href="#">Taxes</a></li>
                    <li class="divider"></li>
                    <li><a href="/users">Users</a></li>
                    <li><a href="#">User Groups</a></li>
                    <li><a href="#">Menu Items</a></li>
                    <li class="divider"></li>
                    <li><a href="/countries">Countries</a></li>
                    <li><a href="#">Provinces / States</a></li>
                </ul>
            </li>
        </ul>
        <ul class="nav navbar-nav navbar-right hidden-xs">
            <li><a href="#"><span class="glyphicon glyphicon-wrench"></span> 1290 / DC</a></li>
            <li><a href="#"><span class="glyphicon glyphicon-cog"></span> {{ !empty(Auth::user()->name) ? Auth::user()->name : '' }}</a></li>
            <li><a href="/logout"><span class="glyphicon glyphicon-log-out"></span> logout</a></li>
        </ul>
    </div>
</div>
