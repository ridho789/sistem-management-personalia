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
                        <h4 class="card-title">List Employee</h4>
                        @if (count($tbl_karyawan) > 0)
                            <!-- <button type="submit" class="btn btn-primary mt-3" id="new-employee">+ Add new employee</button> -->
                            <a href="/form-employee" class="btn btn-light mt-3" id="new-employee">click to add new employee</a>
                        @endif
                    </div>
                    <div class="card-body">
                        @if (count($tbl_karyawan) > 0)
                            <div class="table-responsive">
                                <table id="data-table-employee" class="display" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>employee</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($tbl_karyawan as $j)
                                        <tr data-id="{{$j->id_karyawan}}">
                                            <td class="employee-name-selected">{{$j->nama_karyawan}}</td>
                                            <td style="text-align:right;">
                                                <a href="#" id="edit-button" class="edit-button"><i class="fa fa-edit"> edit |</i></a>
                                                <a href="#"><i class="fa fa-trash"> delete </i></a>
                                            </td>
                                        </tr>
                                        @endforeach 
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="mt-5">
                                <span style="text-align: center;">
                                    <p>Sorry, no data that can be displayed yet. <br>
                                        <!-- <button type="submit" class="btn btn-light mt-2" id="new-employee">click to add new employee</button> -->
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