@extends('frontend.layout.main')
<!-- @section('title', 'Data Payroll') -->
@section('content')
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Hi, welcome back!</h4>
                    <span class="ml-1">Data Payroll</span>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Management</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Data Payroll</a></li>
                </ol>
            </div>
        </div>
        <!-- row -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">                           
                        <h4 class="card-title">Check Payroll</h4>
                    </div>
                    <div class="card-body">
                        <form class="form-check-payroll" action="{{ url('form-check-payroll') }}" method="GET" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Employee</label>
                                <div class="col-sm-5 mb-2">
                                    <div class="input-group">
                                        <input type="text" id="search_employee" class="form-control" placeholder="Search for an employee..." 
                                        oninput="filterEmployees(this.value)">
                                    </div>
                                    <div id="employee_list" style="display: none;">
                                        <div id="scrollable_employee_list" style="max-height: 125px; overflow-y: auto;">
                                            <ul id="filtered_employee_list">
                                                <!-- Daftar karyawan yang sesuai dengan hasil pencarian akan ditampilkan di sini -->
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-5">
                                    <div class="input-group">
                                        <select class="form-control" id="val_employee" name="id_karyawan" required>
                                            <option value="">Select an employee...</option>
                                            @foreach ($employee as $e)
                                                <option value="{{ $e->id_karyawan }}" 
                                                    {{ old('id_karyawan') == $e->id_karyawan ? 'selected' : '' }}>
                                                    {{ $e->nama_karyawan }} - {{ $e->id_card }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <input type="hidden" id="start_date" name="start_date">
                                <input type="hidden" id="end_date" name="end_date">

                                <label class="col-sm-2 col-form-label">Range Date</label>
                                <div class="col-sm-10">
                                    <div class="input-group">
                                        <div class="col-sm-4" id="reportrange" style="background: #fff; cursor: pointer; 
                                            padding: 5.5px 10px; border: 1px solid #ccc;">
                                            <i class="fa fa-calendar"> </i>&nbsp;
                                            <span id="reportrange_display"> Display data based on date range </span> 
                                            <!-- <i class="fa fa-caret-down"></i> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary" id="checkButton" disabled>Check</button>
                        </form>
                    </div>
                </div>
                @if($selectEmployee)
                    @if ($errorInfo)
                        <div class="card">
                            <div class="card-header"></div>
                            <div class="card-body">
                                <span style="text-align: center;">
                                    <p>{{ $rangeDate }}
                                    <br>{{ $errorInfo }}</p>
                                </span>
                            </div>
                        </div>
                    @else
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Basic Information</h4>
                            </div>
                            <div class="card-body">
                                <table class="table table-responsive-sm" id="data-table-payslip">
                                    <tr>
                                        <td>Employee</td>
                                        <td style="text-align: right;">:</td>
                                        <td>{{ $selectEmployee->nama_karyawan }}</td>
                                    </tr>
                                    <tr>
                                        <td>ID Card</td>
                                        <td style="text-align: right;">:</td>
                                        <td>{{ $selectEmployee->id_card }}</td>
                                    </tr>
                                    <tr>
                                        <td>Division</td>
                                        <td style="text-align: right;">:</td>
                                        <td>{{ $division->nama_divisi }}</td>
                                    </tr>
                                    <tr>
                                        <td>Position</td>
                                        <td style="text-align: right;">:</td>
                                        <td>{{ $position->nama_jabatan }}</td>
                                    </tr>
                                    <tr>
                                        <td>Status</td>
                                        <td style="text-align: right;">:</td>
                                        <td>{{ $statusEmployee->nama_status }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        @if ($payrollData)
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Payslip { {{ $rangeDate }} }</h4>
                                </div>
                                <div class="card-body">
                                    <form class="form-payroll" action="{{ url('form-payroll-update') }}" 
                                        method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ isset($payrollId) ? $payrollId : '' }}"/>
                                        <input type="hidden" id="defaultAbsentDay" value="{{ $payrollData['jumlah_hari_tidak_masuk'] }}" />
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Working Days</label>
                                            <div class="col-sm-10">
                                                <div class="input-group-prepend">
                                                    <input type="number" name="working_days" id="working_days" class="form-control" 
                                                    value="{{ old('working_days', 
                                                        optional($checkPayroll->first())->jumlah_hari_kerja ?? $dataPayroll->jumlah_hari_kerja) }}"
                                                    max="{{ strtolower($statusEmployee->nama_status) == 'harian' ? 7 : 26 }}" readonly>

                                                    @if (strtolower($statusEmployee->nama_status) == 'harian')
                                                        <div class="input-group-text">/ 7</div>
                                                    @else
                                                        <div class="input-group-text">/ 26</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label"></label>
                                            <div class="col-sm-2">
                                                <label class="col-form-label">Number of Sick Days</label>
                                                <div>
                                                    <input type="number" class="form-control" id="sick_days" name="sick_days" 
                                                    value="{{ old('sick_days', 
                                                        optional($checkPayroll->first())->jumlah_hari_sakit ?? $dataPayroll->jumlah_hari_sakit) }}">
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <label class="col-form-label">Number of Absent Days</label>
                                                <div>
                                                    <input type="number" class="form-control" id="absent_days" name="absent_days" 
                                                    value="{{ old('absent_days', 
                                                        optional($checkPayroll->first())->jumlah_hari_tidak_masuk ?? $dataPayroll->jumlah_hari_tidak_masuk) }}">
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <label class="col-form-label">Number of Legal Leave Days</label>
                                                <div>
                                                    <input type="number" class="form-control" id="leave_days" name="leave_days" 
                                                    value="{{ old('leave_days', 
                                                        optional($checkPayroll->first())->jumlah_hari_cuti_resmi ?? $dataPayroll->jumlah_hari_cuti_resmi ?? '0') }}">
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <label class="col-form-label">Number of Late Days</label>
                                                <div>
                                                    <input type="number" class="form-control" id="late_days" name="late_days" 
                                                    value="{{ old('late_days', $payrollData['jumlah_hari_telat']) }}" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label"></label>
                                            <div class="col-sm-10">
                                                <div id="accordion-one" class="accordion">
                                                    <div class="accordion__item">
                                                        <div class="accordion__header" data-toggle="collapse" data-target="#default_collapseOne">
                                                            <span class="accordion__header--text">Reveal the detailed list of data</span>
                                                            <span class="accordion__header--indicator"></span>
                                                        </div>
                                                        <div id="default_collapseOne" class="collapse accordion__body" data-parent="#accordion-one">
                                                            <div class="accordion__body--text">
                                                            @if (count($dataLeave) > 0 || count($lateAttendance) > 0 || count($missingDates) > 0)
                                                                @if (count($dataLeave) > 0)
                                                                    <label class="col-form-label" style="color: black;">Number of Leave Data</label>
                                                                    <table class="table table-responsive-sm" id="data-table-leave">
                                                                        <thead>
                                                                            <th>Date</th>
                                                                            <th>Type (C)</th>
                                                                        </thead>
                                                                        <tbody>
                                                                            @foreach ($dataLeave as $leave)
                                                                                <tr>
                                                                                    <td>{{ date('l, Y-m-d', strtotime($leave->mulai_cuti)) }}</td>
                                                                                    <td>
                                                                                        <a href="{{ url('leave-request-edit', 
                                                                                            ['id' => Crypt::encrypt($leave->id_data_cuti)]) }}">
                                                                                        {{ $typeleave[$leave->id_tipe_cuti] }}</a>
                                                                                    </td>
                                                                                </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                @endif

                                                                @if (count($lateAttendance) > 0)
                                                                    <label class="col-form-label" style="color: black;">Number of Late Days</label>
                                                                    <table class="table table-responsive-sm" id="data-table-late">
                                                                        <thead>
                                                                            <th>Date</th>
                                                                            <th>Late</th>
                                                                        </thead>
                                                                        <tbody>
                                                                            @foreach ($lateAttendance as $late)
                                                                                <tr>
                                                                                    <td>{{ date('l, Y-m-d', strtotime($late->attendance_date)) }}</td>
                                                                                    <td>{{ $late->sign_in_late }}</td>
                                                                                </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                @endif

                                                                @if (count($missingDates) > 0)
                                                                    <label class="col-form-label" style="color: black;">Number of Missing Days</label>
                                                                    <table class="table table-responsive-sm" id="data-table-missing">
                                                                        <thead>
                                                                            <th>Date</th>
                                                                            <th>Status</th>
                                                                        </thead>
                                                                        <tbody>
                                                                            @foreach ($missingDates as $date)
                                                                                <tr>
                                                                                    <td>{{ date('l, Y-m-d', strtotime($date)) }}</td>
                                                                                    <td>Absent</td>
                                                                                </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                @endif

                                                            @else
                                                                <label class="col-form-label" style="color: black;">No viewable data</label>
                                                            @endif

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Basic Salary</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="basic_salary" id="basic_salary" class="form-control" 
                                                value="{{ old('basic_salary', $payrollData['gaji_pokok']) }}" readonly>
                                            </div>
                                        </div>
                                        @if(strtolower($statusEmployee->nama_status) != 'harian')
                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label">Position Allowance</label>
                                                <div class="col-sm-10">
                                                    <input type="text" name="position_allowance" id="position_allowance" class="form-control" 
                                                    value="{{ old('position_allowance', $payrollData['tunjangan_jabatan']) }}" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label"></label>
                                                <div class="col-sm-5">
                                                    <label>Absent Deductions</label>
                                                    <input type="text" name="absent_cuts" id="absent_cuts" class="form-control" 
                                                    value="{{ old('absent_cuts', $absentCuts) }}" readonly>
                                                </div>
                                                <div class="col-sm-5">
                                                    <label>Late Deductions</label>
                                                    <input type="text" name="late_cuts" id="late_cuts" class="form-control" 
                                                    value="{{ old('late_cuts', $lateCuts) }}" readonly>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Total Salary Deductions</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="total_salary_deductions" id="total_salary_deductions" class="form-control" 
                                                value="{{ old('total_salary_deductions', 
                                                    optional($checkPayroll->first())->potongan ?? $dataPayroll->potongan ?? '0') }}" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-10 col-form-label"></label>
                                            <div class="col-sm-2">
                                                <label class="col-form-label" style="font-size: larger;">Total Salary</label>
                                                <div>
                                                    <input type="text" name="total_salary" id="total_salary" class="form-control" 
                                                    value="{{ old('total_salary', 
                                                        optional($checkPayroll->first())->total_gaji ?? $dataPayroll->total_gaji ?? '0') }}" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-12">
                                                <label class="col-form-label">Noted</label>
                                                <textarea class="form-control" id="noted" name="noted" rows="3" 
                                                    placeholder="Enter a noted..">{{ old('noted', $checkPayroll->isNotEmpty() 
                                                        && $checkPayroll->first() 
                                                        && $checkPayroll->first()->catatan ? $checkPayroll->first()->catatan : '') }}
                                                </textarea>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-8">
                                                <button type="submit" class="btn btn-primary">Update</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endif
                    @endif
                @endif
            </div>
        </div>
    </div>

    <script>
        function filterEmployees(searchTerm) {
            const selectElement = document.getElementById("val_employee");
            const options = selectElement.getElementsByTagName("option");
            const employeeList = document.getElementById("employee_list");
            const filteredEmployeeList = document.getElementById("filtered_employee_list");

            filteredEmployeeList.innerHTML = ""; // Bersihkan daftar karyawan yang sudah ada

            for (let i = 0; i < options.length; i++) {
                const optionText = options[i].text.toLowerCase();
                if (optionText.includes(searchTerm.toLowerCase())) {
                    options[i].style.display = "block";

                    // Tambahkan karyawan ke daftar yang sesuai
                    const listItem = document.createElement("li");
                    listItem.innerText = options[i].text;
                    listItem.addEventListener("click", function() {
                        selectElement.value = options[i].value; // Pilih nilai karyawan yang sesuai
                        employeeList.style.display = "none"; // Sembunyikan daftar
                    });
                    filteredEmployeeList.appendChild(listItem);
                } else {
                    options[i].style.display = "none";
                }
            }

            // Tampilkan atau sembunyikan div yang berisi daftar karyawan yang sesuai
            if (filteredEmployeeList.children.length > 0) {
                employeeList.style.display = "block";
            } else {
                employeeList.style.display = "none";
            }
        }
        
        document.addEventListener("DOMContentLoaded", function() {

            var reportrange = document.getElementById('reportrange');
            var span = reportrange.querySelector('span');
            var startInput = document.getElementById('start_date');
            var endInput = document.getElementById('end_date');
            var reportrangeDisplay = document.getElementById('reportrange_display');

            var start = moment().subtract(29, 'days');
            var end = moment();

            // Fungsi untuk memeriksa apakah tanggal telah dipilih
            function checkDateSelection() {
                if (startInput.value !== "" && endInput.value !== "") {
                    document.getElementById("checkButton").disabled = false;
                } else {
                    document.getElementById("checkButton").disabled = true;
                }
            }

            function cb(start, end) {
                var rangeText = span.innerHTML = start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY');
                startInput.value = start.format('YYYY-MM-DD');
                endInput.value = end.format('YYYY-MM-DD');

                // Panggil checkDateSelection setiap kali pemilihan tanggal berubah
                checkDateSelection();
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

            // Jalankan saat halaman di muat
            applyDateRangePicker();

            $('#sick_days, #absent_days, #leave_days, #late_days, #working_days').on('change', function() {
                calculateSalaryDeductions();
            });

            // Hapus spasi di value kolom noted
            const valNoted = document.getElementById('noted');
    
            if (valNoted.value.trim() === "") {
                valNoted.value = valNoted.value.trim();
            }

            // Set nilai awal absent days
            const defaultAbsentDays = document.getElementById('defaultAbsentDay')
            var previousAbsentDays = defaultAbsentDays.value;

            // Fungsi untuk menghitung Salary Deductions
            function calculateSalaryDeductions() {
                var sickDays = parseInt($('#sick_days').val()) || 0;
                var absentDays = parseInt($('#absent_days').val()) || 0;
                var leaveDays = parseInt($('#leave_days').val()) || 0;
                var lateDays = parseInt($('#late_days').val()) || 0;
                var workingDays = parseInt($('#working_days').val()) || 0;

                var dataTable = document.getElementById('data-table-payslip');
                var rows = dataTable.querySelectorAll('tr');

                for (var i = 0; i < rows.length; i++) {
                    var cells = rows[i].querySelectorAll('td');
                    if (cells.length >= 3 && cells[0].textContent.trim() === 'Status') {
                        var statusElement = cells[2];
                        var status = statusElement.textContent.trim().toLowerCase();
                    }
                }

                var basicSalary = document.getElementById('basic_salary');
                var positionAllowance = document.getElementById('position_allowance');
                var totalSalaryCuts = document.getElementById('total_salary_deductions');
                var totalSalary = document.getElementById('total_salary');

                var basicSalaryValue = parseInt(basicSalary.value.slice(0, -3).replace(/[^\d]/g, ''));

                if (positionAllowance) {
                    var positionAllowanceValue = parseInt(positionAllowance.value.slice(0, -3).replace(/[^\d]/g, ''));
                }

                if (status == 'harian') {
                    if (absentDays <= 7) {
                        updateWorkingDays = 7 - absentDays;
                        basicSalaryDay = basicSalaryValue * updateWorkingDays;
                        basicSalaryCurrency = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(basicSalaryDay);

                        $('#working_days').val(updateWorkingDays);
                        $('#total_salary').val(basicSalaryCurrency);

                    }

                } else {
                    if (absentDays <= 26) {
                        var lateDeductions = document.getElementById('late_cuts');

                        var positionAllowanceValue = parseInt(positionAllowance.value.slice(0, -3).replace(/[^\d]/g, ''));
                        var lateDeductionsValue = parseInt(lateDeductions.value.slice(0, -3).replace(/[^\d]/g, ''));
                        var totalSalaryCutsValue = parseInt(totalSalaryCuts.value.slice(0, -3).replace(/[^\d]/g, ''));
                        var totalSalaryValue = parseInt(totalSalary.value.slice(0, -3).replace(/[^\d]/g, ''));

                        updateWorkingDays = 26 - absentDays;
                        absentCuts = absentDays * (basicSalaryValue /26);
                        absentCutsCurrency = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(absentCuts);

                        totalSalaryCutsCalculate = absentCuts + lateDeductionsValue;
                        totalSalaryCutsCurrency = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(totalSalaryCutsCalculate);

                        totalSalaryCalculate = (basicSalaryValue + positionAllowanceValue) - totalSalaryCutsCalculate;
                        totalSalaryCurrency = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(totalSalaryCalculate);
                        
                        $('#working_days').val(updateWorkingDays);
                        $('#absent_cuts').val(absentCutsCurrency);
                        $('#total_salary_deductions').val(totalSalaryCutsCurrency);
                        $('#total_salary').val(totalSalaryCurrency);

                    }
                }

                // Tambahkan atribut "required" ke kolom "noted" jika ada perbedaan nilai absendays
                if (absentDays !== parseInt(previousAbsentDays)) {
                    valNoted.setAttribute('required', 'required');

                } else {
                    valNoted.removeAttribute('required');
                }
            }

            calculateSalaryDeductions();
        });
    </script>
@endsection