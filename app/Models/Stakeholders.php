<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stakeholders extends Model
{
    use HasFactory;

    protected $primaryKey = 'stakeholder_id';

    protected $fillable = [
        'stakeholder_jabatan',
        'stakeholder_singkatan',
        'stakeholder_activeStatus',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    // Search Model
    public function scopeSearch($query, $value){
        $query->where('stakeholder_jabatan', 'like', "%{$value}%")
            ->orWhere('stakeholder_singkatan', 'like', "%{$value}%");
    }

    
    // Relation many to one with raci
    public function raci()
    {
        return $this->hasMany(RaciModel::class, 'stakeholder_id');
    }
    // Relation many to one with komunikasi stakeholder
    public function komunikasiStakeholder()
    {
        return $this->hasMany(KomunikasiStakeholder::class, 'stakeholder_id');
    }
    // Relation many to one with konsultasiStakeholder
    public function konsultasiStakeholder()
    {
        return $this->hasMany(KonsultasiStakeholder::class, 'stakeholder_id');
    }
}
