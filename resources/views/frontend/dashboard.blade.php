@extends('frontend.layout.main')
<!-- @section('title', 'Dashboard') -->
@section('content')    
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-sm-6">
                <a href="/list-employee">
                    <div class="card">
                        <div class="stat-widget-two card-body">
                            <div class="stat-content">
                                <div class="stat-text">Employee Active </div>
                                <div class="stat-digit"> <i class="fa fa-user"></i>{{ $employeeActive }}</div>
                            </div>
                            <div class="progress">
                                <div class="progress-bar progress-bar-success w-{{ ($employeeActive / 250) * 100 }}" 
                                role="progressbar" aria-valuenow="{{ $employeeActive }}" aria-valuemin="0" aria-valuemax="500"></div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-sm-6">
                <a href="/list-inactive-employee">
                    <div class="card">
                        <div class="stat-widget-two card-body">
                            <div class="stat-content">
                                <div class="stat-text">Employee Inactive</div>
                                <div class="stat-digit"> <i class="fa fa-user-times"></i>{{ $employeeInactive }}</div>
                            </div>
                            <div class="progress">
                                <div class="progress-bar progress-bar-primary w-{{ ($employeeInactive / 250) * 100 }}" 
                                role="progressbar" aria-valuenow="{{ $employeeInactive }}" aria-valuemin="0" aria-valuemax="500"></div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="card">
                    <div class="stat-widget-two card-body">
                        <div class="stat-content">
                            <div class="stat-text">Contract Will Expired</div>
                            <div class="stat-digit"> <i class="fa fa-calendar-times-o"></i>{{ $countEmployeeExpiredContract }}</div>
                        </div>
                        <div class="progress">
                            <div class="progress-bar progress-bar-warning w-{{ ($countEmployeeExpiredContract / 175) * 100 }}" 
                            role="progressbar" aria-valuenow="{{ $countEmployeeExpiredContract }}" aria-valuemin="0" aria-valuemax="250"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="card">
                    <div class="stat-widget-two card-body">
                        <div class="stat-content">
                            <div class="stat-text">Leave to Approved</div>
                            <div class="stat-digit"> <i class="fa fa-file-text"></i>{{ $countDataLeaveToApproved }}</div>
                        </div>
                        <div class="progress">
                            <div class="progress-bar progress-bar-danger w-{{ ($countDataLeaveToApproved / 100) * 100 }}" 
                            role="progressbar" aria-valuenow="{{ $countDataLeaveToApproved }}" aria-valuemin="0" aria-valuemax="200"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if (count($employeeExpiredContract) > 0)
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Contract Will Expired</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Employee</th>
                                            <th>ID Card</th>
                                            <th>Position</th>
                                            <th>Division</th>
                                            <th>Status</th>
                                            <th>End of Contract Period</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($employeeExpiredContract as $ec)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{$ec->nama_karyawan}}</td>
                                                <td>{{$ec->id_card}}</td>
                                                <td>{{ $positions[$ec->id_jabatan] }}</td>
                                                <td>{{ $divisions[$ec->id_divisi] }}</td>
                                                <td>{{ $statuses[$ec->id_status] }}</td>
                                                <td>{{ date('l, Y-m-d', strtotime($ec->akhir_masa_kontrak)) }}</td>
                                                <td style="text-align:right;">
                                                    <a href="{{ url('form-employee-edit', ['id' => Crypt::encrypt($ec->id_karyawan)]) }}" 
                                                        id="edit-button" class="btn btn-secondary btn-sm edit-button"><i class="icon icon-edit-72"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if (count($dataLeaveToApproved) > 0)
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Leave to Approved</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Employee (C)</th>
                                            <th>Type Leave</th>
                                            <th>Description</th>
                                            <th>Date Leave (Start)</th>
                                            <th>Responsible (C)</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($dataLeaveToApproved as $dla)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    <a href="{{ url('form-employee-edit', ['id' => Crypt::encrypt($dla->id_karyawan)]) }}">
                                                        {{ $employee[$dla->id_karyawan] }} - {{ $idcard[$dla->id_karyawan] }}
                                                    </a>
                                                </td>
                                                <td>{{ $typeleave[$dla->id_tipe_cuti] }}</td>
                                                <td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 100px;">
                                                    {{ $dla->deskripsi }}
                                                </td>
                                                <td>{{ date('l, Y-m-d', strtotime($dla->mulai_cuti)) }}</td>
                                                <td>
                                                    <a href="{{ url('form-employee-edit', ['id' => Crypt::encrypt($dla->id_penangung_jawab)]) }}">
                                                        {{ $employee[$dla->id_penangung_jawab] }} - {{ $idcard[$dla->id_penangung_jawab] }}
                                                    </a>
                                                </td>
                                                <td><span class="badge badge-secondary">{{ $dla->status_cuti }}</span></td>
                                                <td style="text-align:right;">
                                                    <a href="{{ url('leave-request-edit', ['id' => Crypt::encrypt($dla->id_data_cuti)]) }}" 
                                                        class="btn btn-secondary btn-sm">
                                                        <i class="icon icon-edit-72"> </i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection