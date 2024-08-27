<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PemantauanTinjauan extends Model
{
    use HasFactory;

    protected $primaryKey = 'pemantauanTinjauan_id';

    protected $fillable = [
        'perlakuanRisiko_id',
        'pemantauanTinjauan_pemantauanDesc',
        'pemantauanTinjauan_tinjauanDesc',
        'pemantauanTinjauan_buktiPemantauan',
        'pemantauanTinjauan_buktiTinjauan',
        'pemantauanTinjauan_freqPemantauan',
        'pemantauanTinjauan_freqPelaporan',
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
