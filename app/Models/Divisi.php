<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Divisi extends Model
{
    use HasFactory;
    protected $table = 'tbl_divisi';
    public $timestamps = true;
    protected $primaryKey = 'id_divisi';

    protected $fillable = [
        'nama_divisi',
        'kode_divisi',
        'jumlah_hari_kerja',
        'is_daily_report'
    ];
}
