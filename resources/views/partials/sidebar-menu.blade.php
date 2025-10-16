<ul class="navbar-nav flex-column mb-3" id="navbarVerticalNav">
    <li class="nav-item">
        <!-- parent pages-->
        <a class="nav-link dropdown-indicator" href="#dashboard" role="button" data-bs-toggle="collapse" aria-expanded="true" aria-controls="dashboard">
            <div class="d-flex align-items-center">
                <span class="nav-link-icon"><span class="fas fa-chart-pie"></span></span>
                <span class="nav-link-text ps-1">Dashboard</span>
            </div>
        </a>
        <ul class="nav collapse show" id="dashboard">
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('home') }}">
                    <div class="d-flex align-items-center">
                        <span class="nav-link-text ps-1">Default</span>
                    </div>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('home') }}">
                    <div class="d-flex align-items-center">
                        <span class="nav-link-text ps-1">Analytics</span>
                    </div>
                </a>
            </li>
            <!-- Add more dashboard items as needed -->
        </ul>
    </li>

    <li class="nav-item">
        <!-- label-->
        <div class="row navbar-vertical-label-wrapper mt-3 mb-2">
            <div class="col-auto navbar-vertical-label">App</div>
            <div class="col ps-0">
                <hr class="mb-0 navbar-vertical-divider" />
            </div>
        </div>

        <!-- parent pages-->
        <a class="nav-link" href="{{ route('home') }}" role="button">
            <div class="d-flex align-items-center">
                <span class="nav-link-icon"><span class="fas fa-calendar-alt"></span></span>
                <span class="nav-link-text ps-1">Calendar</span>
            </div>
        </a>

        <a class="nav-link" href="{{ route('home') }}" role="button">
            <div class="d-flex align-items-center">
                <span class="nav-link-icon"><span class="fas fa-comments"></span></span>
                <span class="nav-link-text ps-1">Chat</span>
            </div>
        </a>

        <!-- Add more app menu items as needed -->
    </li>

    <!-- Add other menu sections (Pages, Modules, Documentation) following the same pattern -->

    <li class="nav-item">
        <div class="settings my-3">
            <div class="card shadow-none">
                <div class="card-body alert mb-0" role="alert">
                    <div class="btn-close-falcon-container">
                        <button class="btn btn-link btn-close-falcon p-0" aria-label="Close" data-bs-dismiss="alert"></button>
                    </div>
                    <div class="text-center">
                        <img src="{{ asset('assets/img/icons/spot-illustrations/navbar-vertical.png') }}" alt="" width="80" />
                        <p class="fs-11 mt-2">Loving what you see? <br />Get your copy of <a href="#!">Falcon</a></p>
                        <div class="d-grid">
                            <a class="btn btn-sm btn-primary" href="https://themes.getbootstrap.com/product/falcon-admin-dashboard-webapp-template/" target="_blank">Purchase</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </li>
</ul>
