<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeleraRisiko extends Model
{
    use HasFactory;

    protected $primaryKey = 'seleraRisiko_id';

    protected $fillable = [
        'derajatRisiko_id',
        'seleraRisiko_desc',
        'seleraRisiko_tindakLanjut',
        'seleraRisiko_activeStatus',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    // Search Model
    public function scopeSearch($query, $value){
        $query->where('seleraRisiko_desc', 'like', "%{$value}%")
                ->orWhere('seleraRisiko_tindakLanjut', 'like', "%{$value}%");
    }

    // relationship many to one with derajat risiko
    public function derajatRisiko()
    {
        return $this->belongsTo(DerajatRisiko::class, 'derajatRisiko_id');
    }

    // relationship many to one with controlRisk
    public function controlRisk()
    {
        return $this->hasMany(ControlRisk::class, 'seleraRisiko_id');
    }
}
