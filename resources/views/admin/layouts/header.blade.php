<header id="page-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <a href="{{ route('employee-salary-period.index') }}" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="{{ asset('logo/logo.jpg') }}" alt="logo-sm" height="100">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('logo/logo.jpg') }}" alt="logo-dark" height="20">
                    </span>
                </a>

                <a href="{{ route('employee-salary-period.index') }}" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="{{ asset('logo/logo.jpg') }}" alt="logo-sm-light" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('logo/logo.jpg') }}" alt="logo-light" height="70">
                    </span>
                </a>
            </div>

            <button type="button" class="px-3 btn btn-sm font-size-24 header-item waves-effect" id="vertical-menu-btn">
                <i class="align-middle ri-menu-2-line"></i>
            </button>

            <!-- App Search-->
            {{-- <form class="app-search d-none d-lg-block">
                <div class="position-relative">
                    <input type="text" class="form-control" placeholder="Search...">
                    <span class="ri-search-line"></span>
                </div>
            </form> --}}
        </div>

        <div class="d-flex">
            <div class="dropdown d-none d-lg-inline-block ms-1">
                <button type="button" class="btn header-item noti-icon waves-effect" data-toggle="fullscreen">
                    <i class="ri-fullscreen-line"></i>
                </button>
            </div>

            @php
                $id = Auth::user()->id;
                $adminData = App\Models\User::find($id);
            @endphp

            <div class="dropdown d-inline-block user-dropdown">
                <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="d-none d-xl-inline-block ms-1">{{ $adminData->email }}</span>
                    <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                    <!-- item-->
                    <a class="dropdown-item" href="{{ route('change.password') }}"><i
                            class="align-middle ri-wallet-2-line me-1"></i> Change Password</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-danger" href="{{ route('admin.logout') }}"><i
                            class="align-middle ri-shut-down-line me-1 text-danger"></i> Logout</a>
                </div>
            </div>
        </div>
    </div>
</header>
