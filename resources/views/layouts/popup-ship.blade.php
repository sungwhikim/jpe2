<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>

    <!-- css -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">

    <style type="text/css">

        .dataGrid th, .dataGrid td {
            border: 1px solid black;
            font-size: 12px;
            padding: 5px;
        }

        .rowHeader td {
            background-color: #cccccc;
        }

    </style>

    @yield('css-head')
    @yield('script-head')

    @yield('head')

</head>

<body style="padding: 5px 10px 10px 10px;">
@yield('body')
</body>

</html>