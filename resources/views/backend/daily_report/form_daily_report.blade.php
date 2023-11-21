@extends('frontend.layout.main')
<!-- @section('title', 'Form Daily Report') -->
@section('content')
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Hi, welcome back!</h4>
                    <span class="ml-1">Form Daily Report</span>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Management</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Form Daily Report</a></li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-12" id="form-daily-report">
                <div id="button-create-excel" class="mb-3" style="display: block;">
                    <a href="#" class="btn btn-light" id="button-create-daily-report-excel">Click to <b>create by Excel</b></a>
                </div>
                <div id="form-create-excel" class="card" style="display: none;">
                    <div class="card-header">
                        <h4 class="card-title">Import File Excel</h4>
                    </div>
                    <div class="card-body">
                        <div class="basic-form custom_file_input">
                            <form action="{{ url('import-excel-daily-report') }}" method="POST" enctype="multipart/form-data">
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
                        <h4 class="card-title">Form Daily Report</h4>
                    </div>
                    <div class="card-body">
                        <div class="basic-form">
                            @if($dailyReport)
                                <form action="{{ url('daily-report-update') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="user" value="{{ auth()->user()->name }}"/>
                                    <input type="hidden" name="id" value="{{ $dailyReport->id_catatan_harian }}"/>
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <label for="val_employee">Employee</label>
                                            <select class="form-control" id="val_employee" name="id_karyawan" required>
                                                <option value="">Select a employee...</option>
                                                @foreach ($employee as $e)
                                                    <option value="{{ $e->id_karyawan }}" 
                                                        {{ old('id_karyawan', $dailyReport->id_karyawan) == $e->id_karyawan ? 'selected' : '' }}>
                                                        {{ $e->nama_karyawan }} - {{ $e->id_card }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="work-diary-date">Work Diary Date</label>
                                            <input type="date" id="work-diary-date" class="form-control input-work-diary-date" 
                                                name="input_work_diary_date" value="{{ old('input_work_diary_date', $dailyReport->tanggal_catatan_harian) }}" required>
                                        </div>
                                        <div class="col-sm-8">
                                            <label for="information">Information</label>
                                            <input type="text" id="information" class="form-control input-information" 
                                                name="input_information" placeholder="input information" value="{{ old('input_information', $dailyReport->keterangan) }}" required>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary submit-daily-report" id="close-form-daily-report">Submit</button>
                                </form>
                            @else
                                <form action="{{ url('daily-report-add') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="user" value="{{ auth()->user()->name }}"/>
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <label for="val_employee">Employee</label>
                                            <select class="form-control" id="val_employee" name="id_karyawan" required>
                                                <option value="">Select a employee...</option>
                                                @foreach ($employee as $e)
                                                    <option value="{{ $e->id_karyawan }}" 
                                                        {{ old('id_karyawan') == $e->id_karyawan ? 'selected' : '' }}>
                                                        {{ $e->nama_karyawan }} - {{ $e->id_card }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="work-diary-date">Work Diary Date</label>
                                            <input type="date" id="work-diary-date" class="form-control input-work-diary-date" 
                                                name="input_work_diary_date" value="{{ old('input_work_diary_date') }}" required>
                                        </div>
                                        <div class="col-sm-8">
                                            <label for="information">Information</label>
                                            <input type="text" id="information" class="form-control input-information" 
                                                name="input_information" placeholder="input information" value="{{ old('input_information') }}" required>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary submit-daily-report" id="close-form-daily-report">Submit</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
                @if($errorInfo)
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Log Information</h4>
                    </div>
                    <div class="card-body">
                        <span>
                            <p>{{ $errorInfo }}</p>
                        </span>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    <script>
        // show/hide form create by excel
        const toggleFormButton = document.getElementById('button-create-daily-report-excel');
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