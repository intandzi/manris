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
}
