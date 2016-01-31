<!-- app/views/main/layouts/login.blade.php -->
@extends('main')

@section('body')

    <div class="container">
        <div id="loginbox" style="margin-top:150px;" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
            <div class="panel panel-default box-shadow--4dp" >
                <div class="panel-heading">
                    <div class="panel-title">Sign In</div>
                    <div style="float:right; position: relative; top: -20px"><a href="/password">Forgot password?</a></div>
                </div>

                <div style="padding-top:20px" class="panel-body" >

                    @if(Session::has('alert-message'))
                        <p class="col-sm-12 alert alert-{{ Session::get('alert-type', 'info') }}">
                            <span class="glyphicon glyphicon-{{ Session::get('alert-type', 'info') }}"></span>
                            &nbsp;{{ Session::get('alert-message') }}
                        </p>
                    @endif

                    <form class="form-horizontal" role="form" method="post" name="mainForm" action="/user/login">
                        {!! csrf_field() !!}
                        <div style="margin-bottom: 25px" class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                            <input id="login-username" type="text" class="form-control input-lg" name="username" value="{{ old('username') }}" placeholder="User Name">
                        </div>
                        <div style="margin-bottom: 25px" class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                            <input id="login-password" type="password" class="form-control input-lg" name="password" placeholder="Password">
                        </div>
                        <div style="margin-bottom: 10px" class="form-group">
                            <div class="col-sm-12 controls">
                                <button id="btn-login" type="submit" class="btn btn-primary btn-lg">Login</button>
                            </div>
                        </div>
                        <div class="input-group">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="remember_me" value="1"> Remember me
                                </label>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@stop
