<header class="topbar" data-navbarbg="skin6">
    <nav class="navbar top-navbar navbar-expand-md">
        <div class="navbar-header" data-logobg="skin6">
            <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)"><i
                    class="ti-menu ti-close"></i></a>
            <div class="navbar-brand">
                <a href="{{ route('dashboard') }}" class="d-flex align-items-center">
                    <b class="logo-icon">
                        <img src="{{ asset('poltesa.png') }}" height="60" alt="logo" class="dark-logo" />
                        <img src="{{ asset('poltesa.png') }}" height="60" alt="logo" class="light-logo" />
                    </b>
                    <span class="logo-text mt-3 font-weight-bold text-dark font-14">
                        LabAgrobisnisPoltesa
                    </span>
                </a>
            </div>
            <a class="topbartoggler d-block d-md-none waves-effect waves-light" href="javascript:void(0)"
                data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                aria-expanded="false" aria-label="Toggle navigation"><i class="ti-more"></i></a>
        </div>

        <div class="navbar-collapse collapse" id="navbarSupportedContent">
            <ul class="navbar-nav float-left mr-auto ml-3 pl-1">
                {{-- Area Notifikasi --}}
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle pl-md-3 position-relative" href="javascript:void(0)"
                        id="bell" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span><i data-feather="bell" class="svg-icon"></i></span>
                        {{-- Badge angka hanya muncul jika ada notifikasi --}}
                        @if (isset($globalNotifCount) && $globalNotifCount > 0)
                            <span class="badge badge-primary notify-no rounded-circle">{{ $globalNotifCount }}</span>
                        @endif
                    </a>
                    <div class="dropdown-menu dropdown-menu-left mailbox animated bounceInDown"
                        style="min-width: 280px;">
                        <ul class="list-style-none">
                            <li>
                                <div class="message-center notifications position-relative"
                                    style="max-height: 300px; overflow-y: auto;">

                                    @if (isset($globalNotifs) && $globalNotifCount > 0)
                                        {{-- Looping Data Notifikasi --}}
                                        @foreach ($globalNotifs as $notif)
                                            <a href="{{ $notif['link'] }}"
                                                class="message-item d-flex align-items-center border-bottom px-3 py-2">
                                                {{-- Background icon diubah menjadi light agar icon berwarna terlihat jelas --}}
                                                <div
                                                    class="btn btn-light border rounded-circle btn-circle d-flex align-items-center justify-content-center">
                                                    <i class="{{ $notif['icon'] }} font-16"></i>
                                                </div>
                                                <div class="w-75 d-inline-block v-middle pl-2">
                                                    <h6 class="message-title mb-0 mt-1" style="font-size: 13px;">
                                                        {{ $notif['text'] }}</h6>
                                                    <span
                                                        class="font-12 text-nowrap d-block text-muted mt-1">{{ $notif['time'] }}</span>
                                                </div>
                                            </a>
                                        @endforeach
                                    @else
                                        {{-- Jika tidak ada notifikasi --}}
                                        <div class="text-center py-4 text-muted">
                                            <i class="fas fa-bell-slash mb-2 fa-2x opacity-50"></i>
                                            <p class="mb-0 font-13">Tidak ada notifikasi baru.</p>
                                        </div>
                                    @endif

                                </div>
                            </li>

                            @if (isset($globalNotifCount) && $globalNotifCount > 0)
                                <li>
                                    <a class="nav-link pt-3 text-center text-dark" href="javascript:void(0);">
                                        <strong>Tutup</strong>
                                        <i class="fa fa-angle-right"></i>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </li>
            </ul>

            <ul class="navbar-nav float-right">
                <li class="nav-item dropdown">
                    @php
                        $user = auth()->user();
                        $namaTampil = $user->nama ?? $user->role;

                        // LOGIKA FOTO PROFIL & FALLBACK UI-AVATARS
                        if ($user->foto && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->foto)) {
                            $urlFoto = \Illuminate\Support\Facades\Storage::url($user->foto);
                        } else {
                            $namaFallback = $user->nama ?? $user->role;
                            $urlFoto =
                                'https://ui-avatars.com/api/?name=' .
                                urlencode($namaFallback) .
                                '&background=e9ecef&color=343a40&size=140';
                        }
                    @endphp

                    <a class="nav-link dropdown-toggle" href="javascript:void(0)" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">

                        {{-- Tampilan Foto Profil --}}
                        <img src="{{ $urlFoto }}" alt="user" class="rounded-circle border" width="40"
                            height="40" style="object-fit: cover;">

                        <span class="ml-2 d-none d-lg-inline-block"><span class="text-dark">{{ $namaTampil }}</span>
                            <i data-feather="chevron-down" class="svg-icon"></i></span>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right user-dd animated flipInY">
                        <a class="dropdown-item" href="{{ route('profil.show') }}"><i data-feather="user"
                                class="svg-icon mr-2 ml-1"></i>
                            Profil Saya</a>
                        <a class="dropdown-item" href="{{ route('profil.edit') }}"><i data-feather="settings"
                                class="svg-icon mr-2 ml-1"></i>
                            Edit Profil</a>
                        <div class="dropdown-divider"></div>
                        <div class="pl-4 p-3">
                            <a href="javascript:void(0)"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                class="btn btn-sm btn-info">
                                <i data-feather="log-out" style="width:16px; height:16px;" class="mr-1"></i>
                                <span class="hide-menu">Logout</span>
                            </a>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
</header>
