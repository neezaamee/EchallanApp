<footer class="footer">
    <div class="row g-0 justify-content-between fs-10 mt-4 mb-3">
        <div class="col-12 col-sm-auto text-center">
            <p class="mb-0 text-600">Developed by <span class="d-none d-sm-inline-block">| </span><br class="d-sm-none" />
                {{ now()->format('Y') }} &copy; <a href="https://elaftech.com">Elaf Tech</a></p>
        </div>
        <div class="col-12 col-sm-auto text-center">
            @if (app()->isLocal())
                <p class="mb-0 text-600">Your Local IP Address is: {{ request()->getClientIp() }} |
                    {{ env('APP_VERSION') }}
                </p>
            @else
                <p class="mb-0 text-600">Your IP Address is: {{ request()->getClientIp() }} | {{ env('APP_VERSION') }}
                </p>
            @endif
        </div>
    </div>
</footer>
