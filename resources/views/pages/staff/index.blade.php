@extends('layout.cms-layout')

@section('page-title','Staff Management')
@section('add-css')
<link rel="stylesheet" href="{{ asset('vendors/datatables.net-bs5/dataTables.bootstrap5.min.css') }}">
@endsection
@section('add-js-top')
<script src="{{ asset('vendors/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('vendors/datatables.net/dataTables.min.js') }}"></script>
<script src="{{ asset('vendors/datatables.net-bs5/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('vendors/datatables.net-fixedcolumns/dataTables.fixedColumns.min.js') }}"></script>
@endsection
@section('cms-main-content')
<div class="card mb-3">
  <div class="card-header">
    <div class="row flex-between-center">
      <div class="col-6 col-sm-auto d-flex align-items-center pe-0">
        <h5 class="fs-9 mb-0 text-nowrap py-2 py-xl-0">Users </h5>
      </div>
      <div class="col-6 col-sm-auto ms-auto text-end ps-0">
        <div class="d-none" id="table-number-pagination-actions">
          <div class="d-flex">
            <select class="form-select form-select-sm" aria-label="Bulk actions">
              <option selected="">Bulk actions</option>
              <option value="Refund">Refund</option>
              <option value="Delete">Delete</option>
              <option value="Archive">Archive</option>
            </select>
            <button class="btn btn-falcon-default btn-sm ms-2" type="button">Apply</button>
          </div>
        </div>
        <div id="table-number-pagination-replace-element">
          <button class="btn btn-falcon-default btn-sm" type="button"><span class="fas fa-plus" data-fa-transform="shrink-3 down-2"></span><span class="d-none d-sm-inline-block ms-1">New</span></button>
          <button class="btn btn-falcon-default btn-sm mx-2" type="button"><span class="fas fa-filter" data-fa-transform="shrink-3 down-2"></span><span class="d-none d-sm-inline-block ms-1">Filter</span></button>
          <button class="btn btn-falcon-default btn-sm" type="button"><span class="fas fa-external-link-alt" data-fa-transform="shrink-3 down-2"></span><span class="d-none d-sm-inline-block ms-1">Export</span></button>
        </div>
      </div>
    </div>
  </div>
  <div class="card-body p-0">
    <div class="falcon-data-table">
      <table class="table table-sm mb-0 data-table fs-10" data-datatables='{"searching":false,"responsive":false,"pageLength":8,"info":true,"lengthChange":true,"dom":"<&#39;row mx-1&#39;<&#39;col-sm-12 col-md-6&#39;l><&#39;col-sm-12 col-md-6&#39;f>><&#39;table-responsive scrollbar&#39;tr><&#39;row no-gutters px-1 pb-3 align-items-center justify-content-center&#39;<&#39;col-auto&#39; p>>","language":{"paginate":{"next":"<span class=\"fas fa-chevron-right\"></span>","previous":"<span class=\"fas fa-chevron-left\"></span>"}}}'>
        <thead class="bg-200">
          <tr>
            <th class="text-900 no-sort white-space-nowrap" data-orderable="false">
              <div class="form-check mb-0 d-flex align-items-center">
                <input class="form-check-input" id="checkbox-bulk-table-item-select" type="checkbox" data-bulk-select='{"body":"table-number-pagination-body","actions":"table-number-pagination-actions","replacedElement":"table-number-pagination-replace-element"}' />
              </div>
            </th>
            <th class="text-900 sort pe-1 align-middle white-space-nowrap text-center">Name</th>
            <th class="text-900 sort pe-1 align-middle white-space-nowrap text-center">CNIC</th>
            <th class="text-900 sort pe-1 align-middle white-space-nowrap text-center">Username</th>
            <th class="text-900 sort pe-1 align-middle white-space-nowrap text-center">Email</th>
            <th class="text-900 sort pe-1 align-middle white-space-nowrap text-center">Password</th>
            <th class="text-900 sort pe-1 align-middle white-space-nowrap text-center">Created</th>
            <th class="text-900 sort pe-1 align-middle white-space-nowrap text-center">Verified</th>
            <th class="text-900 sort pe-1 align-middle white-space-nowrap text-center">Status</th>
            <th class="text-900 no-sort pe-1 align-middle data-table-row-action"></th>
          </tr>
        </thead>
        <tbody class="list" id="table-number-pagination-body">
          <tr class="btn-reveal-trigger">
            <td class="align-middle" style="width: 28px;">
              <div class="form-check mb-0">
                <input class="form-check-input" type="checkbox" id="number-pagination-item-0" data-bulk-select-row="data-bulk-select-row" />
              </div>
            </td>
            <td class="align-middle white-space-nowrap fw-semi-bold name"><a href="../../app/e-commerce/customer-details.html">Sylvia Plath</a></td>
            <td class="align-middle white-space-nowrap number">3310221557297</td>
            <td class="align-middle white-space-nowrap product">neezaamee</td>
            <td class="align-middle white-space-nowrap email">neezaamee@gmail.com</td>
            <td class="align-middle password">ali123456789</td>
            <td class="align-middle date">10 Oct 2025</td>
            <td class="align-middle date">12 Oct 2025</td>
            <td class="align-middle text-center fs-9 white-space-nowrap payment"><span class="badge badge rounded-pill badge-subtle-success">Success<span class="ms-1 fas fa-check" data-fa-transform="shrink-2"></span></span>
            </td>

            <td class="align-middle white-space-nowrap text-end">
              <div class="dropstart font-sans-serif position-static d-inline-block">
                <button class="btn btn-link text-600 btn-sm dropdown-toggle btn-reveal float-end" type="button" id="dropdown-number-pagination-table-item-0" data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false" data-bs-reference="parent"><span class="fas fa-ellipsis-h fs-10"></span></button>
                <div class="dropdown-menu dropdown-menu-end border py-2" aria-labelledby="dropdown-number-pagination-table-item-0"><a class="dropdown-item" href="#!">View</a><a class="dropdown-item" href="#!">Edit</a><a class="dropdown-item" href="#!">Refund</a>
                  <div class="dropdown-divider"></div><a class="dropdown-item text-warning" href="#!">Archive</a><a class="dropdown-item text-danger" href="#!">Delete</a>
                </div>
              </div>
            </td>
          </tr>

        </tbody>
      </table>
    </div>
  </div>
</div>

@endsection
