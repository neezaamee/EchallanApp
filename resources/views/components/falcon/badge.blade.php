@props([
    'variant' => 'secondary'
])

@php
    $badgeClass = match($variant) {
        'success' => 'badge-soft-success',
        'warning' => 'badge-soft-warning',
        'danger' => 'badge-soft-danger',
        'info' => 'badge-soft-info',
        'primary' => 'badge-soft-primary',
        'secondary' => 'badge-soft-secondary',
        default => 'badge-soft-' . $variant
    };
@endphp

<span {{ $attributes->merge(['class' => 'badge ' . $badgeClass . ' text-dark fs--2']) }}>
    {{ $slot }}
</span>
