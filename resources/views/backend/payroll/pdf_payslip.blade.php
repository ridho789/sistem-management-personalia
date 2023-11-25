<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Payslip</title>
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
                border: 0px solid #000;
            }
            th {
                padding: 10px;
                text-align: left;
                font-size: 13px;
            }
            td {
                padding: 10px;
                text-align: left;
                font-size: 13px;
            }
        </style>
    </head>
    <body>
        <div style="width: 95%; margin: 0 auto;">
            <div style="text-align:center; margin-top:-10px;">
                <b>Payslip </b><br>
                <p style="font-size: small; margin-top:5px;">{{ $payroll->periode_gaji }}</p>
                <p style="font-size: small; margin-top:-10px;">PT Satria Utama Group</p>
            </div>
        </div>
        
        <div style="margin-left: 25px; margin-right: 25px;">
            <table>
                <tr>
                    <td>
                        <th>Tanggal Bergabung</th>
                        <td>: {{ date('j F Y', strtotime($employee->awal_bergabung)) }}</td>
                    </td>
                    <td>
                        <th>Nama Karyawan</th>
                        <td>: {{ $employee->nama_karyawan }}</td>
                    </td>
                </tr>
                <tr>
                    <td>
                        <th>Divisi</th>
                        <td>: {{ $division[$employee->id_divisi] }}</td>
                    </td>
                    <td>
                        <th>Jabatan</th>
                        <td>: {{ $position[$employee->id_jabatan] }}</td>
                    </td>
                </tr>
                <tr>
                    <td>
                        <th>ID Card</th>
                        <td>: {{ $employee->id_card }}</td>
                    </td>
                    <td>
                        <th>Status</th>
                        <td>: {{ $status[$employee->id_status] }}</td>
                    </td>
                </tr>
            </table>

            <table style="margin-left: 20px;">
                <tr>
                    <th width="60%">Total Hari Kerja</th>
                    <td width="5%">:</td>
                    <td width="45%">{{ $payroll->jumlah_hari_kerja }}</td>
                </tr>
                <tr>
                    <th>Total Hari Tidak Masuk</th>
                    <td>:</td>
                    <td>{{ $payroll->jumlah_hari_tidak_masuk }}</td>
                </tr>
                <tr>
                    <th>Total Hari Sakit</th>
                    <td>:</td>
                    <td>{{ $payroll->jumlah_hari_sakit }}</td>
                </tr>
                <tr>
                    <th>Total Hari Cuti Resmi</th>
                    <td>:</td>
                    <td>{{ $payroll->jumlah_hari_cuti_resmi }}</td>
                </tr>
                <tr>
                    <th>Total Hari Telat</th>
                    <td>:</td>
                    <td>{{ $payroll->jumlah_hari_telat }}</td>
                </tr>
            </table>

            <table style="margin-left: 20px;">
                <tr>
                    <th width="60%">Gaji Pokok</th>
                    <td width="5%">:</td>
                    <td width="45%">{{ $payroll->gaji_pokok }}</td>
                </tr>
                <tr>
                    <th>Tunjangan Jabatan</th>
                    <td>:</td>
                    <td>{{ $payroll->tunjangan_jabatan }}</td>
                </tr>
                <tr>
                    <th>Potongan</th>
                    <td>:</td>
                    <td>{{ $payroll->potongan }}</td>
                </tr>
                <tr>
                    <th>Total Gaji</th>
                    <td>:</td>
                    <td>{{ $payroll->total_gaji }}</td>
                </tr>
            </table>

            <div style="margin-top: 50px; text-align:left; font-size:14px; margin-left:20px;">
                <div style="margin-left: 10px; margin-bottom: 50px;">
                    <label style="font-weight: bold;">Yang menerima</label>
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
                    <span>( Dept. Keuangan )</span>
                </div>
            </div>
        </div>
    </body>
</html>
