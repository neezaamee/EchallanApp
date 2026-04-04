<div class="row g-3 mb-3">
    <div class="col-md-6 col-xxl-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="mb-1">{{ $totalUsers ?? 0 }}</h5>
                        <h6 class="text-700 mb-0">Total Users</h6>
                    </div>
                    <div class="fs-4 text-primary"><span class="fas fa-users"></span></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xxl-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="mb-1">{{ $totalRoles ?? 0 }}</h5>
                        <h6 class="text-700 mb-0">System Roles</h6>
                    </div>
                    <div class="fs-4 text-info"><span class="fas fa-user-shield"></span></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xxl-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="mb-1">{{ $totalLogs ?? 0 }}</h5>
                        <h6 class="text-700 mb-0">Audit Logs</h6>
                    </div>
                    <div class="fs-4 text-warning"><span class="fas fa-history"></span></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xxl-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="mb-1">{{ $totalBackups ?? 0 }}</h5>
                        <h6 class="text-700 mb-0">Security Backups</h6>
                    </div>
                    <div class="fs-4 text-success"><span class="fas fa-shield-alt"></span></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card bg-light">
            <div class="card-body">
                <h5 class="card-title">System Administrator Overview</h5>
                <p class="card-text">Welcome to the central control panel. As a Super Administrator, you have full access to manage system configuration, user access, and security audit trails. Use the sidebar to navigate to specific administrative modules.</p>
            </div>
        </div>
    </div>
</div>
