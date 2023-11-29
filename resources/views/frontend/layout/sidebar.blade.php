<!--**********************************
    Sidebar start
***********************************-->
<div class="quixnav">
    <div class="quixnav-scroll">
        <ul class="metismenu" id="menu">
            @if (Auth::check())
                @if (Auth::user()->level == 1)
                    <li class="nav-label first">Main Menu</li>
                    <li>
                        <a href="/dashboard" aria-expanded="false">
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
                            <li><a href="/company">Company</a></li>
                            <li><a href="/devision">Division</a></li>
                            <li><a href="/position">Position</a></li>
                            <li><a href="/employee-status">Employee Status</a></li>
                            <li><a href="/type-leave">Type Leave</a></li>
                        </ul>
                    </li> 

                    <li class="nav-label">Management</li>
                    <li>
                        <a class="has-arrow" href="javascript:void()" aria-expanded="false">
                            <i class="icon-user"></i><span class="nav-text">Employee</span>
                        </a>
                        <ul aria-expanded="false">
                            <li><a href="/list-employee">List Employee</a></li>
                            <li><a href="/list-inactive-employee">List Inactive Employee</a></li>
                            <li><a href="/form-employee">Form Employee</a></li>
                        </ul>
                    </li>
                    <!-- di hide karena fitur aset akan dipisah -->
                    <!-- <li>
                        <a class="has-arrow" href="javascript:void()" aria-expanded="false">
                            <i class="icon icon-chart-bar-33"></i><span class="nav-text">Asset</span>
                        </a>
                        <ul aria-expanded="false">
                            <li><a href="/list-asset" aria-expanded="false">List Asset</a></li>
                            <li><a href="/form-asset" aria-expanded="false">Form Asset</a></li>
                        </ul>
                    </li> -->
                    <li>
                        <a class="has-arrow" href="javascript:void()" aria-expanded="false">
                            <i class="icon icon-attach-87"></i><span class="nav-text">Leave</span>
                        </a>
                        <ul aria-expanded="false">
                            <li><a href="/allocation-request">Allocation Requests</a></li>
                            <li><a href="/leave-request">Leave Request</a></li>
                            <li><a href="/leaves-summary">Leaves Summary</a></li>
                            <li><a href="/national-holiday-leave">National Holiday Leave</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="/list-attendance" aria-expanded="false">
                            <i class="icon icon-time"></i><span class="nav-text">Attendance</span>
                        </a>
                    </li>
                    <li>
                        <a href="/data-payroll" aria-expanded="false">
                            <i class="icon icon-payment"></i><span class="nav-text">Payroll</span>
                        </a>
                    </li>
                    <li>
                        <a class="has-arrow" href="javascript:void()" aria-expanded="false">
                            <i class="icon icon-book-open-2"></i><span class="nav-text">Daily Report</span>
                        </a>
                        <ul aria-expanded="false">
                            <li><a href="/list-daily-report">List Daily Report</a></li>
                            <li><a href="/form-daily-report">Form Daily Report</a></li>
                        </ul>
                    </li>
                    
                @elseif (Auth::user()->level == 2)
                    <li class="nav-label">Management</li>
                    <li>
                        <a class="has-arrow" href="javascript:void()" aria-expanded="false">
                            <i class="icon-user"></i><span class="nav-text">Employee</span>
                        </a>
                        <ul aria-expanded="false">
                            <li><a href="/list-employee">List Employee</a></li>
                            <li><a href="/list-inactive-employee">List Inactive Employee</a></li>
                            <li><a href="/form-employee">Form Employee</a></li>
                        </ul>
                    </li>
                    <li>
                        <a class="has-arrow" href="javascript:void()" aria-expanded="false">
                            <i class="icon icon-attach-87"></i><span class="nav-text">Leave</span>
                        </a>
                        <ul aria-expanded="false">
                            <li><a href="/allocation-request">Allocation Requests</a></li>
                            <li><a href="/leave-request">Leave Request</a></li>
                            <li><a href="/leaves-summary">Leaves Summary</a></li>
                        </ul>
                    </li>
                @endif
            @endif
        </ul>
    </div>
</div>
<!--**********************************
    Sidebar end
***********************************-->