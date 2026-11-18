@props(['status'])
<span class="badge text-bg-{{ $status->badge() }}">{{ $status->label() }}</span>