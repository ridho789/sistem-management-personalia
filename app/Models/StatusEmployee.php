<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusEmployee extends Model
{
    use HasFactory;
    protected $table = 'tbl_status_kary';
    public $timestamps = true;
    protected $primaryKey = 'id_status';

    protected $fillable = [
        'nama_status',
        'kode_status'
    ];
}
