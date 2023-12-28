@extends('frontend.layout.main')
<!-- @section('title', 'Leaves Summary') -->
@section('content')
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Hi, welcome back!</h4>
                    <span class="ml-1">Leaves Summary</span>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Management</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Leaves Summary</a></li>
                </ol>
            </div>
        </div>
        <!-- row -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">                           
                        <h4 class="card-title">Leaves Summary</h4>
                        @if (count($dataleave) > 0)
                            <a href="{{ url('/leave-request') }}" class="btn btn-primary" 
                                id="new-data-leave">+ Create request
                            </a>
                        @endif
                    </div>
                    <div class="card-body">
                        <form action="{{ url('leaves-summary-search') }}" method="GET">
                            @csrf
                            <label>Advance filter base on employee and date range</label>
                            <div class="form-group row">
                                <div class="col-sm-3 mb-2">
                                    <input type="text" name="search_employee" id="search_employee" class="form-control" placeholder="Search employee...">
                                </div>
                                <div class="col-sm-3 mb-2">
                                    <select class="form-control" id="val_employee" name="id_karyawan">
                                        <option value="">Select a employee...</option>
                                        @foreach ($dataEmployee as $e)
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
                    <div class="card-body">
                        @if (count($dataleave) > 0)
                            <div class="table-responsive">
                                <table class="table table-responsive-sm" id="data-table-data-leave" 
                                class="display" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Employee (C)</th>
                                            <th>Type Leave</th>
                                            <th>Description</th>
                                            <th>Date Leave (Start)</th>
                                            <th></th>
                                            <th>Duration (Day)</th>
                                            <th>Responsible (C)</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($dataleave as $dl)
                                            <tr data-id="{{$dl->id_data_cuti}}">
                                                <td>{{ $loop->iteration }}</td>
                                                <td><a href="{{ url('form-employee-edit', ['id' => Crypt::encrypt($dl->id_karyawan)]) }}">
                                                    {{ $employee[$dl->id_karyawan] }} - {{ $idcard[$dl->id_karyawan] }}
                                                    </a>
                                                </td>
                                                <td>{{ $typeleave[$dl->id_tipe_cuti] }}</td>
                                                <td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 100px;">
                                                    {{ $dl->deskripsi }}
                                                </td>
                                                <td colspan="2">{{ date('l, j F Y', strtotime($dl->mulai_cuti)) }}</td>
                                                <td>{{ $dl->durasi_cuti }}</td>
                                                <td><a href="{{ url('form-employee-edit', ['id' => Crypt::encrypt($dl->id_penangung_jawab)]) }}">
                                                    {{ $employee[$dl->id_penangung_jawab] }} - {{ $idcard[$dl->id_penangung_jawab] }}
                                                    </a>
                                                </td>
                                                @if($dl->status_cuti == 'To Approved')
                                                    <td><b><span style="color: #3065D0;">{{ $dl->status_cuti }}</span></b></td>
                                                @elseif($dl->status_cuti == 'Approved')
                                                    <td><b><span style="color: #593BDB;">{{ $dl->status_cuti }}</span></b></td>
                                                @elseif($dl->status_cuti == 'Cancelled')
                                                    <td><b><span style="color: #FD7E14;">{{ $dl->status_cuti }}</span></b></td>
                                                @endif
                                                <td>
                                                    <a href="{{ url('leave-request-edit', ['id' => Crypt::encrypt($dl->id_data_cuti)]) }}" 
                                                        class="btn btn-secondary btn-sm mt-1">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                    @if (!in_array($dl->status_cuti, ['Cancelled', 'To Approved']))
                                                        <button type="button" class="btn btn-warning btn-sm mt-1 leave-cancel-button" data-toggle="modal" 
                                                            data-target="#cancelledLeave" data-id="{{ $dl->id_data_cuti }}" 
                                                            data-employee="{{ $employee[$dl->id_karyawan] }} - {{ $idcard[$dl->id_karyawan] }}" 
                                                            data-description="{{ $dl->deskripsi }}">
                                                            <i class="fa fa-times"></i>
                                                        </button>
                                                    @endif
                                                    @if (in_array($dl->status_cuti, ['To Approved']))
                                                        <a href="{{ url('leave-request-delete/' . $dl->id_data_cuti) }}" class="btn btn-dark btn-sm mt-1">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach 
                                    </tbody>
                                </table>

                                <div class="mt-5 d-flex justify-content-start">
                                    <ul class="pagination pagination-sm pagination-gutter">
                                        <li class="page-item page-indicator {{ $dataleave->onFirstPage() ? 'disabled' : '' }}">
                                            <a class="page-link" href="{{ $dataleave->previousPageUrl() }}" aria-label="Previous">
                                                <i class="icon-arrow-left"></i>
                                            </a>
                                        </li>

                                        @for ($i = 1; $i <= $dataleave->lastPage(); $i++)
                                            <li class="page-item {{ $dataleave->currentPage() == $i ? 'active' : '' }}">
                                                <a class="page-link" href="{{ $dataleave->url($i) }}">{{ $i }}</a>
                                            </li>
                                        @endfor

                                        <li class="page-item page-indicator {{ $dataleave->hasMorePages() ? '' : 'disabled' }}">
                                            <a class="page-link" href="{{ $dataleave->nextPageUrl() }}" aria-label="Next">
                                                <i class="icon-arrow-right"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </div>

                                <!-- Modal -->
                                <div class="modal fade" id="cancelledLeave">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Leave Cancelation</h5>
                                                <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ url('leave-request-cancel') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="id" id="id">
                                                    
                                                    <div class="form-group">
                                                        <label for="employeeInfo">Employee</label>
                                                        <input type="text" id="employeeInfo" class="form-control input-employeeInfo mb-3" readonly>
    
                                                        <label for="descriptionInfo">Description</label>
                                                        <input type="text" id="descriptionInfo" class="form-control input-descriptionInfo mb-3" readonly>

                                                        <label for="reason">Reason</label>
                                                        <textarea class="form-control" id="reason" name="reason" rows="3" 
                                                            placeholder="Enter a reason.." required>
                                                        </textarea>

                                                        <button type="submit" class="btn btn-danger mt-3 float-right">Retrieved</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        @else
                            <div class="mt-3">
                                <span style="text-align: center;">
                                    <p>Sorry, no data that can be displayed yet. <br>
                                        <a href="{{ url('/leave-request') }}" class="btn btn-light mt-2" id="new-data-leave">
                                            click to new leave request
                                        </a>
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

            var start = moment();
            var end = null;

            function cb(start, end) {
                if (start.isValid() && end.isValid()) {
                    var rangeText = span.innerHTML = start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY');
                    startInput.value = start.format('YYYY-MM-DD');
                    endInput.value = end.format('YYYY-MM-DD');
                    reportrangeDisplay.innerHTML = rangeText;

                    // Menyimpan nilai dalam local storage
                    localStorage.setItem('selected_range', rangeText);
                }
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
                    },
                    alwaysShowCalendars: true,
                    
                }, cb);
            }

            applyDateRangePicker();

            // Mengambil id data cuti
            var buttons = document.querySelectorAll('.leave-cancel-button');

            buttons.forEach(function (button) {
                button.addEventListener('click', function () {
                    var idDataCuti = this.getAttribute('data-id');
                    var employee = this.getAttribute('data-employee');
                    var description = this.getAttribute('data-description');

                    document.getElementById('id').value = idDataCuti;
                    document.getElementById('employeeInfo').value = employee;
                    document.getElementById('descriptionInfo').value = description;
                });
            });

            // Hilangkan space dalam value reason saat pertama kali diakses
            const reason = document.getElementById('reason');
    
            if (reason.value.trim() === "") {
                reason.value = reason.value.trim();
            }
        });
    </script>
@endsection