<!--**********************************
    Sidebar start
***********************************-->
<div class="quixnav">
    <div class="quixnav-scroll">
        <ul class="metismenu" id="menu">
            @if (Auth::check())
                @if (Auth::user()->level == 0)
                    <li class="nav-label first">Main Menu</li>
                    <li>
                        <a href="{{ url('/dashboard') }}" aria-expanded="false">
                            <i class="icon icon-world-2"></i><span class="nav-text">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a class="has-arrow" href="javascript:void()" aria-expanded="false">
                            <i class="icon icon-app-store"></i><span class="nav-text">Master Data</span>
                        </a>
                        <ul aria-expanded="false">
                            <!-- di hide karena fitur aset akan dipisah -->
                            <!-- <li><a href="/category">Category</a></li> -->
                            <li><a href="{{ url('/company') }}">Company</a></li>
                            <li><a href="{{ url('/devision') }}">Division</a></li>
                            <li><a href="{{ url('/position') }}">Position</a></li>
                            <li><a href="{{ url('/employee-status') }}">Employee Status</a></li>
                            <li><a href="{{ url('/type-leave') }}">Type Leave</a></li>
                            <li><a href="{{ url('/users') }}">Users</a></li>
                        </ul>
                    </li> 

                    <li class="nav-label">Management</li>
                    <li>
                        <a class="has-arrow" href="javascript:void()" aria-expanded="false">
                            <i class="icon-user"></i><span class="nav-text">Employee</span>
                        </a>
                        <ul aria-expanded="false">
                            <li><a href="{{ url('/list-employee') }}">List Employee</a></li>
                            <li><a href="{{ url('/list-inactive-employee') }}">List Inactive Employee</a></li>
                            <!-- <li><a href="{{ url('/form-employee') }}">Form Employee</a></li> -->
                        </ul>
                    </li>
                    <!-- di hide karena fitur aset akan dipisah -->
                    <!-- <li>
                        <a class="has-arrow" href="javascript:void()" aria-expanded="false">
                            <i class="icon icon-chart-bar-33"></i><span class="nav-text">Asset</span>
                        </a>
                        <ul aria-expanded="false">
                            <li><a href="{{ url('/list-asset') }}" aria-expanded="false">List Asset</a></li>
                            <li><a href="{{ url('/form-asset') }}" aria-expanded="false">Form Asset</a></li>
                        </ul>
                    </li> -->
                    <li>
                        <a class="has-arrow" href="javascript:void()" aria-expanded="false">
                            <i class="icon icon-attach-87"></i><span class="nav-text">Leave</span>
                        </a>
                        <ul aria-expanded="false">
                            <li><a href="{{ url('/allocation-request') }}">Allocation Requests</a></li>
                            <!-- <li><a href="{{ url('/leave-request') }}">Leave Request</a></li> -->
                            <li><a href="{{ url('/leaves-summary') }}">Leaves Summary</a></li>
                            <li><a href="{{ url('/collective-leave') }}">Collective Leave</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="{{ url('/list-attendance') }}" aria-expanded="false">
                            <i class="icon icon-time"></i><span class="nav-text">Attendance</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/data-payroll') }}" aria-expanded="false">
                            <i class="icon icon-payment"></i><span class="nav-text">Payroll</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/list-daily-report') }}" aria-expanded="false">
                            <i class="icon icon-book-open-2"></i><span class="nav-text">Daily Report</span>
                        </a>
                    </li>

                @elseif (Auth::user()->level == 1)
                    <li class="nav-label first">Main Menu</li>
                    <li>
                        <a href="{{ url('/dashboard') }}" aria-expanded="false">
                            <i class="icon icon-world-2"></i><span class="nav-text">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a class="has-arrow" href="javascript:void()" aria-expanded="false">
                            <i class="icon icon-app-store"></i><span class="nav-text">Master Data</span>
                        </a>
                        <ul aria-expanded="false">
                            <!-- di hide karena fitur aset akan dipisah -->
                            <!-- <li><a href="/category">Category</a></li> -->
                            <li><a href="{{ url('/company') }}">Company</a></li>
                            <li><a href="{{ url('/devision') }}">Division</a></li>
                            <li><a href="{{ url('/position') }}">Position</a></li>
                            <li><a href="{{ url('/employee-status') }}">Employee Status</a></li>
                            <li><a href="{{ url('/type-leave') }}">Type Leave</a></li>
                        </ul>
                    </li> 

                    <li class="nav-label">Management</li>
                    <li>
                        <a class="has-arrow" href="javascript:void()" aria-expanded="false">
                            <i class="icon-user"></i><span class="nav-text">Employee</span>
                        </a>
                        <ul aria-expanded="false">
                            <li><a href="{{ url('/list-employee') }}">List Employee</a></li>
                            <li><a href="{{ url('/list-inactive-employee') }}">List Inactive Employee</a></li>
                            <!-- <li><a href="{{ url('/form-employee') }}">Form Employee</a></li> -->
                        </ul>
                    </li>
                    <!-- di hide karena fitur aset akan dipisah -->
                    <!-- <li>
                        <a class="has-arrow" href="javascript:void()" aria-expanded="false">
                            <i class="icon icon-chart-bar-33"></i><span class="nav-text">Asset</span>
                        </a>
                        <ul aria-expanded="false">
                            <li><a href="{{ url('/list-asset') }}" aria-expanded="false">List Asset</a></li>
                            <li><a href="{{ url('/form-asset') }}" aria-expanded="false">Form Asset</a></li>
                        </ul>
                    </li> -->
                    <li>
                        <a class="has-arrow" href="javascript:void()" aria-expanded="false">
                            <i class="icon icon-attach-87"></i><span class="nav-text">Leave</span>
                        </a>
                        <ul aria-expanded="false">
                            <li><a href="{{ url('/allocation-request') }}">Allocation Requests</a></li>
                            <!-- <li><a href="{{ url('/leave-request') }}">Leave Request</a></li> -->
                            <li><a href="{{ url('/leaves-summary') }}">Leaves Summary</a></li>
                            <li><a href="{{ url('/collective-leave') }}">Collective Leave</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="{{ url('/list-attendance') }}" aria-expanded="false">
                            <i class="icon icon-time"></i><span class="nav-text">Attendance</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/data-payroll') }}" aria-expanded="false">
                            <i class="icon icon-payment"></i><span class="nav-text">Payroll</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/list-daily-report') }}" aria-expanded="false">
                            <i class="icon icon-book-open-2"></i><span class="nav-text">Daily Report</span>
                        </a>
                    </li>
                    
                @elseif (Auth::user()->level == 2)
                    <li class="nav-label">Management</li>
                    <li>
                        <a href="{{ url('/leaves-summary') }}" aria-expanded="false">
                            <i class="icon icon-attach-87"></i><span class="nav-text">Leave</span>
                        </a>
                    </li>

                @elseif (Auth::user()->level == 3)
                    <li class="nav-label">Management</li>
                    <li>
                        <a href="{{ url('/list-daily-report') }}" aria-expanded="false">
                            <i class="icon icon-book-open-2"></i><span class="nav-text">Daily Report</span>
                        </a>
                    </li>
                @endif
            @endif
        </ul>
    </div>
</div>
<!--**********************************
    Sidebar end
***********************************-->