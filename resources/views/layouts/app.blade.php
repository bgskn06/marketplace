<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Dashboard</title>

    <script src="https://code.iconify.design/3/3.1.1/iconify.min.js"></script>

    <link href="{{ asset('admin_assets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    @if(!(auth()->check() && auth()->user()->role === 'buyer'))
        <link href="{{ asset('admin_assets/css/sb-admin-2.min.css') }}" rel="stylesheet">
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<body id="page-top">

    <div id="wrapper">

        @if(auth()->check() && auth()->user()->role === 'buyer')
            {{-- Buyer layout: full width, no admin sidebar or topbar --}}
            <div id="content-wrapper" class="min-h-screen bg-gray-100 w-full">
                <div id="content" class="py-6">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        @isset($header)
                            <div class="mb-4">
                                <h1 class="text-2xl font-semibold text-gray-900">{{ $header }}</h1>
                            </div>
                        @endisset

                        <main>
                            {{ $slot }}
                        </main>
                    </div>
                </div>
            </div>
        @else
            {{-- Admin / Seller layout (original) --}}
            @include('layouts.sidebar')
            <div id="content-wrapper" class="d-flex flex-column">

                <div id="content">

                    @include('layouts.topbar')
                    <div class="container-fluid">

                        @isset($header)
                            <div class="d-sm-flex mb-4">
                                <h1 class="h3 mb-0 text-gray-800">{{ $header }}</h1>
                            </div>
                        @endisset

                        <main>
                            {{ $slot }}
                        </main>

                    </div>
                </div>
                @include('layouts.footer')
            </div>
        @endif

    </div>
    {{--  <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>  --}}

    {{--  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>  --}}

    <script src="{{ asset('admin_assets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('admin_assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <script src="{{ asset('admin_assets/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <script src="{{ asset('admin_assets/js/sb-admin-2.min.js') }}"></script>

    <script src="{{ asset('admin_assets/vendor/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('admin_assets/js/demo/chart-area-demo.js') }}"></script>
    <script src="{{ asset('admin_assets/js/demo/chart-pie-demo.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>


    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: "{{ session('success') }}",
                timer: 2000,
                showConfirmButton: false
            })
        </script>
    @endif


</body>

</html>
