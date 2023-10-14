<!--**********************************
    Sidebar start
***********************************-->
<div class="quixnav">
    <div class="quixnav-scroll">
        <ul class="metismenu" id="menu">
            <li class="nav-label first">Main Menu</li>
            <li>
                <a class="has-arrow" href="javascript:void()" aria-expanded="false">
                    <i class="icon icon-world-2"></i><span class="nav-text">Dashboard</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="./index.html">Dashboard 1</a></li>
                    <li><a href="./index2.html">Dashboard 2</a></li>
                </ul>
            </li>
            <li>
                <a class="has-arrow" href="javascript:void()" aria-expanded="false">
                    <i class="icon icon-app-store"></i><span class="nav-text">Master Data</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="/category">Category</a></li>
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
                    <li><a href="/form-employee">Form Employee</a></li>
                </ul>
            </li>
            @php
                $dataCategory = DB::table('tbl_kategori')->get();
            @endphp 
            @if(isset($dataCategory))
                <li>
                    <a class="has-arrow" href="javascript:void()" aria-expanded="false">
                        <i class="icon icon-chart-bar-33"></i><span class="nav-text">Asset</span>
                    </a>
                    <ul aria-expanded="false">
                        <!-- <li>
                            <a class="has-arrow" href="javascript:void()" aria-expanded="false">List Asset</a>
                            <ul aria-expanded="false">
                                @foreach($dataCategory as $kategori)
                                    @php
                                        $subKategori = DB::table('tbl_sub_kategori')->where('id_kategori', $kategori->id_kategori)->get();
                                        $hasSubKategori = count($subKategori) > 0;
                                    @endphp
                                    <li>
                                        <a href="javascript:void(0)" class="{{ $hasSubKategori ? 'has-arrow' : '' }}">{{$kategori->nama_kategori}}</a>
                                        @if($hasSubKategori)
                                            <ul>
                                                @foreach($subKategori as $subItem)
                                                    <li><a href="javascript:void(0)">{{$subItem->nama_sub_kategori}}</a></li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </li> -->
                        <li><a href="/list-asset" aria-expanded="false">List Asset</a></li>
                        <li><a href="/form-asset" aria-expanded="false">Form Asset</a></li>
                    </ul>
                </li>
            @endif
            <li>
                <a class="has-arrow" href="javascript:void()" aria-expanded="false">
                    <i class="icon icon-time-3"></i><span class="nav-text">Attendance</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="#">List Attendance</a></li>
                    <li><a href="#">Form Attendance</a></li>
                    <li>
                        <a class="has-arrow" href="javascript:void()" aria-expanded="false">Leave</a>
                        <ul aria-expanded="false">
                            <li><a href="/leave-request">Leave Request</a></li>
                            <li><a href="/leaves-summary">Leaves Summary</a></li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li>
                <a href="javascript:void()" aria-expanded="false">
                    <i class="icon icon-payment"></i><span class="nav-text">Payroll</span>
                </a>
            </li>
        </ul>
    </div>
</div>
<!--**********************************
    Sidebar end
***********************************-->