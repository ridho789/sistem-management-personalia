@extends('frontend.layout.main')
<!-- @section('title', 'List Daily Report') -->
@section('content')
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Hi, welcome back!</h4>
                    <span class="ml-1">List Daily Report</span>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Management</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">List Daily Report</a></li>
                </ol>
            </div>
        </div>
        <!-- row -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">                           
                        <h4 class="card-title">List Daily Report</h4>
                        @if (count($dailyReport) > 0)
                            <a href="/form-daily-report" class="btn btn-primary" id="new-daily-report">+ Add daily report</a>
                        @endif
                    </div>
                    <div class="card-body">
                        @if (count($dailyReport) > 0)
                            <div class="table-responsive">
                                <table class="table table-responsive-sm" id="data-table-employee" class="display" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Employee (C)</th>
                                            <th>Date</th>
                                            <th>Information</th>
                                            <th>Created by</th>
                                            <th>Updated by</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($dailyReport as $dr)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <a href="{{ url('form-employee-edit', ['id' => Crypt::encrypt($dr->id_karyawan)]) }}">
                                                    {{ $nameEmployee[$dr->id_karyawan] }} - {{ $idCard[$dr->id_karyawan] }}
                                                </a>
                                            </td>
                                            <td>{{ date('l, j F Y', strtotime($dr->tanggal_catatan_harian)) }}</td>
                                            <td>{{ $dr->keterangan }}</td>
                                            <td>{{ $dr->dibuat_oleh ?? '-' }}</td>
                                            <td>{{ $dr->diperbaharui_oleh ?? '-' }}</td>
                                            <td>
                                                <a href="{{ url('daily-report-edit', ['id' => Crypt::encrypt($dr->id_catatan_harian)]) }}" 
                                                    id="edit-button" class="btn btn-secondary btn-sm edit-button"><i class="icon icon-edit-72"></i>
                                                </a>
                                                <!-- <a href="daily-report-delete/{{ $dr->id_catatan_harian }}" class="btn btn-dark btn-sm"><i class="fa fa-trash"></i></a> -->
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
                                        <a href="/form-daily-report" class="btn btn-light mt-2" id="new-daily-report">click to add new daily report</a>
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