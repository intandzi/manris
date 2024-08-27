<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KPI extends Model
{
    use HasFactory;

    protected $primaryKey = 'kpi_id';

    protected $fillable = [
        'unit_id',
        'kategoriStandar_id',
        'kpi_kode',
        'kpi_nama',
        'kpi_tanggalMulai',
        'kpi_tanggalAkhir',
        'kpi_periode',
        'kpi_kategoriKinerja',
        'kpi_indikatorKinerja',
        'kpi_dokumenPendukung',
        'kpi_lockStatus',
        'kpi_sendUMRStatus',
        'kpi_activeStatus',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    // Search Model
    public function scopeSearch($query, $value){
        $query->where('kpi_nama', 'like', "%{$value}%")
            ->orWhere('kpi_tanggalMulai', 'like', "%{$value}%")
            ->orWhere('kpi_kategoriKinerja', 'like', "%{$value}%")
            ->orWhere('kpi_periode', 'like', "%{$value}%")
            ->orWhere('kpi_kode', 'like', "%{$value}%");
    }

    // MAKE CODE
    public static function generateKpiKode($unit_id, $period)
    {
        // Extract the last two digits of the year from the period
        $year = substr($period, 2, 2);

        // Get the last KPI for the given unit_id
        $lastKpi = self::where('unit_id', $unit_id)
            ->where('kpi_kode', 'like', "KP-{$year}-%")
            ->orderBy('kpi_kode', 'desc')
            ->first();

        // Determine the next incrementing number
        if ($lastKpi) {
            $lastNumber = (int)substr($lastKpi->kpi_kode, 6);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        // Format the incrementing number with leading zeros
        $formattedNumber = str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

        // Combine to form the KPI code
        return "KP-{$year}-{$formattedNumber}";
    }

    // relationship with koteks (one-to-many)
    public function konteks()
    {
        return $this->hasMany(KonteksRisiko::class, 'kpi_id');
    }

    // relationship with kategoriStandar (one-to-many)
    public function kategoriStandar()
    {
        return $this->belongsTo(KategoriStandar::class, 'kategoriStandar_id');
    }

    // relationship with unit (one-to-many)
    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
}
