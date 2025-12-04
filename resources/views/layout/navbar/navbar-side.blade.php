<nav class="navbar navbar-light navbar-vertical navbar-expand-xl">
    <script>
        var navbarStyle = localStorage.getItem("navbarStyle");
        if (navbarStyle && navbarStyle !== 'transparent') {
            document.querySelector('.navbar-vertical').classList.add(`navbar-${navbarStyle}`);
        }
    </script>

    {{-- Brand --}}
    <div class="d-flex align-items-center">
        <div class="toggle-icon-wrapper">
            <button class="btn navbar-toggler-humburger-icon navbar-vertical-toggle" data-bs-toggle="tooltip"
                data-bs-placement="left" title="Toggle Navigation">
                <span class="navbar-toggle-icon"><span class="toggle-line"></span></span>
            </button>
        </div>
        <a class="navbar-brand" href="{{ route('dashboard') }}">
            <div class="d-flex align-items-center py-3">
                <img class="me-2" src="{{ asset('assets/img/icons/spot-illustrations/falcon.png') }}" alt="WCMS Logo"
                    width="40" />
                <span class="font-sans-serif text-primary">WCMS</span>
            </div>
        </a>
    </div>

    {{-- Navigation Content --}}
    <div class="collapse navbar-collapse" id="navbarVerticalCollapse">
        <div class="navbar-vertical-content scrollbar">
            <ul class="navbar-nav flex-column mb-3" id="navbarVerticalNav">

                {{-- ==================== DASHBOARD ==================== --}}
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard*') ? 'active' : '' }}"
                        href="{{ route('dashboard') }}">
                        <div class="d-flex align-items-center">
                            <span class="nav-link-icon"><span class="fas fa-chart-pie"></span></span>
                            <span class="nav-link-text ps-1">Dashboard</span>
                        </div>
                    </a>
                </li>

                {{-- ==================== MEDICAL SECTION ==================== --}}
                <li class="nav-item">
                    <div class="row navbar-vertical-label-wrapper mt-3 mb-2">
                        <div class="col-auto navbar-vertical-label">Medical Services</div>
                        <div class="col ps-0">
                            <hr class="mb-0 navbar-vertical-divider" />
                        </div>
                    </div>

                    @can('create medical request')
                        <a class="nav-link {{ request()->routeIs('medical-requests.create') ? 'active' : '' }}"
                            href="{{ route('medical-requests.create') }}">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"><span class="fas fa-plus-circle"></span></span>
                                <span class="nav-link-text ps-1">New Application</span>
                            </div>
                        </a>
                    @endcan

                    <a class="nav-link {{ request()->routeIs('medical-requests.index') || request()->routeIs('medical-requests.show') ? 'active' : '' }}"
                        href="{{ route('medical-requests.index') }}">
                        <div class="d-flex align-items-center">
                            <span class="nav-link-icon"><span class="fas fa-list-alt"></span></span>
                            <span class="nav-link-text ps-1">View Applications</span>
                        </div>
                    </a>
                </li>

                {{-- ==================== PAYMENTS & FINANCE (Admin/Accountant) ==================== --}}
                @role(['super_admin', 'admin', 'accountant'])
                    <li class="nav-item">
                        <div class="row navbar-vertical-label-wrapper mt-3 mb-2">
                            <div class="col-auto navbar-vertical-label">Payments & Finance</div>
                            <div class="col ps-0">
                                <hr class="mb-0 navbar-vertical-divider" />
                            </div>
                        </div>

                        <a class="nav-link {{ request()->routeIs('dashboard.payments') ? 'active' : '' }}"
                            href="{{ route('dashboard.payments') }}">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"><span class="fas fa-chart-pie"></span></span>
                                <span class="nav-link-text ps-1">Payment Dashboard</span>
                            </div>
                        </a>

                        <a class="nav-link {{ request()->routeIs('payments.index') ? 'active' : '' }}"
                            href="{{ route('payments.index') }}">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"><span class="fas fa-money-check-alt"></span></span>
                                <span class="nav-link-text ps-1">All Payments</span>
                            </div>
                        </a>

                        <a class="nav-link {{ request()->routeIs('payments.search') ? 'active' : '' }}"
                            href="{{ route('payments.search') }}">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"><span class="fas fa-search-dollar"></span></span>
                                <span class="nav-link-text ps-1">Advanced Search</span>
                            </div>
                        </a>

                        <a class="nav-link dropdown-indicator" href="#payment-reports" role="button"
                            data-bs-toggle="collapse" aria-expanded="false" aria-controls="payment-reports">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"><span class="fas fa-file-invoice-dollar"></span></span>
                                <span class="nav-link-text ps-1">Reports</span>
                            </div>
                        </a>
                        <ul class="nav collapse" id="payment-reports">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('reports.payments.daily') ? 'active' : '' }}"
                                    href="{{ route('reports.payments.daily') }}">
                                    <div class="d-flex align-items-center">
                                        <span class="nav-link-text ps-1">Daily Report</span>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('reports.payments.monthly') ? 'active' : '' }}"
                                    href="{{ route('reports.payments.monthly') }}">
                                    <div class="d-flex align-items-center">
                                        <span class="nav-link-text ps-1">Monthly Report</span>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('reports.payments.by-method') ? 'active' : '' }}"
                                    href="{{ route('reports.payments.by-method') }}">
                                    <div class="d-flex align-items-center">
                                        <span class="nav-link-text ps-1">By Payment Method</span>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('reports.payments.by-center') ? 'active' : '' }}"
                                    href="{{ route('reports.payments.by-center') }}">
                                    <div class="d-flex align-items-center">
                                        <span class="nav-link-text ps-1">By Medical Center</span>
                                    </div>
                                </a>
                            </li>
                        </ul>

                        <a class="nav-link {{ request()->routeIs('refunds.*') ? 'active' : '' }}"
                            href="{{ route('refunds.index') }}">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"><span class="fas fa-undo-alt"></span></span>
                                <span class="nav-link-text ps-1">Refund Management</span>
                            </div>
                        </a>

                        <a class="nav-link dropdown-indicator" href="#payment-export" role="button"
                            data-bs-toggle="collapse" aria-expanded="false" aria-controls="payment-export">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"><span class="fas fa-download"></span></span>
                                <span class="nav-link-text ps-1">Export Data</span>
                            </div>
                        </a>
                        <ul class="nav collapse" id="payment-export">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('payments.export.excel') }}">
                                    <div class="d-flex align-items-center">
                                        <span class="nav-link-text ps-1">Export to Excel</span>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('payments.export.csv') }}">
                                    <div class="d-flex align-items-center">
                                        <span class="nav-link-text ps-1">Export to CSV</span>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endrole

                {{-- ==================== CHALLAN MANAGEMENT (TODO) ==================== --}}
                @canany(['create challan', 'read challan'])
                    <li class="nav-item">
                        <div class="row navbar-vertical-label-wrapper mt-3 mb-2">
                            <div class="col-auto navbar-vertical-label">Challan Management</div>
                            <div class="col ps-0">
                                <hr class="mb-0 navbar-vertical-divider" />
                            </div>
                        </div>

                        @can('create challan')
                            <a class="nav-link disabled" href="#!" title="Coming Soon">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-icon"><span class="fas fa-file-invoice"></span></span>
                                    <span class="nav-link-text ps-1">New Challan</span>
                                    <span class="badge badge-soft-warning ms-auto">Soon</span>
                                </div>
                            </a>
                        @endcan

                        @can('read challan')
                            <a class="nav-link disabled" href="#!" title="Coming Soon">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-icon"><span class="fas fa-receipt"></span></span>
                                    <span class="nav-link-text ps-1">View Challans</span>
                                    <span class="badge badge-soft-warning ms-auto">Soon</span>
                                </div>
                            </a>
                        @endcan
                    </li>
                @endcanany

                {{-- ==================== INFRASTRUCTURE (Super Admin) ==================== --}}
                @role('super_admin')
                    <li class="nav-item">
                        <div class="row navbar-vertical-label-wrapper mt-3 mb-2">
                            <div class="col-auto navbar-vertical-label">Infrastructure</div>
                            <div class="col ps-0">
                                <hr class="mb-0 navbar-vertical-divider" />
                            </div>
                        </div>

                        <a class="nav-link dropdown-indicator" href="#infrastructure" role="button"
                            data-bs-toggle="collapse" aria-expanded="false" aria-controls="infrastructure">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"><span class="fas fa-building"></span></span>
                                <span class="nav-link-text ps-1">Locations</span>
                            </div>
                        </a>
                        <ul class="nav collapse" id="infrastructure">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('provinces.*') ? 'active' : '' }}"
                                    href="{{ route('provinces.index') }}">
                                    <div class="d-flex align-items-center">
                                        <span class="nav-link-text ps-1">Provinces</span>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('cities.*') ? 'active' : '' }}"
                                    href="{{ route('cities.index') }}">
                                    <div class="d-flex align-items-center">
                                        <span class="nav-link-text ps-1">Cities</span>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('circles.*') ? 'active' : '' }}"
                                    href="{{ route('circles.index') }}">
                                    <div class="d-flex align-items-center">
                                        <span class="nav-link-text ps-1">Circles</span>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('dumping-points.*') ? 'active' : '' }}"
                                    href="{{ route('dumping-points.index') }}">
                                    <div class="d-flex align-items-center">
                                        <span class="nav-link-text ps-1">Dumping Points</span>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('medical-centers.*') ? 'active' : '' }}"
                                    href="{{ route('medical-centers.index') }}">
                                    <div class="d-flex align-items-center">
                                        <span class="nav-link-text ps-1">Medical Centers</span>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endrole

                {{-- ==================== STAFF MANAGEMENT (Super Admin) ==================== --}}
                @can('crud staff')
                    <li class="nav-item">
                        <div class="row navbar-vertical-label-wrapper mt-3 mb-2">
                            <div class="col-auto navbar-vertical-label">Staff Management</div>
                            <div class="col ps-0">
                                <hr class="mb-0 navbar-vertical-divider" />
                            </div>
                        </div>

                        <a class="nav-link {{ request()->routeIs('staff.create') ? 'active' : '' }}"
                            href="{{ route('staff.create') }}">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"><span class="fas fa-user-plus"></span></span>
                                <span class="nav-link-text ps-1">Add Staff</span>
                            </div>
                        </a>

                        <a class="nav-link {{ request()->routeIs('staff.index') || request()->routeIs('staff.edit') ? 'active' : '' }}"
                            href="{{ route('staff.index') }}">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"><span class="fas fa-users"></span></span>
                                <span class="nav-link-text ps-1">View Staff</span>
                            </div>
                        </a>

                        <a class="nav-link {{ request()->routeIs('staff-postings.*') ? 'active' : '' }}"
                            href="{{ route('staff-postings.index') }}">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"><span class="fas fa-exchange-alt"></span></span>
                                <span class="nav-link-text ps-1">Staff Postings</span>
                            </div>
                        </a>
                    </li>
                @endcan

                {{-- ==================== USER MANAGEMENT (TODO) ==================== --}}
                @can('crud users')
                    <li class="nav-item">
                        <div class="row navbar-vertical-label-wrapper mt-3 mb-2">
                            <div class="col-auto navbar-vertical-label">User Management</div>
                            <div class="col ps-0">
                                <hr class="mb-0 navbar-vertical-divider" />
                            </div>
                        </div>

                        <a class="nav-link disabled" href="#!" title="Coming Soon">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"><span class="fas fa-users-cog"></span></span>
                                <span class="nav-link-text ps-1">Manage Users</span>
                                <span class="badge badge-soft-warning ms-auto">Soon</span>
                            </div>
                        </a>

                        <a class="nav-link disabled" href="#!" title="Coming Soon">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"><span class="fas fa-user-shield"></span></span>
                                <span class="nav-link-text ps-1">Roles & Permissions</span>
                                <span class="badge badge-soft-warning ms-auto">Soon</span>
                            </div>
                        </a>
                    </li>
                @endcan

                {{-- ==================== REPORTS & ANALYTICS (TODO) ==================== --}}
                @can('crud reports')
                    <li class="nav-item">
                        <div class="row navbar-vertical-label-wrapper mt-3 mb-2">
                            <div class="col-auto navbar-vertical-label">Reports & Analytics</div>
                            <div class="col ps-0">
                                <hr class="mb-0 navbar-vertical-divider" />
                            </div>
                        </div>

                        <a class="nav-link disabled" href="#!" title="Coming Soon">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"><span class="fas fa-chart-bar"></span></span>
                                <span class="nav-link-text ps-1">Medical Reports</span>
                                <span class="badge badge-soft-warning ms-auto">Soon</span>
                            </div>
                        </a>

                        <a class="nav-link disabled" href="#!" title="Coming Soon">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"><span class="fas fa-file-invoice-dollar"></span></span>
                                <span class="nav-link-text ps-1">Financial Reports</span>
                                <span class="badge badge-soft-warning ms-auto">Soon</span>
                            </div>
                        </a>

                        <a class="nav-link disabled" href="#!" title="Coming Soon">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"><span class="fas fa-chart-line"></span></span>
                                <span class="nav-link-text ps-1">Analytics Dashboard</span>
                                <span class="badge badge-soft-warning ms-auto">Soon</span>
                            </div>
                        </a>
                    </li>
                @endcan

                {{-- ==================== SUPPORT & HELP ==================== --}}
                <li class="nav-item">
                    <div class="row navbar-vertical-label-wrapper mt-3 mb-2">
                        <div class="col-auto navbar-vertical-label">Support</div>
                        <div class="col ps-0">
                            <hr class="mb-0 navbar-vertical-divider" />
                        </div>
                    </div>

                    {{-- TODO: Implement FAQ System --}}
                    <a class="nav-link disabled" href="#!" title="Coming Soon">
                        <div class="d-flex align-items-center">
                            <span class="nav-link-icon"><span class="fas fa-question-circle"></span></span>
                            <span class="nav-link-text ps-1">FAQ</span>
                            <span class="badge badge-soft-warning ms-auto">Soon</span>
                        </div>
                    </a>

                    <a class="nav-link {{ request()->routeIs('changelog.public') ? 'active' : '' }}"
                        href="{{ route('changelog.public') }}">
                        <div class="d-flex align-items-center">
                            <span class="nav-link-icon"><span class="fas fa-code-branch"></span></span>
                            <span class="nav-link-text ps-1">Changelog</span>
                        </div>
                    </a>

                    @role(['super_admin', 'admin'])
                        <a class="nav-link {{ request()->routeIs('changelog.index') || request()->routeIs('changelog.create') || request()->routeIs('changelog.edit') ? 'active' : '' }}"
                            href="{{ route('changelog.index') }}">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"><span class="fas fa-cog"></span></span>
                                <span class="nav-link-text ps-1">Manage Changelog</span>
                            </div>
                        </a>
                    @endrole

                    <a class="nav-link {{ request()->routeIs('feedback.*') ? 'active' : '' }}"
                        href="{{ route('feedback.index') }}">
                        <div class="d-flex align-items-center">
                            <span class="nav-link-icon"><span class="fas fa-comments"></span></span>
                            <span class="nav-link-text ps-1">Feedback</span>
                        </div>
                    </a>
                </li>

            </ul>

            {{-- ==================== FEEDBACK WIDGET (Non-Admin) ==================== --}}
            @unlessrole('super_admin')
                <div class="settings my-3">
                    <div class="card shadow-none">
                        <div class="card-body alert mb-0" role="alert">
                            <div class="btn-close-falcon-container">
                                <button class="btn btn-link btn-close-falcon p-0" aria-label="Close"
                                    data-bs-dismiss="alert"></button>
                            </div>
                            <div class="text-center">
                                <img src="{{ asset('assets/img/icons/spot-illustrations/navbar-vertical.png') }}"
                                    alt="Feedback" width="80" />
                                <p class="fs-11 mt-2">Loving what you see? <br />Give your feedback</p>
                                <div class="d-grid">
                                    <a class="btn btn-sm btn-primary" href="{{ route('feedback.create') }}">
                                        Submit Feedback
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endunlessrole
        </div>
    </div>
</nav>
