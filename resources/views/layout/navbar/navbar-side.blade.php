<nav class="navbar navbar-light navbar-vertical navbar-expand-xl">
    <script>
        var navbarStyle = localStorage.getItem("navbarStyle");
        if (navbarStyle && navbarStyle !== 'transparent') {
            document.querySelector('.navbar-vertical').classList.add(`navbar-${navbarStyle}`);
        }
    </script>
    <div class="d-flex align-items-center">
        <div class="toggle-icon-wrapper">

            <button class="btn navbar-toggler-humburger-icon navbar-vertical-toggle" data-bs-toggle="tooltip"
                data-bs-placement="left" title="Toggle Navigation"><span class="navbar-toggle-icon"><span
                        class="toggle-line"></span></span></button>

        </div><a class="navbar-brand" href="index.html">
            <div class="d-flex align-items-center py-3"><img class="me-2"
                    src="{{ asset('assets/img/icons/spot-illustrations/falcon.png') }}" alt=""
                    width="40" /><span class="font-sans-serif text-primary">CTPF</span>
            </div>
        </a>
    </div>
    <div class="collapse navbar-collapse" id="navbarVerticalCollapse">
        <div class="navbar-vertical-content scrollbar">
            <ul class="navbar-nav flex-column mb-3" id="navbarVerticalNav">
                <li class="nav-item">
                    <!-- parent pages--><a class="nav-link dropdown-indicator" href="#dashboard" role="button"
                        data-bs-toggle="collapse" aria-expanded="true" aria-controls="dashboard">
                        <div class="d-flex align-items-center"><span class="nav-link-icon"><span
                                    class="fas fa-chart-pie"></span></span><span
                                class="nav-link-text ps-1">Dashboard</span>
                        </div>
                    </a>
                    <ul class="nav collapse show" id="dashboard">
                        <li class="nav-item"><a class="nav-link active" href="{{ route('home') }}">
                                <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Home</span>
                                </div>
                            </a>
                            <!-- more inner pages-->
                        </li>
                        <!-- parent pages--><a class="nav-link dropdown-indicator" href="#user" role="button"
                            data-bs-toggle="collapse" aria-expanded="false" aria-controls="user">
                            <div class="d-flex align-items-center"><span class="nav-link-icon"><span
                                        class="fas fa-user"></span></span><span class="nav-link-text ps-1">User</span>
                            </div>
                        </a>
                        <ul class="nav collapse" id="user">
                            <li class="nav-item"><a class="nav-link" href="pages/user/profile.html">
                                    <div class="d-flex align-items-center"><span
                                            class="nav-link-text ps-1">Profile</span>
                                    </div>
                                </a>
                                <!-- more inner pages-->
                            </li>
                            <li class="nav-item"><a class="nav-link" href="pages/user/settings.html">
                                    <div class="d-flex align-items-center"><span
                                            class="nav-link-text ps-1">Settings</span>
                                    </div>
                                </a>
                                <!-- more inner pages-->
                            </li>
                            <li class="nav-item"><a class="nav-link" href="pages/user/settings.html">
                                    <div class="d-flex align-items-center"><span class="nav-link-text ps-1">View
                                            Users</span>
                                    </div>
                                </a>
                                <!-- more inner pages-->
                            </li>
                        </ul>
                    </ul>
                </li>
                <li class="nav-item">
                    <!-- label-->
                    <div class="row navbar-vertical-label-wrapper mt-3 mb-2">
                        <div class="col-auto navbar-vertical-label">Lifter Squad
                        </div>
                        <div class="col ps-0">
                            <hr class="mb-0 navbar-vertical-divider" />
                        </div>
                    </div>
                    <!-- parent pages--><a class="nav-link" href="{{ url('/') }}" role="button">
                        <div class="d-flex align-items-center"><span class="nav-link-icon"><span
                                    class="fas fa-calendar-alt"></span></span><span class="nav-link-text ps-1">New
                                Challan</span>
                        </div>
                    </a>
                    <!-- parent pages--><a class="nav-link" href="{{ url('/') }}" role="button">
                        <div class="d-flex align-items-center"><span class="nav-link-icon"><span
                                    class="fas fa-calendar-alt"></span></span><span class="nav-link-text ps-1">View
                                Challans</span>
                        </div>
                    </a>
                    <!-- parent pages--><a class="nav-link dropdown-indicator" href="#email" role="button"
                        data-bs-toggle="collapse" aria-expanded="false" aria-controls="email">
                        <div class="d-flex align-items-center"><span class="nav-link-icon"><span
                                    class="fas fa-envelope-open"></span></span><span
                                class="nav-link-text ps-1">Infrastructure</span>
                        </div>
                    </a>
                    <ul class="nav collapse" id="email">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dumping-points.*') ? 'active' : '' }}"
                                href="{{ route('dumping-points.index') }}">
                                <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Dumping
                                        Points</span></div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('circles.*') ? 'active' : '' }}"
                                href="{{ route('circles.index') }}">
                                <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Circles</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('cities.*') ? 'active' : '' }}"
                                href="{{ route('cities.index') }}">
                                <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Cities</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('provinces.*') ? 'active' : '' }}"
                                href="{{ route('provinces.index') }}">
                                <div class="d-flex align-items-center"><span
                                        class="nav-link-text ps-1">Provinces</span></div>
                            </a>
                        </li>
                    </ul>

                </li>
                <li class="nav-item">
                    <!-- label-->
                    <div class="row navbar-vertical-label-wrapper mt-3 mb-2">
                        <div class="col-auto navbar-vertical-label">Medical Welfare
                        </div>
                        <div class="col ps-0">
                            <hr class="mb-0 navbar-vertical-divider" />
                        </div>
                    </div>
                    <!-- parent pages--><a class="nav-link" href="pages/starter.html" role="button">
                        <div class="d-flex align-items-center"><span class="nav-link-icon"><span
                                    class="fas fa-flag"></span></span><span class="nav-link-text ps-1">New
                                Application</span>
                        </div>
                    </a>
                    <!-- parent pages--><a class="nav-link" href="pages/landing.html" role="button">
                        <div class="d-flex align-items-center"><span class="nav-link-icon"><span
                                    class="fas fa-globe"></span></span><span class="nav-link-text ps-1">View
                                Applications</span>
                        </div>
                    </a>
                </li>
                <li class="nav-item">
                    <!-- label-->
                    <div class="row navbar-vertical-label-wrapper mt-3 mb-2">
                        <div class="col-auto navbar-vertical-label">Staff Management
                        </div>
                        <div class="col ps-0">
                            <hr class="mb-0 navbar-vertical-divider" />
                        </div>
                    </div>
                    <!-- parent pages--><a class="nav-link" href="pages/starter.html" role="button">
                        <div class="d-flex align-items-center"><span class="nav-link-icon"><span
                                    class="fas fa-flag"></span></span><span class="nav-link-text ps-1">Add New
                                Staff</span>
                        </div>
                    </a>
                    <!-- parent pages--><a class="nav-link" href="pages/landing.html" role="button">
                        <div class="d-flex align-items-center"><span class="nav-link-icon"><span
                                    class="fas fa-globe"></span></span><span class="nav-link-text ps-1">View
                                Staff</span>
                        </div>
                    </a>
                    <!-- parent pages--><a class="nav-link" href="pages/landing.html" role="button">
                        <div class="d-flex align-items-center"><span class="nav-link-icon"><span
                                    class="fas fa-globe"></span></span><span class="nav-link-text ps-1">Transfer
                                Posting</span>
                        </div>
                    </a>
                </li>
                <li class="nav-item">
                    <!-- label-->
                    <div class="row navbar-vertical-label-wrapper mt-3 mb-2">
                        <div class="col-auto navbar-vertical-label">Reporting & Finance
                        </div>
                        <div class="col ps-0">
                            <hr class="mb-0 navbar-vertical-divider" />
                        </div>
                    </div>
                    <!-- parent pages--><a class="nav-link" href="pages/starter.html" role="button">
                        <div class="d-flex align-items-center"><span class="nav-link-icon"><span
                                    class="fas fa-flag"></span></span><span class="nav-link-text ps-1">Report 1</span>
                        </div>
                    </a>
                    <!-- parent pages--><a class="nav-link" href="pages/landing.html" role="button">
                        <div class="d-flex align-items-center"><span class="nav-link-icon"><span
                                    class="fas fa-globe"></span></span><span class="nav-link-text ps-1">Report
                                2</span>
                        </div>
                    </a>
                    <!-- parent pages--><a class="nav-link" href="pages/landing.html" role="button">
                        <div class="d-flex align-items-center"><span class="nav-link-icon"><span
                                    class="fas fa-globe"></span></span><span class="nav-link-text ps-1">Report
                                3</span>
                        </div>
                    </a>
                </li>
                <li class="nav-item">
                    <!-- label-->
                    <div class="row navbar-vertical-label-wrapper mt-3 mb-2">
                        <div class="col-auto navbar-vertical-label">Documentation
                        </div>
                        <div class="col ps-0">
                            <hr class="mb-0 navbar-vertical-divider" />
                        </div>
                    </div>

                    <!-- parent pages--><a class="nav-link" href="documentation/faq.html" role="button">
                        <div class="d-flex align-items-center"><span class="nav-link-icon"><span
                                    class="fas fa-question-circle"></span></span><span
                                class="nav-link-text ps-1">Faq</span>
                        </div>
                    </a>
                    <!-- parent pages--><a class="nav-link" href="changelog.html" role="button">
                        <div class="d-flex align-items-center"><span class="nav-link-icon"><span
                                    class="fas fa-code-branch"></span></span><span
                                class="nav-link-text ps-1">Changelog</span>
                        </div>
                    </a>
                </li>
            </ul>
            <div class="settings my-3">
                <div class="card shadow-none">
                    <div class="card-body alert mb-0" role="alert">
                        <div class="btn-close-falcon-container">
                            <button class="btn btn-link btn-close-falcon p-0" aria-label="Close"
                                data-bs-dismiss="alert"></button>
                        </div>
                        <div class="text-center"><img
                                src="{{ asset('assets/img/icons/spot-illustrations/navbar-vertical.png') }}"
                                alt="" width="80" />
                            <p class="fs-11 mt-2">Loving what you see? <br />Give your feedback</p>
                            <div class="d-grid"><a class="btn btn-sm btn-primary" href="#"
                                    target="_blank">Submit Feedback</a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
