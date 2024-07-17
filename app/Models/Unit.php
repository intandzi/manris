<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    protected $primaryKey = 'unit_id';

    protected $fillable = [
        'unit_name',
        'unit_activeStatus',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    // Search Model
    public function scopeSearch($query, $value){
        $query->where('unit_name', 'like', "%{$value}%");
    }

    // relationship with unit (one-to-many)
    public function user()
    {
        return $this->hasMany(User::class, 'unit_id');
    }

    // relationship with visimisi (one-to-many)
    public function visimisi()
    {
        return $this->hasMany(VisiMisi::class, 'unit_id');
    }

    // relationship with kpi (one-to-many)
    public function kpi()
    {
        return $this->hasMany(KPI::class, 'unit_id');
    }
}
