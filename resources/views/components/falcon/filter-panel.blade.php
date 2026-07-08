<div {{ $attributes->merge(['class' => 'p-3 border-bottom bg-light']) }}>
    <div class="row justify-content-between align-items-center g-2">
        {{ $slot }}
    </div>
</div>
