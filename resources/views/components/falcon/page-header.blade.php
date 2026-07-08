@props([
    'title' => '',
    'breadcrumbs' => [] // Array: ['Home' => route('dashboard'), 'Sectors' => '']
])

<div class="row flex-between-center mb-4">
    <div class="col-auto">
        <h4 class="mb-1 text-dark fw-bold">{{ $title }}</h4>
        @if(!empty($breadcrumbs))
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 fs--1">
                    @foreach($breadcrumbs as $label => $url)
                        @if(empty($url) || $loop->last)
                            <li class="breadcrumb-item active" aria-current="page">{{ $label }}</li>
                        @else
                            <li class="breadcrumb-item"><a href="{{ $url }}">{{ $label }}</a></li>
                        @endif
                    @endforeach
                </ol>
            </nav>
        @endif
    </div>
    @if(isset($actions))
        <div class="col-auto ms-auto">
            <div class="d-flex align-items-center gap-2">
                {{ $actions }}
            </div>
        </div>
    @endif
</div>
