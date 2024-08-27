<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KonsultasiStakeholder extends Model
{
    use HasFactory;

    protected $primaryKey = 'konsultasiStakeholder_id';

    protected $fillable = [
        'konsultasi_id',
        'stakeholder_id',
        'konsultasiStakeholder_ket',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    // relationship with konsultasi
    public function konsultasi()
    {
        return $this->belongsTo(Konsultasi::class, 'konsultasi_id');
    }

    // relationship with stakeholder
    public function stakeholder()
    {
        return $this->belongsTo(Stakeholders::class, 'stakeholder_id');
    }
}
