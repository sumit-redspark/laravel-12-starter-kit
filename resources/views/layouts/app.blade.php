<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<!-- Main HTML head section -->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'Redspark Admin'))</title>

    <!-- Meta tags for SEO and content description -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="title" content="@yield('meta_title', 'Redspark Admin')" />
    <meta name="author" content="@yield('meta_author', 'Redspark Admin')" />
    <meta name="description" content="@yield('meta_description', 'A powerful Redspark Admin dashboard built with Bootstrap 5.')" />
    <meta name="keywords" content="@yield('meta_keywords', 'Redspark Admin, bootstrap 5, admin dashboard, responsive')" />

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">

    <!-- OverlayScrollbars CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.4.5/styles/overlayscrollbars.min.css"
        crossorigin="anonymous">

    <!-- Font imports -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
        crossorigin="anonymous" />

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        crossorigin="anonymous" />

    <!-- Application CSS -->
    <link rel="stylesheet" href="{{ asset('css/adminlte.css') }}" />

    @yield('header')
</head>

<!-- Main body section -->

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <!-- Application wrapper -->
    <div class="app-wrapper">

        @include('layouts.partials.header')

        @include('layouts.partials.navigation')

        <!-- Main content area -->
        <main class="app-main">

            @yield('content-header')

            @yield('content')

        </main>

        @include('layouts.partials.footer')

    </div>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- DataTables JavaScript -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

    <!-- jQuery Validation -->
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.20.0/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.20.0/dist/additional-methods.min.js"></script>

    <!-- Core JavaScript libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>

    <!-- Application JavaScript -->
    <script src="{{ asset('js/adminlte.js') }}"></script>

    <!-- OverlayScrollbars configuration -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarNav = document.querySelector('.sidebar-wrapper nav');
            if (sidebarNav && typeof OverlayScrollbarsGlobal?.OverlayScrollbars !== 'undefined') {
                OverlayScrollbarsGlobal.OverlayScrollbars(sidebarNav, {
                    overflow: {
                        x: 'hidden',
                        y: 'scroll'
                    },
                    scrollbars: {
                        theme: 'os-theme-light',
                        autoHide: 'leave',
                        clickScroll: true
                    }
                });
            }
        });
    </script>

    <script src="{{ asset('js/helper.js') }}"></script>
    @yield('scripts')

    <!-- OverlayScrollbars JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.4.5/browser/overlayscrollbars.browser.es6.min.js"
        crossorigin="anonymous"></script>
</body>

</html>
