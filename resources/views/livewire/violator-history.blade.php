<div>
    @if($hasHistory)
    <div class="card border border-warning mb-3 shadow-sm">
        <div class="card-header bg-warning-subtle text-warning-emphasis d-flex justify-content-between align-items-center py-2">
            <h6 class="mb-0"><i class="fas fa-history me-2"></i>Violator History Found</h6>
            <span class="badge bg-warning text-dark">{{ count($challans) + count($warnings) }} Records</span>
        </div>
        <div class="card-body p-0">
            <div class="row g-0">
                <!-- Warnings History -->
                <div class="col-md-6 border-end">
                    <div class="p-2 bg-light border-bottom fw-bold text-secondary fs--1 text-uppercase">Previous Warnings ({{ count($warnings) }})</div>
                    @if(count($warnings) > 0)
                        <div class="table-responsive scrollbar" style="max-height: 200px;">
                            <table class="table table-sm table-striped fs--1 mb-0">
                                <tbody>
                                    @foreach($warnings as $warning)
                                    <tr>
                                        <td class="ps-3">{{ $warning->created_at->format('d M y') }}</td>
                                        <td>{{ $warning->violation_name }}</td>
                                        <td class="pe-3 text-end"><span class="badge badge-soft-warning">Warning</span></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-3 text-center text-muted fs--1">No previous warnings.</div>
                    @endif
                </div>

                <!-- Challans History -->
                <div class="col-md-6">
                    <div class="p-2 bg-light border-bottom fw-bold text-secondary fs--1 text-uppercase">Previous Challans ({{ count($challans) }})</div>
                    @if(count($challans) > 0)
                        <div class="table-responsive scrollbar" style="max-height: 200px;">
                            <table class="table table-sm table-striped fs--1 mb-0">
                                <tbody>
                                    @foreach($challans as $challan)
                                    <tr>
                                        <td class="ps-3">{{ $challan->created_at->format('d M y') }}</td>
                                        <td>{{ $challan->violation_name }}</td>
                                        <td class="pe-3 text-end">
                                            @if($challan->status === 'paid' || $challan->payment_status === 'paid')
                                                <span class="badge badge-soft-success">Paid</span>
                                            @elseif($challan->status === 'released')
                                                <span class="badge badge-soft-info">Released</span>
                                            @else
                                                <span class="badge badge-soft-danger">Unpaid</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-3 text-center text-muted fs--1">No previous challans.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
