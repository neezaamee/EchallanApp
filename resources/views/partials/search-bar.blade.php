<ul class="navbar-nav align-items-center d-none d-lg-block">
    <li class="nav-item">
        <div class="search-box" data-list='{"valueNames":["title"]}'>
            <form class="position-relative" data-bs-toggle="search" data-bs-display="static">
                <input class="form-control search-input fuzzy-search" type="search" placeholder="Search..." aria-label="Search" />
                <span class="fas fa-search search-box-icon"></span>
            </form>
            <div class="btn-close-falcon-container position-absolute end-0 top-50 translate-middle shadow-none" data-bs-dismiss="search">
                <button class="btn btn-link btn-close-falcon p-0" aria-label="Close"></button>
            </div>
            <div class="dropdown-menu border font-base start-0 mt-2 py-0 overflow-hidden w-100">
                <div class="scrollbar list py-3" style="max-height: 24rem;">
                    <h6 class="dropdown-header fw-medium text-uppercase px-x1 fs-11 pt-0 pb-2">Recently Browsed</h6>
                    <a class="dropdown-item fs-10 px-x1 py-1 hover-primary" href="{{ route('home') }}">
                        <div class="d-flex align-items-center">
                            <span class="fas fa-circle me-2 text-300 fs-11"></span>
                            <div class="fw-normal title">Pages <span class="fas fa-chevron-right mx-1 text-500 fs-11" data-fa-transform="shrink-2"></span> Events</div>
                        </div>
                    </a>
                    <!-- Add more search results -->

                    <hr class="text-200 dark__text-900" />
                    <h6 class="dropdown-header fw-medium text-uppercase px-x1 fs-11 pt-0 pb-2">Suggested Filter</h6>
                    <a class="dropdown-item px-x1 py-1 fs-9" href="{{ route('home') }}">
                        <div class="d-flex align-items-center">
                            <span class="badge fw-medium text-decoration-none me-2 badge-subtle-warning">customers:</span>
                            <div class="flex-1 fs-10 title">All customers list</div>
                        </div>
                    </a>
                    <!-- Add more suggested filters -->

                    <hr class="text-200 dark__text-900" />
                    <h6 class="dropdown-header fw-medium text-uppercase px-x1 fs-11 pt-0 pb-2">Files</h6>
                    <!-- Add file results -->

                    <hr class="text-200 dark__text-900" />
                    <h6 class="dropdown-header fw-medium text-uppercase px-x1 fs-11 pt-0 pb-2">Members</h6>
                    <!-- Add member results -->
                </div>
                <div class="text-center mt-n3">
                    <p class="fallback fw-bold fs-8 d-none">No Result Found.</p>
                </div>
            </div>
        </div>
    </li>
</ul>
