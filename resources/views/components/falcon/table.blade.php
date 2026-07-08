@props([
    'responsive' => true,
    'stickyHeader' => false,
])

<div class="{{ $responsive ? 'table-responsive scrollbar' : '' }}">
    <table {{ $attributes->merge(['class' => 'table table-sm table-striped table-hover align-middle mb-0 fs--1' . ($stickyHeader ? ' table-sticky-header' : '')]) }}>
        {{ $slot }}
    </table>
</div>
