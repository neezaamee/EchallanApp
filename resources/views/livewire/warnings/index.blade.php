<x-falcon.card title="Warning History" bodyClass="p-0">
    <x-slot name="headerActions">
        <x-falcon.button href="{{ route('warnings.create') }}" variant="falcon-default" size="sm" icon="fas fa-plus">
            Issue New Warning
        </x-falcon.button>
    </x-slot>

    {{-- Filter Panel --}}
    <x-falcon.filter-panel>
        <div class="col-sm-auto">
            <x-falcon.search-box placeholder="Search CNIC, Name, Vehicle..." wire:model.live.debounce.300ms="search" />
        </div>
    </x-falcon.filter-panel>

    @if (session()->has('message'))
        <x-falcon.alert variant="success">
            {{ session('message') }}
        </x-falcon.alert>
    @endif

    {{-- Table Component --}}
    <x-falcon.table>
        <thead class="bg-200 text-900">
            <tr>
                <th class="ps-3">Date</th>
                <th>Violator Info</th>
                <th>Vehicle</th>
                <th>Violation</th>
                <th class="pe-3">Officer</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($warnings as $warning)
                <tr>
                    <td class="text-muted ps-3 fs--2">{{ $warning->created_at->format('d M, Y H:i') }}</td>
                    <td>
                        <div class="fw-bold text-dark fs--1">{{ $warning->violator_name }}</div>
                        <div class="text-muted fs--2">{{ $warning->violator_cnic }}</div>
                    </td>
                    <td>
                        <div class="text-dark fw-semi-bold">{{ strtoupper($warning->vehicle_number) }}</div>
                        <div class="text-muted fs--2">{{ ucfirst($warning->vehicle_type) }}</div>
                    </td>
                    <td class="text-800">{{ $warning->violation_name }}</td>
                    <td class="text-muted pe-3">{{ $warning->officer->name ?? 'N/A' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center p-4 text-muted">No warnings found matching your criteria.</td>
                </tr>
            @endforelse
        </tbody>
    </x-falcon.table>

    <x-slot name="footer">
        <x-falcon.pagination>
            {{ $warnings->links() }}
        </x-falcon.pagination>
    </x-slot>
</x-falcon.card>
