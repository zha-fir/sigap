@php
    $menus = [
        ['Dashboard Antrean', 'speedometer2', 'operator.dashboard'],
        ['Daftar Pengajuan', 'inboxes', 'operator.pengajuan.index'],
        ['Detail & Verifikasi', 'clipboard-check', 'operator.verifikasi.index'],
        ['Editor & Unggah Surat', 'file-earmark-text', null],
        ['Kirim ke Kajur', 'send', null],
        ['Serah Terima', 'check2-square', null],
        ['Arsip Surat Keluar', 'archive', null],
        ['Laporan', 'bar-chart', null]
    ];
@endphp

<ul class="nav nav-pills flex-column gap-2">

    @foreach($menus as $menu)
        <li class="nav-item">

            @php
                $aktif = $menu[2] && (
                    request()->routeIs($menu[2]) ||
                    ($menu[2] == 'operator.verifikasi.index' &&
                    request()->routeIs('operator.pengajuan.show'))
                );
            @endphp

            @if($menu[2])

                <a href="{{ route($menu[2]) }}"
                   class="nav-link d-flex align-items-start gap-2 py-2 px-2 {{ $aktif ? 'active bg-primary text-white' : 'text-white' }}">

                    <i class="bi bi-{{ $menu[1] }}"></i>
                    <span>{{ $menu[0] }}</span>

                </a>

            @else

                <span class="nav-link d-flex align-items-start gap-2 py-2 px-2 text-white">

                    <i class="bi bi-{{ $menu[1] }}"></i>
                    <span>{{ $menu[0] }}</span>

                </span>

            @endif

        </li>
    @endforeach

</ul>
