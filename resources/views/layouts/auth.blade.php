<!DOCTYPE html>
<html dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('master') }}/assets/images/favicon.png">
    <title>Silakan Login - LabAgrobisnisPoltesa</title>
    <link href="{{ asset('master') }}/dist/css/style.min.css" rel="stylesheet">
    {{-- CSS SweetAlert2 --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
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
    {{-- JS SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(".preloader ").fadeOut();

        // Konfigurasi Global SweetAlert2
        $(document).ready(function() {
            const swalCustom = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-sm btn-primary font-weight-medium'
                },
                buttonsStyling: false
            });

            // 1. Menangkap session sukses
            @if (session('swal_success'))
                swalCustom.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('swal_success') }}',
                    customClass: {
                        confirmButton: 'btn btn-sm btn-success font-weight-medium'
                    },
                    confirmButtonText: '<i class="fas fa-check-circle mr-1"></i> Tutup'
                });
            @endif

            // 2. Menangkap session error umum
            @if (session('swal_error'))
                swalCustom.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: '{{ session('swal_error') }}',
                    customClass: {
                        confirmButton: 'btn btn-sm btn-danger font-weight-medium'
                    },
                    confirmButtonText: '<i class="fas fa-times-circle mr-1"></i> Coba Lagi'
                });
            @endif

            // 3. Menangkap Session Peringatan (Akun Nonaktif)
            @if (session('swal_warning'))
                swalCustom.fire({
                    icon: 'warning',
                    title: 'Perhatian!',
                    text: '{{ session('swal_warning') }}',
                    customClass: {
                        confirmButton: 'btn btn-sm btn-warning text-dark font-weight-medium'
                    },
                    confirmButtonText: '<i class="fas fa-info-circle mr-1"></i> Mengerti'
                });
            @endif
        });
    </script>

    @stack('scripts')
</body>

</html>
