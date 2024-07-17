<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ControlRisk extends Model
{
    use HasFactory;

    protected $primaryKey = 'controlRisk_id';

    protected $fillable = [
        'kemungkinan_id',
        'dampak_id',
        'deteksiKegagalan_id',
        'risk_id',
        'controlRisk_RPN',
        'controlRisk_RTM',
        'controlRisk_efektivitas',
        'controlRisk_lockStatus',
        'created_by',
        'updaed_by',
        'deleted_by',
    ];

    // relationship many to one with dampak
    public function dampak()
    {
        return $this->belongsTo(KriteriaDampak::class, 'dampak_id');
    }

    // relationship many to one with kemungkinan
    public function kemungkinan()
    {
        return $this->belongsTo(KriteriaKemungkinan::class, 'kemungkinan_id');
    }

    // relationship many to one with deteksi
    public function deteksiKegagalan()
    {
        return $this->belongsTo(KriteriaDeteksiKegagalan::class, 'deteksiKegagalan_id');
    }

    // relationship many to one with risk
    public function risk()
    {
        return $this->belongsTo(Risk::class, 'risk_id');
    }

    // relationship many to one with perlakuan risiko
    public function perlakuanRisiko()
    {
        return $this->hasMany(PerlakuanRisiko::class, 'controlRisk_id');
    }
}
