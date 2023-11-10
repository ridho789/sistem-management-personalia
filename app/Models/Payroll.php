<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;
    protected $table = 'tbl_gaji';

    protected $fillable = [
        'id_karyawan',
        'periode_gaji',
        'gaji_pokok',
        'tunjangan_jabatan',
        'potongan',
        'total_gaji',
        'jumlah_hari_kerja',
        'jumlah_hari_sakit',
        'jumlah_hari_tidak_masuk',
        'jumlah_hari_cuti_resmi',
        'jumlah_hari_telat',
        'bulan',
        'tahun',
        'catatan',
    ];
}
