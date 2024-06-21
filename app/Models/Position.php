<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;
    protected $table = 'tbl_jabatan';
    public $timestamps = true;
    protected $primaryKey = 'id_jabatan';

    protected $fillable = [
        'nama_jabatan',
        'tunjangan_jabatan'
    ];
}
