@extends('layouts.master')
@section('title', 'Detail Profil Pengguna')
@section('content')
    <div class="page-wrapper">
        <div class="page-breadcrumb">
            <div class="row align-items-center">
                <div class="col-md-7 col-12">
                    <h4 class="page-title text-dark font-weight-bold mb-1">Profil Pengguna</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0 p-0 bg-transparent font-14">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('pengguna.index') }}">Pengguna</a></li>
                            <li class="breadcrumb-item text-muted active" aria-current="page">Detail Profil</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-md-5 col-12 mt-3 mt-md-0 text-md-right">
                    <a href="{{ route('pengguna.index') }}" class="btn btn-sm btn-secondary font-weight-medium">
                        <i class="fa fa-arrow-left mr-2"></i> Kembali
                    </a>
                </div>
            </div>
        </div>

        <div class="container-fluid pb-5">
            <div class="row">

                {{-- KARTU PROFIL UTAMA (KIRI) --}}
                <div class="col-lg-4 col-md-12 mb-4 d-flex">
                    <div class="card w-100">
                        <div class="card-body text-center p-4 p-lg-5">

                            {{-- Foto Profil dengan Indikator Status --}}
                            <div class="position-relative d-inline-block mb-4">
                                <img src="{{ $urlFoto }}" alt="Foto Profil" class="rounded-circle border p-1"
                                    width="140" height="140" style="object-fit: cover;">
                                @if ($pengguna->status == 'Aktif' || !$pengguna->status)
                                    <span class="position-absolute bg-success border border-white rounded-circle"
                                        style="width: 22px; height: 22px; bottom: 10px; right: 10px;"
                                        title="Akun Aktif"></span>
                                @else
                                    <span class="position-absolute bg-danger border border-white rounded-circle"
                                        style="width: 22px; height: 22px; bottom: 10px; right: 10px;"
                                        title="Nonaktif"></span>
                                @endif
                            </div>

                            <h4 class="text-dark font-weight-bold mb-1">{{ $namaLengkap }}</h4>
                            <p class="text-muted font-14 mb-3">{{ $pengguna->email ?? 'Email belum diatur' }}</p>

                            {{-- Badge Role & Jabatan --}}
                            <div class="d-flex justify-content-center flex-wrap mb-4">
                                <span
                                    class="badge bg-{{ $roleColor }} text-white px-3 py-2 rounded-pill font-12 font-weight-bold mr-2 mb-2">
                                    {{ $pengguna->role }}
                                </span>
                                @if ($pengguna->jabatan)
                                    <span
                                        class="badge border border-secondary text-secondary px-3 py-2 rounded-pill font-12 font-weight-bold mb-2">
                                        {{ $pengguna->jabatan }}
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
                                        <span
                                            class="text-dark font-weight-bold font-14">{{ $pengguna->telepon ?? '-' }}</span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="icon-box bg-light rounded text-muted mr-3 d-flex justify-content-center align-items-center"
                                        style="width: 36px; height: 36px;">
                                        <i data-feather="calendar" style="width: 16px;"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block font-12 font-weight-medium">Akun Dibuat</small>
                                        <span class="text-dark font-weight-bold font-14">
                                            {{ \Carbon\Carbon::parse($pengguna->created_at)->translatedFormat('d F Y') }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            {{-- Tombol Edit Profil --}}
                            <div class="w-100">
                                <a href="{{ route('pengguna.edit', $pengguna->id) }}"
                                    class="btn btn-sm btn-primary btn-block font-weight-bold">
                                    <i data-feather="edit-2" class="mr-2" style="width: 16px;"></i> Edit Pengguna
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- RINCIAN INFORMASI (KANAN) --}}
                <div class="col-lg-8 col-md-12 mb-4">

                    {{-- INFORMASI AKUN --}}
                    <div class="card">
                        <div class="card-body p-4 p-md-5">
                            <h5 class="text-dark font-weight-bold border-bottom pb-3 mb-4">
                                <i class="fas fa-shield-alt text-primary mr-2"></i> Informasi Akun Login
                            </h5>
                            <div class="row">
                                <div class="col-sm-6 mb-4">
                                    <small class="text-muted font-13 font-weight-medium d-block mb-1">Username Login</small>
                                    <span class="text-primary font-15 font-weight-bold">{{ $pengguna->username }}</span>
                                </div>
                                <div class="col-sm-6 mb-4">
                                    <small class="text-muted font-13 font-weight-medium d-block mb-1">Hak Akses
                                        (Role)</small>
                                    <span class="text-dark font-15 font-weight-bold">{{ $pengguna->role }}</span>
                                </div>
                                <div class="col-sm-6 mb-4 mb-sm-0">
                                    <small class="text-muted font-13 font-weight-medium d-block mb-1">Status
                                        Keaktifan</small>
                                    <span
                                        class="text-dark font-15 font-weight-bold">{{ $pengguna->status ?? 'Aktif' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- INFORMASI PROFESI & AKADEMIK --}}
                    <div class="card">
                        <div class="card-body p-4 p-md-5">
                            <h5 class="text-dark font-weight-bold border-bottom pb-3 mb-4">
                                <i class="fas fa-briefcase text-info mr-2"></i> Detail Struktural & Akademik
                            </h5>

                            <div class="row">
                                <div class="col-sm-6 mb-4">
                                    <small class="text-muted font-13 font-weight-medium d-block mb-1">Nomor Induk Pegawai
                                        (NIP)</small>
                                    <span class="text-dark font-15 font-weight-bold">{{ $pengguna->nip ?? '-' }}</span>
                                </div>
                                <div class="col-sm-6 mb-4">
                                    <small class="text-muted font-13 font-weight-medium d-block mb-1">Program Studi</small>
                                    <span class="text-dark font-15 font-weight-bold">
                                        {{ $pengguna->prodi ? $pengguna->prodi->nama_prodi : '-' }}
                                    </span>
                                </div>
                                <div class="col-sm-6 mb-4 mb-sm-0">
                                    <small class="text-muted font-13 font-weight-medium d-block mb-1">Tanggal
                                        Bergabung</small>
                                    <span class="text-dark font-15 font-weight-bold">
                                        {{ $pengguna->tanggal_bergabung ? \Carbon\Carbon::parse($pengguna->tanggal_bergabung)->translatedFormat('d F Y') : '-' }}
                                    </span>
                                </div>
                                <div class="col-sm-6">
                                    <small class="text-muted font-13 font-weight-medium d-block mb-1">Masa Kerja</small>
                                    <span class="text-dark font-15 font-weight-bold">{{ $masaKerjaText }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- DATA PRIBADI --}}
                    <div class="card">
                        <div class="card-body p-4 p-md-5">
                            <h5 class="text-dark font-weight-bold border-bottom pb-3 mb-4">
                                <i class="fas fa-address-card text-success mr-2"></i> Data Pribadi Lengkap
                            </h5>

                            <div class="row">
                                <div class="col-sm-6 mb-4">
                                    <small class="text-muted font-13 font-weight-medium d-block mb-1">Nama Lengkap (Tanpa
                                        Gelar)</small>
                                    <span class="text-dark font-15 font-weight-bold">{{ $pengguna->nama ?? '-' }}</span>
                                </div>
                                <div class="col-sm-6 mb-4">
                                    <small class="text-muted font-13 font-weight-medium d-block mb-1">Gelar
                                        Akademik</small>
                                    <span class="text-dark font-15 font-weight-bold">
                                        @if ($pengguna->gelar_depan || $pengguna->gelar_belakang)
                                            {{ $pengguna->gelar_depan }} / {{ $pengguna->gelar_belakang }}
                                        @else
                                            -
                                        @endif
                                    </span>
                                </div>
                                <div class="col-sm-6 mb-4 mb-sm-0">
                                    <small class="text-muted font-13 font-weight-medium d-block mb-1">Jenis Kelamin</small>
                                    <span class="text-dark font-15 font-weight-bold">
                                        @if ($pengguna->jenis_kelamin == 'L')
                                            Laki-Laki
                                        @elseif($pengguna->jenis_kelamin == 'P')
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
                                        @if ($pengguna->tanggal_lahir)
                                            {{ \Carbon\Carbon::parse($pengguna->tanggal_lahir)->translatedFormat('d F Y') }}
                                            <span class="text-muted font-13 font-weight-medium ml-1">
                                                ({{ \Carbon\Carbon::parse($pengguna->tanggal_lahir)->age }} thn)
                                            </span>
                                        @else
                                            -
                                        @endif
                                    </span>
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
    <script>
        $(document).ready(function() {
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        });
    </script>
@endpush
