@extends('layouts.master')
@section('title', 'Tambah Akun Pengguna')
@section('content')
    <div class="page-wrapper">
        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-7 align-self-center">
                    <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Tambah Pengguna</h4>
                    <div class="d-flex align-items-center">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb m-0 p-0">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"
                                        class="text-muted">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('pengguna.index') }}"
                                        class="text-muted">Pengguna</a></li>
                                <li class="breadcrumb-item text-muted active" aria-current="page">Tambah</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <div class="col-5 align-self-center">
                    <div class="float-right">
                        <a href="{{ route('pengguna.index') }}" class="btn btn-sm btn-secondary bg-gradient-secondary"><i
                                class="fa fa-arrow-left"></i> Kembali</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="card-title">Form Tambah Pengguna Struktural</h4>
                    </div>
                    <form action="{{ route('pengguna.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label text-dark font-weight-medium">Username</label>
                                    <input type="text" class="form-control @error('username') is-invalid @enderror"
                                        name="username" value="{{ old('username') }}" required>
                                    @error('username')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label text-dark font-weight-medium">Role</label>
                                    <select class="form-control select2 @error('role') is-invalid @enderror" id="role"
                                        name="role" required>
                                        <option value="" selected disabled>Pilih Role</option>
                                        <option value="Super Admin">Super Admin</option>
                                        <option value="Admin">Admin</option>
                                        <option value="Kaprodi">Kaprodi</option>
                                        <option value="Kajur">Kajur</option>
                                    </select>
                                    @error('role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label text-dark font-weight-medium">Password</label>
                                    <div class="position-relative">
                                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                                            name="password" id="password" value="{{ old('password') }}" required
                                            style="padding-right: 2.5rem;">
                                        <span class="toggle-password" data-target="#password"
                                            style="position: absolute; top: 50%; right: 0.75rem; transform: translateY(-50%); cursor: pointer; color: #6c757d;">
                                            <i class="fa fa-eye"></i>
                                        </span>
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="form-label text-dark font-weight-medium">Konfirmasi Password</label>
                                    <div class="position-relative">
                                        <input type="password" class="form-control" name="password_confirmation"
                                            id="password_confirmation" required style="padding-right: 2.5rem;">
                                        <span class="toggle-password" data-target="#password_confirmation"
                                            style="position: absolute; top: 50%; right: 0.75rem; transform: translateY(-50%); cursor: pointer; color: #6c757d;">
                                            <i class="fa fa-eye"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions mt-3 border-top pt-3">
                            <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-save"></i>
                                Simpan Data</button>
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
            // Inisialisasi Select2
            $('#role').select2({
                theme: "bootstrap-5",
                placeholder: "Pilih Role",
                allowClear: true,
                width: '100%'
            });

            // Logika Toggle Show/Hide Password
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
