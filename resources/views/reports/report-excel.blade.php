<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <!-- css -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/style.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <div class="row col-lg-12 col-md-12 col-sm-12">
            <h1>{{ $report['title'] }}</h1>
        </div>

        <div class="row col-lg-12 col-md-12 col-sm-12 report-criteria-display">
            <ul class="tags">
                @foreach( $report['criteria_display'] as $key => $value )
                    <li class="">{{ $key }}: {{ $value }}</li>
                @endforeach
            </ul>
        </div>

        @yield('report-data')
    </div>
</body>

</html>

