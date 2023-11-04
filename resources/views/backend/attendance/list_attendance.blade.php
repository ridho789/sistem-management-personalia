@extends('frontend.layout.main')
<!-- @section('title', 'List Attendance') -->
@section('content')
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Hi, welcome back!</h4>
                    <span class="ml-1">List Attendance</span>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Management</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">List Attendance</a></li>
                </ol>
            </div>
        </div>
        <!-- row -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">                           
                        <h4 class="card-title">List Attendance</h4>
                    </div>
                    @if (count($allattendance) > 0)
                        <div class="card-body">
                            <form action="{{ url('list-attendance-search') }}" method="GET">
                                @csrf
                                <label>Advance filter base on employee and date range</label>
                                <div class="form-group row">
                                    <div class="col-sm-3 mb-2">
                                        <input type="text" name="search_employee" id="search_employee" class="form-control" placeholder="Search employee...">
                                    </div>
                                    <div class="col-sm-3 mb-2">
                                        <select class="form-control" id="val_employee" name="id_karyawan">
                                            <option value="">Select a employee...</option>
                                            @foreach ($employee as $e)
                                                <option value="{{ $e->id_karyawan }}"  
                                                    {{ old('id_karyawan') == $e->id_karyawan ? 'selected' : '' }}>
                                                    {{ $e->nama_karyawan }} - {{ $e->id_card }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="hidden" id="start_date" name="start_date">
                                        <input type="hidden" id="end_date" name="end_date">
                                        <div class="input-group">
                                            <div class="mb-2" id="reportrange" style="background: #fff; cursor: pointer; 
                                                padding: 5.5px 10px; border: 1px solid #ccc;">
                                                <i class="fa fa-calendar"> </i>&nbsp;<span id="reportrange_display"> Display data based on date range </span> 
                                            </div>
                                            <div class="ml-2"></div>
                                            <div>
                                                <button type="submit" class="btn btn-primary">Search</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @endif
                    <div class="card-body">
                        @if (count($allattendance) > 0)
                            <div class="table-responsive">
                                <table class="table table-responsive-sm" id="data-table-attendance" 
                                    class="display" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Employee (C)</th>
                                            <th>ID Card</th>
                                            <th>Attendance Date</th>
                                            <th>Sign In</th>
                                            <th>Late</th>
                                            <th>Sign Out</th>
                                            <th>Information</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($allattendance as $at)
                                        <tr data-id="{{$at->id_attendance}}">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <a href="{{ url('form-employee-edit', ['id' => Crypt::encrypt($at->employee)]) }}">
                                                    {{ $nameEmployee[$at->employee] }}
                                                </a>
                                            </td>
                                            <td>{{$at->id_card}}</td>
                                            <td>{{ date('l, Y-m-d', strtotime($at->attendance_date)) }}</td>
                                            <td>{{$at->sign_in ?? '-'}}</td>
                                            @if ($at->sign_in_late)
                                                <td style="color: red;">{{$at->sign_in_late}}</td>
                                            @else
                                                <td>{{$at->sign_in_late ?? '-'}}</td>
                                            @endif
                                            @if ($at->information && $at->sign_out && (stristr($at->information, 'malam')))
                                                <td>{{$at->sign_out ?? '-'}} (+1)</td>
                                            @else
                                                <td>{{$at->sign_out ?? '-'}}</td>
                                            @endif
                                            <td>
                                                @if ($at->information && (stristr($at->information, 'leave') || stristr($at->information, 'other')))
                                                    <a href="{{ url('leave-request-edit', ['id' => Crypt::encrypt($at->id_data_cuti)]) }}">
                                                        {{$at->information}} (C)
                                                    </a>
                                                @else
                                                    {{$at->information ?? '-'}}
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach 
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="mt-3">
                                <span style="text-align: center;">
                                    <p>Sorry, no data that can be displayed yet.</p>
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('search_employee').addEventListener('input', function() {
            var searchValue = this.value.toLowerCase();
            var selectElement = document.getElementById('val_employee');
            var options = selectElement.getElementsByTagName('option');

            for (var i = 0; i < options.length; i++) {
                var optionText = options[i].text.toLowerCase();
                if (optionText.indexOf(searchValue) !== -1) {
                    options[i].style.display = 'block';
                } else {
                    options[i].style.display = 'none';
                }
            }
        });

        document.addEventListener("DOMContentLoaded", function() {
            var reportrange = document.getElementById('reportrange');
            var span = reportrange.querySelector('span');
            var startInput = document.getElementById('start_date');
            var endInput = document.getElementById('end_date');
            var reportrangeDisplay = document.getElementById('reportrange_display');

            var start = moment().subtract(29, 'days');
            var end = moment();

            function cb(start, end) {
                var rangeText = span.innerHTML = start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY');
                startInput.value = start.format('YYYY-MM-DD');
                endInput.value = end.format('YYYY-MM-DD');
                reportrangeDisplay.innerHTML = rangeText;

                // Menyimpan nilai dalam local storage
                localStorage.setItem('selected_range', rangeText);
            }

            function applyDateRangePicker() {
                new daterangepicker(reportrange, {
                    startDate: start,
                    endDate: end,
                    ranges: {
                        'Today': [moment(), moment()],
                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Last Month': [moment().subtract(1, 'month').startOf('month'), 
                            moment().subtract(1, 'month').endOf('month')]
                    }
                }, cb);
            }

            applyDateRangePicker();
        });
    </script>
@endsection