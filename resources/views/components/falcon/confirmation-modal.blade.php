@props([
    'show' => false,
    'title' => 'Confirm Deletion',
    'message' => 'Are you sure you want to permanently delete this item? This action cannot be undone.',
    'onCancel' => null,
    'onConfirm' => null,
    'confirmText' => 'Delete Permanently',
    'cancelText' => 'Cancel',
    'variant' => 'danger'
])

@php
    $headerBg = 'bg-' . $variant;
    $btnClass = 'btn-' . $variant;
    $icon = match($variant) {
        'danger' => 'fas fa-exclamation-triangle',
        'warning' => 'fas fa-exclamation-circle',
        default => 'fas fa-info-circle'
    };
@endphp

@if($show)
    <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.42);" role="dialog" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header {{ $headerBg }} py-2 px-3">
                    <h5 class="modal-title text-white">
                        <span class="{{ $icon }} me-2"></span>{{ $title }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" wire:click="{{ $onCancel }}"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="mb-0 text-800">{{ $message }}</p>
                </div>
                <div class="modal-footer bg-light py-2">
                    <button class="btn btn-falcon-default btn-sm" wire:click="{{ $onCancel }}">{{ $cancelText }}</button>
                    <button class="btn {{ $btnClass }} btn-sm" wire:click="{{ $onConfirm }}">{{ $confirmText }}</button>
                </div>
            </div>
        </div>
    </div>
@endif
