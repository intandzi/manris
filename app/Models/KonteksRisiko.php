<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KonteksRisiko extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'konteks_id';

    protected $fillable = [
        'kpi_id',
        'konteks_kode',
        'konteks_desc',
        'konteks_kategori',
        'konteks_lockStatus',
        'konteks_isSendUMR',
        'konteks_activeStatus',
    ];

    // relationship with kpi (one-to-many)
    public function kpi()
    {
        return $this->belongsTo(KPI::class, 'kpi_id');
    }

    // relationship one to many with risk
    public function risk()
    {
        return $this->hasMany(Risk::class, 'konteks_id');
    }

    // relationship with historyPengembalian (one-to-many)
    public function historyPengembalian()
    {
        return $this->hasMany(HistoryPengembalian::class, 'konteks_id');
    }

    // Search Model
    public function scopeSearch($query, $value){
        
        $query->where('konteks_kode', 'like', "%{$value}%")
            ->orWhere('konteks_desc', 'like', "%{$value}%")
            ->orWhere('konteks_kategori', 'like', "%{$value}%");
    }

    /**
     * Generate a new Konteks code based on the given KPI ID.
     *
     * @param  int  $kpi_id
     * @return string
     */
    public static function generateKonteksCode($kpi_id) {
        // Get the last inserted Konteks based on the konteks_kode for the given KPI ID
        $lastKonteks = self::where('kpi_id', $kpi_id)
            ->orderBy('konteks_kode', 'desc')
            ->first();

        if ($lastKonteks) {
            // Extract the numerical part from the last konteks_kode and increment it
            $lastCodeNumber = (int) substr($lastKonteks->konteks_kode, 1);
            $newCodeNumber = $lastCodeNumber + 1;
        } else {
            // If no Konteks exists, start with 1
            $newCodeNumber = 1;
        }

        // Format the new code with leading zeros
        $newKonteksCode = 'K' . str_pad($newCodeNumber, 5, '0', STR_PAD_LEFT);

        return $newKonteksCode;
    }

}
