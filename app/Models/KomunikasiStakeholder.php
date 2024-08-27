<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KomunikasiStakeholder extends Model
{
    use HasFactory;

    protected $primaryKey = 'komunikasiStakeholder_id';

    protected $fillable = [
        'komunikasi_id',
        'stakeholder_id',
        'komunikasiStakeholder_ket',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    // relationship with komunikasi
    public function komunikasi()
    {
        return $this->belongsTo(Komunikasi::class, 'komunikasi_id');
    }

    // relationship with stakeholder
    public function stakeholder()
    {
        return $this->belongsTo(Stakeholders::class, 'stakeholder_id');
    }
}
