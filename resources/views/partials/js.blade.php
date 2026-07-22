<!-- begin::NexLink Required Scripts -->
<script src="{{ asset('assets/libs/global/global.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables/datatables.min.js') }}"></script>
<!-- end::NexLink Required Scripts -->

<!-- begin::NexLink Page Scripts -->
<script src="{{ asset('assets/js/appSettings.js') }}"></script>
<script src="{{ asset('assets/js/main.js') }}"></script>
<!-- end::NexLink Page Scripts -->


<!-- begin::Scripts por vista -->
@stack('scripts')
<!-- end::Scripts por vista -->
