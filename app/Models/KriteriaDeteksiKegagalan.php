<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KriteriaDeteksiKegagalan extends Model
{
    use HasFactory;

    protected $primaryKey = 'deteksiKegagalan_id';

    protected $fillable = [
        'risk_id',
        'deteksiKegagalan_scale',
        'deteksiKegagalan_desc',
        'deteksiKegagalan_lockStatus',
        'deteksiKegagalan_activeStatus',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    // Scope to filter by dampak_lockStatus
    public function scopeLocked($query)
    {
        return $query->where('deteksiKegagalan_lockStatus', true);
    }

    // relationship many to one with risk
    public function risk()
    {
        return $this->belongsTo(Risk::class, 'risk_id');
    }

    // relationship one to many with controlRisk
    public function controlRisk()
    {
        return $this->hasMany(ControlRisk::class, 'deteksiKegagalan_id');
    }
}
