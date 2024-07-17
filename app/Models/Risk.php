<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Risk extends Model
{
    use HasFactory;

    protected $primaryKey = 'risk_id';

    protected $fillable = [
        'konteks_id',
        'risk_kode',
        'risk_riskDesc',
        'risk_penyebabKode',
        'risk_penyebab',
        'risk_lockStatus',
        'risk_activeStatus',
        'risk_kriteriaLockStatus',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    // Search Model
    public function scopeSearch($query, $value){
        
        $query->where('risk_kode', 'like', "%{$value}%")
            ->orWhere('risk_riskDesc', 'like', "%{$value}%")
            ->orWhere('risk_penyebab', 'like', "%{$value}%");
    }

    // relationship one to many with kriteria dampak
    public function dampak()
    {
        return $this->hasMany(KriteriaDampak::class, 'risk_id');
    }

    // relationship one to many with kriteria kemungkinan
    public function kemungkinan()
    {
        return $this->hasMany(KriteriaKemungkinan::class, 'risk_id');
    }

    // relationship one to many with kriteria deteksi
    public function deteksiKegagalan()
    {
        return $this->hasMany(KriteriaDeteksiKegagalan::class, 'risk_id');
    }

    // relationship one to many with kriteria controlRisk
    public function controlRisk()
    {
        return $this->hasMany(ControlRisk::class, 'risk_id');
    }

    /**
     * Generate a new Risk code based on the given prefix and KONTEKS ID.
     *
     * @param  string  $prefix
     * @param  int  $konteks_id
     * @return string
     */
    public static function generateRiskCode($prefix, $konteks_id) {
        return self::generateCode($prefix, $konteks_id, 'risk_kode');
    }

    /**
     * Generate a new Penyebab code based on the given prefix and KONTEKS ID.
     *
     * @param  string  $prefix
     * @param  int  $konteks_id
     * @return string
     */
    public static function generatePenyebabCode($prefix, $konteks_id) {
        return self::generateCode($prefix, $konteks_id, 'risk_penyebabKode');
    }

    /**
     * Generate a new code based on the given prefix, KONTEKS ID, and column name.
     *
     * @param  string  $prefix
     * @param  int  $konteks_id
     * @param  string  $column
     * @return string
     */
    private static function generateCode($prefix, $konteks_id, $column) {
        // Get the last inserted Risk based on the prefix and konteks_id
        $lastRisk = self::where('konteks_id', $konteks_id)
            ->where($column, 'like', $prefix . '-%')
            ->orderBy('risk_id', 'desc')
            ->first();

        if ($lastRisk) {
            // Extract the numerical part from the last code and increment it
            $lastCodeNumber = (int) substr($lastRisk->$column, strlen($prefix) + 1);
            $newCodeNumber = $lastCodeNumber + 1;
        } else {
            // If no Risk exists, start with 1
            $newCodeNumber = 1;
        }

        // Format the new code with leading zeros
        return $prefix . '-' . str_pad($newCodeNumber, 3, '0', STR_PAD_LEFT);
    }
}
