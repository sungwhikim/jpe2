@extends('main')

@section('nav')
    @include('layouts.nav')
@stop

@section('body')
    <div class="container main-content home-page">
        <div class="row col-lg-12"><h1>Transaction</h1></div>
        <div class="row menu-row">
            <div class="col-lg-12">
                <div class="panel panel-default panel-nav box-shadow--4dp">
                <div class="panel-heading">{{ $tx_type_name }}</div>
                    <div class="panel-body panel-body-large">
                        <ul class="nav nav-pills nav-stacked">
                        @yield('transaction-input')
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop


@section('js-footer')
    @include('layouts.angular-js')
    @include('layouts.transaction-js')
@stop