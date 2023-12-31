@extends('frontend.layout.main')
<!-- @section('title', 'Divison') -->
@section('content')
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Hi, welcome back!</h4>
                    <span class="ml-1">Division</span>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Master Data</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Division</a></li>
                </ol>
            </div>
        </div>
        <!-- row -->

        <div class="row">
            <!-- form add new division -->
            <div class="col-12" id="form-new-division" style="display: none;">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">New Division</h4>
                    </div>
                    <div class="card-body">
                        <div class="basic-form">
                            <form action="{{ url('division-add') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="name-division">Name Division</label>
                                    <input type="hidden" id="is-daily-report-hidden" name="input_is_daily_report">
                                    <input type="text" id="name-division" class="form-control input-new-division" 
                                        name="input_divisi" placeholder="input new division" required>
                                    <!-- <div class="mb-3">
                                        <label for="code-division">Code Division</label>
                                        <input type="text" id="code-division" class="form-control input-new-code-division" 
                                            name="input_code_divisi" placeholder="input new code division" required>
                                    </div> -->
                                    <!-- <div class="mb-3">
                                        <label for="workdays-division">Number of Workdays</label>
                                        <input type="number" id="workdays-division" class="form-control input-new-workdays-division" 
                                            name="input_workdays_divisi" placeholder="input new workdays division" required>
                                    </div> -->
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="is-daily-report" value="">
                                    <label class="form-check-label" for="is-daily-report">Whether this division will be associated with daily work reports</label>
                                </div>
                                <button type="submit" class="btn btn-primary submit-division mt-3" id="close-form-new-division">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- form edit division -->
            <div class="col-12" id="form-edit-division" style="display: none;">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Edit Division</h4>
                    </div>
                    <div class="card-body">
                        <div class="basic-form">
                            <form id="form-edit-value-division" action="{{ url('division-update') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <input type="hidden" id="edit-id" name="id_divisi">
                                    <input type="hidden" id="edit-is-daily-report-hidden" name="value_is_daily_report">
                                    <label for="edit-division">Name Division</label>
                                    <input type="text" id="edit-division" class="form-control input-edit-division" 
                                        name="value_divisi" placeholder="input edit division" required>
                                    <!-- <div class="mb-3">
                                        <label for="edit-code-division">Code Division</label>
                                        <input type="text" id="edit-code-division" class="form-control input-edit-code-division" 
                                            name="value_code_divisi" placeholder="input edit code division" required>
                                    </div> -->
                                    <!-- <div class="mb-3">
                                        <label for="edit-workdays-division">Number of Workdays</label>
                                        <input type="text" id="edit-workdays-division" class="form-control input-edit-workdays-division" 
                                            name="value_workdays_divisi" placeholder="input edit workdays division" required>
                                    </div> -->
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="edit-is-daily-report" value="">
                                    <label class="form-check-label" for="edit-is-daily-report">Whether this division will be associated with daily work reports</label>
                                </div>
                                <button type="submit" class="btn btn-primary submit-division mt-3" id="close-form-edit-division">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Division</h4>
                        @if (count($tbl_divisi) > 0)
                            <button type="submit" class="btn btn-primary" id="new-division">+ Add new division</button>
                        @endif
                    </div>
                    <div class="card-body">
                        @if (count($tbl_divisi) > 0)
                            <div class="table-responsive">
                                <table class="table table-responsive-sm" id="data-table-division" class="display" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Division</th>
                                            <!-- <th>Code</th> -->
                                            <!-- <th>Number of Workdays</th> -->
                                            <th style="display: none;">Daily Work Report</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($tbl_divisi as $d)
                                        <tr data-id="{{$d->id_divisi}}">
                                            <td>{{ $loop->iteration }}</td>
                                            @if ($d->is_daily_report == 1)
                                                <td style="color:#673BB7" class="division-name-selected"><b>{{$d->nama_divisi}}</b></td>
                                            @else
                                                <td class="division-name-selected">{{$d->nama_divisi}}</td>
                                            @endif
                                            <!-- <td class="division-code-selected">{{$d->kode_divisi}}</td> -->
                                            <!-- <td class="division-workdays-selected">{{$d->jumlah_hari_kerja}}</td> -->
                                            <td style="display: none;" class="daily-report-selected">{{$d->is_daily_report}}</td>
                                            <td style="text-align:right;">
                                                <a href="#" id="edit-button" class="btn btn-secondary btn-sm edit-button"><i class="fa fa-edit"></i></a>
                                                <!-- <a href="{{ url('division-delete/' . $d->id_divisi) }}" class="btn btn-dark btn-sm"><i class="fa fa-trash"></i></a> -->
                                            </td>
                                        </tr>
                                        @endforeach 
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="mt-3">
                                <span style="text-align: center;">
                                    <p>Sorry, no data that can be displayed yet. <br>
                                        <button type="submit" class="btn btn-light mt-2" id="new-division">click to add new Division</button>
                                    </p>
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // script to show/hide form add new division
        const toggleFormButton = document.getElementById('new-division');
        const myForm = document.getElementById('form-new-division');

        toggleFormButton.addEventListener('click', function() {
            if (myForm.style.display === 'none') {
                myForm.style.display = 'block';
            }

            // close form edit
            if (myEditForm.style.display === 'block') {
                myEditForm.style.display = 'none';
            }
        });

        // script to show/hide edit form
        const toggleFormEditButton = document.getElementById('edit-button');
        const myEditForm = document.getElementById('form-edit-division');

        // show edit form
        var editButtons = document.querySelectorAll(".edit-button");
        editButtons.forEach(function (button) {
            button.addEventListener("click", function (event) {
                event.preventDefault();

                // Mengambil data dari baris yang dipilih
                var row = this.closest("tr");
                var id = row.getAttribute("data-id");
                var divisionName = row.querySelector(".division-name-selected").textContent;
                // var divisionCode = row.querySelector(".division-code-selected").textContent;
                // var divisionWorkdays = row.querySelector(".division-workdays-selected").textContent;
                
                // Mengisi data ke dalam formulir
                document.getElementById("edit-id").value = id;
                document.getElementById("edit-division").value = divisionName;
                // document.getElementById("edit-code-division").value = divisionCode;
                // document.getElementById("edit-workdays-division").value = divisionWorkdays;

                var dailyReport = row.querySelector(".daily-report-selected").textContent;
                var isDailyReportCheckboxEditForm = document.getElementById('edit-is-daily-report');
                isDailyReportCheckboxEditForm.checked = parseInt(dailyReport) === 1;

                if (myEditForm.style.display === 'none') {
                    myEditForm.style.display = 'block';
                }

                // close form add
                if (myForm.style.display === 'block') {
                    myForm.style.display = 'none';
                }
            });
        });

        // Elemen checkbox form add
        var isDailyReportCheckbox = document.getElementById('is-daily-report');

        isDailyReportCheckbox.addEventListener('click', function () {
            var isDailyReport = isDailyReportCheckbox.checked ? 1 : 0;

            var hiddenValueDailyReport = document.querySelector("input[name='input_is_daily_report']");
            hiddenValueDailyReport.value = isDailyReport;
        });

        // Elemen checkbox form edit
        var isDailyReportCheckboxEdit = document.getElementById('edit-is-daily-report');

        isDailyReportCheckboxEdit.addEventListener('click', function () {
            var isDailyReportEdit = isDailyReportCheckboxEdit.checked ? 1 : 0;
            
            // Setel nilai checkbox ke input tersembunyi
            var hiddenEditValueDailyReport = document.querySelector("input[name='value_is_daily_report']");
            hiddenEditValueDailyReport.value = isDailyReportEdit;
        });



    </script>
@endsection