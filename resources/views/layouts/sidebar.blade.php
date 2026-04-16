<aside class="left-sidebar" data-sidebarbg="skin6">
    <div class="scroll-sidebar" data-sidebarbg="skin6">
        <nav class="sidebar-nav">
            <ul id="sidebarnav">

                {{-- MENU DASHBOARD (BERLAKU UNTUK SEMUA) --}}
                <li class="sidebar-item {{ request()->routeIs('dashboard') ? 'selected' : '' }}">
                    <a class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                        href="{{ route('dashboard') }}" aria-expanded="false">
                        <i data-feather="home" class="feather-icon"></i><span class="hide-menu">Dashboard</span>
                    </a>
                </li>

                {{-- Menu Khusus Super Admin --}}
                @if (auth()->user()->role == 'Super Admin')
                    <li class="list-divider"></li>
                    <li class="nav-small-cap"><span class="hide-menu">Master Data</span></li>
                    <li class="sidebar-item {{ request()->routeIs('pengguna.*') ? 'selected' : '' }}">
                        <a class="sidebar-link {{ request()->routeIs('pengguna.*') ? 'active' : '' }}"
                            href="{{ route('pengguna.index') }}">
                            <i data-feather="users" class="feather-icon"></i><span class="hide-menu">Pengguna</span>
                        </a>
                    </li>
                    <li class="sidebar-item {{ request()->routeIs('prodi.*') ? 'selected' : '' }}">
                        <a class="sidebar-link {{ request()->routeIs('prodi.*') ? 'active' : '' }}"
                            href="{{ route('prodi.index') }}">
                            <i data-feather="layers" class="feather-icon"></i><span class="hide-menu">Program
                                Studi</span>
                        </a>
                    </li>
                    <li class="sidebar-item {{ request()->routeIs('makul.*') ? 'selected' : '' }}">
                        <a class="sidebar-link {{ request()->routeIs('makul.*') ? 'active' : '' }}"
                            href="{{ route('makul.index') }}">
                            <i data-feather="book-open" class="feather-icon"></i><span class="hide-menu">Mata
                                Kuliah</span>
                        </a>
                    </li>
                    <li class="sidebar-item {{ request()->routeIs('lab.*') ? 'selected' : '' }}">
                        <a class="sidebar-link {{ request()->routeIs('lab.*') ? 'active' : '' }}"
                            href="{{ route('lab.index') }}">
                            <i data-feather="monitor" class="feather-icon"></i><span
                                class="hide-menu">Laboratorium</span>
                        </a>
                    </li>
                    <li class="sidebar-item {{ request()->routeIs('kategori.*') ? 'selected' : '' }}">
                        <a class="sidebar-link {{ request()->routeIs('kategori.*') ? 'active' : '' }}"
                            href="{{ route('kategori.index') }}">
                            <i data-feather="tag" class="feather-icon"></i><span class="hide-menu">Kategori</span>
                        </a>
                    </li>
                @endif

                {{-- Menu Khusus Admin --}}
                @if (auth()->user()->role == 'Admin')
                    <li class="list-divider"></li>
                    <li class="nav-small-cap"><span class="hide-menu">Inventaris</span></li>
                    <li class="sidebar-item {{ request()->routeIs('alat.*') ? 'selected' : '' }}">
                        <a class="sidebar-link {{ request()->routeIs('alat.*') ? 'active' : '' }}"
                            href="{{ route('alat.index') }}">
                            <i data-feather="box" class="feather-icon"></i><span class="hide-menu">Data Alat</span>
                        </a>
                    </li>
                @endif

                {{-- Menu Khusus Dosen --}}
                @if (auth()->user()->role == 'Dosen')
                    <li class="list-divider"></li>
                    <li class="nav-small-cap"><span class="hide-menu">Praktikum</span></li>
                    <li class="sidebar-item {{ request()->routeIs('pengajuan.create') ? 'selected' : '' }}">
                        <a class="sidebar-link {{ request()->routeIs('pengajuan.create') ? 'active' : '' }}"
                            href="{{ route('pengajuan.create') }}" aria-expanded="false">
                            <i data-feather="plus-square" class="feather-icon"></i>
                            <span class="hide-menu">Buat Pengajuan</span>
                        </a>
                    </li>
                    <li
                        class="sidebar-item {{ request()->routeIs(['pengajuan.index', 'pengajuan.show']) ? 'selected' : '' }}">
                        <a class="sidebar-link {{ request()->routeIs(['pengajuan.index', 'pengajuan.show']) ? 'active' : '' }}"
                            href="{{ route('pengajuan.index') }}" aria-expanded="false">
                            <i data-feather="clock" class="feather-icon"></i>
                            <span class="hide-menu">Riwayat Pengajuan</span>
                        </a>
                    </li>
                @endif

                {{-- ======================================================== --}}
                {{-- MENU KEGIATAN (GLOBAL UNTUK SEMUA DI BAWAH MENU KHUSUS)  --}}
                {{-- ======================================================== --}}
                <li class="list-divider"></li>
                <li class="nav-small-cap"><span class="hide-menu">Kegiatan</span></li>

                {{-- Jadwal Praktikum (Dapat diakses semua pengguna) --}}
                <li class="sidebar-item {{ request()->routeIs('jadwal.*') ? 'selected' : '' }}">
                    <a class="sidebar-link {{ request()->routeIs('jadwal.*') ? 'active' : '' }}"
                        href="{{ route('jadwal.index') }}" aria-expanded="false">
                        <i data-feather="calendar" class="feather-icon"></i><span class="hide-menu">Jadwal
                            Praktikum</span>
                    </a>
                </li>

                {{-- Rekap Kegiatan (Hanya dapat diakses oleh selain Dosen) --}}
                @if (in_array(auth()->user()->role, ['Super Admin', 'Admin', 'Kaprodi', 'Kajur']))
                    <li class="sidebar-item {{ request()->routeIs('rekap.*') ? 'selected' : '' }}">
                        <a class="sidebar-link {{ request()->routeIs('rekap.*') ? 'active' : '' }}"
                            href="{{ route('rekap.index') }}" aria-expanded="false">
                            <i data-feather="file-text" class="feather-icon"></i><span class="hide-menu">Rekap
                                Kegiatan</span>
                        </a>
                    </li>
                @endif

                {{-- Menu Profil --}}
                <li class="list-divider"></li>
                <li class="nav-small-cap"><span class="hide-menu">Pengaturan Akun</span></li>
                <li class="sidebar-item {{ request()->routeIs('profil.*') ? 'selected' : '' }}">
                    <a class="sidebar-link {{ request()->routeIs('profil.*') ? 'active' : '' }}"
                        href="{{ route('profil.show') }}" aria-expanded="false">
                        <i data-feather="user" class="feather-icon"></i><span class="hide-menu">Profil Saya</span>
                    </a>
                </li>

                {{-- Menu Logout --}}
                <li class="list-divider"></li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="#"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i data-feather="log-out" class="feather-icon text-danger"></i><span
                            class="hide-menu text-danger font-weight-medium">Logout</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
    <form action="{{ route('logout') }}" method="post" id="logout-form" style="display: none;">
        @csrf
    </form>
</aside>
