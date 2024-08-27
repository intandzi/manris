<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DerajatRisiko extends Model
{
    use HasFactory;

    protected $primaryKey = 'derajatRisiko_id';

    protected $fillable = [
        'derajatRisiko_desc',
        'derajatRisiko_nilaiTingkatMin',
        'derajatRisiko_nilaiTingkatMax',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    // Search Model
    public function scopeSearch($query, $value)
    {
        return $query->where(function($query) use ($value) {
            $query->where('derajatRisiko_desc', 'like', "%{$value}%")
                  ->orWhereHas('seleraRisiko', function($query) use ($value) {
                      $query->where('seleraRisiko_desc', 'like', "%{$value}%")
                            ->orWhere('seleraRisiko_tindakLanjut', 'like', "%{$value}%");
                  });
        });
    }

    // selera risiko active
    public function scopeWithActiveSeleraRisiko($query)
    {
        return $query->whereHas('seleraRisiko', function($query) {
            $query->where('seleraRisiko_activeStatus', true);
        });
    }

    // relationship many to one with selera risiko
    public function seleraRisiko()
    {
        return $this->hasMany(SeleraRisiko::class, 'derajatRisiko_id');
    }

    // relationship many to one with risk
    public function risk()
    {
        return $this->hasMany(Risk::class, 'derajatRisiko_id');
    }

    // relationship many to one with risk
    public function controlRisk()
    {
        return $this->hasMany(ControlRisk::class, 'derajatRisiko_id');
    }
}
