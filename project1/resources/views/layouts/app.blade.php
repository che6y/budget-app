<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Nunito', sans-serif;
            font-weight: 200;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 84px;
        }

        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md {
            margin-bottom: 30px;
        }
        .action-link,
        .back-button{
            text-decoration: none;
        }
        .purchase-item {
            display: grid;
            grid-template-columns: 1fr 50px 50px;
            grid-template-rows: 30px;
            grid-gap: 5px;
        }
        .purchase-item .action-link,
        .purchase-item button,
        .back-button{
            font-size: 14px;
            border: 1px solid #636b6f;
            color: #636b6f;
            font-family: inherit;
            padding: 3px 8px;
            vertical-align: top;
            cursor: pointer;
        }
        .purchase-item .action-form,
        .purchase-item .action-link,
        .purchase-item_text{
            place-self: center;
        }
        form:not(.action-form) {
            margin: 0 0 20px 0;
        }
    </style>
</head>
<body>
<div class="flex-center position-ref full-height">
    @if (Route::has('login'))
    <div class="top-right links">
        @auth
        <a href="{{ url('/home') }}">Home</a>
        @else
        <a href="{{ route('login') }}">Login</a>

        @if (Route::has('register'))
        <a href="{{ route('register') }}">Register</a>
        @endif
        @endauth
    </div>
    @endif

    <div class="content">
        <div id="today-date"></div>
        @yield('content')
    </div>
</div>
<script type="text/javascript">
    var date = new Date();
    var weekDay = date.getDay();
    var month = date.getMonth();
    var day = date.getDate();
    var monthArr = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul","Aug", "Sep", "Oct", "Nov", "Dec"];
    var weekDayArr = ['Sunday','Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    document.getElementById('today-date').innerHTML = '<h2>'+ weekDayArr[weekDay] + ', ' + monthArr[month] + ' ' + day + '</h2>';
</script>
</body>
</html>
