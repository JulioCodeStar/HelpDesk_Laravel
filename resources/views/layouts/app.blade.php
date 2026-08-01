<!doctype html>
<html lang="en">
<head>
    @include('partials.head')
    @stack('styles')
    <script>
        (function () {
            var s = localStorage.getItem('sidebar-state');
            if (s) document.documentElement.setAttribute('data-app-sidebar', s);
        })();
    </script>
</head>

<body>
<div class="page-layout">

    <!-- begin::NexLink Page Header -->
    @include('partials.header')
    <!-- end::NexLink Page Header -->



    <!-- begin::NexLink Sidebar Menu -->
    @include('partials.sidebar')
    <!-- end::NexLink Sidebar Menu -->



    <main class="app-wrapper">

        <div class="container-fluid">

            {{-- Page Content --}}
            @include('partials.alerts')
            @yield('content')

        </div>

    </main>

    <!-- begin::NexLink Footer -->
    @include('partials.footer')
    <!-- end::NexLink Footer -->

</div>

<!-- begin::NexLink Page Scripts -->
@include('partials.js')
<!-- end::NexLink Page Scripts -->
</body>
</html>
