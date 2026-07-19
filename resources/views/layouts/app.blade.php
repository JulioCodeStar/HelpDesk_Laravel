<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none">
<head>
    @include('partials.head')
</head>

<body class="bg-body-bg">

<!-- Start Preloader Area -->
{{--<div class="preloader" id="preloader">--}}
{{--    <div class="preloader">--}}
{{--        <div class="waviy position-relative">--}}
{{--                    <span class="d-inline-block">--}}
{{--                    S--}}
{{--                    </span>--}}
{{--            <span class="d-inline-block">--}}
{{--                    T--}}
{{--                    </span>--}}
{{--            <span class="d-inline-block">--}}
{{--                    K--}}
{{--                    </span>--}}
{{--            <span class="d-inline-block">--}}
{{--                    H--}}
{{--                    </span>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}
<!-- End Preloader Area -->


{{-- SIDEBAR --}}
@include('partials.sidebar')

<!-- Start Main Content Area -->
<div class="container-fluid">
    <div class="main-content d-flex flex-column">

        {{-- Header Sticky --}}
        @include('partials.header')

        <div class="main-content-container overflow-hidden">
            <div class="card bg-white rounded-10 border border-white p-20 mb-4 text-center">

                @yield('content')
            </div>
        </div>
        <div class="flex-grow-1">
        </div>
        <!-- Start Footer Area -->
       @include('partials.footer')
        <!-- End Footer Area -->
    </div>
</div>
<!-- Start Main Content Area -->
@include('partials.top')
<!-- Link Of JS File -->
@include('partials.js')

@stack('scripts')
</body>
</html>
