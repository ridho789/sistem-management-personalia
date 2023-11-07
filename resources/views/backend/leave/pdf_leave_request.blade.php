<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Leave Request</title>
        <style>
            body {
                font-family: Arial, sans-serif;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
            }
            th, td {
                border: 1px solid #000;
            }
            th {
                padding: 10px;
                text-align: left;
                font-size: 14.5px;
            }
            td {
                padding: 10px;
                text-align: left;
                font-size: 13.5px;
            }
        </style>
    </head>
    <body>
        <div style="width: 95%; margin: 0 auto;">
            <div style="float:right;margin-top:-10px;">
                <b>PT. SATRIA UTAMA GROUP <br>
            </div>
        </div>
        <h4>Leave Requests to be Approved ( {{ $typeLeave->nama_tipe_cuti }} )</h4>
        <table>
            <tr aria-rowspan="3">
                <tr>
                    <th>Nama</th>
                    <th>Jabatan</th>
                    <th>Divisi</th>
                </tr>
                <tr>
                    <td>{{ $employee->nama_karyawan . ' - ' . $employee->id_card ?? 'N/A' }}</td>
                    <td>{{ $position[$employee->id_jabatan] ?? 'N/A' }}</td>
                    <td>{{ $division[$employee->id_divisi] ?? 'N/A' }}</td>
                </tr>
            </tr>
        </table>
        <table>
            <tr aria-rowspan="2">
                <tr>
                    <th colspan="2">Deskripsi</th>
                </tr>
                <tr>
                    <td colspan="2">{{ $dataleave->deskripsi ?? 'N/A' }}</td>
                </tr>
            </tr>
            <tr>
                <th>Tanggal/Waktu Mulai:</th>
                <td>{{ date('l, Y-m-d H:i:s', strtotime($dataleave->mulai_cuti)) }}</td>
            </tr>
            <tr>
                <th>Tanggal/Waktu Selesai:</th>
                <td>{{ date('l, Y-m-d H:i:s', strtotime($dataleave->selesai_cuti)) }}</td>
            </tr>
        </table>

        <div style="margin-top: 10px;">
            @if (strpos(strtolower($typeLeave->nama_tipe_cuti), 'sick') !== false)
                @if ( $dataleave->file )
                    <span><i>Bukti/berkas pendukung sudah terlampir</i></span>
                @else
                    <span><i>Bukti/berkas pendukung belum terlampir</i></span>
                @endif
            @endif
        </div>
        <!-- ttd -->
        <div style="margin-top: 50px; text-align:left; font-size:14px">
            <div style="margin-right: 10px; margin-bottom: 50px;">
                <label style="font-weight: bold;">Yang mengajukan</label>
            </div>
            <div>
                <span>( {{ $employee->nama_karyawan . ' - ' . $employee->id_card ?? '...' }} )</span>
            </div>
        </div>
        <div style="margin-top: -83px; text-align:right; font-size:14px">
            <div style="margin-right: 20px; margin-bottom: 50px;">
                <label style="font-weight: bold;">Mengetahui</label>
            </div>
            <div>
                <span>( {{ $responsible->nama_karyawan . ' - ' . $responsible->id_card ?? '...' }} )</span>
            </div>
        </div>
    </body>
</html>
