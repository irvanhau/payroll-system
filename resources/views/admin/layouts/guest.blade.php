<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Login | Payroll System</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesdesign" name="author" />
    @include('admin.layouts.master_css')
</head>

<body data-topbar="dark" data-sidebar="dark">

    @yield('content')
    <div id="layout-wrapper">

        <div class="main-content">


        </div>

    </div>

    <!-- JAVASCRIPT -->
    @include('admin.layouts.master_js')
</body>

</html>
