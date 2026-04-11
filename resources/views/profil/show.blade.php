@extends('layouts.master')
@section('title', 'Profil Saya')
@section('content')
    <div class="page-wrapper">
        <div class="page-breadcrumb">
            <div class="row align-items-center">
                <div class="col-md-7 col-12">
                    <h4 class="page-title text-dark font-weight-bold mb-1">Profil Saya</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0 p-0 bg-transparent font-14">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item text-muted active" aria-current="page">Profil Saya</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-md-5 col-12 mt-3 mt-md-0 text-md-right">
                    <a href="{{ route('dashboard') }}" class="btn btn-sm btn-secondary font-weight-medium">
                        <i class="fa fa-arrow-left mr-2"></i> Kembali
                    </a>
                </div>
            </div>
        </div>

        <div class="container-fluid pb-5">
            <div class="row">

                {{-- KARTU PROFIL UTAMA (KOLOM KIRI) --}}
                <div class="col-lg-4 col-md-12 mb-4 d-flex">
                    <div class="card w-100">
                        <div class="card-body text-center p-4 p-lg-5">

                            <div class="position-relative d-inline-block mb-4">
                                <img src="{{ $urlFoto }}" alt="Foto Profil" class="rounded-circle border p-1"
                                    width="140" height="140" style="object-fit: cover;">
                                @if ($user->status == 'Aktif' || !$user->status)
                                    <span class="position-absolute bg-success border border-white rounded-circle"
                                        style="width: 22px; height: 22px; bottom: 10px; right: 10px;" title="Aktif"></span>
                                @else
                                    <span class="position-absolute bg-danger border border-white rounded-circle"
                                        style="width: 22px; height: 22px; bottom: 10px; right: 10px;"
                                        title="Nonaktif"></span>
                                @endif
                            </div>

                            <h4 class="text-dark font-weight-bold mb-1">{{ $namaLengkap }}</h4>
                            <p class="text-muted font-14 mb-3">{{ $user->email ?? 'Email belum diatur' }}</p>

                            {{-- Badge Role & Jabatan --}}
                            <div class="d-flex justify-content-center flex-wrap mb-4">
                                <span
                                    class="badge bg-{{ $roleColor }} text-white px-3 py-2 rounded-pill font-12 font-weight-bold mr-2 mb-2">
                                    {{ $user->role }}
                                </span>
                                @if ($user->jabatan)
                                    <span
                                        class="badge border border-secondary text-secondary px-3 py-2 rounded-pill font-12 font-weight-bold mb-2">
                                        {{ $user->jabatan }}
                                    </span>
                                @endif
                            </div>

                            <hr class="my-4 border-light w-100">

                            {{-- Info Singkat Kiri --}}
                            <div class="text-left mb-4">
                                <div class="mb-3 d-flex align-items-center">
                                    <div class="icon-box bg-light rounded text-muted mr-3 d-flex justify-content-center align-items-center"
                                        style="width: 36px; height: 36px;">
                                        <i data-feather="phone" style="width: 16px;"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block font-12 font-weight-medium">No. Telepon</small>
                                        <span class="text-dark font-weight-bold font-14">{{ $user->telepon ?? '-' }}</span>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center">
                                    <div class="icon-box bg-light rounded text-muted mr-3 d-flex justify-content-center align-items-center"
                                        style="width: 36px; height: 36px;">
                                        <i data-feather="calendar" style="width: 16px;"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block font-12 font-weight-medium">Akun Dibuat
                                            Pada</small>
                                        <span
                                            class="text-dark font-weight-bold font-14">{{ \Carbon\Carbon::parse($user->created_at)->translatedFormat('d F Y') }}</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Tombol Edit Profil --}}
                            <div class="w-100">
                                <a href="{{ route('profil.edit') }}"
                                    class="btn btn-sm btn-primary btn-block font-weight-bold">
                                    <i data-feather="edit-2" class="mr-2" style="width: 16px;"></i> Edit Profil
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- RINCIAN INFORMASI (KOLOM KANAN) --}}
                <div class="col-lg-8 col-md-12 mb-4">

                    {{-- INFORMASI AKADEMIK --}}
                    <div class="card mb-4">
                        <div class="card-body p-4 p-md-5">
                            <h5 class="text-dark font-weight-bold border-bottom pb-3 mb-4">
                                <i class="fas fa-graduation-cap text-primary mr-2"></i> Informasi Akademik
                            </h5>

                            <div class="row">
                                <div class="col-sm-6 mb-4">
                                    <small class="text-muted font-13 font-weight-medium d-block mb-1">Nomor Induk Pegawai
                                        (NIP)</small>
                                    <span class="text-dark font-15 font-weight-bold">{{ $user->nip ?? '-' }}</span>
                                </div>
                                <div class="col-sm-6 mb-4">
                                    <small class="text-muted font-13 font-weight-medium d-block mb-1">Program Studi</small>
                                    <span
                                        class="text-dark font-15 font-weight-bold">{{ $user->prodi ? $user->prodi->nama_prodi : '-' }}</span>
                                </div>
                                <div class="col-sm-6 mb-4 mb-sm-0">
                                    <small class="text-muted font-13 font-weight-medium d-block mb-1">Tanggal
                                        Bergabung</small>
                                    <span class="text-dark font-15 font-weight-bold">
                                        {{ $user->tanggal_bergabung ? \Carbon\Carbon::parse($user->tanggal_bergabung)->translatedFormat('d F Y') : '-' }}
                                    </span>
                                </div>
                                <div class="col-sm-6">
                                    <small class="text-muted font-13 font-weight-medium d-block mb-1">Lama Mengabdi</small>
                                    <span class="text-dark font-15 font-weight-bold">{{ $masaKerjaText }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- DATA PRIBADI --}}
                    <div class="card mb-4">
                        <div class="card-body p-4 p-md-5">
                            <h5 class="text-dark font-weight-bold border-bottom pb-3 mb-4">
                                <i class="fas fa-address-card text-info mr-2"></i> Data Pribadi
                            </h5>

                            <div class="row">
                                <div class="col-sm-6 mb-4">
                                    <small class="text-muted font-13 font-weight-medium d-block mb-1">Nama Lengkap (Tanpa
                                        Gelar)</small>
                                    <span class="text-dark font-15 font-weight-bold">{{ $user->nama ?? '-' }}</span>
                                </div>
                                <div class="col-sm-6 mb-4">
                                    <small class="text-muted font-13 font-weight-medium d-block mb-1">Gelar Akademik</small>
                                    <span class="text-dark font-15 font-weight-bold">
                                        @if ($user->gelar_depan || $user->gelar_belakang)
                                            {{ $user->gelar_depan ?? '-' }} / {{ $user->gelar_belakang ?? '-' }}
                                        @else
                                            -
                                        @endif
                                    </span>
                                </div>
                                <div class="col-sm-6 mb-4 mb-sm-0">
                                    <small class="text-muted font-13 font-weight-medium d-block mb-1">Jenis Kelamin</small>
                                    <span class="text-dark font-15 font-weight-bold">
                                        @if ($user->jenis_kelamin == 'L')
                                            Laki-Laki
                                        @elseif($user->jenis_kelamin == 'P')
                                            Perempuan
                                        @else
                                            -
                                        @endif
                                    </span>
                                </div>
                                <div class="col-sm-6">
                                    <small class="text-muted font-13 font-weight-medium d-block mb-1">Tanggal Lahir
                                        (Usia)</small>
                                    <span class="text-dark font-15 font-weight-bold">
                                        @if ($user->tanggal_lahir)
                                            {{ \Carbon\Carbon::parse($user->tanggal_lahir)->translatedFormat('d F Y') }}
                                            <span
                                                class="text-muted font-13 font-weight-medium ml-1">({{ \Carbon\Carbon::parse($user->tanggal_lahir)->age }}
                                                thn)</span>
                                        @else
                                            -
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- INFORMASI AKUN LOGIN --}}
                    <div class="card">
                        <div class="card-body p-4 p-md-5">
                            <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
                                <h5 class="text-dark font-weight-bold m-0">
                                    <i class="fas fa-shield-alt text-warning mr-2"></i> Autentikasi Sistem
                                </h5>
                                <span class="badge bg-light-success text-success px-3 py-1 rounded-pill font-12">
                                    <i class="fas fa-check-circle mr-1"></i> Akun Terhubung
                                </span>
                            </div>

                            <div class="row align-items-center">
                                <div class="col-sm-6 mb-4 mb-sm-0">
                                    <div class="mb-4">
                                        <small class="text-muted font-13 font-weight-medium d-block mb-1">Username
                                            Login</small>
                                        <span class="text-primary font-15 font-weight-bold">{{ $user->username }}</span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="p-3 bg-light rounded border border-light">
                                        <div class="d-flex align-items-start">
                                            <i data-feather="info" class="text-muted mr-2 mt-1" style="width: 16px;"></i>
                                            <small class="text-muted font-12" style="line-height: 1.5;">Anda dapat
                                                mengubah kredensial (Username & Password) Anda melalui tombol <b>Edit Profil
                                                    Saya</b> di sebelah kiri.</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            if (typeof feather !== 'undefined') {
                feather.replace();
            }

            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    showConfirmButton: false,
                    timer: 2000,
                    customClass: {
                        popup: 'rounded-lg shadow-sm'
                    }
                });
            @endif
        });
    </script>
@endpush
