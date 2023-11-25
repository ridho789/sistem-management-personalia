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
                    @if (count($allocationRequest) > 0)
                        <div class="card-body">
                            <form action="{{ url('allocation-request-search') }}" method="GET">
                            @csrf
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label>Showing data base on employee or ID card</label>
                                        <input type="text" name="search" class="form-control" 
                                        placeholder="Search employee or ID card.." value="{{ Request::get('search') }}">
                                    </div>
                                </div>
                            </form>
                        </div>
                    @endif
                    <div class="card-body">
                        @if (count($allocationRequest) > 0)
                            <div class="table-responsive">
                                <table class="table table-responsive-sm" id="data-table-allocation-request" 
                                    class="display" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Employee (C)</th>
                                            <th>Leave Type</th>
                                            <th>Remaining Leave</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($allocationRequest as $ar)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    <a href="{{ url('form-employee-edit', ['id' => Crypt::encrypt($ar->id_karyawan)]) }}">
                                                        {{ $employee[$ar->id_karyawan] }} - {{ $idcard[$ar->id_karyawan] }}
                                                    </a>
                                                </td>
                                                <td>{{ $typeleave[$ar->id_tipe_cuti] }}</td>
                                                <td>{{ $ar->sisa_cuti }}</td>
                                            </tr>
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