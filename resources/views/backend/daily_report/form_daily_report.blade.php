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
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Form Daily Report</h4>
                    </div>
                    <div class="card-body">
                        <div class="basic-form">
                            <form action="#" method="POST">
                                @csrf
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection