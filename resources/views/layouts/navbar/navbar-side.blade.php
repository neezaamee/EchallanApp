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
                @canany(['medical-centers:view', 'medical-requests:view', 'medical-requests:create'])
                <li class="nav-item">
                    <a class="nav-link dropdown-indicator {{ request()->routeIs('medical-requests.*') ? '' : 'collapsed' }}" 
                        href="#medical-services" role="button" data-bs-toggle="collapse" 
                        aria-expanded="{{ request()->routeIs('medical-requests.*') ? 'true' : 'false' }}" 
                        aria-controls="medical-services">
                        <div class="d-flex align-items-center">
                            <span class="nav-link-icon"><span class="fas fa-notes-medical"></span></span>
                            <span class="nav-link-text ps-1">Medical Services</span>
                        </div>
                    </a>
                    <ul class="nav collapse {{ request()->routeIs('medical-requests.*') ? 'show' : '' }}" id="medical-services">
                        @can('medical-requests:create')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('medical-requests.create') ? 'active' : '' }}"
                                href="{{ route('medical-requests.create') }}">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-icon"><span class="fas fa-plus-circle"></span></span>
                                    <span class="nav-link-text ps-1">New Application</span>
                                </div>
                            </a>
                        </li>
                        @endcan
                        @can('medical-requests:view')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('medical-requests.index') || request()->routeIs('medical-requests.show') ? 'active' : '' }}"
                                href="{{ route('medical-requests.index') }}">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-icon"><span class="fas fa-list-alt"></span></span>
                                    <span class="nav-link-text ps-1">View Applications</span>
                                </div>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcanany

                {{-- ==================== PAYMENTS & FINANCE (Admin/Accountant) ==================== --}}
                @role(['super_admin', 'admin', 'accountant'])
                    <li class="nav-item">
                        <a class="nav-link dropdown-indicator {{ request()->routeIs('dashboard.payments') || request()->routeIs('payments.*') || request()->routeIs('reports.payments.*') || request()->routeIs('refunds.*') ? '' : 'collapsed' }}"
                            href="#payments-finance" role="button" data-bs-toggle="collapse"
                            aria-expanded="{{ request()->routeIs('dashboard.payments') || request()->routeIs('payments.*') || request()->routeIs('reports.payments.*') || request()->routeIs('refunds.*') ? 'true' : 'false' }}"
                            aria-controls="payments-finance">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"><span class="fas fa-wallet"></span></span>
                                <span class="nav-link-text ps-1">Payments & Finance</span>
                            </div>
                        </a>
                        <ul class="nav collapse {{ request()->routeIs('dashboard.payments') || request()->routeIs('payments.*') || request()->routeIs('reports.payments.*') || request()->routeIs('refunds.*') ? 'show' : '' }}" id="payments-finance">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('dashboard.payments') ? 'active' : '' }}"
                                    href="{{ route('dashboard.payments') }}">
                                    <div class="d-flex align-items-center">
                                        <span class="nav-link-icon"><span class="fas fa-chart-pie"></span></span>
                                        <span class="nav-link-text ps-1">Payment Dashboard</span>
                                    </div>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('payments.index') ? 'active' : '' }}"
                                    href="{{ route('payments.index') }}">
                                    <div class="d-flex align-items-center">
                                        <span class="nav-link-icon"><span class="fas fa-money-check-alt"></span></span>
                                        <span class="nav-link-text ps-1">All Payments</span>
                                    </div>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('payments.search') ? 'active' : '' }}"
                                    href="{{ route('payments.search') }}">
                                    <div class="d-flex align-items-center">
                                        <span class="nav-link-icon"><span class="fas fa-search-dollar"></span></span>
                                        <span class="nav-link-text ps-1">Advanced Search</span>
                                    </div>
                                </a>
                            </li>

                            <li class="nav-item">
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
                            </li>

                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('refunds.*') ? 'active' : '' }}"
                                    href="{{ route('refunds.index') }}">
                                    <div class="d-flex align-items-center">
                                        <span class="nav-link-icon"><span class="fas fa-undo-alt"></span></span>
                                        <span class="nav-link-text ps-1">Refund Management</span>
                                    </div>
                                </a>
                            </li>

                            <li class="nav-item">
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
                        </ul>
                    </li>
                @endrole

                {{-- ==================== CHALLAN Services ==================== --}}
                @canany(['challans:create', 'challans:view'])
                    <li class="nav-item">
                        <a class="nav-link dropdown-indicator {{ request()->routeIs('challans.*') ? '' : 'collapsed' }}" 
                            href="#challan-services" role="button" data-bs-toggle="collapse" 
                            aria-expanded="{{ request()->routeIs('challans.*') ? 'true' : 'false' }}" 
                            aria-controls="challan-services">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"><span class="fas fa-file-invoice"></span></span>
                                <span class="nav-link-text ps-1">Challan Services</span>
                            </div>
                        </a>
                        <ul class="nav collapse {{ request()->routeIs('challans.*') ? 'show' : '' }}" id="challan-services">
                            @can('challans:create')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('challans.create') ? 'active' : '' }}" href="{{ route('challans.create') }}">
                                    <div class="d-flex align-items-center">
                                        <span class="nav-link-icon"><span class="fas fa-plus-circle"></span></span>
                                        <span class="nav-link-text ps-1">New Challan</span>
                                    </div>
                                </a>
                            </li>
                            @endcan

                            @can('challans:view')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('challans.index') ? 'active' : '' }}" href="{{ route('challans.index') }}">
                                    <div class="d-flex align-items-center">
                                        <span class="nav-link-icon"><span class="fas fa-receipt"></span></span>
                                        <span class="nav-link-text ps-1">View Challans</span>
                                    </div>
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany

                {{-- ==================== INFRASTRUCTURE ==================== --}}
                @canany(['provinces:view', 'cities:view', 'circles:view', 'dumping-points:view', 'pick-up-points:view', 'medical-centers:view'])
                    <li class="nav-item">
                        <a class="nav-link dropdown-indicator {{ request()->routeIs('provinces.*') || request()->routeIs('cities.*') || request()->routeIs('circles.*') || request()->routeIs('dumping-points.*') || request()->routeIs('pick-up-points.*') || request()->routeIs('medical-centers.*') ? '' : 'collapsed' }}" 
                            href="#infrastructure" role="button" data-bs-toggle="collapse" 
                            aria-expanded="{{ request()->routeIs('provinces.*') || request()->routeIs('cities.*') || request()->routeIs('circles.*') || request()->routeIs('dumping-points.*') || request()->routeIs('pick-up-points.*') || request()->routeIs('medical-centers.*') ? 'true' : 'false' }}" 
                            aria-controls="infrastructure">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"><span class="fas fa-building"></span></span>
                                <span class="nav-link-text ps-1">Infrastructure</span>
                            </div>
                        </a>
                        <ul class="nav collapse {{ request()->routeIs('provinces.*') || request()->routeIs('cities.*') || request()->routeIs('circles.*') || request()->routeIs('dumping-points.*') || request()->routeIs('pick-up-points.*') || request()->routeIs('medical-centers.*') ? 'show' : '' }}" id="infrastructure">
                            @can('provinces:view')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('provinces.*') ? 'active' : '' }}"
                                    href="{{ route('provinces.index') }}">
                                    <div class="d-flex align-items-center">
                                        <span class="nav-link-text ps-1">Provinces</span>
                                    </div>
                                </a>
                            </li>
                            @endcan
                            @can('cities:view')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('cities.*') ? 'active' : '' }}"
                                    href="{{ route('cities.index') }}">
                                    <div class="d-flex align-items-center">
                                        <span class="nav-link-text ps-1">Cities</span>
                                    </div>
                                </a>
                            </li>
                            @endcan
                            @can('circles:view')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('circles.*') ? 'active' : '' }}"
                                    href="{{ route('circles.index') }}">
                                    <div class="d-flex align-items-center">
                                        <span class="nav-link-text ps-1">Circles</span>
                                    </div>
                                </a>
                            </li>
                            @endcan
                            @can('dumping-points:view')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('dumping-points.*') ? 'active' : '' }}"
                                    href="{{ route('dumping-points.index') }}">
                                    <div class="d-flex align-items-center">
                                        <span class="nav-link-text ps-1">Dumping Points</span>
                                    </div>
                                </a>
                            </li>
                            @endcan
                            @can('pick-up-points:view')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('pick-up-points.*') ? 'active' : '' }}"
                                    href="{{ route('pick-up-points.index') }}">
                                    <div class="d-flex align-items-center">
                                        <span class="nav-link-text ps-1">Pick Up Points</span>
                                    </div>
                                </a>
                            </li>
                            @endcan
                            @can('medical-centers:view')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('medical-centers.*') ? 'active' : '' }}"
                                    href="{{ route('medical-centers.index') }}">
                                    <div class="d-flex align-items-center">
                                        <span class="nav-link-text ps-1">Medical Centers</span>
                                    </div>
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany

                {{-- ==================== SYSTEM ADMINISTRATION ==================== --}}
                @canany(['logs:view', 'backups:view'])
                    <li class="nav-item">
                        <a class="nav-link dropdown-indicator {{ request()->routeIs('activity-logs.*') || request()->routeIs('backups.*') || request()->routeIs('changelog.index') ? '' : 'collapsed' }}" 
                            href="#system-admin" role="button" data-bs-toggle="collapse" 
                            aria-expanded="{{ request()->routeIs('activity-logs.*') || request()->routeIs('backups.*') || request()->routeIs('changelog.index') ? 'true' : 'false' }}" 
                            aria-controls="system-admin">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"><span class="fas fa-cogs"></span></span>
                                <span class="nav-link-text ps-1">System Admin</span>
                            </div>
                        </a>
                        <ul class="nav collapse {{ request()->routeIs('activity-logs.*') || request()->routeIs('backups.*') || request()->routeIs('changelog.index') ? 'show' : '' }}" id="system-admin">
                            @can('logs:view')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('activity-logs.*') ? 'active' : '' }}"
                                    href="{{ route('activity-logs.index') }}">
                                    <div class="d-flex align-items-center">
                                        <span class="nav-link-icon"><span class="fas fa-list-alt"></span></span>
                                        <span class="nav-link-text ps-1">Activity Logs</span>
                                    </div>
                                </a>
                            </li>
                            @endcan
                            @can('backups:view')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('backups.index') ? 'active' : '' }}"
                                    href="{{ route('backups.index') }}">
                                    <div class="d-flex align-items-center">
                                        <span class="nav-link-icon"><span class="fas fa-database"></span></span>
                                        <span class="nav-link-text ps-1">Database Backup</span>
                                    </div>
                                </a>
                            </li>
                            @endcan
                            @can('settings:view')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('changelog.index') || request()->routeIs('changelog.create') || request()->routeIs('changelog.edit') ? 'active' : '' }}"
                                    href="{{ route('changelog.index') }}">
                                    <div class="d-flex align-items-center">
                                        <span class="nav-link-icon"><span class="fas fa-edit"></span></span>
                                        <span class="nav-link-text ps-1">Manage Changelog</span>
                                    </div>
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany

                {{-- ==================== STAFF MANAGEMENT ==================== --}}
                @canany(['staff:view', 'staff-postings:view'])
                    <li class="nav-item">
                        <a class="nav-link dropdown-indicator {{ request()->routeIs('staff.*') || request()->routeIs('staff-postings.*') ? '' : 'collapsed' }}" 
                            href="#staff-management" role="button" data-bs-toggle="collapse" 
                            aria-expanded="{{ request()->routeIs('staff.*') || request()->routeIs('staff-postings.*') ? 'true' : 'false' }}" 
                            aria-controls="staff-management">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"><span class="fas fa-users"></span></span>
                                <span class="nav-link-text ps-1">Staff and Postings</span>
                            </div>
                        </a>
                        <ul class="nav collapse {{ request()->routeIs('staff.*') || request()->routeIs('staff-postings.*') ? 'show' : '' }}" id="staff-management">

                            @can('staff:view')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('staff.index') || request()->routeIs('staff.edit') ? 'active' : '' }}"
                                    href="{{ route('staff.index') }}">
                                    <div class="d-flex align-items-center">
                                        <span class="nav-link-icon"><span class="fas fa-users"></span></span>
                                        <span class="nav-link-text ps-1">View Staff</span>
                                    </div>
                                </a>
                            </li>
                            @endcan

                            @can('staff-postings:view')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('staff-postings.*') ? 'active' : '' }}"
                                    href="{{ route('staff-postings.index') }}">
                                    <div class="d-flex align-items-center">
                                        <span class="nav-link-icon"><span class="fas fa-exchange-alt"></span></span>
                                        <span class="nav-link-text ps-1">Staff Postings</span>
                                    </div>
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany

                {{-- ==================== USER MANAGEMENT ==================== --}}
                @canany(['users:view', 'roles:view', 'permissions:view'])
                    <li class="nav-item">
                        <a class="nav-link dropdown-indicator {{ request()->routeIs('users.*') || request()->routeIs('roles.*') || request()->routeIs('permissions.*') ? '' : 'collapsed' }}" 
                            href="#user-management" role="button" data-bs-toggle="collapse" 
                            aria-expanded="{{ request()->routeIs('users.*') || request()->routeIs('roles.*') || request()->routeIs('permissions.*') ? 'true' : 'false' }}" 
                            aria-controls="user-management">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"><span class="fas fa-user-shield"></span></span>
                                <span class="nav-link-text ps-1">User Management</span>
                            </div>
                        </a>
                        <ul class="nav collapse {{ request()->routeIs('users.*') || request()->routeIs('roles.*') || request()->routeIs('permissions.*') ? 'show' : '' }}" id="user-management">
                                @can('users:view')
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}"
                                        href="{{ route('users.index') }}">
                                        <div class="d-flex align-items-center">
                                            <span class="nav-link-icon"><span class="fas fa-users-cog"></span></span>
                                            <span class="nav-link-text ps-1">Manage Users</span>
                                        </div>
                                    </a>
                                </li>
                                @endcan

                                @role('super_admin')
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('roles.matrix') ? 'active' : '' }}"
                                        href="{{ route('roles.matrix') }}">
                                        <div class="d-flex align-items-center">
                                            <span class="nav-link-icon"><span class="fas fa-th"></span></span>
                                            <span class="nav-link-text ps-1">Role Matrix</span>
                                        </div>
                                    </a>
                                </li>
                                @endrole

                                @canany(['roles:view', 'permissions:view'])
                                <li class="nav-item">
                                <a class="nav-link dropdown-indicator {{ request()->routeIs('roles.*') || request()->routeIs('permissions.*') ? '' : 'collapsed' }}"
                                    href="#roles-permissions" data-bs-toggle="collapse" role="button" aria-expanded="{{ request()->routeIs('roles.*') || request()->routeIs('permissions.*') ? 'true' : 'false' }}"
                                    aria-controls="roles-permissions">
                                    <div class="d-flex align-items-center">
                                        <span class="nav-link-icon"><span class="fas fa-shield-alt"></span></span>
                                        <span class="nav-link-text ps-1">Roles & Permissions</span>
                                    </div>
                                </a>
                                <ul class="nav collapse {{ request()->routeIs('roles.*') || request()->routeIs('permissions.*') ? 'show' : '' }}" id="roles-permissions">
                                    @can('roles:view')
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('roles.*') ? 'active' : '' }}"
                                            href="{{ route('roles.index') }}">
                                            <div class="d-flex align-items-center">
                                                <span class="nav-link-text ps-1">Roles</span>
                                            </div>
                                        </a>
                                    </li>
                                    @endcan
                                    @can('permissions:view')
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('permissions.*') ? 'active' : '' }}"
                                            href="{{ route('permissions.index') }}">
                                            <div class="d-flex align-items-center">
                                                <span class="nav-link-text ps-1">Permissions</span>
                                            </div>
                                        </a>
                                    </li>
                                    @endcan
                                </ul>
                            </li>
                            @endcanany
                        </ul>
                    </li>
                @endcanany

                {{-- ==================== REPORTS & ANALYTICS ==================== --}}
                @can('reports:view')
                    <li class="nav-item">
                        <a class="nav-link dropdown-indicator collapsed" 
                            href="#reports-analytics" role="button" data-bs-toggle="collapse" 
                            aria-expanded="false" aria-controls="reports-analytics">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"><span class="fas fa-chart-line"></span></span>
                                <span class="nav-link-text ps-1">Reports & Analytics</span>
                            </div>
                        </a>
                        <ul class="nav collapse" id="reports-analytics">
                            <li class="nav-item">
                                <a class="nav-link disabled" href="#!" title="Coming Soon">
                                    <div class="d-flex align-items-center">
                                        <span class="nav-link-icon"><span class="fas fa-chart-bar"></span></span>
                                        <span class="nav-link-text ps-1">Medical Reports</span>
                                        <span class="badge badge-soft-warning ms-auto">Soon</span>
                                    </div>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link disabled" href="#!" title="Coming Soon">
                                    <div class="d-flex align-items-center">
                                        <span class="nav-link-icon"><span class="fas fa-file-invoice-dollar"></span></span>
                                        <span class="nav-link-text ps-1">Financial Reports</span>
                                        <span class="badge badge-soft-warning ms-auto">Soon</span>
                                    </div>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link disabled" href="#!" title="Coming Soon">
                                    <div class="d-flex align-items-center">
                                        <span class="nav-link-icon"><span class="fas fa-chart-line"></span></span>
                                        <span class="nav-link-text ps-1">Analytics Dashboard</span>
                                        <span class="badge badge-soft-warning ms-auto">Soon</span>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan

                {{-- ==================== SUPPORT & HELP ==================== --}}
                <li class="nav-item">
                    <a class="nav-link dropdown-indicator {{ request()->routeIs('changelog.public') || request()->routeIs('feedback.*') ? '' : 'collapsed' }}" 
                        href="#support-help" role="button" data-bs-toggle="collapse" 
                        aria-expanded="{{ request()->routeIs('changelog.public') || request()->routeIs('feedback.*') ? 'true' : 'false' }}" 
                        aria-controls="support-help">
                        <div class="d-flex align-items-center">
                            <span class="nav-link-icon"><span class="fas fa-question-circle"></span></span>
                            <span class="nav-link-text ps-1">Support & Help</span>
                        </div>
                    </a>
                    <ul class="nav collapse {{ request()->routeIs('changelog.public') || request()->routeIs('feedback.*') ? 'show' : '' }}" id="support-help">
                        {{-- FAQ System --}}
                        <li class="nav-item">
                            <a class="nav-link disabled" href="#!" title="Coming Soon">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-icon"><span class="fas fa-info-circle"></span></span>
                                    <span class="nav-link-text ps-1">FAQ</span>
                                    <span class="badge badge-soft-warning ms-auto">Soon</span>
                                </div>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('changelog.public') ? 'active' : '' }}"
                                href="{{ route('changelog.public') }}">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-icon"><span class="fas fa-code-branch"></span></span>
                                    <span class="nav-link-text ps-1">Changelog</span>
                                </div>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('feedback.*') ? 'active' : '' }}"
                                href="{{ route('feedback.index') }}">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-icon"><span class="fas fa-comments"></span></span>
                                    <span class="nav-link-text ps-1">Feedback</span>
                                </div>
                            </a>
                        </li>
                    </ul>
                </li>
                @can('staff:view')
                    {{-- Future: Add more support links here --}}
                @endcan
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