<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenilaianEfektifitas extends Model
{
    use HasFactory;

    protected $primaryKey = 'penilaianEfektifitas_id';

    protected $fillable = [
        'penilaianEfektifitas_pertanyaan',
        'penilaianEfektifitas_ya',
        'penilaianEfektifitas_sebagian',
        'penilaianEfektifitas_tidak',
        'penilaianEfektifitas_activeStatus',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    // Search Model
    public function scopeSearch($query, $value){
        $query->where('penilaianEfektifitas_pertanyaan', 'like', "%{$value}%")
                ->orWhere('penilaianEfektifitas_ya', 'like', "%{$value}%")
                ->orWhere('penilaianEfektifitas_sebagian', 'like', "%{$value}%")
                ->orWhere('penilaianEfektifitas_tidak', 'like', "%{$value}%");
    }

    // relationship one to many with detail efektifitas kontrol
    public function detailEfektifitasKontrol()
    {
        return $this->hasMany(DetailEfektifitasKontrol::class, 'penilaianEfektifitas_id');
    }
}
