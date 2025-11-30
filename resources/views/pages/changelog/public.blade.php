@extends('layout.cms-layout')
@section('page-title', 'Changelog - ')

@section('cms-main-content')
    <div class="container mt-4">
        <div class="mb-4">
            <h2>Changelog</h2>
            <p class="text-muted">All notable changes to Welfare CMS are documented here.</p>
        </div>

        @forelse($changelogs as $version => $entries)
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        Version {{ $version }}
                        <small class="float-end">{{ $entries->first()->release_date->format('F d, Y') }}</small>
                    </h4>
                </div>
                <div class="card-body">
                    @php
                        $groupedByType = $entries->groupBy('type');
                        $typeOrder = ['added', 'changed', 'fixed', 'deprecated', 'removed', 'security'];
                    @endphp

                    @foreach ($typeOrder as $type)
                        @if (isset($groupedByType[$type]))
                            <div class="mb-4">
                                <h5 class="text-{{ $groupedByType[$type]->first()->type_badge_color }}">
                                    <span class="fas fa-circle me-2" style="font-size: 0.5rem;"></span>
                                    {{ ucfirst($type) }}
                                </h5>
                                <ul class="list-unstyled ms-4">
                                    @foreach ($groupedByType[$type]->sortBy('order') as $entry)
                                        <li class="mb-2">
                                            <strong>{{ $entry->title }}</strong>
                                            @if ($entry->description)
                                                <p class="text-muted mb-0">{{ $entry->description }}</p>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        @empty
            <div class="card">
                <div class="card-body text-center py-5">
                    <span class="fas fa-file-alt fa-3x text-muted mb-3"></span>
                    <h5>No changelog entries yet</h5>
                    <p class="text-muted">Check back later for updates!</p>
                </div>
            </div>
        @endforelse
    </div>
@endsection
