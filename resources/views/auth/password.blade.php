@extends('main')

@section('body')

    <div class="container">
        <div style="margin-top:150px;" class="mainbox col-lg-4 col-md-4 col-md-offset-3 col-sm-6 col-sm-offset-2 col-xs-8 col-xs-offset-1">
            <div class="panel panel-default box-shadow--4dp" >
                <div class="panel-heading">
                    <div class="panel-title">Reset Password</div>
                </div>

                <div style="padding-top:20px" class="panel-body" >
                    <form method="POST" action="/password/email">
                        {!! csrf_field() !!}

                        @if (count($errors) > 0)
                            @foreach ($errors->all() as $error)
                                <p class="alert alert-danger"><span class="glyphicon glyphicon-danger"> {{ $error }}</p>
                            @endforeach
                        @endif

                        <div class="form-group">
                            <lable>Email</lable>
                            <input type="email" name="email" value="{{ old('email') }}" class="form-control">
                        </div>

                        <div class="form-group">
                            <button class="btn btn-success" type="submit">
                                Send Password Reset Link
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@stop
