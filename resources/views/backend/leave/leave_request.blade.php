@extends('frontend.layout.main')
<!-- @section('title', 'Leave Request') -->
@section('content')
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Hi, welcome back!</h4>
                    <p class="mb-1">Leave Request</p>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Management</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Leave Request</a></li>
                </ol>
            </div>
        </div>
        <!-- row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        @if($dataleave)
                            <h4 class="card-title">Leave Request 
                                @if($dataleave->status_cuti == 'Approved')
                                    <span class="text-primary" style="font-size: small;">({{ $dataleave->status_cuti }})</span>
                                @elseif ($dataleave->status_cuti == 'To Approved')
                                    <span class="text-secondary" style="font-size: small;">({{ $dataleave->status_cuti }})</span>
                                @elseif ($dataleave->status_cuti == 'Cancelled')
                                    <span class="text-warning" style="font-size: small;">({{ $dataleave->status_cuti }})</span>
                                @endif
                            </h4>
                        @else
                            <h4 class="card-title">Leave Request</h4>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="form-validation-attendance">
                            <!-- form edit leave request -->
                            @if($dataleave)
                            <form class="form-leave-request" action="{{ url('leave-request-update') }}" 
                            method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="id" value="{{ $dataleave->id_data_cuti }}">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Employee</label>
                                    <div class="col-sm-5 mb-2">
                                        <input type="text" id="search_employee" class="form-control" placeholder="Search employee...">
                                    </div>
                                    <div class="col-sm-5">
                                        <select class="form-control" id="val_employee" name="id_karyawan" required>
                                            <option value="">Select an employee...</option>
                                            @foreach ($employee as $e)
                                                <option value="{{ $e->id_karyawan }}"
                                                    data-position="{{ $position[$e->id_jabatan] ?? '' }}"
                                                    data-division="{{ $division[$e->id_divisi] ?? '' }}"
                                                    {{ old('id_karyawan', $dataleave->id_karyawan ?? '') == $e->id_karyawan ? 'selected' : '' }}>
                                                    {{ $e->nama_karyawan }} - {{ $e->id_card }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row" style="margin-top: -15px;">
                                    <div class="col-sm-2"></div>
                                    <div class="col-sm-5">
                                        <label class="col-form-label">Position</label>
                                        <div>
                                            <input type="text" class="form-control" id="position" name="position" 
                                            placeholder="Position" readonly>
                                        </div>
                                    </div>
                                    <div class="col-sm-5">
                                        <label class="col-form-label">Division</label>
                                        <div>
                                            <input type="text" class="form-control" id="division" name="division" 
                                            placeholder="Division" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Responsible</label>
                                    <div class="col-sm-10">
                                        <select class="form-control" id="val_responsible" name="id_penangung_jawab" required>
                                            <option value="">Select a employee...</option>
                                            @foreach ($employee as $e)
                                                <option value="{{ $e->id_karyawan }}" data-position="{{ $position[$e->id_jabatan] }}" 
                                                    data-division="{{ $division[$e->id_divisi] }}" 
                                                    {{ old('id_karyawan', $dataleave->id_penangung_jawab) == 
                                                        $e->id_karyawan ? 'selected' : '' }}>
                                                    {{ $e->nama_karyawan }} - {{ $e->id_card }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Description</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="deskripsi" 
                                        value="{{ old('deskripsi', $dataleave->deskripsi) }}" 
                                        placeholder="Description.." required>
                                    </div>
                                </div>
                                <fieldset class="form-group">
                                    <div class="row">
                                        <label class="col-sm-2 col-form-label">Leave Type</label>
                                        <div class="col-sm-10">
                                            <select class="form-control" id="val_type_leave" name="id_tipe_cuti" required>
                                                <option value="">Select a type leave...</option>
                                                @foreach ($typeLeave as $tl)
                                                    <option value="{{ $tl->id_tipe_cuti }}" data-type-leave="{{ $tl->nama_tipe_cuti }}" 
                                                        {{ old('id_tipe_cuti', $dataleave->id_tipe_cuti) == 
                                                            $tl->id_tipe_cuti ? 'selected' : '' }}>
                                                        {{ $tl->nama_tipe_cuti }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </fieldset>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Duration</label>
                                    <!-- start -->
                                    <div class='col-sm-5 input-group date mb-2'>
                                        <input type='datetime-local' name="datetimestart" id="datetimestart" class="form-control" 
                                        value="{{ old('datetimestart', $dataleave->mulai_cuti) }}"/>
                                    </div>
                                    <!-- end -->
                                    <div class='col-sm-5 input-group date'>
                                        <input type='datetime-local' name="datetimeend" id="datetimeend" class="form-control" 
                                        value="{{ old('datetimeend', $dataleave->selesai_cuti) }}"/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label"></label>
                                    <div class="col-sm-10">
                                        <div class="input-group-prepend">
                                            <input type="text" class="form-control" id="duration" name="duration" 
                                            value="{{ old('duration', $dataleave->durasi_cuti) }}" readonly>
                                            <span class="input-group-text">days</span>
                                        </div>
                                    </div>
                                </div>
                                <div id="attach_file" style="display: none;">
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Attach File</label>
                                        <div class="col-sm-10">
                                            <input type="file" class="form-control @error('file') is-invalid @enderror" 
                                            id="file" name="file">
                                            @error('file')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            @if($dataleave->file)
                                                <img src="{{ asset('storage/'. $dataleave->file) }}" alt="Attach File" width="100">
                                            @else
                                                <p>No photo available.</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @if (!in_array($dataleave->status_cuti, ['Cancelled', 'Approved']))
                                    <div class="form-group row mt-5">
                                        <div class="col-sm-10">
                                            <button type="submit" id="btnUpdate" class="btn btn-primary mr-1">Update</button>
                                        </div>
                                    </div>
                                @else
                                    <div class="form-group row mt-5">
                                        <div class="col-sm-10">
                                            <span>This request has been <b>{{ $dataleave->status_cuti }}</b> and <b>cannot</b> be updated.</span>
                                            
                                            @if ($dataleave->reason)
                                                <br><span>Reason for leave cancellation: <b>{{ $dataleave->reason }}</b>.</span>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </form>
                            @else
                            <!-- form add leave request -->
                            <form class="form-leave-request" action="{{ url('leave-request-add') }}" 
                            method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Employee</label>
                                    <div class="col-sm-5 mb-2">
                                        <input type="text" id="search_employee" class="form-control" placeholder="Search employee...">
                                    </div>
                                    <div class="col-sm-5">
                                        <select class="form-control" id="val_employee" name="id_karyawan" required>
                                            <option value="">Select a employee...</option>
                                            @foreach ($employee as $e)
                                                <option value="{{ $e->id_karyawan }}" data-position="{{ $position[$e->id_jabatan] ?? '' }}" 
                                                    data-division="{{ $division[$e->id_divisi] ?? '' }}" 
                                                    {{ old('id_karyawan') == $e->id_karyawan ? 'selected' : '' }}>
                                                    {{ $e->nama_karyawan }} - {{ $e->id_card }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row" style="margin-top: -15px;">
                                    <div class="col-sm-2"></div>
                                    <div class="col-sm-5">
                                        <label class="col-form-label">Position</label>
                                        <div>
                                            <input type="text" class="form-control" id="position" name="position" 
                                            placeholder="Position" readonly>
                                        </div>
                                    </div>
                                    <div class="col-sm-5">
                                        <label class="col-form-label">Division</label>
                                        <div>
                                            <input type="text" class="form-control" id="division" name="division" 
                                            placeholder="Division" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Responsible</label>
                                    <div class="col-sm-10">
                                        <select class="form-control" id="val_responsible" name="id_penangung_jawab" required>
                                            <option value="">Select a responsible...</option>
                                            @foreach ($employee as $e)
                                                <option value="{{ $e->id_karyawan }}" data-position="{{ $position[$e->id_jabatan] }}" 
                                                    data-division="{{ $division[$e->id_divisi] }}" 
                                                    {{ old('id_karyawan') == $e->id_karyawan ? 'selected' : '' }}>
                                                    {{ $e->nama_karyawan }} - {{ $e->id_card }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Description</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="deskripsi" value="{{ old('deskripsi') }}" 
                                        placeholder="Description.." required>
                                    </div>
                                </div>
                                <fieldset class="form-group">
                                    <div class="row">
                                        <label class="col-sm-2 col-form-label">Leave Type</label>
                                        <div class="col-sm-10">
                                            <select class="form-control" id="val_type_leave" name="id_tipe_cuti" required>
                                                <option value="">Select a type leave...</option>
                                                @foreach ($typeLeave as $tl)
                                                    <option value="{{ $tl->id_tipe_cuti }}" data-type-leave="{{ $tl->nama_tipe_cuti }}" 
                                                        {{ old('id_tipe_cuti') == $tl->id_tipe_cuti ? 'selected' : '' }}>
                                                        {{ $tl->nama_tipe_cuti }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </fieldset>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Duration</label>
                                    <!-- start -->
                                    <div class='col-sm-5 input-group date mb-2'>
                                        <input type='datetime-local' name="datetimestart" id="datetimestart" class="form-control"/>
                                    </div>
                                    <!-- end -->
                                    <div class='col-sm-5 input-group date'>
                                        <input type='datetime-local' name="datetimeend" id="datetimeend" class="form-control"/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label"></label>
                                    <div class="col-sm-10">
                                        <div class="input-group-prepend">
                                            <input type="text" class="form-control" id="duration" name="duration" readonly>
                                            <span class="input-group-text">days</span>
                                        </div>
                                    </div>
                                </div>
                                <div id="attach_file" style="display: none;">
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Attach File</label>
                                        <div class="col-sm-10">
                                            <input type="file" class="form-control @error('file') is-invalid @enderror" 
                                            id="file" name="file">
                                            @error('file')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row mt-5">
                                    <div class="col-sm-10">
                                        <button type="submit" id="btnSubmit" class="btn btn-primary">Submit</button>
                                    </div>
                                </div>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>

                @if(session()->has('errorInfo'))
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Log Information</h4>
                        </div>
                        <div class="card-body">
                            {!! session('errorInfo') !!}
                        </div>
                    </div>
                @endif
                
                @if (Auth::check())
                    @if (Auth::user()->level == 1)
                        @if($dataleave)
                            @if (!in_array($dataleave->status_cuti, ['Cancelled', 'Approved']))
                                <div class="card">
                                    <div class="card-body">
                                        <div class="form-group row">
                                            <div class="col-sm-6">
                                                <form action="{{ url('leave-request-print') }}" method="POST">
                                                    @csrf
                                                    <div class="form-group row">
                                                        <input type="hidden" name="id_data_cuti" value="{{ $dataleave->id_data_cuti }}">
                                                        <label class="col-sm-12 col-form-label">Click the button below to download 
                                                            the leave permit and send it to the Responsible in charge for processing..</label>
                                                        <div class="col-sm-12">
                                                            <!-- <button type="submit" id="pdf_leave_request" class="btn btn-rounded btn-secondary">
                                                                <span class="btn-icon-left text-primary">
                                                                    <i class="fa fa-download color-primary"></i>
                                                                </span>Download PDF
                                                            </button> -->
                                                            <button type="submit" class="btn btn-secondary">Download PDF</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="col-sm-6">
                                                <form action="{{ url('leave-request-upload') }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="form-group row">
                                                        <input type="hidden" name="id_data_cuti" value="{{ $dataleave->id_data_cuti }}">
                                                        <label class="col-sm-12 col-form-label">
                                                            <span class="text-danger">*</span>
                                                            Attach the leave application form file that has been approved
                                                        </label>
                                                        <div class="col-sm-8 mb-2">
                                                            <input type="file" class="form-control @error('file_approved') is-invalid @enderror" 
                                                            id="file_approved" name="file_approved">
                                                            @error('file_approved')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <button type="submit" class="btn btn-primary">Upload</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif
                    @endif
                @endif
            </div>
        </div>
    </div>
    <script>
        // Fungsi untuk memfilter opsi Responsible
        function filterResponsibleOptions(selectedEmployeeId) {
            const responsibleSelect = document.getElementById("val_responsible");
            const employeeSelect = document.getElementById("val_employee");

            // Dapatkan data position dari employee yang dipilih
            const selectedEmployeeOption = employeeSelect.querySelector(`option[value="${selectedEmployeeId}`);
            const selectedEmployeePosition = selectedEmployeeOption.getAttribute("data-position");
            const selectedEmployeeDivision = selectedEmployeeOption.getAttribute("data-division");

            // Mendapatkan semua opsi Responsible
            const responsibleOptions = responsibleSelect.getElementsByTagName("option");

            // Iterasi melalui opsi Responsible
            for (let i = 1; i < responsibleOptions.length; i++) {
                const responsibleOption = responsibleOptions[i];
                const responsiblePosition = responsibleOption.getAttribute("data-position").toLowerCase();
                const responsibleDivision = responsibleOption.getAttribute("data-division");

                if (((responsiblePosition === "manager" || responsiblePosition === "supervisor") && 
                    selectedEmployeeDivision === responsibleDivision) || responsiblePosition === "hrd") {
                    responsibleOption.style.display = 'block'
                } else {
                    responsibleOption.style.display = 'none'
                }
            }
        }

        // Add event listener for input changes
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
        
        document.addEventListener('DOMContentLoaded', function () {
            // Ambil elemen berdasarkan ID
            var valEmployee = document.getElementById('val_employee');
            var positionInput = document.getElementById('position');
            var divisionInput = document.getElementById('division');

            // Fungsi untuk mengatur nilai posisi dan divisi
            function updateValues() {
                var selectedOption = valEmployee.options[valEmployee.selectedIndex];
                var position = selectedOption ? selectedOption.getAttribute('data-position') : '';
                var division = selectedOption ? selectedOption.getAttribute('data-division') : '';

                positionInput.value = position;
                divisionInput.value = division;

                // Inisialisasi filter
                const selectedEmployeeId = valEmployee.value;
                filterResponsibleOptions(selectedEmployeeId);
            }

            // Tambahkan event listener untuk perubahan pada elemen valEmployee
            valEmployee.addEventListener('change', updateValues);

            // Panggil fungsi updateValues saat halaman pertama kali dimuat
            updateValues();
        });

        const datetimestart = document.getElementById('datetimestart');
        const datetimeend = document.getElementById('datetimeend');
        const durationInput = document.getElementById('duration');
        // Ambil tanggal saat ini dalam format datetime-local
        const currentDate = new Date().toISOString().slice(0, 16); 

        // Mengambil nilai default dari elemen datetimestart dan datetimeend
        let defaultValueStart = datetimestart.value;
        let defaultValueEnd = datetimeend.value;

        const defaultDate = new Date();
        const year = defaultDate.getFullYear();
        const month = (defaultDate.getMonth() + 1).toString().padStart(2, '0');
        const day = defaultDate.getDate().toString().padStart(2, '0');
        const hours = defaultDate.getHours().toString().padStart(2, '0');
        const minutes = defaultDate.getMinutes().toString().padStart(2, '0');

        // Jika elemen datetimestart kosong (atau null), maka kita mengambil waktu default
        if (!defaultValueStart) {
            // Set default datetime start
            defaultDate.setHours(8, 0, 0, 0); // Atur jam ke 08:00:00
            defaultValueStart = `${year}-${month}-${day}T08:00`;
        }

        if (!defaultValueEnd) {
            // Set default datetime end
            defaultDate.setHours(17, 0, 0, 0); // Atur jam ke 17:00:00
            const hoursEnd = defaultDate.getHours().toString().padStart(2, '0');
            defaultValueEnd = `${year}-${month}-${day}T${hoursEnd}:00`;
        }

        datetimestart.value = defaultValueStart;
        datetimeend.value = defaultValueEnd;

        // batasi agar tidak dapat memilih dibawah tanggal defaultValueStart (tidak dipakai)
        // datetimestart.addEventListener('input', function() {
        //     const selectedDate = this.value;
        //     if (selectedDate < defaultValueStart) {
        //         this.value = defaultValueStart;
        //     }
        // });

        // batasi agar datetimeend tidak di bawah datetimestart
        datetimeend.addEventListener('input', function() {
            const startDate = new Date(datetimestart.value);
            const endDate = new Date(this.value);

            if (endDate < startDate) {
                this.value = datetimestart.value;
            }
        });

        // hitung selisih waktu (datetimestart, datetimeend)
        datetimestart.addEventListener('input', calculateTimeDifference);
        datetimeend.addEventListener('input', calculateTimeDifference);

        // Hitung selisih waktu saat halaman dimuat
        calculateTimeDifference();

        function calculateTimeDifference() {
            const startValue = new Date(datetimestart.value);
            const endValue = new Date(datetimeend.value);

            var btnSubmit = document.getElementById("btnSubmit");
            var btnUpdate = document.getElementById("btnUpdate");

            if (!isNaN(startValue) && !isNaN(endValue) && startValue < endValue) {
                // Mengabaikan waktu di luar rentang 08:00 - 17:00
                if (startValue.getHours() < 8) {
                    startValue.setHours(8, 0, 0, 0); // Set waktu mulai ke 08:00
                }
                if (endValue.getHours() > 17 || (endValue.getHours() === 17 && endValue.getMinutes() > 0)) {
                    endValue.setHours(17, 0, 0, 0); // Set waktu akhir ke 17:00
                }
                
                const diffInMilliseconds = endValue - startValue;
                const diffInSeconds = diffInMilliseconds / 1000;
                const diffInMinutes = diffInSeconds / 60;
                const diffInHours = diffInMinutes / 60;

               // Menghitung selisih hari
                const daysDifference = Math.floor(diffInHours / 24);

                let hours = Math.floor(diffInHours % 24);
                
                // Modifikasi perhitungan
                if (startValue.getHours() <= 12 && endValue.getHours() >= 13) {
                    // Jika waktu dimulai sebelum atau pada jam 12 dan berakhir setelah jam 13
                    hours -= 1; // Mengurangi 1 jam
                }
                
                // Hitung jumlah hari berdasarkan jam kerja (8 jam = 1 hari)
                const workingHoursPerDay = 8;
                const workingDays = ((daysDifference * workingHoursPerDay) + hours) / workingHoursPerDay
                // const totalWorkingHours = (daysDifference * workingHoursPerDay) + hoursDifference;

                durationInput.value = workingDays

                btnSubmit && (btnSubmit.disabled = false);
                btnUpdate && (btnUpdate.disabled = false);


            } else {
                durationInput.value = 0

                // Disabled button submit or update
                btnSubmit && (btnSubmit.disabled = true);
                btnUpdate && (btnUpdate.disabled = true);
            }
        }

        // Munculkan column attach file jika tipe cuti = sakit
        document.addEventListener("DOMContentLoaded", function () {
            const leaveTypeSelect = document.getElementById("val_type_leave");
            const attachFileSection = document.getElementById("attach_file");
            const attachFileColumn = document.getElementById("file");

            // Dapatkan tahun saat ini
            var currentYear = new Date().getFullYear();

            // Loop melalui opsi dan sembunyikan opsi legal leave/cuti tahunan jika tidak sama dengan tahun saat ini hide
            for (var i = 1; i < leaveTypeSelect.options.length; i++) {
                var option = leaveTypeSelect.options[i];
                var typeLeaveText = option.getAttribute('data-type-leave');
                
                if (typeLeaveText.toLowerCase().includes('legal leave') || typeLeaveText.toLowerCase().includes('cuti tahunan')) {
                    var yearInOption = parseInt(typeLeaveText.match(/\d+/)[0]);
    
                    if (yearInOption && yearInOption !== currentYear) {
                        option.style.display = 'none';
                    }
                }
            }

            // Function to toggle the visibility of the "Attach File" section
            function toggleAttachFileVisibility() {
                const selectedOption = leaveTypeSelect.options[leaveTypeSelect.selectedIndex];
                
                if (selectedOption.getAttribute("data-type-leave")) {
                    const typeLeave = selectedOption.getAttribute("data-type-leave").toLowerCase();

                    if (typeLeave.includes("sick") || typeLeave.includes("sakit")) {
                        attachFileSection.style.display = "block";
                        attachFileColumn.setAttribute('required', 'required');

                    } else {
                        attachFileSection.style.display = "none";
                        attachFileColumn.removeAttribute('required');
                    }
                }
            }

            // Initial visibility setup
            toggleAttachFileVisibility();

            // Listen for changes in the "Leave Type" dropdown
            leaveTypeSelect.addEventListener("change", toggleAttachFileVisibility);
        });

    </script>

@endsection