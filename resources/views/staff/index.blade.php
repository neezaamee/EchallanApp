@extends('layouts.app')

@section('page-title', 'Staff List (q) - ')

@section('cms-main-content')
<div class="container-fluid mt-2">
    {{-- Page Header --}}
    <x-falcon.page-header title="Staff List (Queue Management)" :breadcrumbs="['Home' => route('dashboard'), 'Staff (q)' => '']">
        <x-slot name="actions">
            @can('staff:create')
                <x-falcon.button href="{{ route('staff.create') }}" variant="primary" icon="fas fa-plus">
                    New Staff
                </x-falcon.button>
            @endcan
        </x-slot>
    </x-falcon.page-header>

    {{-- Main Content Card --}}
    <x-falcon.card title="Staff Records" description="Manage all departmental staff postings and active status indicators.">
        
        <x-falcon.table>
            <thead class="bg-200 text-900">
                <tr>
                    <th class="ps-3" style="width: 50px;">S.No</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Role</th>
                    <th>Rank</th>
                    <th>City</th>
                    <th>Circle</th>
                    <th>Sector</th>
                    <th>Dumping Point</th>
                    <th>Medical Center</th>
                    <th>Status</th>
                    <th class="text-end pe-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($staff as $s)
                    <tr>
                        <td class="text-muted ps-3">{{ $loop->iteration }}</td>
                        <td class="fw-semi-bold text-dark">{{ $s->first_name }} {{ $s->last_name }}</td>
                        <td>{{ $s->email }}</td>
                        <td>{{ $s->phone }}</td>
                        <td>
                            <x-falcon.badge variant="primary">
                                {{ optional($s->user?->getRoleNames()->first())->toString() ?? '—' }}
                            </x-falcon.badge>
                        </td>
                        <td>{{ $s->rank?->name ?? '—' }}</td>
                        <td>{{ optional($s->activePosting?->city)->name ?? '—' }}</td>
                        <td>{{ optional($s->activePosting?->circle)->name ?? '—' }}</td>
                        <td>{{ optional($s->activePosting?->sector)->name ?? '—' }}</td>
                        <td>{{ optional($s->activePosting?->dumpingPoint)->name ?? '—' }}</td>
                        <td>{{ optional($s->activePosting?->medicalCenter)->name ?? '—' }}</td>
                        <td>
                            <x-falcon.badge :variant="$s->status === 'active' ? 'success' : 'secondary'">
                                {{ ucfirst($s->status) }}
                            </x-falcon.badge>
                        </td>
                        <td class="text-end pe-3">
                            <x-falcon.action-dropdown 
                                :editRoute="route('staff.edit', $s->id)"
                                :deleteRoute="route('staff.destroy', $s->id)"
                            />
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="13" class="text-center p-4 text-muted">No staff records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </x-falcon.table>

    </x-falcon.card>
</div>
@endsection
