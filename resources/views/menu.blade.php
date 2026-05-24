<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ url('/dashboard') }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                <span class="text-primary">
                    <img src="{{ asset('/images/logo.png') }}" width="50px">
                </span>
            </span>
            <span class="app-brand-text demo menu-text fw-bold ms-2">AppExt IAIN Kendari</span>
        </a>
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="bx bx-chevron-left d-block d-xl-none align-middle"></i>
        </a>
    </div>
    <div class="menu-divider mt-0"></div>
    <div class="menu-inner-shadow"></div>
    <ul class="menu-inner py-1">
        <!-- Dashboards -->
        <li class="menu-item active open">
            <a href="{{ url('/dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-smile"></i>
                <div class="text-truncate" data-i18n="Boxicons">Dashboards</div>
            </a>
        </li>

        <!-- Apps & Pages -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Apps &amp; Pages</span>
        </li>

        <li class="menu-item d-none" data-role="Dosen,Pengelola">
            <a href="{{ route('pegawai.profil') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-id-card"></i>
                <div class="text-truncate" data-i18n="Boxicons">Profil Pegawai</div>
            </a>
        </li>

        <li class="menu-item d-none" data-role="Mahasiswa">
            <a href="{{ route('mahasiswa.profil') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-book-reader"></i>
                <div class="text-truncate" data-i18n="Boxicons">Profil Mahasiswa</div>
            </a>
        </li>

        <li class="menu-item d-none" data-role="Admin">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-user"></i>
                <div class="text-truncate" data-i18n="Layouts">Akun Web</div>
            </a>

            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="{{ route('user') }}" class="menu-link">
                        <div class="text-truncate" data-i18n="Without menu">Pengguna</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('role') }}" class="menu-link">
                        <div class="text-truncate" data-i18n="Without navbar">Role</div>
                    </a>
                </li>
            </ul>
        </li>


        <!-- Components -->
        <li class="menu-header small text-uppercase"><span class="menu-header-text">Akun</span></li>
        <!-- Cards -->
        <li class="menu-item">
            <a href="{{ route('identitas') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bxs-user-account"></i>
                <div class="text-truncate" data-i18n="Boxicons">Profil Akun</div>
            </a>
        </li>

        <li class="menu-item d-none" id="menu-ganti-role">
            <a href="javascript:;" class="menu-link" id="menu-ganti-role-link">
                <i class="menu-icon tf-icons bx bx-shield-quarter"></i>
                <div class="text-truncate" data-i18n="Boxicons">Ganti Akses</div>
            </a>
        </li>

    </ul>
</aside>
