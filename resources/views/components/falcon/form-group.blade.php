@props([
    'label' => null,
    'name' => null,
    'required' => false,
    'helpText' => null
])

<div {{ $attributes->merge(['class' => 'mb-3']) }}>
    @if($label)
        <label class="form-label text-dark fw-semi-bold fs--1" @if($name) for="{{ $name }}" @endif>
            {{ $label }}
            @if($required)
                <span class="required-indicator">*</span>
            @endif
        </label>
    @endif
    
    {{ $slot }}
    
    @if($helpText)
        <div class="form-text fs--2 text-muted mt-1">{{ $helpText }}</div>
    @endif
    
    @if($name)
        @error($name)
            <span class="text-danger small fs--2 mt-1 d-block fw-bold">{{ $message }}</span>
        @enderror
    @endif
</div>
