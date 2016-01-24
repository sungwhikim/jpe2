<!-- app/views/main.blade.php -->

<!DOCTYPE html>
<html lang="en" ng-app="myApp">
<head>
    @section('head')
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>JPE 2.0</title>
    @show

    <!-- css -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/style.css" rel="stylesheet">
    <link href="/css/style.media.css" rel="stylesheet">

    @section('css')
    @show

    @section('script-head')
    @show
</head>

<body>

@section('nav')
    @include('layouts.nav')
@show

@yield('body')

<footer class="footer">
    <div class="container text-center">
        <p class="text-muted"><span class="glyphicon glyphicon-copyright-mark"></span> JP Enterprises 2016</p>
    </div>
</footer>

@section('js-bootstrap')
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="/js/jquery-1.11.3.min.js"></script>
<script src="/js/bootstrap.min.js"></script>
@show

@section('js-data')
@show

@yield('js-footer')

@section('script-footer')
@show
</body>
</html>