<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryPengembalian extends Model
{
    use HasFactory;

    protected $primaryKey = 'historyPengembalian_id';

    protected $fillable = [
        'konteks_id',
        'historyPengembalian_tgl',
        'historyPengembalian_alasan',
        'historyPengembalian_isRiskRegister',
        'historyPengembalian_isRiskControl',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    // relationship with konteks (one-to-many)
    public function konteks()
    {
        return $this->belongsTo(KonteksRisiko::class, 'konteks_id');
    }

}
