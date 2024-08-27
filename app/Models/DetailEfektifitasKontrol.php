<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailEfektifitasKontrol extends Model
{
    use HasFactory;

    protected $primaryKey = 'detailEfektifitasKontrol_id';

    protected $fillable = [
        'efektifitasKontrol_id',
        'penilaianEfektifitas_id',
        'detailEfektifitasKontrol_skor',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    // relationship one to many with efektifitas kontrol
    public function efektifitasKontrol()
    {
        return $this->belongsTo(EfektifitasKontrol::class, 'efektifitasKontrol_id');
    }

    // relationship one to many with penilaian efektifitas
    public function penilaianEfektifitas()
    {
        return $this->belongsTo(PenilaianEfektifitas::class, 'penilaianEfektifitas_id');
    }
}
