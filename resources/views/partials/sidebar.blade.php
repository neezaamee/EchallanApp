<nav class="navbar navbar-light navbar-vertical navbar-expand-xl">
    <script>
        var navbarStyle = localStorage.getItem("navbarStyle");
        if (navbarStyle && navbarStyle !== 'transparent') {
            document.querySelector('.navbar-vertical').classList.add(`navbar-${navbarStyle}`);
        }
    </script>

    <div class="d-flex align-items-center">
        <div class="toggle-icon-wrapper">
            <button class="btn navbar-toggler-humburger-icon navbar-vertical-toggle" data-bs-toggle="tooltip" data-bs-placement="left" title="Toggle Navigation">
                <span class="navbar-toggle-icon"><span class="toggle-line"></span></span>
            </button>
        </div>

        <a class="navbar-brand" href="{{ route('home') }}">
            <div class="d-flex align-items-center py-3">
                <img class="me-2" src="{{ asset('assets/img/icons/spot-illustrations/falcon.png') }}" alt="" width="40" />
                <span class="font-sans-serif text-primary">falcon</span>
            </div>
        </a>
    </div>

    <div class="collapse navbar-collapse" id="navbarVerticalCollapse">
        <div class="navbar-vertical-content scrollbar">
            @include('partials.sidebar-menu')
        </div>
    </div>
</nav>
