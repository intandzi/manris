<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PemantauanKajian extends Model
{
    use HasFactory;

    protected $primaryKey = 'pemantauanKajian_id';

    protected $fillable = [
        'perlakuanRisiko_id',
        'pemantauanKajian_pemantauan',
        'pemantauanKajian_kajian',
        'pemantauanKajian_buktiPemantauan',
        'pemantauanKajian_buktiKajian',
        'pemantauanKajian_freqPemantauan',
        'pemantauanKajian_freqPelaporan',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    // relationship one to many with perlakuan risiko
    public function perlakuanRisiko()
    {
        return $this->belongsTo(PerlakuanRisiko::class, 'perlakuanRisiko_id');
    }
}
