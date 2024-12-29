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
    <meta name="author" content="Extreme Coders" />
    @if(!empty($setting->favicon) && file_exists(public_path('storage/' . $setting->favicon)))
        <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . $setting->favicon) }}">
    @else
        <link rel="icon" type="image/gif" href="https://raw.githubusercontent.com/abisanthm/abisanthm.github.io/main2/favicon.gif">
    @endif
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-rqOj4VIlxqLw8ZXoGgH5YtRZ+8DOOhfe+BKVtNsIw9UqQomD0rIbbAgXt1NaMaa" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/material.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/tabler-icons.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx" crossorigin="anonymous" />
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Add this line to include Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body class="theme-1">
    <header class="site-header">
        <div class="header-wrapper">
            <div class="me-auto flex-grow-1 d-flex align-items-center">
                <ul class="list-unstyled header-menu-nav">
                    <li class="hdr-itm mob-hamburger">
                    <a href="#!" class="app-head-link" id="mobile-collapse">
                        <div class="hamburger hamburger-arrowturn">
                        <div class="hamburger-box">
                            <div class="hamburger-inner"></div>
                        </div>
                        </div>
                    </a>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    @include('layouts.admin.nav')

</body>

<div class="page-content-wrapper">
    <div class="content-container">
        <div class="page-content">
            @if ($message = Session::get('success'))
                <div class="alert alert-success text-center" role="alert">
                    {{ $message }}
                </div>
            @endif
            @yield('content')
        </div>
    </div>
</div>

@include('components.modal')

<footer class="app-footer">
  <div class="footer-wrapper">
    <div class="py-1">
        <span class="text-muted">&copy; {{ date('Y') }} {{ $setting->company_name }}. All Rights Reserved | Powered By {{ $setting->solution }}</span>
    </div>
  </div>
</footer>

@include('components.scripts')

@include('components.modal-scripts')

</body>
</html>
