<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Asset extends Model
{
    use HasFactory;
    protected $table = 'tbl_aset';

    protected $fillable = [
        'nama_aset',
        'id_kategori',
        'id_sub_kategori',
        'spesifikasi',
        'nopol',
        'merk',
        'tahun',
        'masa_pajak',
        'masa_plat',
        'lokasi',
        'id_perusahaan',
    ];

    public function isExpiring($expirationDate)
    {
        $expiration = Carbon::parse($expirationDate);
        $now = Carbon::now();
        $threeDaysFromNow = $now->copy()->addDays(3);
        $sevenDaysFromNow = $now->copy()->addDays(7);

        if ($expiration->lte($threeDaysFromNow)) {
            return 'red';
        } elseif ($expiration->lte($sevenDaysFromNow)) {
            return 'orange';
        } else {
            return 'normal';
        }
    }

    public function isTaxExpiring()
    {
        if ($this->masa_pajak){
            return $this->isExpiring($this->masa_pajak);
        }
    }

    public function isPlateExpiring()
    {
        if ($this->masa_plat){
            return $this->isExpiring($this->masa_plat);
        }
    }



}
