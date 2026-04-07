<!DOCTYPE html>
<html dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('master') }}/assets/images/favicon.png">
    <title>Silakan Login - LabPoltesa</title>
    <link href="{{ asset('master') }}/dist/css/style.min.css" rel="stylesheet">
</head>

<body>
    <div class="main-wrapper">
        <div class="preloader">
            <div class="lds-ripple">
                <div class="lds-pos"></div>
                <div class="lds-pos"></div>
            </div>
        </div>
        @yield('login')
    </div>
    <script src="{{ asset('master') }}/assets/libs/jquery/dist/jquery.min.js "></script>
    <script src="{{ asset('master') }}/assets/libs/popper.js/dist/umd/popper.min.js "></script>
    <script src="{{ asset('master') }}/assets/libs/bootstrap/dist/js/bootstrap.min.js "></script>
    <script>
        $(".preloader ").fadeOut();
    </script>

    @stack('scripts')
</body>

</html>
