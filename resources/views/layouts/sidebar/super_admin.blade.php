<ul class="nav nav-pills flex-column gap-1">
    <li class="nav-item">
        <a class="nav-link text-white {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" 
            href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
    </li>
    <li class="nav-item">
        <a class="nav-link text-white {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
           href="{{ route('admin.users.index') }}"><i class="bi bi-people me-2"></i>Kelola Pengguna</a>
    </li>

    <li class="text-uppercase small text-white-50 mt-3 px-3">Data Master</li>
    <li class="nav-item">
        <a class="nav-link text-white {{ request()->routeIs('admin.program-studi.*') ? 'active' : '' }}"
           href="{{ route('admin.program-studi.index') }}"><i class="bi bi-mortarboard me-2"></i></i>Program Studi</a>
    </li>
    <li class="nav-item">
        <a class="nav-link text-white {{ request()->routeIs('admin.jenis-surat.*') ? 'active' : '' }}"
           href="{{ route('admin.jenis-surat.index') }}"><i class="bi bi-file-earmark me-2"></i>Jenis Surat</a>
    </li>
    <li class="nav-item">
        <a class="nav-link text-white {{ request()->routeIs('admin.template.*') ? 'active' : '' }}"
           href="{{ route('admin.template.index') }}"><i class="bi bi-file-earmark-text me-2"></i>Template Surat</a>
    </li>

    <li class="text-uppercase small text-white-50 mt-3 px-3">Sistem</li>
    <li class="nav-item">
        <a class="nav-link text-white {{ request()->routeIs('admin.pengaturan.*') ? 'active' : '' }}"
           href="{{ route('admin.pengaturan.edit') }}"><i class="bi bi-gear me-2"></i>Pengaturan</a>
    </li>
    <li class="nav-item">
        <a class="nav-link text-white {{ request()->routeIs('admin.log.*') ? 'active' : '' }}"
           href="{{ route('admin.log.index') }}"><i class="bi bi-clock-history me-2"></i>Log Aktivitas</a>
    </li>
</ul>