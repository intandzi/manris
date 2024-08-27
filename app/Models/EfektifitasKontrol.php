<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EfektifitasKontrol extends Model
{
    use HasFactory;

    protected $primaryKey = 'efektifitasKontrol_id';

    protected $fillable = [
        'risk_id',
        'controlRisk_id',
        'efektifitasKontrol_kontrolStatus',
        'efektifitasKontrol_kontrolDesc',
        'efektifitasKontrol_totalEfektifitas',
        'efektifitasKontrol_lockStatus',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    // relationship one to many with risk
    public function risk()
    {
        return $this->belongsTo(Risk::class, 'risk_id');
    }
    // relationship one to many with detail efektifitas kontrol
    public function detailEfektifitasKontrol()
    {
        return $this->hasMany(DetailEfektifitasKontrol::class, 'efektifitasKontrol_id');
    }

    // relationship many to one with risk
    public function controlRisk()
    {
        return $this->belongsTo(ControlRisk::class, 'controlRisk_id');
    }
}
