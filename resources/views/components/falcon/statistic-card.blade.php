@props([
    'value' => '0',
    'label' => '',
    'icon' => null,
    'color' => 'primary',
    'trend' => null,
    'trendColor' => 'success'
])

<div {{ $attributes->merge(['class' => 'card h-100 border shadow-none']) }}>
    <div class="card-body p-3">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1 text-dark fw-bold">{{ $value }}</h4>
                <h6 class="text-600 mb-0 fs--1 fw-semi-bold">{{ $label }}</h6>
                @if($trend)
                    <div class="mt-2">
                        <span class="badge badge-soft-{{ $trendColor }} fs--2">{{ $trend }}</span>
                    </div>
                @endif
            </div>
            @if($icon)
                <div class="fs-2 text-{{ $color }} opacity-75">
                    <span class="{{ $icon }}"></span>
                </div>
            @endif
        </div>
    </div>
</div>
