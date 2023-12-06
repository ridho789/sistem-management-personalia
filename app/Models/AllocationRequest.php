<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllocationRequest extends Model
{
    use HasFactory;
    protected $table = 'tbl_alokasi_sisa_cuti';
    protected $primaryKey = 'id_alokasi_sisa_cuti';

    protected $fillable = [
        'id_karyawan',
        'id_tipe_cuti',
        'sisa_cuti',
        'status'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'id_karyawan', 'id_karyawan');
    }
}
