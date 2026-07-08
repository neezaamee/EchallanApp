@props([
    'id' => null,
    'title' => '',
    'size' => 'modal-dialog-centered',
    'headerClass' => 'bg-light',
    'show' => false,
    'onClose' => null
])

<div class="modal fade @if($show) show d-block @endif" 
     @if($id) id="{{ $id }}" @endif 
     tabindex="-1" 
     role="dialog" 
     @if($show) aria-modal="true" style="background: rgba(0,0,0,0.42);" @else aria-hidden="true" @endif>
    <div class="modal-dialog {{ $size }}" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header {{ $headerClass }} py-2 px-3">
                <h5 class="modal-title @if(str_contains($headerClass, 'bg-') && !str_contains($headerClass, 'bg-light')) text-white @else text-dark @endif fw-bold">
                    {{ $title }}
                </h5>
                @if($onClose)
                    <button type="button" class="btn-close @if(str_contains($headerClass, 'bg-') && !str_contains($headerClass, 'bg-light')) btn-close-white @endif" wire:click="{{ $onClose }}" aria-label="Close"></button>
                @else
                    <button type="button" class="btn-close @if(str_contains($headerClass, 'bg-') && !str_contains($headerClass, 'bg-light')) btn-close-white @endif" data-bs-dismiss="modal" aria-label="Close"></button>
                @endif
            </div>
            <div class="modal-body p-3">
                {{ $slot }}
            </div>
            @if(isset($footer))
                <div class="modal-footer bg-light py-2">
                    {{ $footer }}
                </div>
            @endif
        </div>
    </div>
</div>
