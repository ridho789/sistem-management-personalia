@extends('frontend.layout.main')
<!-- @section('title', 'Leaves Summary') -->
@section('content')
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Hi, welcome back!</h4>
                    <span class="ml-1">Leaves Summary</span>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Management</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Leaves Summary</a></li>
                </ol>
            </div>
        </div>
        <!-- row -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">                           
                        <h4 class="card-title">Leaves Summary</h4>
                        @if (count($dataleave) > 0)
                            <a href="/leave-request" class="btn btn-primary mt-3" 
                                id="new-data-leave">+ New leave request
                            </a>
                        @endif
                    </div>
                    <div class="card-body">
                        @if (count($dataleave) > 0)
                            <div class="table-responsive">
                                <table class="table table-responsive-sm" id="data-table-data-leave" 
                                class="display" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Employee</th>
                                            <th>Type Leave</th>
                                            <th>Description</th>
                                            <th>Date-Time Leave (Start)</th>
                                            <th>Duration (Day)</th>
                                            <th>Responsible</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($dataleave as $dl)
                                            <tr data-id="{{$dl->id_data_cuti}}">
                                                <th>{{ $loop->iteration }}</th>
                                                <td>{{ $employee[$dl->id_karyawan] }} - {{ $idcard[$dl->id_karyawan] }}</td>
                                                <td>{{ $typeleave[$dl->id_tipe_cuti] }}</td>
                                                <td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 100px;">
                                                    {{ $dl->deskripsi }}
                                                </td>
                                                <td>{{ $dl->mulai_cuti }}</td>
                                                <td>{{ $dl->durasi_cuti }}</td>
                                                <td>{{ $employee[$dl->id_penangung_jawab] }} - {{ $idcard[$dl->id_penangung_jawab] }}</td>
                                                @if($dl->status_cuti == 'To Submit')
                                                    <td><span class="badge badge-warning">{{ $dl->status_cuti }}</span></td>
                                                @elseif($dl->status_cuti == 'To Approved')
                                                    <td><span class="badge badge-secondary">{{ $dl->status_cuti }}</span></td>
                                                @elseif($dl->status_cuti == 'Approved')
                                                    <td><span class="badge badge-primary">{{ $dl->status_cuti }}</span></td>
                                                @endif
                                                <td>
                                                    <a href="{{ url('leave-request-edit', ['id' => Crypt::encrypt($dl->id_data_cuti)]) }}" class="btn btn-dark btn-sm">
                                                        <i class="icon icon-edit-72"> </i>
                                                    </a>
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
                                        <a href="/leave-request" class="btn btn-light mt-2" id="new-data-leave">
                                            click to new leave request
                                        </a>
                                    </p>
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection