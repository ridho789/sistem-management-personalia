@extends('frontend.layout.main')
<!-- @section('title', 'List Inactive Employee') -->
@section('content')
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Hi, welcome back!</h4>
                    <span class="ml-1">List Inactive Employee</span>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Management</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">List Inactive Employee</a></li>
                </ol>
            </div>
        </div>
        <!-- row -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">                           
                        <h4 class="card-title">List Inactive Employee</h4>
                    </div>
                    <div class="card-body">
                        @if (count($tbl_karyawan) > 0)
                            <form action="{{ url('list-inactive-employee-search') }}" method="GET">
                                @csrf
                                <div class="form-group row">
                                    <div class="col-sm-3 mb-3">
                                        <label>Display employee data based on search name or ID card</label>
                                        <input type="text" name="search" class="form-control" 
                                        placeholder="Search name or ID card" value="{{ Request::get('search') }}">
                                        <!-- <span class="input-group-btn">
                                            <button type="submit" class="btn btn-primary">Search</button>
                                        </span> -->
                                    </div>
                                </div>
                            </form>
                            <div class="table-responsive">
                                <table class="table table-responsive-sm" id="data-table-employee" class="display" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Employee</th>
                                            <th>NIK</th>
                                            <th>Phone</th>
                                            <th>ID Card</th>
                                            <th>Position</th>
                                            <th>Division</th>
                                            <th>Company</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($tbl_karyawan as $k)
                                        <tr data-id="{{$k->id_karyawan}}" data-status-id="{{ $k->id_status }}">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{$k->nama_karyawan}}</td>
                                            <td>{{$k->nik}}</td>
                                            <td>{{$k->no_telp}}</td>
                                            <td>{{$k->id_card}}</td>
                                            <td>{{ $positions[$k->id_jabatan] }}</td>
                                            <td>{{ $divisions[$k->id_divisi] }}</td>
                                            <td>{{ $companies[$k->id_perusahaan] }}</td>
                                            <td>{{ $statuses[$k->id_status] }}</td>
                                            <td>
                                                <a href="{{ url('form-employee-edit', ['id' => Crypt::encrypt($k->id_karyawan)]) }}" 
                                                    id="edit-button" class="btn btn-secondary btn-sm edit-button"><i class="icon icon-edit-72"></i>
                                                </a>
                                                <!-- <a href="list-employee-delete/{{$k->id_karyawan}}" class="btn btn-dark btn-sm"><i class="fa fa-trash"></i></a> -->
                                            </td>
                                        </tr>
                                        @endforeach 
                                    </tbody>
                                </table>
                            </div>

                        @else
                            <div class="mt-3">
                                <span style="text-align: center;">
                                    <p>Sorry, no data that can be displayed yet.</p>
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection