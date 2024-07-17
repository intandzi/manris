<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriStandar extends Model
{
    use HasFactory;

    protected $primaryKey = 'kategoriStandar_id';

    protected $fillable = [
        'kategoriStandar_desc',
        'kategoriStandar_activeStatus',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    // Search Model
    public function scopeSearch($query, $value){
        $query->where('kategoriStandar_desc', 'like', "%{$value}%");
    }


    // relationship with kpiUnit (one-to-many)
    public function kpiUnit()
    {
        return $this->hasMany(KPI::class, 'kategoriStandar_id');
    }
}
