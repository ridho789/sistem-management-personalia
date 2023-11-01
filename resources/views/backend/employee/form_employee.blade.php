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
                            <form class="form-valide-employee" action="{{ url('form-employee-update') }}" 
                                method="POST" enctype="multipart/form-data">
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
                                                id="val_name" name="val_name" placeholder="Enter a name.." 
                                                value="{{ old('val_name', $employee->nama_karyawan) }}" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label" for="val_nik">NIK<span
                                                    class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <input type="number" class="form-control @error('val_nik') is-invalid @enderror" 
                                                id="val_nik" name="val_nik" placeholder="Enter a NIK.." 
                                                value="{{ old('val_nik', $employee->nik) }}" required>
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
                                                id="val_place_birth" name="val_place_birth" placeholder="Enter a place of birth.." 
                                                value="{{ old('val_place_birth', $employee->tempat_lahir) }}" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label" for="val_date_birth">Date of birth
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <input type="date" class="form-control" 
                                                id="val_date_birth" min="1980-01-01" name="val_date_birth" 
                                                value="{{ old('val_date_birth', $employee->tanggal_lahir) }}" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label" for="val_gender">Gender
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <select class="form-control" id="val_gender" name="val_gender" required>
                                                    <option value="">Please select</option>
                                                    <option value="male" {{ old('val_gender', $employee->
                                                        jenis_kelamin) == 'male' ? 'selected' : '' }}>Male
                                                    </option>
                                                    <option value="female" {{ old('val_gender', $employee->
                                                        jenis_kelamin) == 'female' ? 'selected' : '' }}>Female
                                                    </option>
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
                                                    id="val_phone" name="val_phone" placeholder="000-0000-0000" 
                                                    value="{{ old('val_phone', $employee->no_telp) }}" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label" for="val_address">Address
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <textarea class="form-control" id="val_address" name="val_address" rows="5" 
                                                    placeholder="Enter a address.." required>{{ old('val_address', $employee->alamat) }}
                                                </textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6">
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label" for="val_photo">Photo
                                                <span class="text-primary">(Optional)</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <input type="file" class="form-control  @error('val_photo') is-invalid @enderror" 
                                                id="val_photo" name="val_photo">
                                                @error('val_photo')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                @if($employee->foto)
                                                    <img src="{{ asset('storage/'. $employee->foto) }}" alt="Employee Photo" width="100">
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
                                                        <option value="{{ $p->id_jabatan }}" {{ old('id_jabatan', $employee->
                                                            id_jabatan) == $p->id_jabatan ? 'selected' : '' }}>{{ $p->nama_jabatan }}
                                                        </option>
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
                                                        <option value="{{ $d->id_divisi }}" data-code-division="{{ $d->kode_divisi }}" 
                                                            {{ old('id_divisi', $employee->id_divisi) == $d->
                                                            id_divisi ? 'selected' : '' }}>{{ $d->nama_divisi }}
                                                        </option>
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
                                                        <option value="{{ $c->id_perusahaan }}" {{ old('id_perusahaan', $employee->id_perusahaan) == $c->
                                                            id_perusahaan ? 'selected' : '' }}>{{ $c->nama_perusahaan }}
                                                        </option>
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
                                                        <option value="{{ $s->id_status }}" data-status="{{ $s->nama_status }}" 
                                                            data-code-status="{{ $s->kode_status }}" 
                                                            {{ old('id_status', $employee->id_status) == $s->id_status ? 'selected' : '' }}>{{ $s->nama_status }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div id="basic_salary">
                                            <div class="form-group row">
                                                <label class="col-lg-4 col-form-label">Basic Salary
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <div class="col-lg-6">
                                                    <input type="text" class="form-control val_basic_salary" placeholder="Enter a basic salary.." 
                                                    id="val_basic_salary" name="val_basic_salary" value="{{ old('val_basic_salary', $employee->gaji_pokok) }}" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="start_joining">
                                            <div class="form-group row">
                                                <label class="col-lg-4 col-form-label">Start Joining
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <div class="col-lg-6">
                                                    <input type="date" class="form-control" 
                                                    id="val_start_joining" name="val_start_joining" 
                                                    value="{{ old('val_start_joining', $employee->awal_bergabung) }}" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-4" id="contract_status" style="display: none;">
                                            <div class="form-group row">
                                                <label class="col-lg-4 col-form-label"></label>
                                                <div class="col-lg-6">
                                                    <label>Term Contract</label>
                                                    <input type="text" class="form-control" 
                                                    id="val_term_contract" name="val_term_contract" placeholder="Enter a term contract.." 
                                                    value="{{ old('val_term_contract', $employee->lama_kontrak) }}">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-lg-4 col-form-label"></label>
                                                <div class="col-lg-6">
                                                    <label>Start of Contract Period</label>
                                                    <input type="date" class="form-control" 
                                                    id="val_start_contract" name="val_start_contract" 
                                                    value="{{ old('val_start_contract', $employee->awal_masa_kontrak) }}">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-lg-4 col-form-label"></label>
                                                <div class="col-lg-6">
                                                    <label>End of Contract Period</label>
                                                    <input type="date" class="form-control" 
                                                    id="val_end_contract" name="val_end_contract" 
                                                    value="{{ old('val_end_contract', $employee->akhir_masa_kontrak) }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label" for="val_idcard">ID Card
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <input type="number" class="form-control @error('val_idcard') is-invalid @enderror" 
                                                id="val_idcard" name="val_idcard" placeholder="Enter a ID card.." 
                                                value="{{ old('val_idcard', $employee->id_card) }}" required>
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
                            <form class="form-valide-employee" action="{{ url('form-employee-add') }}" 
                                method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-xl-6">
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label" for="val_name">Name
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <input type="text" class="form-control" 
                                                id="val_name" name="val_name" placeholder="Enter a name.." 
                                                value="{{ old('val_name') }}" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label" for="val_nik">NIK<span
                                                    class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <input type="number" class="form-control @error('val_nik') is-invalid @enderror" 
                                                id="val_nik" name="val_nik" placeholder="Enter a NIK.." 
                                                value="{{ old('val_nik') }}" required>
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
                                                id="val_place_birth" name="val_place_birth" placeholder="Enter a place of birth.." 
                                                value="{{ old('val_place_birth') }}" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label" for="val_date_birth">Date of birth
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <input type="date" class="form-control" 
                                                id="val_date_birth" min="1980-01-01" name="val_date_birth" 
                                                value="{{ old('val_date_birth') }}" required>
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
                                                    id="val_phone" name="val_phone" placeholder="000-0000-0000" 
                                                    value="{{ old('val_phone') }}" required>
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
                                                <span class="text-primary">(Optional)</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <input type="file" class="form-control  @error('val_photo') is-invalid @enderror" 
                                                id="val_photo" name="val_photo">
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
                                                        <option value="{{ $p->id_jabatan }}" 
                                                            {{ old('id_jabatan') == $p->id_jabatan ? 'selected' : '' }}>{{ $p->nama_jabatan }}
                                                        </option>
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
                                                        <option value="{{ $d->id_divisi }}" data-code-division="{{ $d->kode_divisi }}"
                                                            {{ old('id_divisi') == $d->id_divisi ? 'selected' : '' }}>{{ $d->nama_divisi }}
                                                        </option>
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
                                                        <option value="{{ $c->id_perusahaan }}" 
                                                            {{ old('id_perusahaan') == $c->id_perusahaan ? 'selected' : '' }}>{{ $c->nama_perusahaan }}
                                                        </option>
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
                                                        <option value="{{ $s->id_status }}" data-status="{{ $s->nama_status }}" 
                                                            data-code-status="{{ $s->kode_status }}" 
                                                            {{ old('id_status') == $s->id_status ? 'selected' : '' }}>{{ $s->nama_status }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div id="basic_salary">
                                            <div class="form-group row">
                                                <label class="col-lg-4 col-form-label">Basic Salary
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <div class="col-lg-6">
                                                    <input type="text" class="form-control val_basic_salary" placeholder="Enter a basic salary.." 
                                                    id="val_basic_salary" name="val_basic_salary" value="{{ old('val_basic_salary') }}" required>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div id="start_joining">
                                            <div class="form-group row">
                                                <label class="col-lg-4 col-form-label">Start Joining
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <div class="col-lg-6">
                                                    <input type="date" class="form-control" 
                                                    id="val_start_joining" name="val_start_joining" value="{{ old('val_start_joining') }}" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-4" id="contract_status" style="display: none;">
                                            <div class="form-group row">
                                                <label class="col-lg-4 col-form-label"></label>
                                                <div class="col-lg-6">
                                                    <label>Term Contract</label>
                                                    <input type="text" class="form-control" 
                                                    id="val_term_contract" name="val_term_contract" placeholder="Enter a term contract.." 
                                                    value="{{ old('val_term_contract') }}">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-lg-4 col-form-label"></label>
                                                <div class="col-lg-6">
                                                    <label>Start of Contract Period</label>
                                                    <input type="date" class="form-control" 
                                                    id="val_start_contract" name="val_start_contract" value="{{ old('val_start_contract') }}">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-lg-4 col-form-label"></label>
                                                <div class="col-lg-6">
                                                    <label>End of Contract Period</label>
                                                    <input type="date" class="form-control" 
                                                    id="val_end_contract" name="val_end_contract" value="{{ old('val_end_contract') }}">
                                                </div>
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
        // document.addEventListener('DOMContentLoaded', function () {
        //     document.getElementById('val_nik').addEventListener('blur', function () {
        //         // Ambil NIK yang dimasukkan oleh pengguna
        //         var nik = document.getElementById('val_nik').value;

        //         // Kirim permintaan ke API KTP
        //         fetch('https://indonesian-identification-card-ktp.p.rapidapi.com/api/check?nik=' + nik, {
        //             method: 'GET',
        //             headers: {
        //                 'X-RapidAPI-Host': 'indonesian-identification-card-ktp.p.rapidapi.com',
        //                 'X-RapidAPI-Key': 'bb98f2613fmsh4953f916c3d0c17p1e4126jsn872e2cabdbf7',
        //             },
        //         })
        //         .then(response => response.json())
        //         .then(data => {
        //             // Isi data formulir secara otomatis dengan data yang diterima dari API
        //             getData = data['results']['parse_data']

        //             if (getData){
        //                 // format tanggal lahir
        //                 var dateOfBirth1 = getData['tanggal_lahir'];
        //                 var dateParts = dateOfBirth1.split('/')
        //                 var formattedDate = dateParts[2] + '-' + dateParts[1] + '-' + dateParts[0];
    
        //                 // gender
        //                 var gender = getData['jenis_kelamin']
        //                 var jns_kelamin = ''
    
        //                 if(gender == 'LAKI-LAKI'){
        //                     jns_kelamin = 'male'
        //                 }else{
        //                     jns_kelamin = 'female'
        //                 }
    
        //                 document.getElementById('val_date_birth').value = formattedDate;
        //                 document.getElementById('val_gender').value = jns_kelamin;
        //             }
                    
        //         })
        //         .catch(error => {
        //             console.error('Error:', error);
        //         });
        //     });
        // });

        // var divisionSelect = document.getElementById("val_division");
        // divisionSelect.addEventListener("change", function() {
        //     // Ambil nilai terpilih dari <select>
        //     var selectedDivision = divisionSelect.value;
        //     if(divisionSelect.value.length == 1){
        //         selectedDivision = '0'+ selectedDivision
        //     }
        //     $('#val_idcard').val(selectedDivision);
        // });

        document.addEventListener('DOMContentLoaded', function () {
            // show-hide element term contract, start contract, and end contract
            const valStatus = document.getElementById('val_status');
            const valDivision = document.getElementById('val_division');

            const contractStatus = document.getElementById('contract_status');
            const startJoining = document.getElementById('start_joining');

            const startJoiningColumn = document.getElementById('val_start_joining');
            const termContractColumn = document.getElementById('val_term_contract');
            const startContractColumn = document.getElementById('val_start_contract');
            const endContractColumn = document.getElementById('val_end_contract');
            const dateBirthColumn = document.getElementById('val_date_birth');
            const valIdCard = document.getElementById('val_idcard');

            // tidak digunakan ganti format
            // function updateIdCard() {
            //     const selectedStatus = valStatus.value;
            //     const selectedDivision = valDivision.value;
                
            //     const selectedStatusOption = valStatus.querySelector(`option[value="${selectedStatus}"`);
            //     const selectedDivisionOption = valDivision.querySelector(`option[value="${selectedDivision}"`);

            //     if (selectedDivision || selectedStatus) {
            //         // ambil data kode divisi dan status
            //         const selectedCodeStatus = selectedStatusOption.getAttribute("data-code-status");
            //         const selectedCodeDivision = selectedDivisionOption.getAttribute("data-code-division");

            //         // Dapatkan nilai tanggal berdasarkan kolom yang sesuai
            //         let dateColumn;
            //         if (selectedStatusOption.getAttribute("data-status").toLowerCase() === 'kontrak') {
            //             dateColumn = startContractColumn;
            //         } else {
            //             dateColumn = startJoiningColumn;
            //         }

            //         function updateIdCardValue() {
            //             const selectedStatus = valStatus.value;
            //             const selectedDivision = valDivision.value;
                        
            //             const selectedStatusOption = valStatus.querySelector(`option[value="${selectedStatus}"`);
            //             const selectedDivisionOption = valDivision.querySelector(`option[value="${selectedDivision}"`);

            //             // ambil data kode divisi dan status
            //             const selectedCodeStatus = selectedStatusOption.getAttribute("data-code-status");
            //             const selectedCodeDivision = selectedDivisionOption.getAttribute("data-code-division");

            //             var startDate = dateColumn.value;
            //             var year = new Date(startDate).getFullYear();
            //             var twoDigitYear = year % 100;
            //             var randomDigits = (Math.floor(Math.random() * 1000)).toString().padStart(2, '0');
                        
            //             // set value id card
            //             var idcardValue = (twoDigitYear ? twoDigitYear : '00') +
            //                 (selectedCodeStatus ? selectedCodeStatus : '00') +
            //                 (selectedCodeDivision ? selectedCodeDivision : '00') + 
            //                 randomDigits;
            //             valIdCard.value = idcardValue;
            //         }

            //         // Panggil fungsi untuk mengupdate ID card saat status atau divisi berubah
            //         valStatus.addEventListener('change', updateIdCardValue);
            //         valDivision.addEventListener('change', updateIdCardValue);

            //         // Panggil fungsi untuk mengupdate ID card saat input di dateColumn
            //         dateColumn.addEventListener("input", updateIdCardValue);
                    
            //         // Panggil fungsi pertama kali untuk menginisialisasi nilai ID card
            //         // updateIdCardValue();
            //     }
            // }

            function updateIdCardValue() {
                const dateBirth = dateBirthColumn.value;
                const startDate = startJoiningColumn.value;

                const getTwoDigitYear = (date) => {
                    const year = new Date(date).getFullYear();
                    return year % 100;
                };

                const twoDigitYearBirth = getTwoDigitYear(dateBirth);
                const twoDigitYearJoin = getTwoDigitYear(startDate);
                const randomDigits = String(Math.floor(Math.random() * 1000)).padStart(3, '0');

                const idcardValue = String(twoDigitYearJoin || '00') + String(twoDigitYearBirth || '00') + String(randomDigits || '000');
                valIdCard.value = idcardValue;
            }

            // Fungsi yang memeriksa apakah ada perubahan tahun di dalam input
            function checkYearChange(event) {
                const inputElement = event.target;
                const currentValue = inputElement.value;
                const previousValue = inputElement.getAttribute('data-previous-value') || '';

                if (currentValue !== previousValue) {
                    inputElement.setAttribute('data-previous-value', currentValue);
                    updateIdCardValue();
                }
            }

            // Tambahkan event listener untuk kedua kolom input (dateBirthColumn / startJoiningColumn)
            dateBirthColumn.addEventListener("input", checkYearChange);
            startJoiningColumn.addEventListener("input", checkYearChange);

            function toggleContractStatus() {
                const selectedStatus = valStatus.value;
                const selectedStatusOption = valStatus.querySelector(`option[value="${selectedStatus}"`);

                if (selectedStatus) {
                    const selectedNameStatus = selectedStatusOption.getAttribute("data-status").toLowerCase();
                    // Show or hide contractStatus based on the selected status
                    if (selectedNameStatus === 'kontrak') {
                        termContractColumn.setAttribute('required', 'required');
                        startContractColumn.setAttribute('required', 'required');
                        endContractColumn.setAttribute('required', 'required');

                        contractStatus.style.display = 'block';

                    } else {
                        termContractColumn.removeAttribute('required');
                        startContractColumn.removeAttribute('required');
                        endContractColumn.removeAttribute('required');

                        contractStatus.style.display = 'none';
                    }
                }
            }

            valStatus.addEventListener('change', toggleContractStatus);
            // valDivision.addEventListener('change', toggleContractStatus);

            // Set initial state based on the value of val_status on page load
            toggleContractStatus();
        });

        // Fungsi untuk mengubah angka ke format IDR
        function formatToIDR(amount) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(amount);
        }

        // Fungsi untuk mengubah nilai input menjadi format IDR saat selesai mengedit
        function updateIDRFormat(inputElement) {
            const value = inputElement.value;
            const numericValue = parseFloat(value.replace(/[^\d.-]/g, ''));

            if (!isNaN(numericValue)) {
                inputElement.value = formatToIDR(numericValue);
            }
        }

        // Event listener untuk memanggil fungsi saat input berhenti diedit
        const inputSelectors = [
            '.form-control.val_basic_salary',
        ];

        inputSelectors.forEach(function (selector) {
            const inputElements = document.querySelectorAll(selector);
            inputElements.forEach(function (inputElement) {
                inputElement.addEventListener('blur', function () {
                    updateIDRFormat(this);
                });
            });
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