<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KriteriaDampak extends Model
{
    use HasFactory;

    protected $primaryKey = 'dampak_id';

    protected $fillable = [
        'risk_id',
        'dampak_scale',
        'dampak_desc',
        'dampak_lockStatus',
        'dampak_activeStatus',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    // Scope to filter by dampak_lockStatus
    public function scopeLocked($query)
    {
        return $query->where('dampak_lockStatus', true);
    }

    // relationship many to one with risk
    public function risk()
    {
        return $this->belongsTo(Risk::class, 'risk_id');
    }

    // relationship one to many with controlRisk
    public function controlRisk()
    {
        return $this->hasMany(ControlRisk::class, 'dampak_id');
    }
}
