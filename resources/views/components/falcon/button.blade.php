@props([
    'variant' => 'primary',
    'size' => 'sm',
    'icon' => null,
    'type' => 'button',
    'href' => null,
    'loadingTarget' => null
])

@php
    $variantClass = match($variant) {
        'primary' => 'btn-primary',
        'success' => 'btn-success',
        'danger' => 'btn-danger',
        'warning' => 'btn-warning',
        'info' => 'btn-info',
        'secondary' => 'btn-secondary',
        'falcon-default' => 'btn-falcon-default',
        'falcon-primary' => 'btn-falcon-primary',
        'falcon-success' => 'btn-falcon-success',
        'falcon-danger' => 'btn-falcon-danger',
        'falcon-warning' => 'btn-falcon-warning',
        'link' => 'btn-link',
        default => 'btn-' . $variant
    };
    
    $sizeClass = $size ? 'btn-' . $size : '';
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => 'btn ' . $variantClass . ' ' . $sizeClass]) }}>
        @if($icon)
            <span class="{{ $icon }}"></span>
        @endif
        <span class="{{ $icon ? 'ms-1' : '' }}">{{ $slot }}</span>
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => 'btn ' . $variantClass . ' ' . $sizeClass]) }}
        @if($loadingTarget) wire:loading.attr="disabled" wire:target="{{ $loadingTarget }}" @endif>
        
        @if($loadingTarget)
            <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true" 
                wire:loading wire:target="{{ $loadingTarget }}"></span>
        @endif
        
        @if($icon)
            <span class="{{ $icon }}" @if($loadingTarget) wire:loading.remove wire:target="{{ $loadingTarget }}" @endif></span>
        @endif
        
        <span class="{{ $icon ? 'ms-1' : '' }}">{{ $slot }}</span>
    </button>
@endif
