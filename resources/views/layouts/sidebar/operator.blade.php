<ul class="nav nav-pills flex-column gap-1">

    <li class="nav-item">
        <a href="{{ route('operator.dashboard') }}"
           class="nav-link text-white {{ request()->routeIs('operator.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2 me-2"></i>
            Dashboard
        </a>
    </li>

    <li class="nav-item">
        <a href="{{ route('operator.pengajuan.index') }}"
           class="nav-link text-white {{ request()->routeIs('operator.pengajuan.index') ? 'active' : '' }}">
            <i class="bi bi-inboxes me-2"></i>
            Antrean Pengajuan
        </a>
    </li>

    <li class="nav-item">
        <a href="{{ route('operator.verifikasi.index') }}"
           class="nav-link text-white {{ request()->routeIs('operator.verifikasi.*') || request()->routeIs('operator.pengajuan.show') ? 'active' : '' }}">
            <i class="bi bi-clipboard-check me-2"></i>
            Detail &amp; Verifikasi
        </a>
    </li>

    <li class="text-uppercase small text-white-50 mt-3 px-3">
        Pengelolaan Surat
    </li>

    <li class="nav-item">
        <a href="{{ route('operator.surat.buat') }}"
           class="nav-link text-white {{ request()->routeIs('operator.surat.buat') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-plus me-2"></i>
            Buat Surat
        </a>
    </li>

    <li class="nav-item">
        <a href="{{ route('operator.surat.index') }}"
           class="nav-link text-white {{ request()->routeIs('operator.surat.index') || request()->routeIs('operator.surat.cetak') || request()->routeIs('operator.surat.siap-diambil') || request()->routeIs('operator.surat.selesai') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-text me-2"></i>
            Daftar Surat
        </a>
    </li>

</ul>
