<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Leave Request</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                font-size: 12.5px;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
            }
            th, td {
                border: 0px solid #000;
            }
            td {
                padding: 5.5px;
                text-align: left;
            }
        </style>
    </head>
    <body>
        <div style="width: 95%; margin: 0 auto;">
            <div style="margin-top:-10px;">
                <img src="data:image/jpeg;base64,{{ base64_encode($imageContent) }}" 
                style="max-width: 100%; max-height: 100%; width: auto; height: auto;">
            </div>
        </div>

        <div style="text-align: center; margin-top:20px;">
            <hr><br>
            <span style="font-weight: bold; font-size: 14px; margin-bottom:15px;">SURAT TUGAS</span><br>
        </div>

        <div style="margin-top:50px;">
            <div>
                <span style="margin-left: 25px;">Yang bertanda tangan di bawah ini: </span>
                <table>
                    <tr>
                        <td>
                            <td>Nama</td>
                            <td>: {{ $responsible->nama_karyawan }}</td>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <td>ID Card</td>
                            <td>: {{ $responsible->id_card }}</td>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <td>Divisi</td>
                            <td>: {{ $division[$responsible->id_divisi] }}</td>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <td>Jabatan</td>
                            <td>: {{ $position[$responsible->id_jabatan] }}</td>
                        </td>
                    </tr>
                </table>
            </div>

            <div style="margin-top:25px;">
                <span style="margin-left: 25px;">Memberikan tugas kepada: </span>
                <table>
                    <tr>
                        <td>
                            <td>Nama</td>
                            <td>: {{ $employee->nama_karyawan }}</td>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <td>ID Card</td>
                            <td>: {{ $employee->id_card }}</td>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <td>Divisi</td>
                            <td>: {{ $division[$employee->id_divisi] }}</td>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <td>Jabatan</td>
                            <td>: {{ $position[$employee->id_jabatan] }}</td>
                        </td>
                    </tr>
                </table>
            </div>

            <div style="margin-top:25px; line-height: 1.75; margin-left: 25px;">
                <span>Agenda/kegiatan: {{ $dataleave->deskripsi }}.</span><br>
                <span>Dilaksanakan pada tanggal {{ date('l, j F Y', strtotime($dataleave->mulai_cuti)) }} 
                s.d. selesai.
                </span>
            </div>

            <div style="margin-top:15px; line-height: 1.75; margin-left: 25px;">
                <span>Demikian surat tugas ini dikeluarkan untuk dapat dilaksanakan dengan baik.
                Terima kasih atas perhatiannya.</span>
            </div>

            <div style="margin-top:55px;">
                <div style="text-align:right; margin-right: 40px;">
                    <span>Batam, . . . . . . . . . . . . . . . . .</span>
                </div>

                <div style="text-align:left;">
                    <div style="line-height: 1.75; margin-bottom: 75px;">
                        <label style="margin-left: 30px;">Penerima Tugas,</label>
                    </div>
                    <div style="margin-left: 30px;">
                        <span>{{ $employee->nama_karyawan . ' - ' . $employee->id_card ?? '...' }}</span>
                    </div>
                </div>

                <div style="text-align:right; margin-top: -155px;">
                    <div style="line-height: 1.75; margin-bottom: 75px;">
                        <label style="margin-right: 60px;">Pemberi Tugas,</label>
                    </div>
                    <div style="margin-right: 60px;">
                        <span>{{ $responsible->nama_karyawan . ' - ' . $responsible->id_card ?? '...' }}</span>
                    </div>
                </div>
            </div>

        </div>
    </body>
</html>
