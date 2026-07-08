@props([
    'viewRoute' => null,
    'editRoute' => null,
    'deleteAction' => null,
    'deleteRoute' => null,
    'printRoute' => null,
    'historyRoute' => null
])

<div class="dropdown font-sans-serif d-inline-block">
    <button class="btn btn-link text-600 dropdown-toggle btn-sm dropdown-caret-none transition-none p-0" 
            type="button" 
            data-bs-toggle="dropdown" 
            aria-haspopup="true" 
            aria-expanded="false">
        <span class="fas fa-ellipsis-h fs--1"></span>
    </button>
    <div class="dropdown-menu dropdown-menu-end border py-2">
        @if($viewRoute)
            <a class="dropdown-item text-800" href="{{ $viewRoute }}">
                <span class="fas fa-eye text-info me-2 fs--2"></span>View
            </a>
        @endif
        @if($editRoute)
            <a class="dropdown-item text-800" href="{{ $editRoute }}">
                <span class="fas fa-edit text-primary me-2 fs--2"></span>Edit
            </a>
        @endif
        @if($printRoute)
            <a class="dropdown-item text-800" href="{{ $printRoute }}" target="_blank">
                <span class="fas fa-print text-success me-2 fs--2"></span>Print
            </a>
        @endif
        @if($historyRoute)
            <a class="dropdown-item text-800" href="{{ $historyRoute }}">
                <span class="fas fa-history text-warning me-2 fs--2"></span>History
            </a>
        @endif
        {{ $slot ?? '' }}
        @if($deleteAction || $deleteRoute)
            <div class="dropdown-divider"></div>
            @if($deleteAction)
                <button type="button" class="dropdown-item text-danger" wire:click.prevent="{{ $deleteAction }}">
                    <span class="fas fa-trash-alt me-2 fs--2"></span>Delete
                </button>
            @elseif($deleteRoute)
                <form action="{{ $deleteRoute }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this item?');" style="display: block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="dropdown-item text-danger w-100 text-start border-0 bg-transparent">
                        <span class="fas fa-trash-alt me-2 fs--2"></span>Delete
                    </button>
                </form>
            @endif
        @endif
    </div>
</div>
