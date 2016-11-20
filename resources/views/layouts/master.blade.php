<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Quati</title>
    <script src="{{ URL::asset('@res/jquery-3.1.1.min.js')}}"></script>
    <script src="{{ URL::asset('@res/bootstrap-3.3.7-dist/js/bootstrap.min.js')}}"></script>
    <link href="{{ URL::asset('@res/bootstrap-3.3.7-dist/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{ URL::asset('@res/quati.css')}}" rel="stylesheet">
</head>

<body>

<header class="site-header">
    @include('layouts.header')
</header>

<nav class="navbar navbar-inverse">
    @include('layouts.mainmenu')
</nav>

<div class="container">
    <div class="row">
        <div class="col-sm-3" role="complementary">
            @include('layouts.sidemenu')
        </div>
        <div class="col-sm-9" role="main">
            @yield('content')
        </div>
    </div>
</div>

</body>

</html>
