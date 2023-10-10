@extends('frontend.layout.main')
<!-- @section('title', 'List Employee') -->
@section('content')
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Hi, welcome back!</h4>
                    <span class="ml-1">List Employee</span>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Management</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">List Employee</a></li>
                </ol>
            </div>
        </div>
        <!-- row -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        @if (count($tbl_karyawan) > 0)
                            <form action="{{ url('list-employee-search') }}" method="GET">
                                @csrf
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" 
                                    placeholder="Search name or ID card" value="{{ Request::get('search') }}">
                                    <span class="input-group-btn">
                                        <button type="submit" class="btn btn-primary">Search</button>
                                    </span>
                                </div>
                            </form>
                        @endif
                    </div>
                    <div class="card-header">                           
                        <h4 class="card-title">List Employee</h4>
                        @if (count($tbl_karyawan) > 0)
                            <a href="/form-employee" class="btn btn-primary mt-3" id="new-employee">+ Add new employee</a>
                        @endif
                    </div>
                    <div class="card-body">
                        @if (count($tbl_karyawan) > 0)
                            <div class="table-responsive">
                                <table>
                                    <tr>
                                        <td>
                                            <label>Display employee data based on status</label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-group">
                                                @foreach ($statuses as $statusId => $statusName)
                                                    <div class="form-check form-check-inline">
                                                        <label class="form-check-label">
                                                            <input type="checkbox" class="form-check-input status-filter" 
                                                            value="{{ $statusId }}">{{ $statusName }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                                <table class="table table-responsive-sm" id="data-table-employee" class="display" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Employee</th>
                                            <th>NIK</th>
                                            <th>Phone</th>
                                            <th>ID Card</th>
                                            <th>Position</th>
                                            <th>Division</th>
                                            <th>Company</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($tbl_karyawan as $k)
                                        <tr data-id="{{$k->id_karyawan}}" data-status-id="{{ $k->id_status }}">
                                            <th>{{ $loop->iteration }}</th>
                                            <td>{{$k->nama_karyawan}}</td>
                                            <td>{{$k->nik}}</td>
                                            <td>{{$k->no_telp}}</td>
                                            <td>{{$k->id_card}}</td>
                                            <td>{{ $positions[$k->id_jabatan] }}</td>
                                            <td>{{ $divisions[$k->id_divisi] }}</td>
                                            <td>{{ $companies[$k->id_perusahaan] }}</td>
                                            <td>{{ $statuses[$k->id_status] }}</td>
                                            <td style="text-align:right;">
                                                <a href="{{ url('form-employee-edit', ['id' => Crypt::encrypt($k->id_karyawan)]) }}" 
                                                    id="edit-button" class="edit-button"><i class="fa fa-edit"> edit |</i>
                                                </a>
                                                <a href="list-employee-delete/{{$k->id_karyawan}}"><i class="fa fa-trash"> delete </i></a>
                                            </td>
                                        </tr>
                                        @endforeach 
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3" id="download-pdf" style="display: block;">
                                <form action="{{ url('list-employee-print') }}" method="POST" id="pdf-form">
                                    @csrf
                                    <!-- Menambahkan input tersembunyi untuk dataRow -->
                                    <input type="hidden" name="dataRow" id="dataRowInput">
                                    <button type="submit" id="button-download-pdf" class="btn btn-rounded btn-primary mt-3">
                                        <span class="btn-icon-left text-primary">
                                            <i class="fa fa-download color-primary"></i>
                                        </span>Download PDF
                                    </button>
                                </form>
                            </div>

                        @else
                            <div class="mt-3">
                                <span style="text-align: center;">
                                    <p>Sorry, no data that can be displayed yet. <br>
                                        <a href="/form-employee" class="btn btn-light mt-2" id="new-employee">click to add new employee</a>
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
        document.addEventListener('DOMContentLoaded', function () {
            // Mengambil elemen tabel
            var table = document.getElementById('data-table-employee');

            // Mengambil semua baris dalam tabel
            var rows = table.querySelectorAll('tbody tr');
            var dataRow = [];

            // Event handler untuk checkbox status-filter
            var statusCheckboxes = document.querySelectorAll('.status-filter');
            statusCheckboxes.forEach(function (checkbox) {
                checkbox.addEventListener('change', function () {
                    // Mengumpulkan status yang dicentang
                    var selectedStatus = [];
                    var statusRow = [];

                    statusCheckboxes.forEach(function (checkbox) {
                        if (checkbox.checked) {
                            selectedStatus.push(checkbox.value);
                        }
                    });

                    // Mengosongkan array dataRow
                    dataRow = [];

                    // Menampilkan atau menyembunyikan baris berdasarkan status yang dipilih
                    rows.forEach(function (row) {
                        var statusCell = row.querySelector('td:nth-child(9)');
                        var statusValue = statusCell.textContent.trim();
                        var statusId = row.getAttribute('data-status-id');

                        // Memeriksa apakah status dalam baris ada dalam status yang dipilih
                        var isVisible = selectedStatus.length === 0 || selectedStatus.includes(statusId);

                        // Tampung semua data-id dari row yang tampil
                        if (isVisible) {
                            dataRow.push(row.getAttribute('data-id'));
                        }

                        // Tampung semua status row yang tampil
                        statusRow.push(isVisible);

                        // Menampilkan atau menyembunyikan baris sesuai dengan hasil perbandingan
                        row.style.display = isVisible ? '' : 'none';
                    });

                    // hide button download pdf jika tidak ada data yang ditampilkan
                    const buttonDownloadPDF = document.getElementById('download-pdf');
                    if (statusRow.includes(true)) {
                        if (buttonDownloadPDF.style.display === 'none') {
                            buttonDownloadPDF.style.display = 'block';
                        }
                    } else {
                        if (buttonDownloadPDF.style.display === 'block') {
                            buttonDownloadPDF.style.display = 'none';
                        }
                    }
                });
            });

            const pdfForm = document.getElementById('pdf-form');
            pdfForm.addEventListener('submit', function (event) {
                // Menghentikan pengiriman formulir
                event.preventDefault();

                // Mengambil nilai dataRow dan mengatur nilainya pada input tersembunyi
                var dataRowInput = document.getElementById('dataRowInput');
                dataRowInput.value = dataRow.join(',');

                // Sekarang formulir siap untuk dikirim
                pdfForm.submit();
            });

        });

    </script>
@endsection