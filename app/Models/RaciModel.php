<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RaciModel extends Model
{
    use HasFactory;

    protected $primaryKey = 'raci_id';

    protected $fillable = [
        'risk_id',
        'stakeholder_id',
        'controlRisk_id',
        'raci_desc',
        'raci_lockStatus',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    // Relation one to many with risk
    public function risk()
    {
        return $this->belongsTo(Risk::class, 'risk_id');
    }

    // Relation one to many with controlRisk
    public function controlRisk()
    {
        return $this->belongsTo(ControlRisk::class, 'controlRisk_id');
    }

    // Relation one to many with stakeholders
    public function stakeholder()
    {
        return $this->belongsTo(Stakeholders::class, 'stakeholder_id');
    }
}
