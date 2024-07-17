<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisiMisi extends Model
{
    use HasFactory;

    protected $primaryKey = 'visimisi_id';

    protected $fillable = [
        'unit_id',
        'visimisi_visi',
        'visimisi_misi',
        'visimisi_activeStatus',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
    
    // relationship with unit (one-to-many)
    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
}
