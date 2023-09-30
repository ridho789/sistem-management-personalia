<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $table = 'tbl_karyawan';

    protected $fillable = [
        'nama_karyawan',
        'nik',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'no_telp',
        'alamat',
        'foto',
        'id_card',
        'id_jabatan',
        'id_divisi',
        'id_perusahaan',
        'id_status',
    ];
}
