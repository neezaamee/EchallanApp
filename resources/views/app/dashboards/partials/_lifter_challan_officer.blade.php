<div class="row g-3 mb-3">
    <div class="col-md-6 col-xxl-3">
        <x-falcon.statistic-card 
            value="{{ $todayChallans ?? 0 }}"
            label="Today's Challans"
            icon="fas fa-file-invoice"
            color="primary"
        />
    </div>
    <div class="col-md-6 col-xxl-3">
        <x-falcon.statistic-card 
            value="{{ $totalChallans ?? 0 }}"
            label="Lifetime Issued"
            icon="fas fa-history"
            color="info"
        />
    </div>
    <div class="col-md-6 col-xxl-3">
        <x-falcon.statistic-card 
            value="{{ $unpaidChallans ?? 0 }}"
            label="Unpaid Challans"
            icon="fas fa-clock"
            color="warning"
        />
    </div>
    <div class="col-md-6 col-xxl-3">
        <x-falcon.statistic-card 
            value="Active"
            label="Patrol Status"
            icon="fas fa-shield-alt"
            color="success"
        />
    </div>
</div>

<div class="row">
    <div class="col-12">
        <x-falcon.card title="Lifter Officer Operations" bodyClass="p-4" headerClass="bg-light">
            <p class="card-text text-800 fs--1">Manage your lifter/impound traffic enforcement operations here. You can issue new challans and track payment statuses by using the buttons below or the sidebar menu.</p>
            <div class="mt-3">
                <x-falcon.button href="{{ route('challans.create') }}" variant="info" icon="fas fa-plus">
                    New Challan
                </x-falcon.button>
                <x-falcon.button href="{{ route('challans.index') }}" variant="outline-info" class="ms-2" icon="fas fa-list">
                    View All History
                </x-falcon.button>
            </div>
        </x-falcon.card>
    </div>
</div>
