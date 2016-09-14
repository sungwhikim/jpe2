@extends('main')

@section('nav')
    @include('layouts.nav')
@stop

@section('body')
    <div class="container main-content">
        <div class="row content-header">
            <div class="col-lg-12">
                <h1>{{ $report['title'] }}</h1>
            </div>
        </div>

        <div class="status-row">
            <div class="col-lg-12">
            </div>
        </div>

        <div class="row col-lg-12 report-criteria-container">
            @yield('report-criteria')
        </div>

        <div class="report-data-container">
            @yield('report-data')
        </div>

    </div>
@stop

@yield('js-data')

@section('js-footer')
    @include('layouts.angular-js')
    @include('layouts.report-js')
@stop