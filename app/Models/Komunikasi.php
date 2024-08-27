<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Komunikasi extends Model
{
    use HasFactory;

    protected $primaryKey = 'komunikasi_id';

    protected $fillable = [
        'risk_id',
        'komunikasi_tujuan',
        'komunikasi_konten',
        'komunikasi_media',
        'komunikasi_metode',
        'komunikasi_pemilihanWaktu',
        'komunikasi_frekuensi',
        'komunikasi_lockStatus',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    // Search Model
    public function scopeSearch($query, $value){
        $query->where('komunikasi_tujuan', 'like', "%{$value}%")
            ->orWhere('komunikasi_konten', 'like', "%{$value}%")
            ->orWhere('komunikasi_media', 'like', "%{$value}%")
            ->orWhere('komunikasi_pemilihanWaktu', 'like', "%{$value}%")
            ->orWhere('komunikasi_frekuensi', 'like', "%{$value}%");
    }

    // relationship with risk
    public function risk()
    {
        return $this->belongsTo(Risk::class, 'risk_id');
    }

    // relationship with komunikasi stakeholder
    public function komunikasiStakeholder()
    {
        return $this->hasMany(KomunikasiStakeholder::class, 'komunikasi_id');
    }
}
