<!-- app/views/main/page/register.blade.php -->
@extends('main')

@section('body')

    <div class="container" style="margin-top:150px;">
        <div class="row col-lg-6 col-md-6 col-sm-6">
            <form method="POST" action="/auth/register">
                {!! csrf_field() !!}

                <div class="form-group">
                    <lable>User Name</lable>
                    <input type="text" name="username" value="{{ old('username') }}" class="form-control">
                </div>

                <div class="form-group">
                    <lable>Name</lable>
                    <input type="text" name="name" value="{{ old('name') }}" class="form-control">
                </div>

                <div class="form-group">
                    <lable>Email</lable>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-control">
                </div>

                <div class="form-group">
                    <lable>Password</lable>
                    <input type="password" name="password" class="form-control">
                </div>

                <div class="form-group">
                    <lable>Confirm Password</lable>
                    <input type="password" name="password_confirmation" class="form-control">
                </div>

                <div class="form-group">
                    <button class="btn btn-success" type="submit">Register</button>
                </div>
            </form>
        </div>
    </div>

@stop
