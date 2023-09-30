@extends('frontend.layout.main')
<!-- @section('title', 'List Employee') -->
@section('content')
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Hi, welcome back!</h4>
                    <span class="ml-1">List Employee</span>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Management</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">List Employee</a></li>
                </ol>
            </div>
        </div>
        <!-- row -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <form action="{{ url('list-employee-search') }}" method="GET">
                            @csrf
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Search Name or ID Card" value="{{ Request::get('search') }}">
                                <span class="input-group-btn">
                                    <button type="submit" class="btn btn-primary">Search</button>
                                </span>
                            </div>
                        </form>
                    </div>
                    <div class="card-header">                           
                        <h4 class="card-title">List Employee</h4>
                        @if (count($tbl_karyawan) > 0)
                            <a href="/form-employee" class="btn btn-primary mt-3" id="new-employee">+ Add new employee</a>
                        @endif
                    </div>
                    <div class="card-body">
                        @if (count($tbl_karyawan) > 0)
                            <div class="table-responsive">
                                <table id="data-table-employee" class="display" style="width:100%">
                                    <thead>
                                        <tr>
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
                                        <tr data-id="{{$k->id_karyawan}}">
                                            <td>{{$k->nama_karyawan}}</td>
                                            <td>{{$k->nik}}</td>
                                            <td>{{$k->no_telp}}</td>
                                            <td>{{$k->id_card}}</td>
                                            <td>{{ $positions[$k->id_jabatan] }}</td>
                                            <td>{{ $divisions[$k->id_divisi] }}</td>
                                            <td>{{ $companies[$k->id_perusahaan] }}</td>
                                            <td>{{ $statuses[$k->id_status] }}</td>
                                            <td style="text-align:right;">
                                                <a href="form-employee-edit/{{$k->id_karyawan}}" id="edit-button" class="edit-button"><i class="fa fa-edit"> edit |</i></a>
                                                <a href="list-employee-delete/{{$k->id_karyawan}}"><i class="fa fa-trash"> delete </i></a>
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
                                        <a href="/form-employee" class="btn btn-light mt-2" id="new-employee">click to add new employee</a>
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