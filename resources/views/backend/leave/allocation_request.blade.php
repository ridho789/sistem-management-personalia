@extends('frontend.layout.main')
<!-- @section('title', 'Allocation Request') -->
@section('content')
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Hi, welcome back!</h4>
                    <span class="ml-1">Allocation Request</span>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Management</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Allocation Request</a></li>
                </ol>
            </div>
        </div>
        <!-- row -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">                           
                        <h4 class="card-title">Allocation Request</h4>
                    </div>
                    @if ($allocationRequest)
                        <div class="card-body">
                            <form action="{{ url('allocation-request-search') }}" method="GET">
                            @csrf
                                <div class="form-group row">
                                    <div class="col-sm-3">
                                        <!-- <label>Showing data base on employee or ID card</label> -->
                                        <input type="text" name="search" class="form-control" 
                                        placeholder="Search employee or ID card.." value="{{ Request::get('search') }}">
                                    </div>
                                    <div class="col-sm-3">
                                        <select class="form-control" id="val_legal_leave" name="id_legal_leave">
                                            <option value="all">All Type Legal Leave</option>
                                            @foreach ($legalLeaveIds as $ll)
                                                <option value="{{ $ll->id_tipe_cuti }}"  
                                                    {{ old('id_tipe_cuti') == $ll->id_tipe_cuti ? 'selected' : '' }}>
                                                    {{ $ll->nama_tipe_cuti }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <button type="submit" class="btn btn-primary">Search</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @endif
                    <div class="card-body">
                        @if ($allocationRequest)
                            <div class="table-responsive">
                                <table class="table table-responsive-sm" id="data-table-allocation-request" 
                                    class="display" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th width=5%>
                                                <input type="checkbox" id="selectAllCheckbox">
                                            </th>
                                            <th>Employee (C)</th>
                                            <th>Leave Type</th>
                                            <th>Remaining Leave</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($allocationRequest as $ar)
                                            <tr data-id="{{$ar->id_alokasi_sisa_cuti }}">
                                                <td><input type="checkbox" class="select-checkbox"></td>
                                                <td>
                                                    <a href="{{ url('form-employee-edit', ['id' => Crypt::encrypt($ar->id_karyawan)]) }}">
                                                        {{ $employee[$ar->id_karyawan] }} - {{ $idcard[$ar->id_karyawan] }}
                                                    </a>
                                                </td>
                                                <td>{{ $typeleave[$ar->id_tipe_cuti] }}</td>
                                                <td>{{ $ar->sisa_cuti }}</td>
                                                @if ($ar->status == 'Cashed')
                                                    <td style="color:#673BB7"><b>{{ $ar->status }}</b></td>
                                                @else
                                                    <td>{{ $ar->status }}</td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-3">
                                <form action="{{ url('allocation-request-status') }}" method="POST">
                                    @csrf
                                    <label>Update status allocation</label>
                                    <input type="hidden" id="allSelectRow" name="allSelectRow" value="">
                                    <div class="form-group row">
                                        <div class="col-sm-3">
                                            <button type="submit" id="searchButtonCashed" class="btn btn-secondary" name="action" value="cashed" disabled>Cashed</button>
                                            <button type="submit" id="searchButtonDefault" class="btn btn-dark" name="action" value="default" disabled>Set Default</button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="mt-5 d-flex justify-content-start">
                                <ul class="pagination pagination-sm pagination-gutter">
                                    <li class="page-item page-indicator {{ $allocationRequest->onFirstPage() ? 'disabled' : '' }}">
                                        <a class="page-link" href="{{ $allocationRequest->previousPageUrl() }}" aria-label="Previous">
                                            <i class="icon-arrow-left"></i>
                                        </a>
                                    </li>

                                    @for ($i = 1; $i <= $allocationRequest->lastPage(); $i++)
                                        <li class="page-item {{ $allocationRequest->currentPage() == $i ? 'active' : '' }}">
                                            <a class="page-link" href="{{ $allocationRequest->url($i) }}">{{ $i }}</a>
                                        </li>
                                    @endfor

                                    <li class="page-item page-indicator {{ $allocationRequest->hasMorePages() ? '' : 'disabled' }}">
                                        <a class="page-link" href="{{ $allocationRequest->nextPageUrl() }}" aria-label="Next">
                                            <i class="icon-arrow-right"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>

                        @else
                            <div class="mt-3">
                                <span style="text-align: center;">
                                    <p>Sorry, no data that can be displayed yet. <br></p>
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var table = document.getElementById('data-table-allocation-request');
        var checkboxes;
        var selectAllCheckbox = document.getElementById('selectAllCheckbox');
        var searchButtonCashed = document.getElementById('searchButtonCashed');
        var searchButtonDefault = document.getElementById('searchButtonDefault');
        var allSelectRowInput = document.getElementById('allSelectRow');

        if (table) {
            checkboxes = table.getElementsByClassName('select-checkbox');

            // Event listener untuk checkbox "Select All"
            selectAllCheckbox.addEventListener('change', function () {
                for (var i = 0; i < checkboxes.length; i++) {
                    checkboxes[i].checked = this.checked;
                    var row = checkboxes[i].parentNode.parentNode;
                    row.classList.toggle('selected', this.checked);
                }

                // Ambil dan simpan ID semua baris yang terpilih ke dalam input hidden
                updateAllSelectRow();

                // Aktifkan atau nonaktifkan button berdasarkan status "Select All"
                searchButtonCashed.disabled = !this.checked;
                searchButtonDefault.disabled = !this.checked;

            });

            // Event listener untuk checkbox di setiap baris
            for (var i = 0; i < checkboxes.length; i++) {
                checkboxes[i].addEventListener('change', function () {
                    var row = this.parentNode.parentNode;
                    row.classList.toggle('selected', this.checked);

                    // Periksa apakah setidaknya satu checkbox terpilih
                    var atLeastOneChecked = Array.from(checkboxes).some(function (checkbox) {
                        return checkbox.checked;
                    });

                    // Aktifkan atau nonaktifkan button berdasarkan hasil pemeriksaan
                    searchButtonCashed.disabled = !atLeastOneChecked;
                    searchButtonDefault.disabled = !atLeastOneChecked;

                    // Periksa apakah semua checkbox terpilih
                    var allChecked = true;
                    for (var j = 0; j < checkboxes.length; j++) {
                        if (!checkboxes[j].checked) {
                            allChecked = false;
                            break;
                        }
                    }

                    // Atur status checkbox "Select All"
                    selectAllCheckbox.checked = allChecked;

                    // Ambil dan simpan ID semua baris yang terpilih ke dalam input hidden
                    updateAllSelectRow();
                });
            }

            // Fungsi untuk mengambil dan menyimpan ID semua baris yang terpilih
            function updateAllSelectRow() {
                var selectedIds = Array.from(checkboxes)
                    .filter(function (checkbox) {
                        return checkbox.checked;
                    })
                    .map(function (checkbox) {
                        return checkbox.closest('tr').getAttribute('data-id');
                    });

                allSelectRowInput.value = selectedIds.join(',');
            }
        }
    </script>
@endsection