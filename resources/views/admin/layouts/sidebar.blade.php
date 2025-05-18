@php
    $user = Auth::user();
@endphp
<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!-- User details -->
        {{-- <div class="mt-3 text-center user-profile">
            <div class="">
                <img src="{{asset('backend/assets/images/users/avatar-1.jpg')}}" alt="" class="avatar-md rounded-circle">
            </div>
            <div class="mt-3">
                <h4 class="mb-1 font-size-16">Julia Hudda</h4>
                <span class="text-muted"><i class="align-middle ri-record-circle-line font-size-14 text-success"></i> Online</span>
            </div>
        </div> --}}

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title">Menu</li>

                {{-- <li>
                    <a href="{{ route('dashboard') }}" class="waves-effect">
                        <i class="ri-dashboard-line"></i></span>
                        <span>Dashboard</span>
                    </a>
                </li> --}}
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="ri-database-line"></i><span>Master Data</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('chart-of-account.index') }}">Chart Of Account</a></li>
                        <li><a href="{{ route('allowance.index') }}">Allowance</a></li>
                        <li><a href="{{ route('deduction.index') }}">Deduction</a></li>
                        <li><a href="{{ route('employee.index') }}">Employee</a></li>
                    </ul>
                </li>
                <li>
                    <a href="{{ route('employee-salary-period.index') }}" class="waves-effect">
                        <i class="ri-currency-line"></i></span>
                        <span>Salary Period</span>
                    </a>
                </li>
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="ri-database-line"></i><span>Report</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('salary-period-report.index') }}">Salary Period</a></li>
                        <li><a href="{{ route('balance-sheet.index') }}">Balance Sheet</a></li>
                        <li><a href="{{ route('profit-loss.index') }}">Profit Loss</a></li>
                        <li><a href="{{ route('general-ledger.index') }}">General Ledger</a></li>
                        <li><a href="{{ route('trial-balance.index') }}">Trial Balance</a></li>
                    </ul>
                </li>
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
