<!-- app/views/page/dashboard.blade.php -->

@extends('main')

@section('nav')
    @include('layouts.nav')
@stop

@section('body')
    <div class="container main-content home-page">
        <div class="row col-lg-12"><h1>Dashboard</h1></div>
        <div class="row menu-row">
            <div class="col-lg-6">
                <div class="panel panel-default panel-nav box-shadow--4dp">
                    <div class="panel-heading">Transactions</div>
                    <div class="panel-body panel-body-large">
                        <ul class="nav nav-pills nav-stacked">
                            <li><a href="#">Transaction Finder</a></li>
                            <li class="divider"></li>
                            <li><a href="#">Receiving</a></li>
                            <li><a href="#">Shipping</a></li>
                            <li><a href="#">Advanced Receiving Notice</a></li>
                            <li><a href="#">Advanced Shipping Notice</a></li>
                            <li class="divider"></li>
                            <li><a href="#">Warehouse Transfer</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="panel panel-default panel-nav box-shadow--4dp">
                    <div class="panel-heading">Reports</div>
                    <div class="panel-body panel-body-large">
                        <ul class="nav nav-pills nav-stacked">
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
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="panel panel-default panel-nav box-shadow--4dp">
                    <div class="panel-heading">Lists</div>
                    <div class="panel-body panel-body-large">
                        <ul class="nav nav-pills nav-stacked">
                            <li><a href="#">Products</a></li>
                            <li><a href="#">Bin Locations</a></li>
                            <li class="divider"></li>
                            <li><a href="#">Clients</a></li>
                            <li><a href="/warehouses">Warehouses</a></li>
                            <li><a href="/customers">Customers</a></li>
                            <li><a href="#">Carriers</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="panel panel-default panel-nav box-shadow--4dp">
                    <div class="panel-heading">Site Maintenance</div>
                    <div class="panel-body panel-body-large">
                        <ul class="nav nav-pills nav-stacked">
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
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
