<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>JPE 2.0</title>

    <!-- css -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/style.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <button type="button" class="btn btn-info hidden-print" onclick="window.print()" style="margin: 15px 0 0 0">Print</button>

        <div class="row col-lg-12 col-md-12 col-sm-12">
            <h1>{{ $report['title'] }}</h1>
        </div>

        <div class="row col-lg-12 col-md-12 col-sm-12 report-criteria-display">
            <ul class="tags">
                @foreach( $report['criteria_display'] as $key => $value )
                    <li class="tag-primary tag-primary-print">{{ $key }}: {{ $value }}</li>
                @endforeach
            </ul>
        </div>

        @yield('report-data')
    </div>

    <script>
        //show print dialog automatically after loading
        //window.print();
    </script>
</body>

</html>

