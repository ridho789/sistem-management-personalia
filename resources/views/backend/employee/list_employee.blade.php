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
                        <h4 class="card-title">List Employee</h4>
                        @if (count($tbl_karyawan) > 0)
                            <a href="{{ url('/form-employee') }}" class="btn btn-primary" id="new-employee">+ Add new employee</a>
                        @endif
                    </div>
                    <div class="card-body">
                        @if (count($tbl_karyawan) > 0)
                            <form action="{{ url('list-employee-search') }}" method="GET">
                                @csrf
                                <label>Display employee data based on search name or ID card</label>
                                <div class="form-group row">
                                    <div class="col-sm-3 mb-3">
                                        <input type="text" name="search" class="form-control" 
                                        placeholder="Search name or ID card" value="{{ Request::get('search') }}">
                                    </div>
                                    <div class="col-sm-3 mb-3">
                                        <select class="form-control" id="val_division" name="id_divisi">
                                            <option value="">Select a division...</option>
                                            @foreach ($dataDivision as $d)
                                                <option value="{{ $d->id_divisi }}"  
                                                    {{ old('id_divisi') == $d->id_divisi ? 'selected' : '' }}>
                                                    {{ $d->nama_divisi }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <button type="submit" class="btn btn-primary">Search</button>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-3">
                                        <label>Display employee data based on status</label>
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
                                    </div>
                                </div>
                            </form>
                            <div class="table-responsive">
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
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{$k->nama_karyawan}}</td>
                                            <td>{{$k->nik}}</td>
                                            <td>{{$k->no_telp ?? '-'}}</td>
                                            <td>{{$k->id_card}}</td>
                                            <td>{{ $positions[$k->id_jabatan] ?? '-' }}</td>
                                            <td>{{ $divisions[$k->id_divisi] ?? '-' }}</td>
                                            <td>{{ $companies[$k->id_perusahaan] ?? '-' }}</td>
                                            <td>{{ $statuses[$k->id_status] ?? '-' }}</td>
                                            <td>
                                                <a href="{{ url('form-employee-edit', ['id' => Crypt::encrypt($k->id_karyawan)]) }}" 
                                                    id="edit-button" class="btn btn-secondary btn-sm edit-button"><i class="fa fa-pencil"></i>
                                                </a>
                                                <!-- <a href="{{ url('list-employee-delete/' . $k->id_karyawan) }}" class="btn btn-dark btn-sm"><i class="fa fa-trash"></i></a> -->
                                            </td>
                                        </tr>
                                        @endforeach 
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-2" id="download-pdf" style="display: block;">
                                <form action="{{ url('list-employee-print') }}" method="POST" id="pdf-form">
                                    @csrf
                                    <input type="hidden" name="dataSearch" id="dataSearchInput">
                                    <input type="hidden" name="dataRow" id="dataRowInput">
                                    <button type="submit" id="button-download-pdf" class="btn btn-rounded btn-primary mt-3">
                                        <span class="btn-icon-left text-primary">
                                            <i class="fa fa-download color-primary"></i>
                                        </span>Download PDF
                                    </button>
                                </form>
                            </div>

                            <div class="mt-5 d-flex justify-content-start">
                                <ul class="pagination pagination-sm pagination-gutter">
                                    <li class="page-item page-indicator {{ $tbl_karyawan->onFirstPage() ? 'disabled' : '' }}">
                                        <a class="page-link" href="{{ $tbl_karyawan->previousPageUrl() }}" aria-label="Previous">
                                            <i class="icon-arrow-left"></i>
                                        </a>
                                    </li>

                                    @for ($i = 1; $i <= $tbl_karyawan->lastPage(); $i++)
                                        <li class="page-item {{ $tbl_karyawan->currentPage() == $i ? 'active' : '' }}">
                                            <a class="page-link" href="{{ $tbl_karyawan->url($i) }}">{{ $i }}</a>
                                        </li>
                                    @endfor

                                    <li class="page-item page-indicator {{ $tbl_karyawan->hasMorePages() ? '' : 'disabled' }}">
                                        <a class="page-link" href="{{ $tbl_karyawan->nextPageUrl() }}" aria-label="Next">
                                            <i class="icon-arrow-right"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>

                        @else
                            <div class="mt-3">
                                <span style="text-align: center;">
                                    <p>Sorry, no data that can be displayed yet. <br>
                                        <a href="{{ url('/form-employee') }}" class="btn btn-light mt-2" id="new-employee">click to add new employee</a>
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

            rows.forEach(function (row) {
                var statusCell = row.querySelector('td:nth-child(9)');
                var isVisible = true;

                // Mengumpulkan data-id dari semua baris yang terlihat
                if (isVisible) {
                    dataRow.push(row.getAttribute('data-id'));
                }

                // Menampilkan atau menyembunyikan baris sesuai dengan hasil perbandingan
                row.style.display = isVisible ? '' : 'none';
            });

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

            // form download-pdf
            const pdfForm = document.getElementById('pdf-form');
            pdfForm.addEventListener('submit', function (event) {
                // Menghentikan pengiriman formulir
                event.preventDefault();

                // Mengambil nilai dari input pencarian
                var searchInput = document.querySelector('input[name="search"]');
                if (searchInput){
                    var searchData = searchInput.value;

                    // Mengatur nilai input tersembunyi dalam formulir PDF
                    var dataSearchInput = document.getElementById('dataSearchInput');
                    dataSearchInput.value = searchData;
                }

                // Mengambil nilai dataRow dan mengatur nilainya pada input tersembunyi
                var dataRowInput = document.getElementById('dataRowInput');
                dataRowInput.value = dataRow.join(',');

                // Sekarang formulir siap untuk dikirim
                pdfForm.submit();
            });

        });

    </script>
@endsection