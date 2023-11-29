@extends('frontend.layout.main')
<!-- @section('title', 'List Asset') -->
@section('content')
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Hi, welcome back!</h4>
                    <span class="ml-1">List Asset</span>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Management</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">List Asset</a></li>
                </ol>
            </div>
        </div>
        <!-- row -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        @if (count($asset) > 0)
                            <form action="{{ url('list-asset-search') }}" method="GET">
                            @csrf
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" 
                                    placeholder="Search name or polnum" value="{{ Request::get('search') }}">
                                    <!-- <span class="input-group-btn">
                                        <button type="submit" class="btn btn-primary">Search</button>
                                    </span> -->
                                </div>
                            </form>
                        @endif
                    </div>
                    <div class="card-header">                           
                        <h4 class="card-title">List Asset</h4>
                        @if (count($asset) > 0)
                            <a href="/form-asset" class="btn btn-primary mt-3" id="new-asset">+ Add new asset</a>
                        @endif
                    </div>
                    <div class="card-body">
                        @if (count($asset) > 0)
                            <div class="table-responsive">
                                <table>
                                    <tr>
                                        <td>
                                            <div>
                                                <input type="checkbox" class="mr-2" id="filterWarningExpiration">
                                                <label>Show status warning deadline Expiration</label>

                                            </div>
                                            <div>
                                                <input type="checkbox" class="mr-2" id="filterDangerExpiration">
                                                <label>Show status danger deadline Expiration</label>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                                <table class="table table-responsive-sm" id="data-table-asset" class="display" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name Asset</th>
                                            <th>Category</th>
                                            <th>Sub Category</th>
                                            <th>Spesification</th>
                                            <th>Police Number</th>
                                            <th>Brand</th>
                                            <th>Year</th>
                                            <th>Tax Expiration</th>
                                            <th>Plate Expiration</th>
                                            <th>Location</th>
                                            <th>Company</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($asset as $a)
                                        <tr data-id="{{$a->id_aset}}">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{$a->nama_aset}}</td>
                                            <td>{{ $category[$a->id_kategori] }}</td>
                                            <td>{{ isset($subcategory[$a->id_sub_kategori]) ? $subcategory[$a->id_sub_kategori] : '-' }}</td>
                                            <td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 100px;">
                                                {{ $a->spesifikasi ?? '-' }}
                                            </td>
                                            <td>{{$a->nopol ?? '-' }}</td>
                                            <td>{{$a->merk ?? '-' }}</td>
                                            <td>{{$a->tahun ?? '-' }}</td>
                                            <td class="{{ $a->isTaxExpiring() === 'red' ? 
                                                'text-red' : ($a->isTaxExpiring() === 'orange' ? 'text-orange' : '') }} tax-expiration" 
                                                data-color="{{ $a->isTaxExpiring() }}">
                                                {{$a->masa_pajak ?? '-' }}
                                            </td>
                                            <td class="{{ $a->isPlateExpiring() === 'red' ? 
                                                'text-red' : ($a->isPlateExpiring() === 'orange' ? 'text-orange' : '') }} plate-expiration" 
                                                data-color="{{ $a->isPlateExpiring() }}">
                                                {{$a->masa_plat ?? '-' }}
                                            </td>
                                            <td>{{$a->lokasi}}</td>
                                            <td>{{ $company[$a->id_perusahaan] }}</td>
                                            <td>
                                                <a href="{{ url('form-asset-edit', ['id' => Crypt::encrypt($a->id_aset)]) }}" 
                                                    id="edit-button" class="btn btn-secondary btn-sm edit-button mr-2"><i class="icon icon-edit-72"></i>
                                                </a>
                                                <!-- <a href="{{ url('list-asset-delete/' . $a->id_aset) }}" class="btn btn-dark btn-sm"><i class="fa fa-trash"></i></a> -->
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
                                        <a href="/form-asset" class="btn btn-light mt-2" id="new-asset">click to add new asset</a>
                                    </p>
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .text-red {
            color: red;
        }

        .text-orange {
            color: orange;
        }
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Fungsi untuk menampilkan/menyembunyikan baris berdasarkan checkbox yang dipilih
            function filterRows() {
                var warningChecked = document.getElementById("filterWarningExpiration").checked;
                var dangerChecked = document.getElementById("filterDangerExpiration").checked;

                var tableRows = document.querySelectorAll("#data-table-asset tbody tr");

                tableRows.forEach(function (row) {
                    var taxExpirationColor = row.querySelector(".tax-expiration").getAttribute("data-color");
                    var plateExpirationColor = row.querySelector(".plate-expiration").getAttribute("data-color");

                    var shouldShow = (!warningChecked && !dangerChecked) ||
                                    (warningChecked && (taxExpirationColor === "orange" || plateExpirationColor === "orange")) ||
                                    (dangerChecked && (taxExpirationColor === "red" || plateExpirationColor === "red"));

                    if (shouldShow) {
                        row.style.display = "table-row";
                    } else {
                        row.style.display = "none";
                    }
                });
            }

            // Event handler untuk perubahan pada checkbox
            document.getElementById("filterWarningExpiration").addEventListener("change", filterRows);
            document.getElementById("filterDangerExpiration").addEventListener("change", filterRows);

            // Inisialisasi filter saat halaman dimuat
            filterRows();
        });
    </script>
@endsection