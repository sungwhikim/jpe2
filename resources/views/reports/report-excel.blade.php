<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <!-- css -->
    {{--<link href="/css/bootstrap.min.css" rel="stylesheet">--}}
    {{--<link href="/css/style.css" rel="stylesheet">--}}
</head>

<body>
    <div class="container">
        <table>
            <tr>
                <td colspan="4">
                    <h1>{{ $report['title'] }}</h1>
                </td>
            </tr>
            <tr><td colspan="4"></td></tr>

            @foreach( $report['criteria_display'] as $key => $value )
                <tr>
                    <td colspan="2">{{ $key }}: {{ $value }}</td>
                    <td></td><td></td>
                </tr>
            @endforeach
        </table>

        @yield('report-data')
    </div>
</body>

</html>

