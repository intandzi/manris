<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisPerlakuan extends Model
{
    use HasFactory;

    protected $primaryKey = 'jenisPerlakuan_id';

    protected $fillable = [
        'jenisPerlakuan_desc',
        'jenisPerlakuan_activeStatus',
        'created_by',
        'updated_by',
        'deleted_by',

    ];

    // relationship one to many with perlakuan risiko
    public function perlakuanRisiko()
    {
        return $this->hasMany(PerlakuanRisiko::class, 'jenisPerlakuan_id');
    }
}
