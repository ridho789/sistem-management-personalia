@extends('frontend.layout.main')
<!-- @section('title', 'List Attendance') -->
@section('content')
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Hi, welcome back!</h4>
                    <span class="ml-1">List Attendance</span>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Management</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">List Attendance</a></li>
                </ol>
            </div>
        </div>
        <!-- row -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">                           
                        <h4 class="card-title">List Attendance</h4>
                    </div>
                    <div class="card-body">
                        @if (count($allattendance) > 0)
                            <div class="table-responsive">
                                <table class="table table-responsive-sm" id="data-table-employee" 
                                    class="display" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Employee (C)</th>
                                            <th>ID Card</th>
                                            <th>Attendance Date</th>
                                            <th>Sign In</th>
                                            <th>Late</th>
                                            <th>Sign Out</th>
                                            <th>Information</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($allattendance as $at)
                                        <tr data-id="{{$at->id_attendance}}">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <a href="{{ url('form-employee-edit', ['id' => Crypt::encrypt($at->employee)]) }}">
                                                    {{ $nameEmployee[$at->employee] }}
                                                </a>
                                            </td>
                                            <td>{{$at->id_card}}</td>
                                            <td>{{ date('l, Y-m-d', strtotime($at->attendance_date)) }}</td>
                                            <td>{{$at->sign_in ?? '-'}}</td>
                                            @if ($at->sign_in_late)
                                                <td style="color: red;">{{$at->sign_in_late}}</td>
                                            @else
                                                <td>{{$at->sign_in_late ?? '-'}}</td>
                                            @endif
                                            <td>{{$at->sign_out ?? '-'}}</td>
                                            <td>
                                                @if ($at->information && (stristr($at->information, 'leave') || stristr($at->information, 'other')))
                                                    <a href="{{ url('leave-request-edit', ['id' => Crypt::encrypt($at->id_data_cuti)]) }}">
                                                        {{$at->information}} (C)
                                                    </a>
                                                @else
                                                    {{$at->information ?? '-'}}
                                                @endif
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