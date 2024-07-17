<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RencanaPerlakuan extends Model
{
    use HasFactory;

    protected $primaryKey = 'perlakuanRisiko_id';

    protected $fillable = [
        'perlakuanRisiko_id',
        'rencanaPerlakuan_desc',
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
