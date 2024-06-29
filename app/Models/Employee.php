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
        'lokasi',
        'alamat',
        'id_jabatan',
        'id_divisi',
        'id_perusahaan',
        'id_status',
        'gaji_pokok',
        'lama_kontrak',
        'awal_masa_kontrak',
        'akhir_masa_kontrak',
        'awal_bergabung',
        'kontak_darurat',
        'bpjs_tk',
        'bpjs_kis',
        'id_card',
        'foto',
    ];
    
}
