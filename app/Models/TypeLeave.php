<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeLeave extends Model
{
    use HasFactory;
    protected $table = 'tbl_tipe_cuti';

    public $timestamps = true;
}
