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
                    @if (count($allocationRequest) > 0)
                        <div class="card-header">
                            <form action="{{ url('allocation-request-search') }}" method="GET">
                            @csrf
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" 
                                    placeholder="Search employee / idcard" value="{{ Request::get('search') }}">
                                </div>
                            </form>
                        </div>
                    @endif
                    <div class="card-header">                           
                        <h4 class="card-title">Allocation Request</h4>
                    </div>
                    <div class="card-body">
                        @if (count($allocationRequest) > 0)
                            <div class="table-responsive">
                                <table class="table table-responsive-sm" id="data-table-allocation-request" 
                                    class="display" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Employee (C)</th>
                                            <th>Remaining Leave</th>
                                            <th>Leave Type</th>
                                            <th>Duration</th>
                                            <th>Date Leave (C)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($allocationRequest as $ar)
                                            @php
                                            $dataCutiKaryawan = $dataCuti->where('id_karyawan', $ar->id_karyawan);
                                            @endphp
                                            @if (count($dataCutiKaryawan) > 0)
                                                <tr data-id="{{$ar->id_alokasi_sisa_cuti}}">
                                                    <td rowspan="{{ $dataCutiKaryawan->count() > 0 ? $dataCutiKaryawan->count() + 1 : 2 }}">
                                                        {{ $loop->iteration }}
                                                    </td>
                                                    <td rowspan="{{ $dataCutiKaryawan->count() > 0 ? $dataCutiKaryawan->count() + 1 : 2 }}">
                                                        <a href="{{ url('form-employee-edit', ['id' => Crypt::encrypt($ar->id_karyawan)]) }}">
                                                            {{ $employee[$ar->id_karyawan] }} - {{ $idcard[$ar->id_karyawan] }}
                                                        </a>
                                                    </td>
                                                    <td rowspan="{{ $dataCutiKaryawan->count() > 0 ? $dataCutiKaryawan->count() + 1 : 2 }}">
                                                        {{ $ar->sisa_cuti }}
                                                    </td>
                                                    @if ($dataCutiKaryawan->count() > 0)
                                                        @foreach($dataCutiKaryawan as $cuti)
                                                            <tr>
                                                                <td>{{  $typeleave[$cuti->id_tipe_cuti] }}</td>
                                                                <td>{{ $cuti->durasi_cuti }} days</td>
                                                                <td>
                                                                    <a href="{{ url('leave-request-edit', ['id' => Crypt::encrypt($cuti->id_data_cuti)]) }}">
                                                                    {{ date('l, Y-m-d', strtotime($cuti->mulai_cuti)) }}</a>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @else
                                                        <td colspan="{{ $dataCutiKaryawan->count() > 0 ? $dataCutiKaryawan->count() + 1 : 2 }}">No Leave Data</td>
                                                    @endif
                                                </tr>
                                            @else
                                                <tr data-id="{{$ar->id_alokasi_sisa_cuti}}">
                                                    <td>
                                                        {{ $loop->iteration }}
                                                    </td>
                                                    <td>
                                                        {{ $employee[$ar->id_karyawan] }} - {{ $idcard[$ar->id_karyawan] }}
                                                    </td>
                                                    <td>
                                                        {{ $ar->sisa_cuti }}
                                                    </td>
                                                    <td>No Leave Data</td>
                                                </tr>
                                            @endif
                                        @endforeach 
                                    </tbody>
                                </table>
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
@endsection