@props([
    'placeholder' => 'Search...'
])

<div class="position-relative" style="max-width: 250px;">
    <input {{ $attributes->merge(['class' => 'form-control form-control-sm shadow-none search']) }}
        type="search" 
        placeholder="{{ $placeholder }}" 
        aria-label="search"
    />
    <span class="fas fa-search position-absolute top-50 end-0 translate-middle-y me-2 text-400"></span>
</div>
