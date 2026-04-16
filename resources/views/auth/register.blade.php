@extends('layouts.auth')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

@section('login')
    <div class="auth-wrapper d-flex no-block justify-content-center align-items-center position-relative"
        style="background:url() no-repeat center center; min-height: 100vh; padding: 2rem 0;">
        <div class="auth-box row shadow-lg rounded" style="max-width: 950px; width: 95%; overflow: hidden;">
            {{-- Bagian Kiri: Gambar --}}
            <div class="col-lg-5 col-md-5 modal-bg-img d-none d-md-block"
                style="background-image: url({{ asset('bg-register.jpg') }}); background-size: cover; background-position: center;">
            </div>

            {{-- Bagian Kanan: Form Registrasi Multi-step --}}
            <div class="col-lg-7 col-md-7 bg-white">
                <div class="p-4 p-md-5">
                    <div class="text-center mb-4">
                        <img src="{{ asset('poltesa.png') }}" height="70" alt="logo">
                        <h3 class="mt-3 font-weight-bold text-dark">Registrasi Akun</h3>
                        <p class="text-muted font-14">Lengkapi data Anda dalam 3 langkah mudah.</p>
                    </div>

                    {{-- Progress Indicator --}}
                    <div class="d-flex justify-content-between align-items-start mb-4 position-relative px-md-3 px-0"
                        style="max-width: 450px; margin: 0 auto;">
                        <div class="position-absolute"
                            style="top: 17px; left: 15%; right: 15%; height: 3px; background: #e9ecef; z-index: 1;">
                            <div id="progress-line-fill" class="bg-primary"
                                style="height: 100%; width: 0%; transition: 0.4s ease-in-out;"></div>
                        </div>

                        <div class="text-center step-item step-1" style="z-index: 2; width: 33.33%;">
                            <div class="step-circle mx-auto d-flex align-items-center justify-content-center bg-white rounded-circle"
                                style="width: 36px; height: 36px; border: 2px solid #e9ecef; transition: 0.3s;">
                                <i class="fas fa-id-badge font-14 step-icon text-muted"></i>
                            </div>
                            <div class="mt-1 font-12 step-label text-muted">Identitas</div>
                        </div>

                        <div class="text-center step-item step-2" style="z-index: 2; width: 33.33%;">
                            <div class="step-circle mx-auto d-flex align-items-center justify-content-center bg-white rounded-circle"
                                style="width: 36px; height: 36px; border: 2px solid #e9ecef; transition: 0.3s;">
                                <i class="fas fa-address-book font-14 step-icon text-muted"></i>
                            </div>
                            <div class="mt-1 font-12 step-label text-muted">Personal</div>
                        </div>

                        <div class="text-center step-item step-3" style="z-index: 2; width: 33.33%;">
                            <div class="step-circle mx-auto d-flex align-items-center justify-content-center bg-white rounded-circle"
                                style="width: 36px; height: 36px; border: 2px solid #e9ecef; transition: 0.3s;">
                                <i class="fas fa-shield-alt font-14 step-icon text-muted"></i>
                            </div>
                            <div class="mt-1 font-12 step-label text-muted">Keamanan</div>
                        </div>
                    </div>

                    <form id="registerForm" action="{{ route('register') }}" method="POST">
                        @csrf

                        {{-- ============================== --}}
                        {{-- STEP 1: IDENTITAS UTAMA        --}}
                        {{-- ============================== --}}
                        <div class="form-step step-content-1">
                            <div class="row">
                                <div class="col-md-12 form-group mb-3">
                                    <label class="text-dark font-weight-medium font-14">NIP (Nomor Induk Pegawai) <span
                                            class="text-danger">*</span></label>
                                    <input class="form-control p-2 @error('nip') is-invalid @enderror" type="text"
                                        name="nip" value="{{ old('nip') }}" placeholder="Masukkan NIP" required>
                                    <small class="text-info mt-1 d-block"><i class="fas fa-info-circle mr-1"></i>NIP akan
                                        digunakan sebagai Username saat login.</small>
                                </div>

                                <div class="col-md-12 form-group mb-3">
                                    <label class="text-dark font-weight-medium font-14">Nama Lengkap (Tanpa Gelar) <span
                                            class="text-danger">*</span></label>
                                    <input class="form-control p-2 @error('nama') is-invalid @enderror" type="text"
                                        name="nama" value="{{ old('nama') }}" placeholder="Masukkan Nama Lengkap"
                                        required>
                                </div>

                                <div class="col-md-6 form-group mb-3">
                                    <label class="text-dark font-weight-medium font-14">Gelar Depan <span
                                            class="text-muted font-12 font-weight-normal">(Opsional)</span></label>
                                    <input class="form-control p-2" type="text" name="gelar_depan"
                                        value="{{ old('gelar_depan') }}" placeholder="Contoh: Dr., Prof., Ir.">
                                </div>

                                <div class="col-md-6 form-group mb-3">
                                    <label class="text-dark font-weight-medium font-14">Gelar Belakang <span
                                            class="text-danger">*</span></label>
                                    <input class="form-control p-2" type="text" name="gelar_belakang"
                                        value="{{ old('gelar_belakang') }}" placeholder="Contoh: S.T., S.P.">
                                </div>

                                {{-- Program Studi --}}
                                <div class="col-md-6 form-group mb-3">
                                    <label class="text-dark font-weight-medium font-14">Program Studi <span
                                            class="text-danger">*</span></label>
                                    <select class="form-control p-2 @error('id_prodi') is-invalid @enderror" name="id_prodi"
                                        required>
                                        <option value="" disabled selected>Pilih Program Studi</option>
                                        @foreach ($prodi as $p)
                                            <option value="{{ $p->id_prodi }}"
                                                {{ old('id_prodi') == $p->id_prodi ? 'selected' : '' }}>
                                                {{ $p->kode }} - {{ $p->nama_prodi }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- TAMBAHAN: Jabatan --}}
                                <div class="col-md-6 form-group mb-3">
                                    <label class="text-dark font-weight-medium font-14">Jabatan <span
                                            class="text-danger">*</span></label>
                                    <input class="form-control p-2 @error('jabatan') is-invalid @enderror" type="text"
                                        name="jabatan" value="{{ old('jabatan') }}" placeholder="Contoh: Dosen">
                                </div>
                            </div>
                        </div>

                        {{-- ============================== --}}
                        {{-- STEP 2: DATA PERSONAL & KONTAK --}}
                        {{-- ============================== --}}
                        <div class="form-step step-content-2 d-none">
                            <div class="row">
                                <div class="col-md-12 form-group mb-3">
                                    <label class="text-dark font-weight-medium font-14">Email Valid <span
                                            class="text-danger">*</span></label>
                                    <input class="form-control p-2 @error('email') is-invalid @enderror" type="email"
                                        name="email" value="{{ old('email') }}" placeholder="contoh@email.com"
                                        required>
                                </div>

                                <div class="col-md-6 form-group mb-3">
                                    <label class="text-dark font-weight-medium font-14">No. Telepon <span
                                            class="text-danger">*</span></label>
                                    <input class="form-control p-2 @error('telepon') is-invalid @enderror" type="text"
                                        name="telepon" value="{{ old('telepon') }}" placeholder="08xxxxxxxxxx" required>
                                </div>

                                <div class="col-md-6 form-group mb-3">
                                    <label class="text-dark font-weight-medium font-14">Jenis Kelamin <span
                                            class="text-danger">*</span></label>
                                    <select class="form-control p-2 @error('jenis_kelamin') is-invalid @enderror"
                                        name="jenis_kelamin" required>
                                        <option value="" disabled selected>Pilih Jenis Kelamin</option>
                                        <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>
                                            Laki-Laki</option>
                                        <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>
                                            Perempuan</option>
                                    </select>
                                </div>

                                <div class="col-md-6 form-group mb-3">
                                    <label class="text-dark font-weight-medium font-14">Tanggal Lahir <span
                                            class="text-danger">*</span></label>
                                    <div class="position-relative">
                                        <input
                                            class="form-control p-2 datepicker @error('tanggal_lahir') is-invalid @enderror"
                                            type="text" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}"
                                            placeholder="Pilih Tanggal" style="background-color: #fff;" required>
                                        <i class="far fa-calendar-alt position-absolute text-muted"
                                            style="right: 12px; top: 50%; transform: translateY(-50%); pointer-events: none;"></i>
                                    </div>
                                </div>

                                <div class="col-md-6 form-group mb-3">
                                    <label class="text-dark font-weight-medium font-14">Tgl Bergabung <span
                                            class="text-danger">*</span></label>
                                    <div class="position-relative">
                                        <input
                                            class="form-control p-2 datepicker @error('tanggal_bergabung') is-invalid @enderror"
                                            type="text" name="tanggal_bergabung"
                                            value="{{ old('tanggal_bergabung') }}" placeholder="Pilih Tanggal"
                                            style="background-color: #fff;" required>
                                        <i class="far fa-calendar-alt position-absolute text-muted"
                                            style="right: 12px; top: 50%; transform: translateY(-50%); pointer-events: none;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ============================== --}}
                        {{-- STEP 3: KEAMANAN AKUN          --}}
                        {{-- ============================== --}}
                        <div class="form-step step-content-3 d-none">
                            <div class="row">
                                <div class="col-md-12 form-group mb-3">
                                    <label class="text-dark font-weight-medium font-14">Password <span
                                            class="text-danger">*</span></label>
                                    <div class="position-relative">
                                        <input class="form-control p-2 @error('password') is-invalid @enderror"
                                            type="password" id="password" name="password"
                                            placeholder="Minimal 6 Karakter" required style="padding-right: 2.5rem;">
                                        <span class="toggle-password" data-target="#password"
                                            style="position: absolute; top: 50%; right: 1rem; transform: translateY(-50%); cursor: pointer; color: #6c757d;">
                                            <i class="fa fa-eye"></i>
                                        </span>
                                    </div>
                                </div>

                                <div class="col-md-12 form-group mb-3">
                                    <label class="text-dark font-weight-medium font-14">Konfirmasi Password <span
                                            class="text-danger">*</span></label>
                                    <div class="position-relative">
                                        <input class="form-control p-2" type="password" id="password_confirmation"
                                            name="password_confirmation" placeholder="Ulangi Password" required
                                            style="padding-right: 2.5rem;">
                                        <span class="toggle-password" data-target="#password_confirmation"
                                            style="position: absolute; top: 50%; right: 1rem; transform: translateY(-50%); cursor: pointer; color: #6c757d;">
                                            <i class="fa fa-eye"></i>
                                        </span>
                                    </div>
                                    <small id="passwordMatchMessage" class="d-none mt-3 font-weight-medium"></small>
                                </div>
                            </div>
                        </div>

                        {{-- Tombol Navigasi Wizard --}}
                        <div class="d-flex justify-content-between mt-3 pt-4 border-top">
                            <button type="button" class="btn btn-sm btn-secondary px-4 font-weight-medium d-none"
                                id="btnPrev">
                                <i class="fas fa-arrow-left mr-1"></i> Kembali
                            </button>
                            <button type="button" class="btn btn-sm btn-primary px-4 font-weight-medium ml-auto"
                                id="btnNext">
                                Lanjut <i class="fas fa-arrow-right ml-1"></i>
                            </button>
                            <button type="submit" class="btn btn-sm btn-success px-4 font-weight-medium d-none ml-auto"
                                id="btnSubmit">
                                <i class="fas fa-user-check mr-1"></i> Daftar Sekarang
                            </button>
                        </div>

                        <div class="col-lg-12 text-center mt-5 font-14">
                            Sudah punya akun? <a href="{{ route('login') }}" class="font-weight-bold text-primary">Login
                                di sini</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>

    <script>
        $(document).ready(function() {
            flatpickr(".datepicker", {
                dateFormat: "Y-m-d",
                locale: "id",
                allowInput: true
            });

            let currentStep = 1;
            const totalSteps = 3;

            function updateUI() {
                $('.form-step').addClass('d-none');
                $(`.step-content-${currentStep}`).removeClass('d-none');

                let progressWidth = ((currentStep - 1) / (totalSteps - 1)) * 100;
                $('#progress-line-fill').css('width', progressWidth + '%');

                $('.step-item .step-circle').removeClass('border-primary bg-primary shadow-sm').css('border-color',
                    '#e9ecef').addClass('bg-white');
                $('.step-item .step-icon').removeClass('text-white text-primary').addClass('text-muted');
                $('.step-item .step-label').removeClass('text-primary font-weight-bold').addClass('text-muted');

                for (let i = 1; i <= totalSteps; i++) {
                    if (i < currentStep) {
                        $(`.step-${i} .step-circle`).removeClass('bg-white').addClass('bg-primary').css(
                            'border-color', 'transparent');
                        $(`.step-${i} .step-icon`).removeClass('text-muted').addClass('text-white');
                        $(`.step-${i} .step-label`).removeClass('text-muted').addClass('text-primary');
                    } else if (i === currentStep) {
                        $(`.step-${i} .step-circle`).addClass('border-primary shadow-sm').css('border-color',
                            'transparent');
                        $(`.step-${i} .step-icon`).removeClass('text-muted').addClass('text-primary');
                        $(`.step-${i} .step-label`).removeClass('text-muted').addClass(
                            'text-primary font-weight-bold');
                    }
                }

                if (currentStep === 1) {
                    $('#btnPrev').addClass('d-none');
                } else {
                    $('#btnPrev').removeClass('d-none');
                }

                if (currentStep === totalSteps) {
                    $('#btnNext').addClass('d-none');
                    $('#btnSubmit').removeClass('d-none');
                } else {
                    $('#btnNext').removeClass('d-none');
                    $('#btnSubmit').addClass('d-none');
                }
            }

            function validateStep() {
                let isValid = true;
                $(`.step-content-${currentStep} input[required], .step-content-${currentStep} select[required]`)
                    .each(function() {
                        if (!this.checkValidity()) {
                            isValid = false;
                            this.reportValidity();
                            return false;
                        }
                    });

                if (currentStep === 3) {
                    let pass = $('#password').val();
                    let confirmPass = $('#password_confirmation').val();

                    if (pass.length > 0 && pass.length < 6) {
                        isValid = false;
                        $('#password')[0].setCustomValidity("Password minimal 6 karakter.");
                        $('#password')[0].reportValidity();
                    } else {
                        $('#password')[0].setCustomValidity("");
                    }

                    if (pass !== confirmPass && confirmPass !== '') {
                        isValid = false;
                        $('#passwordMatchMessage').removeClass('d-none text-success').addClass('text-danger').text(
                            '❌ Password dan konfirmasi tidak cocok!');
                    } else if (pass !== '' && pass === confirmPass) {
                        $('#passwordMatchMessage').removeClass('d-none text-danger').addClass('text-success').text(
                            '✅ Password cocok!');
                    }
                }

                return isValid;
            }

            $('#password_confirmation, #password').on('input', function() {
                if (currentStep === 3) validateStep();
            });

            $('#btnNext').click(function() {
                if (validateStep()) {
                    currentStep++;
                    updateUI();
                }
            });

            $('#btnPrev').click(function() {
                currentStep--;
                updateUI();
            });

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

            updateUI();
        });
    </script>
@endpush
