@extends('main')

@section('nav')
    @include('layouts.nav')
@stop

@section('body')
    @yield('transaction-input')
@stop

@section('js-footer')
    @yield('tx-inline-javascript')
    @include('layouts.angular-js')
    @include('layouts.transaction-js')
@stop