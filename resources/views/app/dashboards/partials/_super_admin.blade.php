<div class="row g-3 mb-3">
    <div class="col-md-6 col-xxl-3">
        <x-falcon.statistic-card 
            value="{{ $totalUsers ?? 0 }}"
            label="Total Users"
            icon="fas fa-users"
            color="primary"
        />
    </div>
    <div class="col-md-6 col-xxl-3">
        <x-falcon.statistic-card 
            value="{{ $totalRoles ?? 0 }}"
            label="System Roles"
            icon="fas fa-user-shield"
            color="info"
        />
    </div>
    <div class="col-md-6 col-xxl-3">
        <x-falcon.statistic-card 
            value="{{ $totalLogs ?? 0 }}"
            label="Audit Logs"
            icon="fas fa-history"
            color="warning"
        />
    </div>
    <div class="col-md-6 col-xxl-3">
        <x-falcon.statistic-card 
            value="{{ $totalBackups ?? 0 }}"
            label="Security Backups"
            icon="fas fa-shield-alt"
            color="success"
        />
    </div>
</div>

<div class="row">
    <div class="col-12">
        <x-falcon.card title="System Administrator Overview" bodyClass="p-4" headerClass="bg-light">
            <p class="card-text text-800 fs--1">Welcome to the central control panel. As a Super Administrator, you have full access to manage system configuration, user access, and security audit trails. Use the sidebar to navigate to specific administrative modules.</p>
        </x-falcon.card>
    </div>
</div>
