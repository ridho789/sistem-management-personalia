@extends('frontend.layout.main')
<!-- @section('title', 'Collective Leave') -->
@section('content')
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Hi, welcome back!</h4>
                    <span class="ml-1">Collective Leave</span>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Management</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Collective Leave</a></li>
                </ol>
            </div>
        </div>
        <!-- row -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">                           
                        <h4 class="card-title">Collective Leave</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ url('collective-leave-search') }}" method="GET">
                            @csrf
                            <label>Set filter base on division and date collective leave</label>
                            <input type="hidden" name="information" id="information" value="">
                            <div class="form-group row">
                                <div class="col-sm-3 mb-2">
                                    <select class="form-control" id="val_division" name="id_divisi" required>
                                        <option value="">Select a division...</option>
                                        <option value="all">All Division</option>
                                        @foreach ($division as $d)
                                            <option value="{{ $d->id_divisi }}"  
                                                {{ old('id_divisi') == $d->id_divisi ? 'selected' : '' }}>
                                                {{ $d->nama_divisi }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-2 mb-2">
                                        <input type="date" class="form-control" 
                                            id="val_date_collective_leave" min="2022-01-01" name="val_date_collective_leave" 
                                            value="{{ old('val_date_collective_leave') }}" required>
                                    </div>
                                <div class="col-sm-3">
                                    <button type="submit" class="btn btn-primary" id="searchButton" disabled>Search</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    @if (count($employee) > 0)
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-responsive-sm" id="data-table-collective">
                                    <thead>
                                        <tr>
                                            <th width=5%>
                                                <input type="checkbox" id="selectAllCheckbox">
                                            </th>
                                            <th>Employee</th>
                                            <th>ID Card</th>
                                            <th>Position</th>
                                            <th>Division</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($employee as $e)
                                            <tr data-id="{{$e->id_karyawan}}">
                                                <td><input type="checkbox" class="select-checkbox"></td>
                                                <td>{{ $e->nama_karyawan }}</td>
                                                <td>{{ $e->id_card }}</td>
                                                <td>{{ $namePosistion[$e->id_jabatan] }}</td>
                                                <td>{{ $nameDivision[$e->id_divisi] }}</td>
                                                <td>{{ $nameStatus[$e->id_status] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ url('collective-leave-add') }}" method="POST">
                                @csrf
                                <label>Create leave for {{ $informationcollective }}</label>
                                <input type="hidden" id="allSelectRow" name="allSelectRow" value="">
                                <input type="hidden" id="dateCollective" name="dateCollective" value="{{ $collectiveDate }}">
                                <input type="hidden" id="descCollective" name="descCollective" value="{{ $collectiveName }}">
                                <div class="form-group row">
                                    <div class="col-sm-3">
                                        <button type="submit" class="btn btn-secondary" id="searchButtonCreate" disabled>Create</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
                @if ($logError)
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Log Information</h4>
                        </div>
                        <div class="card-body">
                            <span style="text-align: center;">
                                {{ $logError }}
                            </span>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var dateCollective = document.getElementById('val_date_collective_leave');
            var searchButton = document.getElementById('searchButton');
            var collectivesData;

            // Ambil data libur nasional saat halaman dimuat
            fetchcollectivesData();

            dateCollective.addEventListener('input', function () {
                var selectedDate = dateCollective.value;

                // Memisahkan tahun, bulan, dan hari
                var parts = selectedDate.split("-");
                var year = parts[0];
                var month = parts[1];
                var day = parts[2];

                // Menghilangkan angka 0 di depan hari jika ada
                day = parseInt(day, 10);

                // Menggabungkan kembali dengan format yang diinginkan
                var formattedDate = year + "-" + month + "-" + day;

                // Validasi apakah tanggal tersebut merupakan hari libur nasional
                var isCollective = checkIfCollective(formattedDate);
                var collectives = getCollectives(formattedDate);

                // Mengambil tanggal dan nama libur dari hasil filter
                var collectiveInfo = collectives.map(collective => {
                    return {
                        collective_date: collective.tanggal,
                        collective_name: collective.keterangan
                    };
                });

                // Mengatur nilai input hidden
                document.getElementById("information").value = JSON.stringify(collectiveInfo);

                // Aktifkan atau nonaktifkan tombol berdasarkan hasil validasi
                searchButton.disabled = !isCollective;
            });

            // Function untuk cek apakah tanggal yang diinput termasuk hari libur nasional?
            async function fetchcollectivesData() {
                try {
                    const apiUrl = 'https://dayoffapi.vercel.app/api';
                    const response = await fetch(apiUrl);
                    collectivesData = await response.json();

                } catch (error) {
                    console.error('Error fetching collectives data:', error);
                }
            }

            function checkIfCollective(date) {
                // Cek apakah tanggal termasuk dalam data libur nasional
                return collectivesData.some(collective => collective.tanggal === date && collective.is_cuti);
            }

            function getCollectives(date) {
                // Mengambil libur nasional sesuai dengan tanggal
                return collectivesData.filter(collective => collective.tanggal === date && collective.is_cuti);
            }

            var table = document.getElementById('data-table-collective');
            var checkboxes;
            var selectAllCheckbox = document.getElementById('selectAllCheckbox');
            var searchButtonCreate = document.getElementById('searchButtonCreate');
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

                    // Aktifkan atau nonaktifkan tombol "Create" berdasarkan status "Select All"
                    searchButtonCreate.disabled = !this.checked;

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

                        // Aktifkan atau nonaktifkan tombol "Create" berdasarkan hasil pemeriksaan
                        searchButtonCreate.disabled = !atLeastOneChecked;

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
        });
    </script>
@endsection