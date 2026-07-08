@props([
    'items' => []
])

<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0 fs--1">
        @foreach($items as $label => $url)
            @if(empty($url) || $loop->last)
                <li class="breadcrumb-item active" aria-current="page">{{ $label }}</li>
            @else
                <li class="breadcrumb-item"><a href="{{ $url }}">{{ $label }}</a></li>
            @endif
        @endforeach
    </ol>
</nav>
