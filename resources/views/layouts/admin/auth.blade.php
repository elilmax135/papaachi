
<!DOCTYPE html>
<html lang="en">
<head>
    <title>{{ $setting->company_name }}</title>
    <!-- Meta -->
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta  name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="{{ $setting->desc }}" />
    <meta name="keywords" content="{{ $setting->tags }}" />
    <meta name="author" content="{{ $setting->solution }}" />
    @if(!empty($setting->favicon) && file_exists(public_path('storage/' . $setting->favicon)))
        <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . $setting->favicon) }}">
    @else
        <link rel="icon" type="image/gif" href="https://raw.githubusercontent.com/abisanthm/abisanthm.github.io/main2/favicon.gif">
    @endif

    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/material.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/tabler-icons.min.css') }}">

</head>

<body class="theme-1">


    <div class="auth-wrapper auth-v2 ">
        <div class="auth-content">
            <div class="authentication-inner row m-0">
                <div class="d-none d-lg-block col-lg-7 col-xl-8 p-0 img-side">
                    @if(!empty($setting->login_img) && file_exists(public_path('storage/' . $setting->login_img)))
                        <img class="img-fluid" width="100%" src="{{ asset('storage/' . $setting->login_img) }}" alt="Login Form Image">
                    @else
                        <img class="img-fluid" width="100%" src="https://raw.githubusercontent.com/abisanthm/abisanthm.github.io/main2/loginbg.png" alt="Default Login Form Image">
                    @endif
                </div>
                <div class="d-flex col-12 col-lg-5 col-xl-4 align-items-center authentication-bg p-sm-5 p-4">
                    <div class="w-px-400 mx-auto">
                        <h4 class="mb-2">Welcome to <b class="login_name">{{ $setting->company_name }}!</b></h4>
                        <p class="mb-4"><b>{{ $setting->desc }}</b></p>
                        <div class="row justify-content-center">
                            <div class="col-lg-12">
                                @yield('content')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Required Js -->
    <script src="../assets/js/vendor-all.js"></script>
    <script src="../assets/js/plugins/bootstrap.min.js"></script>
    <script src="../assets/js/plugins/feather.min.js"></script>

</body>
</html>
