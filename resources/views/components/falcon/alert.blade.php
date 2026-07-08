@props([
    'variant' => 'success',
    'dismissible' => true
])

@php
    $alertClass = 'alert-' . $variant;
    $iconClass = match($variant) {
        'success' => 'fas fa-check-circle',
        'danger' => 'fas fa-exclamation-circle',
        'warning' => 'fas fa-exclamation-triangle',
        'info' => 'fas fa-info-circle',
        default => 'fas fa-info-circle'
    };
@endphp

<div {{ $attributes->merge(['class' => 'alert ' . $alertClass . ' border-2 d-flex align-items-center p-3 mb-3' . ($dismissible ? ' alert-dismissible fade show' : '')]) }} role="alert">
    <div class="bg-{{ $variant }} me-3 icon-item d-flex align-items-center justify-content-center" style="width: 2.5rem; height: 2.5rem; border-radius: 50%;">
        <span class="{{ $iconClass }} text-white fs-1"></span>
    </div>
    <div class="flex-1">
        {{ $slot }}
    </div>
    @if($dismissible)
        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
    @endif
</div>
