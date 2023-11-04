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
                            <a href="/leave-request" class="btn btn-primary mt-3" 
                                id="new-data-leave">+ Create request
                            </a>
                        @endif
                    </div>
                    <div class="card-header">
                        @if (count($dataleave) > 0)
                            <form action="{{ url('leaves-summary-search') }}" method="GET">
                                @csrf
                                <input type="hidden" id="start_date" name="start_date">
                                <input type="hidden" id="end_date" name="end_date">
                                <label>Showing data base on date range</label>
                                <div class="input-group">
                                    <div class="mb-1" id="reportrange" style="background: #fff; cursor: pointer; 
                                        padding: 5.5px 10px; border: 1px solid #ccc;">
                                        <i class="fa fa-calendar"> </i>&nbsp;<span id="reportrange_display"> Display data based on date range </span> 
                                        <i class="fa fa-caret-down"></i>
                                    </div>
                                    <div class="ml-2"></div>
                                    <div class="mb-1">
                                        <button type="submit" class="btn btn-dark">Search</button>
                                    </div>
                                </div>
                            </form>
                        @endif
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
                                                <td colspan="2">{{ date('l, Y-m-d', strtotime($dl->mulai_cuti)) }}</td>
                                                <td>{{ $dl->durasi_cuti }}</td>
                                                <td><a href="{{ url('form-employee-edit', ['id' => Crypt::encrypt($dl->id_penangung_jawab)]) }}">
                                                    {{ $employee[$dl->id_penangung_jawab] }} - {{ $idcard[$dl->id_penangung_jawab] }}
                                                    </a>
                                                </td>
                                                @if($dl->status_cuti == 'To Approved')
                                                    <td><span class="badge badge-secondary">{{ $dl->status_cuti }}</span></td>
                                                @elseif($dl->status_cuti == 'Approved')
                                                    <td><span class="badge badge-primary">{{ $dl->status_cuti }}</span></td>
                                                @endif
                                                <td>
                                                    <a href="{{ url('leave-request-edit', ['id' => Crypt::encrypt($dl->id_data_cuti)]) }}" 
                                                        class="btn btn-secondary btn-sm">
                                                        <i class="icon icon-edit-72"> </i>
                                                    </a>
                                                    <!-- <a href="leave-request-delete/{{$dl->id_data_cuti}}" class="btn btn-dark btn-sm"><i class="fa fa-trash"></i></a> -->
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
                                        <a href="/leave-request" class="btn btn-light mt-2" id="new-data-leave">
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
        document.addEventListener("DOMContentLoaded", function() {
            var reportrange = document.getElementById('reportrange');
            var span = reportrange.querySelector('span');
            var startInput = document.getElementById('start_date');
            var endInput = document.getElementById('end_date');
            var reportrangeDisplay = document.getElementById('reportrange_display');

            // Mengecek apakah ada nilai yang tersimpan dalam local storage
            var storedRange = localStorage.getItem('selected_range');
            if (storedRange) {
                reportrangeDisplay.innerHTML = storedRange;
            }

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