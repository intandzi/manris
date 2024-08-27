<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Konsultasi extends Model
{
    use HasFactory;

    protected $primaryKey = 'konsultasi_id';

    protected $fillable = [
        'risk_id',
        'konsultasi_tujuan',
        'konsultasi_konten',
        'konsultasi_media',
        'konsultasi_metode',
        'konsultasi_lockStatus',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    // Search Model
    public function scopeSearch($query, $value){
        $query->where('konsultasi_tujuan', 'like', "%{$value}%")
            ->orWhere('konsultasi_konten', 'like', "%{$value}%")
            ->orWhere('konsultasi_metode', 'like', "%{$value}%")
            ->orWhere('konsultasi_media', 'like', "%{$value}%");
    }

    // relationship with risk
    public function risk()
    {
        return $this->belongsTo(Risk::class, 'risk_id');
    }

    // relationship with konsultasi stakeholder
    public function konsultasiStakeholder()
    {
        return $this->hasMany(KonsultasiStakeholder::class, 'konsultasi_id');
    }
}
