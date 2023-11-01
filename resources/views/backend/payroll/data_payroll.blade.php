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
                                        <input type="text" id="search_employee" class="form-control" placeholder="Search for an employee..." oninput="filterEmployees(this.value)">
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
                                            <i class="fa fa-calendar"> </i>&nbsp;<span id="reportrange_display"> Display data based on date range </span> 
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
                    @if ($NotValidPeriod)
                        <div class="card">
                            <div class="card-header"></div>
                            <div class="card-body">
                                <span style="text-align: center;">
                                    <p>{{ $rangeDate }}
                                    <br>Sorry, Not Applicable Period.</p>
                                </span>
                            </div>
                        </div>
                    @else
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Payslip { {{ $rangeDate }} }</h4>
                            </div>
                            <div class="card-body">
                                <table class="table table-responsive-sm" id="data-table-payslip" class="display" style="width:100%">
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
                                        <td>Position</td>
                                        <td style="text-align: right;">:</td>
                                        <td>{{ $position->nama_jabatan }}</td>
                                    </tr>
                                    <tr>
                                        <td>Division</td>
                                        <td style="text-align: right;">:</td>
                                        <td>{{ $division->nama_divisi }}</td>
                                    </tr>
                                    <tr>
                                        <td>Basic Salary</td>
                                        <td style="text-align: right;">:</td>
                                        <td>{{ $selectEmployee->gaji_pokok }}</td>
                                    </tr>
                                    <tr>
                                        <td>Position Allowance</td>
                                        <td style="text-align: right;">:</td>
                                        <td>{{ $position->tunjangan_jabatan }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
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

            applyDateRangePicker();
        });
    </script>
@endsection