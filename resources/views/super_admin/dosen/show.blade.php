@extends('layouts.master')
@section('title', 'Detail Profil Dosen')
@section('content')
    <div class="page-wrapper">
        <div class="page-breadcrumb">
            <div class="row align-items-center">
                <div class="col-md-7 col-12">
                    <h4 class="page-title text-dark font-weight-bold mb-1">Profil Dosen</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0 p-0 bg-transparent font-14">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{ route('dosen.index') }}">Dosen</a>
                            </li>
                            <li class="breadcrumb-item text-muted active" aria-current="page">Detail Profil</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-md-5 col-12 mt-3 mt-md-0 text-md-right">
                    <a href="{{ route('dosen.index') }}" class="btn btn-sm btn-dark shadow-sm">
                        <i class="fa fa-arrow-left mr-2"></i> Kembali
                    </a>
                </div>
            </div>
        </div>

        <div class="container-fluid pb-5">
            <div class="row">

                {{-- KARTU PROFIL UTAMA --}}
                <div class="col-lg-4 col-md-12 mb-4 d-flex">
                    <div class="card border-0 w-100">
                        <div class="card-body text-center p-4 p-lg-5">
                            @php
                                $urlFoto = $dosen->foto ? Storage::url($dosen->foto) : asset('images/default.jpg');
                                $namaLengkap = trim($dosen->gelar_depan . ' ' . $dosen->nama);
                                if ($dosen->gelar_belakang) {
                                    $namaLengkap .= ', ' . $dosen->gelar_belakang;
                                }
                            @endphp

                            {{-- Foto Profil dengan Indikator Status --}}
                            <div class="position-relative d-inline-block mb-4">
                                <img src="{{ $urlFoto }}" alt="Foto {{ $dosen->nama }}"
                                    class="rounded-circle shadow-sm border p-1" width="140" height="140"
                                    style="object-fit: cover;">
                                @if ($dosen->status == 'Aktif')
                                    <span class="position-absolute bg-success border border-white rounded-circle"
                                        style="width: 22px; height: 22px; bottom: 10px; right: 10px;"
                                        title="Aktif Mengajar"></span>
                                @else
                                    <span class="position-absolute bg-danger border border-white rounded-circle"
                                        style="width: 22px; height: 22px; bottom: 10px; right: 10px;"
                                        title="Nonaktif"></span>
                                @endif
                            </div>

                            <h4 class="text-dark font-weight-bold mb-1">{{ $namaLengkap }}</h4>
                            <p class="text-muted font-14 mb-3">{{ $dosen->email }}</p>

                            {{-- Badge Role & Jabatan --}}
                            <div class="d-flex justify-content-center flex-wrap mb-4">
                                <span class="badge badge-light px-3 py-2 rounded-pill font-12 font-weight-bold mr-2 mb-2">
                                    {{ $dosen->jabatan }}
                                </span>
                                <span class="badge badge-light text-primary px-3 py-2 rounded-pill font-12 font-weight-bold mb-2">
                                    {{ $dosen->users ? $dosen->users->role : 'Dosen' }}
                                </span>
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
                                        <small class="text-muted d-block font-12 font-weight-medium">No. Telepon /
                                            WA</small>
                                        <span class="text-dark font-weight-bold font-14">{{ $dosen->telepon }}</span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="icon-box bg-light rounded text-muted mr-3 d-flex justify-content-center align-items-center"
                                        style="width: 36px; height: 36px;">
                                        <i data-feather="calendar" style="width: 16px;"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block font-12 font-weight-medium">Terdaftar Sejak</small>
                                        <span
                                            class="text-dark font-weight-bold font-14">{{ \Carbon\Carbon::parse($dosen->created_at)->translatedFormat('d M Y') }}</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Tombol Edit Profil --}}
                            <div class="w-100">
                                <a href="{{ route('dosen.edit', $dosen->id_dosen) }}"
                                    class="btn btn-sm btn-primary btn-block py-2 font-weight-bold">
                                    <i data-feather="edit-2" class="mr-2" style="width: 16px;"></i> Edit Profil
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- RINCIAN INFORMASI --}}
                <div class="col-lg-8 col-md-12 mb-4">

                    {{-- INFORMASI AKADEMIK --}}
                    <div class="card border-0 mb-4">
                        <div class="card-body p-4 p-md-5">
                            <h5 class="text-dark font-weight-bold border-bottom pb-3 mb-4">
                                <i class="fas fa-graduation-cap text-primary mr-2"></i> Informasi Akademik
                            </h5>

                            <div class="row">
                                <div class="col-sm-6 mb-4">
                                    <small class="text-muted font-13 font-weight-medium d-block mb-1">Nomor Induk Pegawai
                                        (NIP)</small>
                                    <span class="text-dark font-15 font-weight-bold">{{ $dosen->nip }}</span>
                                </div>
                                <div class="col-sm-6 mb-4">
                                    <small class="text-muted font-13 font-weight-medium d-block mb-1">Program Studi</small>
                                    <span
                                        class="text-dark font-15 font-weight-bold">{{ $dosen->prodi ? $dosen->prodi->nama_prodi : '-' }}</span>
                                </div>
                                <div class="col-sm-6 mb-4 mb-sm-0">
                                    <small class="text-muted font-13 font-weight-medium d-block mb-1">Tanggal
                                        Bergabung</small>
                                    <span
                                        class="text-dark font-15 font-weight-bold">{{ \Carbon\Carbon::parse($dosen->tanggal_bergabung)->translatedFormat('d F Y') }}</span>
                                </div>
                                <div class="col-sm-6">
                                    @php
                                        $tglBergabung = \Carbon\Carbon::parse($dosen->tanggal_bergabung);
                                        $masaKerja = $tglBergabung->diff(\Carbon\Carbon::now());
                                    @endphp
                                    <small class="text-muted font-13 font-weight-medium d-block mb-1">Lama Mengabdi</small>
                                    <span class="text-dark font-15 font-weight-bold">{{ $masaKerja->y }} Tahun,
                                        {{ $masaKerja->m }} Bulan</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- DATA PRIBADI --}}
                    <div class="card border-0 mb-4">
                        <div class="card-body p-4 p-md-5">
                            <h5 class="text-dark font-weight-bold border-bottom pb-3 mb-4">
                                <i class="fas fa-address-card text-info mr-2"></i> Data Pribadi
                            </h5>

                            <div class="row">
                                <div class="col-sm-6 mb-4">
                                    <small class="text-muted font-13 font-weight-medium d-block mb-1">Nama Lengkap (Tanpa
                                        Gelar)</small>
                                    <span class="text-dark font-15 font-weight-bold">{{ $dosen->nama }}</span>
                                </div>
                                <div class="col-sm-6 mb-4">
                                    <small class="text-muted font-13 font-weight-medium d-block mb-1">Gelar Akademik</small>
                                    <span class="text-dark font-15 font-weight-bold">
                                        @if ($dosen->gelar_depan || $dosen->gelar_belakang)
                                            {{ $dosen->gelar_depan }} / {{ $dosen->gelar_belakang }}
                                        @else
                                            -
                                        @endif
                                    </span>
                                </div>
                                <div class="col-sm-6 mb-4 mb-sm-0">
                                    <small class="text-muted font-13 font-weight-medium d-block mb-1">Jenis Kelamin</small>
                                    <span
                                        class="text-dark font-15 font-weight-bold">{{ $dosen->jenis_kelamin == 'L' ? 'Laki-Laki' : 'Perempuan' }}</span>
                                </div>
                                <div class="col-sm-6">
                                    <small class="text-muted font-13 font-weight-medium d-block mb-1">Tanggal Lahir
                                        (Usia)</small>
                                    <span class="text-dark font-15 font-weight-bold">
                                        {{ \Carbon\Carbon::parse($dosen->tanggal_lahir)->translatedFormat('d F Y') }}
                                        <span
                                            class="text-muted font-13 font-weight-medium ml-1">({{ \Carbon\Carbon::parse($dosen->tanggal_lahir)->age }}
                                            thn)</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- INFORMASI AKUN & SISTEM --}}
                    <div class="card border-0">
                        <div class="card-body p-4 p-md-5">
                            <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
                                <h5 class="text-dark font-weight-bold m-0">
                                    <i class="fas fa-shield-alt text-info mr-2"></i> Autentikasi Sistem
                                </h5>
                                @if ($dosen->users)
                                    <span class="badge bg-light-success text-success px-3 py-1 rounded-pill font-12"><i
                                            class="fas fa-check-circle mr-1"></i> Akun Terhubung</span>
                                @else
                                    <span class="badge bg-light-danger text-danger px-3 py-1 rounded-pill font-12"><i
                                            class="fas fa-times-circle mr-1"></i> Tidak Ada Akun</span>
                                @endif
                            </div>

                            <div class="row align-items-center">
                                <div class="col-sm-6 mb-4 mb-sm-0">
                                    <small class="text-muted font-13 font-weight-medium d-block mb-1">Username
                                        Login</small>
                                    <span
                                        class="text-primary font-15 font-weight-bold">{{ $dosen->users ? $dosen->users->username : '-' }}</span>
                                </div>
                                <div class="col-sm-6">
                                    <div class="p-3 bg-light rounded border border-light">
                                        <div class="d-flex align-items-start">
                                            <i data-feather="info" class="text-muted mr-2 mt-1" style="width: 16px;"></i>
                                            <small class="text-muted font-12" style="line-height: 1.5;">Untuk mereset kata
                                                sandi atau mengubah hak akses, silakan kelola melalui menu <b>Master Data >
                                                    Pengguna</b>.</small>
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

    {{-- <style>
        .custom-shadow {
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.04) !important;
        }

        .custom-shadow-sm {
            box-shadow: 0 4px 12px rgba(95, 118, 232, 0.2) !important;
        }

        .custom-radius {
            border-radius: 16px !important;
        }
    </style> --}}
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
