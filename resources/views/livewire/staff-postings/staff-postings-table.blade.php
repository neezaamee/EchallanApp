<x-falcon.card title="Staff Postings & Transfers">
    <x-slot name="headerActions">
        <div class="d-flex align-items-center gap-2">
            <div class="position-relative">
                <input class="form-control form-control-sm shadow-none search" type="search" placeholder="Search Staff..." wire:model.live.debounce.500ms="search" />
                <span class="fas fa-search position-absolute top-50 end-0 translate-middle-y me-2 text-400"></span>
            </div>
            <select wire:model.live="perPage" class="form-select form-select-sm shadow-none w-auto">
                <option value="20">20 entries</option>
                <option value="50">50 entries</option>
                <option value="100">100 entries</option>
            </select>
            @can('staff-postings:create')
            <x-falcon.button href="{{ route('staff-postings.create') }}" variant="falcon-default" size="sm" icon="fas fa-plus">
                New Posting
            </x-falcon.button>
            @endcan
        </div>
    </x-slot>

    <x-falcon.table>
        <thead>
            <tr>
                <th class="ps-3 sort cursor-pointer" wire:click="sortBy('id')">
                    S.No
                    @if($sortField === 'id')
                        <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-1"></i>
                    @endif
                </th>
                <th>Staff Name</th>
                <th>CNIC</th>
                <th>Place of Posting</th>
                <th>Start Date</th>
                <th>Status</th>
                <th class="text-end pe-3">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($postings as $posting)
                <tr>
                    <td class="ps-3 text-muted fw-bold">{{ ($postings->currentPage() - 1) * $postings->perPage() + $loop->iteration }}</td>
                    <td>
                        @if($posting->staff)
                            <div class="fw-bold text-dark">{{ $posting->staff->fullName() }}</div>
                            <small class="text-muted">{{ $posting->staff->email }}</small>
                        @else
                            <div class="text-danger fw-bold italic fs--2">Staff data unavailable</div>
                        @endif
                    </td>
                    <td><span class="font-monospace text-muted">{{ $posting->staff->cnic ?? 'N/A' }}</span></td>
                    <td>
                        @php
                            $place = 'N/A';
                            if ($posting->medical_center_id) $place = $posting->medicalCenter->name ?? 'N/A';
                            elseif ($posting->dumping_point_id) $place = $posting->dumpingPoint->name ?? 'N/A';
                            elseif ($posting->sector_id) $place = $posting->sector->name ?? 'N/A';
                            elseif ($posting->circle_id) $place = $posting->circle->name ?? 'N/A';
                            elseif ($posting->city_id) $place = $posting->city->name ?? 'N/A';
                            elseif ($posting->province_id) $place = $posting->province->name ?? 'N/A';
                        @endphp
                        <span class="fw-semi-bold text-700">{{ $place }}</span>
                    </td>
                    <td class="text-nowrap">{{ \Carbon\Carbon::parse($posting->start_date)->format('d M, Y') }}</td>
                    <td>
                        <x-falcon.badge :variant="$posting->status === 'active' ? 'success' : 'secondary'">
                            {{ ucfirst($posting->status) }}
                        </x-falcon.badge>
                    </td>
                    <td class="text-end pe-3">
                        @can('staff-postings:create')
                        <x-falcon.button href="{{ route('staff-postings.create') }}?staff_id={{ $posting->staff_id }}" variant="link" class="p-0 text-primary" title="Transfer Staff" icon="fas fa-exchange-alt" />
                        @endcan
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center p-5 text-muted">
                        <i class="fas fa-map-marker-alt fa-2x mb-3 d-block opacity-25"></i>
                        No active postings found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </x-falcon.table>

    <x-slot name="footer">
        <x-falcon.pagination>
            {{ $postings->links() }}
        </x-falcon.pagination>
    </x-slot>
</x-falcon.card>
