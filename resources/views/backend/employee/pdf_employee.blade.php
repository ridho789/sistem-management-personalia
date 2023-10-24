<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Data Employee</title>

        <style>
            table {
                width: 95%;
                border-collapse: collapse;
                margin: 50px auto;
            }

            /* Zebra striping */
            tr:nth-of-type(odd) {
                background: #eee;
            }

            th {
                background: #055fb4;
                color: white;
                font-weight: bold;
            }

            td,
            th {
                padding: 5px;
                border: 1px solid #ccc;
                text-align: left;
                font-size: 12px;
            }
        </style>

    </head>

    <body>

        <div style="width: 95%; margin: 0 auto;">
            <div style="float:right;margin-top:-10px;">
                <b>PT. SATRIA UTAMA GROUP <br>
            </div>
            <div style="float: left; margin-left:-25px;">
                <h4>Data Employee</h4>
            </div>
        </div>

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
                </tr>
                @endforeach 
            </tbody>
        </table>

    </body>
</html>
