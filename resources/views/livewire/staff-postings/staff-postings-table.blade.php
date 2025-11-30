<div>
    <div class="d-flex justify-content-between mb-3 align-items-center">
        <input type="text" class="form-control w-25" placeholder="Search by staff name, CNIC..."
            wire:model.live.debounce.500ms="search">

        <a href="{{ route('staff-postings.create') }}" class="btn btn-success">Transfer / Post Staff</a>
    </div>

    @if (session()->has('message'))
        <div class="alert alert-success mb-2">{{ session('message') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th wire:click="sortBy('id')" style="cursor: pointer;">
                        #
                        @if($sortField === 'id')
                            <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                        @endif
                    </th>
                    <th>Staff Name</th>
                    <th>CNIC</th>
                    <th>Place of Posting</th>
                    <th>Start Date</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($postings as $posting)
                    <tr>
                        <td>{{ $posting->id }}</td>
                        <td>
                            <div>
                                {{ $posting->staff->fullName() }}<br>
                                <small class="text-muted">{{ $posting->staff->email }}</small>
                            </div>
                        </td>
                        <td>{{ $posting->staff->cnic }}</td>
                        <td>
                            @php
                                $place = '—';
                                if ($posting->medical_center_id) {
                                    $place = $posting->medicalCenter->name ?? '—';
                                } elseif ($posting->dumping_point_id) {
                                    $place = $posting->dumpingPoint->name ?? '—';
                                } elseif ($posting->circle_id) {
                                    $place = $posting->circle->name ?? '—';
                                } elseif ($posting->city_id) {
                                    $place = $posting->city->name ?? '—';
                                } elseif ($posting->province_id) {
                                    $place = $posting->province->name ?? '—';
                                }
                            @endphp
                            {{ $place }}
                        </td>
                        <td>{{ \Carbon\Carbon::parse($posting->start_date)->format('d M Y') }}</td>
                        <td>
                            <span class="badge bg-{{ $posting->status === 'active' ? 'success' : 'secondary' }}">
                                {{ ucfirst($posting->status) }}
                            </span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('staff-postings.create') }}?staff_id={{ $posting->staff_id }}" 
                               class="btn btn-link p-0 text-primary" title="Transfer">
                                <i class="fas fa-exchange-alt"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">No active postings found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-3">
            {{ $postings->links() }}
        </div>
    </div>
</div>
