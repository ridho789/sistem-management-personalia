<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataLeave extends Model
{
    use HasFactory;
    protected $table = 'tbl_data_cuti';

    public $timestamps = true;
}
