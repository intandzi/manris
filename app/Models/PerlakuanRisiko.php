<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerlakuanRisiko extends Model
{
    use HasFactory;

    protected $primaryKey = 'perlakuanRisiko_id';

    protected $fillable = [
        'controlRisk_id',
        'jenisPerlakuan_id',
        'perlakuanRisiko_lockStatus',
        'pemantauanKajian_lockStatus',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    // relationship many to one with control risk
    public function controlRisk()
    {
        return $this->belongsTo(ControlRisk::class, 'controlRisk_id');
    }

    // relationship one to many with jenis perlakuan
    public function jenisPerlakuan()
    {
        return $this->belongsTo(JenisPerlakuan::class, 'jenisPerlakuan_id');
    }

    // relationship one to many with rencana perlakuan risiko
    public function rencanaPerlakuan()
    {
        return $this->hasMany(RencanaPerlakuan::class, 'perlakuanRisiko_id');
    }

    // relationship one to many with pemantauan kajian
    public function pemantauanKajian()
    {
        return $this->hasMany(PemantauanKajian::class, 'perlakuanRisiko_id');
    }
}
