@props([
    'title' => null,
    'description' => null,
    'headerClass' => 'bg-light',
    'bodyClass' => 'p-0' // default to p-0 so tables and forms can choose padding
])

<div {{ $attributes->merge(['class' => 'card mb-3']) }}>
    @if($title || isset($headerActions))
        <div class="card-header {{ $headerClass }} d-flex justify-content-between align-items-center py-2">
            <div>
                @if($title)
                    <h5 class="mb-0 text-dark fw-bold">{{ $title }}</h5>
                @endif
                @if($description)
                    <p class="mb-0 fs--2 text-muted mt-1">{{ $description }}</p>
                @endif
            </div>
            @if(isset($headerActions))
                <div class="ms-auto">
                    {{ $headerActions }}
                </div>
            @endif
        </div>
    @endif
    <div class="card-body {{ $bodyClass }}">
        {{ $slot }}
    </div>
    @if(isset($footer))
        <div class="card-footer bg-light py-2">
            {{ $footer }}
        </div>
    @endif
</div>
