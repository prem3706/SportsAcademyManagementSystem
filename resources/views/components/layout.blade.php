<!DOCTYPE html>
<!--
* CoreUI - Free Bootstrap Admin Template
-->

<html lang="en">

<head>

    <base href="{{ url('/') }}/">

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

    <meta name="description" content="Sports Academy Management System">

    <meta name="author" content="Prem">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Sports Academy Management System</title>

    <!-- CoreUI CSS -->
    <link rel="stylesheet" href="vendors/simplebar/css/simplebar.css">
    <link rel="stylesheet" href="css/vendors/simplebar.css">

    <link href="css/style.css" rel="stylesheet">
    <link href="css/examples.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">

    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/style.css">
    
    <!-- Dropify CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css">

    <link rel="stylesheet" href="css/app.css?v={{ file_exists(public_path('css/app.css')) ? filemtime(public_path('css/app.css')) : time() }}">


</head>

<body>

    {{ $slot }}

    <x-off-canvas />

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- CoreUI -->
    <script src="vendors/@coreui/coreui/js/coreui.bundle.min.js"></script>

    <script src="vendors/simplebar/js/simplebar.min.js"></script>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Toastr -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

    <!-- Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js"></script>

    <!-- Dropify JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"></script>


    <!-- Custom Scripts -->
    <script src="js/config.js"></script>

    <script src="js/color-modes.js"></script>

    <script src="js/script.js?v={{ file_exists(public_path('js/script.js')) ? filemtime(public_path('js/script.js')) : time() }}"></script>


    <!-- Header Shadow -->
    <script>
        const header = document.querySelector("header.header");

        document.addEventListener("scroll", () => {

            if (header) {

                header.classList.toggle(
                    "shadow-sm",
                    document.documentElement.scrollTop > 0
                );
            }
        });
    </script>



    <!-- Toastr Config -->
    <script>
        toastr.options = {

            "closeButton": true,

            "progressBar": true,

            "positionClass": "toast-top-right",

            "timeOut": "3000",

            "extendedTimeOut": "1000",

            "showMethod": "fadeIn",

            "hideMethod": "fadeOut"
        };


        // 1. Success Message or Password Status Link
        @if (session('success'))
            toastr.success("{{ session('success') }}");
        @endif

        @if (session('status'))
            toastr.success("{{ session('status') }}");
        @endif

        // 2. Explicit Error Message
        @if (session('error'))
            toastr.error("{{ session('error') }}");
        @endif

        // 3. Form Validation Errors (like the 'email' error)
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                toastr.error("{{ $error }}");
            @endforeach
        @endif

        // 4. Other Statuses
        @if (session('warning'))
            toastr.warning("{{ session('warning') }}");
        @endif

        @if (session('info'))
            toastr.info("{{ session('info') }}");
        @endif
    </script>

    @stack('scripts')

</body>

</html>
