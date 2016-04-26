<!-- app/views/main.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>JPE 2.0</title>

    <!-- css -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/titatoggle-dist-min.css" rel="stylesheet">
    <link href="/css/style.css" rel="stylesheet">
    <link href="/css/style.media.css" rel="stylesheet">

    @yield('css-head')
    @yield('script-head')

</head>

<body ng-app="myApp">

<!-- navigation -->
<nav class="navbar navbar-default navbar-fixed-top">
    @include('layouts.header-bar')
    @yield('nav')
</nav>

<!-- angular ajax spinner container -->
<span us-spinner="{radius:30, width:8, length: 16}"></span>

<!-- main body content -->
@yield('body')

<!-- footer -->
@section('footer')
<footer class="footer">
    <div class="container text-center">
        <p class="text-muted"><span class="glyphicon glyphicon-copyright-mark"></span> JP Enterprises <?php echo date('Y'); ?></p>
    </div>
</footer>
@show

@section('js-bootstrap')
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="/js/jquery-1.11.3.min.js"></script>
<script src="/js/bootstrap.min.js"></script>
@show

@yield('js-data')
@yield('js-footer')
@yield('script-footer')

</body>
</html>