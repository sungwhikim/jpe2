<!-- app/views/page/dashboard.blade.php -->

@extends('main')

@section('nav')
    @include('layouts.nav')
@stop

@section('body')
    <div class="container main-content home-page">
        <div class="row col-lg-12"><h1>Dashboard</h1></div>
        <div class="row menu-row">
            @foreach( Auth::user()->userFunctions() as $category => $functions )
            <div class="col-lg-6">
                <div class="panel panel-default panel-nav box-shadow--4dp">
                    <div class="panel-heading">{{ $category }}</div>
                    <div class="panel-body panel-body-large">
                        <ul class="nav nav-pills nav-stacked">
                        @foreach( $functions as $user_function )
                            @if( $user_function->name == 'divider' || $user_function->url == 'divider ')
                                <li class="divider"></li>
                            @else
                                <li><a href="{{ $user_function->url }}">{{ $user_function->name }}</a></li>
                            @endif
                        @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
@stop


@section('js-footer')
    @include('layouts.angular-js')
    @include('layouts.list-js')
@stop