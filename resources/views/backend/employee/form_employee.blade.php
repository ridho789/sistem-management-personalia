@extends('frontend.layout.main')
<!-- @section('title', 'Form Employee') -->
@section('content')
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Hi, welcome back!</h4>
                    <p class="mb-1">Form Employee</p>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Management</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Form Employee</a></li>
                </ol>
            </div>
        </div>
        <!-- row -->
        <div class="row">
            <div class="col-lg-12">
                <div id="button-create-excel" class="mb-3" style="display: block;">
                    <a href="#" class="btn btn-light" id="button-create-employee-excel">Click to <b>create by Excel</b></a>
                </div>
                <div id="form-create-excel" class="card" style="display: none;">
                    <div class="card-header">
                        <h4 class="card-title">Import File Excel</h4>
                    </div>
                    <div class="card-body">
                        <div class="basic-form custom_file_input">
                            <form action="{{ url('import-excel-employee') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="input-group">
                                    <input type="file" name="file" class="form-control">
                                    <button class="btn btn-primary ml-1" type="submit">Import</button>
                                    <button class="btn btn-dark ml-1" type="button" id="close-form-create-excel">Cancel</button>
                                </div>
                                @error('file')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Log Importing Data -->
                @if($logErrors)
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Error Log</h4>
                    </div>
                    <div class="card-body">
                        <ul>@if(is_array($logErrors))
                                @foreach($logErrors as $logError)
                                    <li>{{ $logError }}</li>
                                @endforeach
                            @else
                            {{ $logErrors }}
                            @endif
                        </ul>
                    </div>
                </div>
                @endif
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Form Employee</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-validation-employee">
                            <!-- form edit employee -->
                            @if($employee)
                            <form class="form-valide-employee" action="{{ url('form-employee-update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <!-- Tambahkan input tersembunyi untuk ID karyawan yang akan diedit -->
                                <input type="hidden" name="id" value="{{ $employee->id_karyawan }}">
                                <div class="row">
                                    <div class="col-xl-6">
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label" for="val_name">Name
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <input type="text" class="form-control" 
                                                id="val_name" name="val_name" placeholder="Enter a name.." value="{{ old('val_name', $employee->nama_karyawan) }}" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label" for="val_nik">NIK<span
                                                    class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <input type="number" class="form-control @error('val_nik') is-invalid @enderror" 
                                                id="val_nik" name="val_nik" placeholder="Enter a NIK.." value="{{ old('val_nik', $employee->nik) }}" required>
                                                @error('val_nik')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label" for="val_place_birth">Place of birth
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <input type="text" class="form-control" 
                                                id="val_place_birth" name="val_place_birth" placeholder="Enter a place of birth.." value="{{ old('val_place_birth', $employee->tempat_lahir) }}" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label" for="val_date_birth">Date of birth
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <input type="date" class="form-control" 
                                                id="val_date_birth" min="1980-01-01" name="val_date_birth" value="{{ old('val_date_birth', $employee->tanggal_lahir) }}" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label" for="val_gender">Gender
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <select class="form-control" id="val_gender" name="val_gender" required>
                                                    <option value="">Please select</option>
                                                    <option value="male" {{ old('val_gender', $employee->jenis_kelamin) == 'male' ? 'selected' : '' }}>Male</option>
                                                    <option value="female" {{ old('val_gender', $employee->jenis_kelamin) == 'female' ? 'selected' : '' }}>Female</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label" for="val_phone">Phone
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">+62</div>
                                                    <input type="text" class="form-control" 
                                                    id="val_phone" name="val_phone" placeholder="000-0000-0000" value="{{ old('val_phone', $employee->no_telp) }}" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label" for="val_address">Address
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <textarea class="form-control" id="val_address" name="val_address" rows="5" 
                                                placeholder="Enter a address.." required>{{ old('val_address', $employee->alamat) }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6">
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label" for="val_photo">Photo
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <input type="file" class="form-control  @error('val_photo') is-invalid @enderror" 
                                                id="val_photo" name="val_photo">
                                                @error('val_photo')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                @if($employee->foto)
                                                    <img src="{{ asset($employee->foto) }}" alt="Employee Photo" width="100">
                                                @else
                                                    <p>No photo available.</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label" for="val_position">Position
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <select class="form-control" id="val_position" name="id_jabatan" required>
                                                    <option value="">Select a position...</option>
                                                    @foreach ($position as $p)
                                                        <option value="{{ $p->id_jabatan }}" {{ old('id_jabatan', $employee->id_jabatan) == $p->id_jabatan ? 'selected' : '' }}>{{ $p->nama_jabatan }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label" for="val_division">Division
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <select class="form-control" id="val_division" name="id_divisi" required>
                                                    <option value="">Select a division...</option>
                                                    @foreach ($division as $d)
                                                        <option value="{{ $d->id_divisi }}" {{ old('id_divisi', $employee->id_divisi) == $d->id_divisi ? 'selected' : '' }}>{{ $d->nama_divisi }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label" for="val_company">Company
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <select class="form-control" id="val_company" name="id_perusahaan" required>
                                                    <option value="">Select a company...</option>
                                                    @foreach ($company as $c)
                                                        <option value="{{ $c->id_perusahaan }}" {{ old('id_perusahaan', $employee->id_perusahaan) == $c->id_perusahaan ? 'selected' : '' }}>{{ $c->nama_perusahaan }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label" for="val_status">Status
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <select class="form-control" id="val_status" name="id_status" required>
                                                    <option value="">Select a status...</option>
                                                    @foreach ($statusEmployee as $s)
                                                        <option value="{{ $s->id_status }}" {{ old('id_status', $employee->id_status) == $s->id_status ? 'selected' : '' }}>{{ $s->nama_status }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label" for="val_idcard">ID Card
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <input type="number" class="form-control @error('val_idcard') is-invalid @enderror" 
                                                id="val_idcard" name="val_idcard" placeholder="Enter a ID card.." value="{{ old('val_idcard', $employee->id_card) }}" required>
                                                @error('val_idcard')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-lg-8 ml-auto">
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            @else
                            <!-- form create new employee -->
                            <form class="form-valide-employee" action="{{ url('form-employee-add') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-xl-6">
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label" for="val_name">Name
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <input type="text" class="form-control" 
                                                id="val_name" name="val_name" placeholder="Enter a name.." value="{{ old('val_name') }}" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label" for="val_nik">NIK<span
                                                    class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <input type="number" class="form-control @error('val_nik') is-invalid @enderror" 
                                                id="val_nik" name="val_nik" placeholder="Enter a NIK.." value="{{ old('val_nik') }}" required>
                                                @error('val_nik')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label" for="val_place_birth">Place of birth
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <input type="text" class="form-control" 
                                                id="val_place_birth" name="val_place_birth" placeholder="Enter a place of birth.." value="{{ old('val_place_birth') }}" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label" for="val_date_birth">Date of birth
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <input type="date" class="form-control" 
                                                id="val_date_birth" min="1980-01-01" name="val_date_birth" value="{{ old('val_date_birth') }}" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label" for="val_gender">Gender
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <select class="form-control" id="val_gender" name="val_gender" required>
                                                    <option value="">Please select</option>
                                                    <option value="male" {{ old('val_gender') == 'male' ? 'selected' : '' }}>Male</option>
                                                    <option value="female" {{ old('val_gender') == 'female' ? 'selected' : '' }}>Female</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label" for="val_phone">Phone
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">+62</div>
                                                    <input type="text" class="form-control" 
                                                    id="val_phone" name="val_phone" placeholder="000-0000-0000" value="{{ old('val_phone') }}" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label" for="val_address">Address
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <textarea class="form-control" id="val_address" name="val_address" rows="5" 
                                                placeholder="Enter a address.." required>{{ old('val_address') }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6">
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label" for="val_photo">Photo
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <input type="file" class="form-control  @error('val_photo') is-invalid @enderror" 
                                                id="val_photo" name="val_photo" required>
                                                @error('val_photo')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label" for="val_position">Position
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <select class="form-control" id="val_position" name="id_jabatan" required>
                                                    <option value="">Select a position...</option>
                                                    @foreach ($position as $p)
                                                        <option value="{{ $p->id_jabatan }}" {{ old('id_jabatan') == $p->id_jabatan ? 'selected' : '' }}>{{ $p->nama_jabatan }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label" for="val_division">Division
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <select class="form-control" id="val_division" name="id_divisi" required>
                                                    <option value="">Select a division...</option>
                                                    @foreach ($division as $d)
                                                        <option value="{{ $d->id_divisi }}" {{ old('id_divisi') == $d->id_divisi ? 'selected' : '' }}>{{ $d->nama_divisi }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label" for="val_company">Company
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <select class="form-control" id="val_company" name="id_perusahaan" required>
                                                    <option value="">Select a company...</option>
                                                    @foreach ($company as $c)
                                                        <option value="{{ $c->id_perusahaan }}" {{ old('id_perusahaan') == $c->id_perusahaan ? 'selected' : '' }}>{{ $c->nama_perusahaan }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label" for="val_status">Status
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <select class="form-control" id="val_status" name="id_status" required>
                                                    <option value="">Select a status...</option>
                                                    @foreach ($statusEmployee as $s)
                                                        <option value="{{ $s->id_status }}" {{ old('id_status') == $s->id_status ? 'selected' : '' }}>{{ $s->nama_status }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label" for="val_idcard">ID Card
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <input type="number" class="form-control @error('val_idcard') is-invalid @enderror" 
                                                id="val_idcard" name="val_idcard" placeholder="Enter a ID card.." value="{{ old('val_idcard') }}" required>
                                                @error('val_idcard')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-lg-8 ml-auto">
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // API Indonesian Identification Card (KTP)
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('val_nik').addEventListener('blur', function () {
                // Ambil NIK yang dimasukkan oleh pengguna
                var nik = document.getElementById('val_nik').value;

                // Kirim permintaan ke API KTP
                fetch('https://indonesian-identification-card-ktp.p.rapidapi.com/api/check?nik=' + nik, {
                    method: 'GET',
                    headers: {
                        'X-RapidAPI-Host': 'indonesian-identification-card-ktp.p.rapidapi.com',
                        'X-RapidAPI-Key': 'bb98f2613fmsh4953f916c3d0c17p1e4126jsn872e2cabdbf7',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    // Isi data formulir secara otomatis dengan data yang diterima dari API
                    getData = data['results']['parse_data']

                    if (getData){
                        // format tanggal lahir
                        var dateOfBirth1 = getData['tanggal_lahir'];
                        var dateParts = dateOfBirth1.split('/')
                        var formattedDate = dateParts[2] + '-' + dateParts[1] + '-' + dateParts[0];
    
                        // gender
                        var gender = getData['jenis_kelamin']
                        var jns_kelamin = ''
    
                        if(gender == 'LAKI-LAKI'){
                            jns_kelamin = 'male'
                        }else{
                            jns_kelamin = 'female'
                        }
    
                        document.getElementById('val_date_birth').value = formattedDate;
                        document.getElementById('val_gender').value = jns_kelamin;
                    }
                    
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });
        });

        var divisionSelect = document.getElementById("val_division");
        divisionSelect.addEventListener("change", function() {
            // Ambil nilai terpilih dari <select>
            var selectedDivision = divisionSelect.value;
            if(divisionSelect.value.length == 1){
                selectedDivision = '0'+ selectedDivision
            }
            $('#val_idcard').val(selectedDivision);
        });

        // show/hide form create by excel
        const toggleFormButton = document.getElementById('button-create-employee-excel');
        const toggleCloseButton = document.getElementById('button-create-excel')
        const toggleCloseFormButton = document.getElementById('close-form-create-excel');
        const myForm = document.getElementById('form-create-excel');

        toggleFormButton.addEventListener('click', function() {
            if (myForm.style.display === 'none') {
                myForm.style.display = 'block';
            }

            if (toggleCloseButton.style.display === 'block') {
                toggleCloseButton.style.display = 'none';
            }
        });

        toggleCloseFormButton.addEventListener('click', function() {
            if (myForm.style.display === 'block') {
                myForm.style.display = 'none';
            }

            if (toggleCloseButton.style.display === 'none') {
                toggleCloseButton.style.display = 'block';
            }
        });
    </script>
@endsection