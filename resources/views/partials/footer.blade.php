<li class="nav-item ps-2 pe-0">
    <div class="dropdown theme-control-dropdown">
        <a class="nav-link d-flex align-items-center dropdown-toggle fa-icon-wait fs-9 pe-1 py-0" href="#" role="button" id="themeSwitchDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="fas fa-sun fs-7" data-fa-transform="shrink-2" data-theme-dropdown-toggle-icon="light"></span>
            <span class="fas fa-moon fs-7" data-fa-transform="shrink-3" data-theme-dropdown-toggle-icon="dark"></span>
            <span class="fas fa-adjust fs-7" data-fa-transform="shrink-2" data-theme-dropdown-toggle-icon="auto"></span>
        </a>
        <div class="dropdown-menu dropdown-menu-end dropdown-caret border py-0 mt-3" aria-labelledby="themeSwitchDropdown">
            <div class="bg-white dark__bg-1000 rounded-2 py-2">
                <button class="dropdown-item d-flex align-items-center gap-2" type="button" value="light" data-theme-control="theme">
                    <span class="fas fa-sun"></span>Light
                    <span class="fas fa-check dropdown-check-icon ms-auto text-600"></span>
                </button>
                <button class="dropdown-item d-flex align-items-center gap-2" type="button" value="dark" data-theme-control="theme">
                    <span class="fas fa-moon" data-fa-transform=""></span>Dark
                    <span class="fas fa-check dropdown-check-icon ms-auto text-600"></span>
                </button>
                <button class="dropdown-item d-flex align-items-center gap-2" type="button" value="auto" data-theme-control="theme">
                    <span class="fas fa-adjust" data-fa-transform=""></span>Auto
                    <span class="fas fa-check dropdown-check-icon ms-auto text-600"></span>
                </button>
            </div>
        </div>
    </div>
</li>

<li class="nav-item d-none d-sm-block">
    <a class="nav-link px-0 notification-indicator notification-indicator-warning notification-indicator-fill fa-icon-wait" href="{{ route('home') }}">
        <span class="fas fa-shopping-cart" data-fa-transform="shrink-7" style="font-size: 33px;"></span>
        <span class="notification-indicator-number">1</span>
    </a>
</li>

<li class="nav-item dropdown">
    <a class="nav-link notification-indicator notification-indicator-primary px-0 fa-icon-wait" id="navbarDropdownNotification" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-hide-on-body-scroll="data-hide-on-body-scroll">
        <span class="fas fa-bell" data-fa-transform="shrink-6" style="font-size: 33px;"></span>
    </a>
    <div class="dropdown-menu dropdown-caret dropdown-caret dropdown-menu-end dropdown-menu-card dropdown-menu-notification dropdown-caret-bg" aria-labelledby="navbarDropdownNotification">
        <div class="card card-notification shadow-none">
            <div class="card-header">
                <div class="row justify-content-between align-items-center">
                    <div class="col-auto">
                        <h6 class="card-header-title mb-0">Notifications</h6>
                    </div>
                    <div class="col-auto ps-0 ps-sm-3">
                        <a class="card-link fw-normal" href="#">Mark all as read</a>
                    </div>
                </div>
            </div>
            <div class="scrollbar-overlay" style="max-height:19rem">
                <div class="list-group list-group-flush fw-normal fs-10">
                    <!-- Add notification items -->
                    <div class="list-group-title border-bottom">NEW</div>
                    <div class="list-group-item">
                        <a class="notification notification-flush notification-unread" href="#!">
                            <div class="notification-avatar">
                                <div class="avatar avatar-2xl me-3">
                                    <img class="rounded-circle" src="{{ asset('assets/img/team/1-thumb.png') }}" alt="" />
                                </div>
                            </div>
                            <div class="notification-body">
                                <p class="mb-1"><strong>Emma Watson</strong> replied to your comment : "Hello world 😍"</p>
                                <span class="notification-time">
                                    <span class="me-2" role="img" aria-label="Emoji">💬</span>Just now
                                </span>
                            </div>
                        </a>
                    </div>
                    <!-- Add more notifications -->
                </div>
            </div>
            <div class="card-footer text-center border-top">
                <a class="card-link d-block" href="{{ route('home') }}">View all</a>
            </div>
        </div>
    </div>
</li>

<li class="nav-item dropdown px-1">
    <!-- Apps dropdown menu (nine dots) -->
    <a class="nav-link fa-icon-wait nine-dots p-1" id="navbarDropdownMenu" role="button" data-hide-on-body-scroll="data-hide-on-body-scroll" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <!-- SVG nine dots icon -->
    </a>
    <!-- Apps dropdown content -->
</li>

<li class="nav-item dropdown">
    <a class="nav-link pe-0 ps-2" id="navbarDropdownUser" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <div class="avatar avatar-xl">
            <img class="rounded-circle" src="{{ asset('assets/img/team/3-thumb.png') }}" alt="" />
        </div>
    </a>
    <div class="dropdown-menu dropdown-caret dropdown-caret dropdown-menu-end py-0" aria-labelledby="navbarDropdownUser">
        <div class="bg-white dark__bg-1000 rounded-2 py-2">
            <a class="dropdown-item fw-bold text-warning" href="#!">
                <span class="fas fa-crown me-1"></span>
                <span>Go Pro</span>
            </a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#!">Set status</a>
            <a class="dropdown-item" href="{{ route('home') }}">Profile &amp; account</a>
            <a class="dropdown-item" href="#!">Feedback</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="{{ route('home') }}">Settings</a>
            <a class="dropdown-item" href="{{ route('home') }}">Logout</a>
        </div>
    </div>
</li>
