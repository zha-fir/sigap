<ul class="nav nav-pills flex-column gap-1">
    <li class="nav-item">
        <a class="nav-link text-white {{ request()->routeIs('operator.dashboard') ? 'active' : '' }}"
           href="{{ route('operator.dashboard') }}">
            <i class="bi bi-speedometer2 me-2"></i>Dashboard Antrean
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link text-white {{ request()->routeIs('operator.pengajuan.index') ? 'active' : '' }}"
           href="{{ route('operator.pengajuan.index') }}">
            <i class="bi bi-inboxes me-2"></i>Daftar Pengajuan
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link text-white {{ request()->routeIs('operator.verifikasi.index') || request()->routeIs('operator.pengajuan.show') ? 'active' : '' }}"
           href="{{ route('operator.verifikasi.index') }}">
            <i class="bi bi-clipboard-check me-2"></i>Detail & Verifikasi
        </a>
    </li>
</ul>
