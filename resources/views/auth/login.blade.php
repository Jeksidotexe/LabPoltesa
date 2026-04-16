@extends('layouts.auth')

@section('login')
    <div class="auth-wrapper d-flex no-block justify-content-center align-items-center position-relative"
        style="background:url() no-repeat center center;">
        <div class="auth-box row shadow-lg">
            <div class="col-lg-7 col-md-5 modal-bg-img" style="background-image: url({{ asset('bg-login.jpg') }});">
            </div>
            <div class="col-lg-5 col-md-7 bg-white">
                <div class="p-3">
                    <div class="text-center">
                        <img src="{{ asset('poltesa.png') }}" height="100" alt="logo">
                    </div>
                    <h2 class="mt-3 text-center">Silakan Login</h2>
                    <p class="text-center">Masukkan username dan password untuk mengakses halaman dashboard.</p>

                    <form action="{{ route('login') }}" method="POST" class="mt-4">
                        @csrf
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group mb-3">
                                    <label class="text-dark font-weight-medium" for="username">
                                        <i class="fas fa-user text-primary mr-1"></i> Username
                                    </label>
                                    <input class="form-control @error('username') is-invalid @enderror" type="text"
                                        id="username" name="username" value="{{ old('username') }}"
                                        placeholder="Masukkan username">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group mb-3">
                                    <label class="text-dark font-weight-medium" for="password">
                                        <i class="fas fa-lock text-primary mr-1"></i> Password
                                    </label>
                                    <div class="position-relative">
                                        <input class="form-control @error('password') is-invalid @enderror" type="password"
                                            id="password" name="password" placeholder="Masukkan password"
                                            style="padding-right: 2.5rem;">
                                        <span class="toggle-password" data-target="#password"
                                            style="position: absolute; top: 50%; right: 0.75rem; transform: translateY(-50%); cursor: pointer; color: #6c757d;">
                                            <i class="fa fa-eye"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 text-center mt-3">
                                <button type="submit" class="btn btn-block btn-primary" style="border-radius: 8px;">
                                    <i class="fas fa-sign-in-alt mr-1"></i> Masuk
                                </button>
                            </div>
                            <div class="col-lg-12 text-center mt-4">
                                Belum punya akun? <a href="{{ route('register') }}" class="text-primary">Register</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.toggle-password').click(function() {
                let targetInput = $($(this).data('target'));
                let icon = $(this).find('i');

                if (targetInput.attr('type') === 'password') {
                    targetInput.attr('type', 'text');
                    icon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    targetInput.attr('type', 'password');
                    icon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });
        });
    </script>
@endpush
