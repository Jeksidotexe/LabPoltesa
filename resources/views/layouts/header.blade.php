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
                    <span class="logo-text mt-3 ml-2 font-weight-bold text-dark font-12">
                        LabAgrobisnisPOLTESA
                    </span>
                </a>
            </div>
            <a class="topbartoggler d-block d-md-none waves-effect waves-light" href="javascript:void(0)"
                data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                aria-expanded="false" aria-label="Toggle navigation"><i class="ti-more"></i></a>
        </div>

        <div class="navbar-collapse collapse" id="navbarSupportedContent">
            <ul class="navbar-nav float-left mr-auto ml-3 pl-1">
                {{-- Area Notifikasi (Biarkan seperti semula) --}}
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle pl-md-3 position-relative" href="javascript:void(0)"
                        id="bell" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span><i data-feather="bell" class="svg-icon"></i></span>
                        <span class="badge badge-primary notify-no rounded-circle">5</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-left mailbox animated bounceInDown">
                        <ul class="list-style-none">
                            <li>
                                <div class="message-center notifications position-relative">
                                    <a href="javascript:void(0)"
                                        class="message-item d-flex align-items-center border-bottom px-3 py-2">
                                        <div class="btn btn-danger rounded-circle btn-circle"><i data-feather="airplay"
                                                class="text-white"></i></div>
                                        <div class="w-75 d-inline-block v-middle pl-2">
                                            <h6 class="message-title mb-0 mt-1">Luanch Admin</h6>
                                            <span class="font-12 text-nowrap d-block text-muted">Just see the my new
                                                admin!</span>
                                            <span class="font-12 text-nowrap d-block text-muted">9:30 AM</span>
                                        </div>
                                    </a>
                                    {{-- ... Notifikasi lainnya ... --}}
                                </div>
                            </li>
                            <li>
                                <a class="nav-link pt-3 text-center text-dark" href="javascript:void(0);">
                                    <strong>Check all notifications</strong>
                                    <i class="fa fa-angle-right"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>

            <ul class="navbar-nav float-right">
                <li class="nav-item dropdown">
                    @php
                        $user = auth()->user();
                        $namaTampil = $user->nama ?? $user->role;
                        $hasFoto = $user->foto;
                    @endphp

                    <a class="nav-link dropdown-toggle" href="javascript:void(0)" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">

                        {{-- Logika Tampilan Foto vs Inisial --}}
                        @if ($hasFoto)
                            <img src="{{ Storage::url($user->foto) }}" alt="user" class="rounded-circle border"
                                width="40" height="40" style="object-fit: cover;">
                        @else
                            <div class="bg-primary text-white rounded-circle d-inline-flex justify-content-center align-items-center shadow-sm border"
                                style="width: 40px; height: 40px; font-size: 18px; border-width: 2px !important;">
                                {{ strtoupper(substr($user->nama, 0, 1)) }}
                            </div>
                        @endif

                        <span class="ml-2 d-none d-lg-inline-block"><span class="text-dark">{{ $namaTampil }}</span>
                            <i data-feather="chevron-down" class="svg-icon"></i></span>
                    </a>

                    <ddiv class="dropdown-menu dropdown-menu-right user-dd animated flipInY">
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
                    </ddiv>
                </li>
            </ul>
        </div>
    </nav>
</header>
