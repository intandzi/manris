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
        'derajatRisiko_id',
        'seleraRisiko_id',
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

    // relationship many to one with efektifitasControl
    public function efektifitasControl()
    {
        return $this->hasOne(EfektifitasKontrol::class, 'controlRisk_id');
    }

    // relationship many to one with derajat risiko
    public function derajatRisiko()
    {
        return $this->belongsTo(DerajatRisiko::class, 'derajatRisiko_id');
    }

    // relationship many to one with selera risiko
    public function seleraRisiko()
    {
        return $this->belongsTo(SeleraRisiko::class, 'seleraRisiko_id');
    }

    // relationship many to one with perlakuan risiko
    public function perlakuanRisiko()
    {
        return $this->hasMany(PerlakuanRisiko::class, 'controlRisk_id');
    }

    // relationship many to one with raci
    public function raci()
    {
        return $this->hasMany(RaciModel::class, 'controlRisk_id');
    }
}
