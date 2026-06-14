<div class="row g-3 mb-3">
    <div class="col-md-6 col-xxl-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="mb-1">{{ $todayChallans ?? 0 }}</h5>
                        <h6 class="text-700 mb-0">Today's Challans</h6>
                    </div>
                    <div class="fs-4 text-primary"><span class="fas fa-file-invoice"></span></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xxl-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="mb-1">{{ $totalChallans ?? 0 }}</h5>
                        <h6 class="text-700 mb-0">Lifetime Issued</h6>
                    </div>
                    <div class="fs-4 text-info"><span class="fas fa-history"></span></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xxl-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="mb-1">{{ $unpaidChallans ?? 0 }}</h5>
                        <h6 class="text-700 mb-0">Unpaid Challans</h6>
                    </div>
                    <div class="fs-4 text-warning"><span class="fas fa-clock"></span></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xxl-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="mb-1">Active</h5>
                        <h6 class="text-700 mb-0">Patrol Status</h6>
                    </div>
                    <div class="fs-4 text-success"><span class="fas fa-shield-alt"></span></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card bg-soft-info border-info">
            <div class="card-body">
                <h5 class="card-title text-info">Lifter Officer Operations</h5>
                <p class="card-text">Manage your lifter/impound traffic enforcement operations here. You can issue new challans and track payment statuses by using the buttons below or the sidebar menu.</p>
                <div class="mt-3">
                    <a href="{{ route('challans.create') }}" class="btn btn-info btn-sm">
                        <span class="fas fa-plus me-2"></span> New Challan
                    </a>
                    <a href="{{ route('challans.index') }}" class="btn btn-outline-info btn-sm ms-2">
                        <span class="fas fa-list me-2"></span> View All History
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
