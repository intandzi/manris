<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KriteriaKemungkinan extends Model
{
    use HasFactory;

    protected $primaryKey = 'kemungkinan_id';

    protected $fillable = [
        'risk_id',
        'kemungkinan_scale',
        'kemungkinan_desc',
        'kemungkinan_lockStatus',
        'kemungkinan_activeStatus',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    // Scope to filter by dampak_lockStatus
    public function scopeLocked($query)
    {
        return $query->where('kemungkinan_lockStatus', true);
    }

    // relationship many to one with risk
    public function risk()
    {
        return $this->belongsTo(Risk::class, 'risk_id');
    }

    // relationship one to many with controlRisk
    public function controlRisk()
    {
        return $this->hasMany(ControlRisk::class, 'kemungkinan_id');
    }
}
