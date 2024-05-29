<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    protected $primaryKey = 'unit_id';

    protected $fillable = [
        'unit_name',
        'unit_activeStatus',
    ];

    // relationship with unit (one-to-many)
    public function user()
    {
        return $this->belongsTo(User::class, 'unit_id');
    }
}
