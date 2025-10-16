<nav class="navbar navbar-light navbar-glass navbar-top navbar-expand">
    <button class="btn navbar-toggler-humburger-icon navbar-toggler me-1 me-sm-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarVerticalCollapse" aria-controls="navbarVerticalCollapse" aria-expanded="false" aria-label="Toggle Navigation">
        <span class="navbar-toggle-icon"><span class="toggle-line"></span></span>
    </button>

    <a class="navbar-brand me-1 me-sm-3" href="{{ route('home') }}">
        <div class="d-flex align-items-center">
            <img class="me-2" src="{{ asset('assets/img/icons/spot-illustrations/falcon.png') }}" alt="" width="40" />
            <span class="font-sans-serif text-primary">falcon</span>
        </div>
    </a>

    @include('partials.search-bar')

    <ul class="navbar-nav navbar-nav-icons ms-auto flex-row align-items-center">
        @include('partials.navbar-icons')
    </ul>
</nav>
