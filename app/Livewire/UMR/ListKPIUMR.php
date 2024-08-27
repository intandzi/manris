<?php

namespace App\Livewire\UMR;

use App\Models\KPI;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class ListKPIUMR extends Component
{
    use WithPagination;

    // TITLE COMPONENT
    #[Title('KPI Unit')]
    public $title;

    // GLOBAL VARIABLES
    public $perPage     = 5;
    public $search      = '';
    public $searchPeriod = '';

    public $years = [];

    // VARIABLES LIST UNIT MODEL
    public $unit_id, $kategoriStandar;

    // VARIABLES KPI MODEL
    public $kpi_id, $kpi_kode, $kpi_nama, $kpi_tanggalMulai, $kpi_tanggalAkhir, $kpi_periode, $kategoriStandar_id, $kpi_kategoriKinerja, $kpi_indikatorKinerja, $kpi_dokumenPendukung, $dokumen;

    public $isShow = false;

    public $isEdit = false;

    public $role, $encryptedRole, $unit, $encryptedUnit;
    // CONTRUCTOR COMPONENT
    public function mount()
    {
        // RECEIVE ROLE USER
        $this->role = Crypt::decryptString(request()->query('role'));

        $unit       = Crypt::decryptString(request()->query('unit'));
        
        // RETRIVE ROLE USER
        $this->encryptedRole = Crypt::encryptString($this->role);

        // RETRIVE ROLE UNIT
        $this->encryptedUnit = Crypt::encryptString($this->unit);
        
        // RECEIVE UNIT ID
        $this->unit_id = $unit;
        
        // FIND UNIT SPECIFIC
        $this->unit = Unit::with(['kpi.kategoriStandar', 'visimisi'])->where('unit_id', $unit)->first();
        
        // SET TITLE
        $this->title = $this->unit->unit_name;

        // Get the current year
        $currentYear = Carbon::now()->year;

        // Generate years for the select options
        for ($i = $currentYear - 4; $i <= $currentYear + 4; $i++) {
            $this->years[$i] = $i;
        }
    }

    // COMPONENT RENDER
    public function render()
    {
        $kpis = KPI::with(['unit', 'kategoriStandar', 'konteks.risk', 'konteks.historyPengembalian'])->where('unit_id', $this->unit_id)
            ->where('kpi_lockStatus', true)    
            ->where('kpi_activeStatus', true)
            ->search($this->search)
            ->when($this->searchPeriod, function($query){
                $query->where('kpi_periode', 'like', '%' . $this->searchPeriod . '%');
            })
            ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')
            ->paginate($this->perPage);
        
        return view('livewire.pages.umr.validasi-kpi.list-kpi-umr', [
            'kpis'             => $kpis,
            'paginationInfo'   => $kpis->total() > 0
            ? "Showing " . ($kpis->firstItem()) . " to " . ($kpis->lastItem()) . " of " . ($kpis->total()) . " entries"
            : "No entries found",
        ]);
    }

    // COUNT KONTEKS ALREADY SEND
    public function countKontekssWithStatusTrue($kpi)
    {
        return $kpi->konteks->filter(function ($konteks) {
            return $konteks->konteks_isSendUMR === 1;
        })->count();
        // return $kpi->konteks->flatMap(function ($konteks) {
        //     return $konteks->risk->filter(function ($risk) {
        //         return $risk->risk_isSendUMR === 1;
        //     });
        // })->count();
    }

    // REDIRECT TO DETAIL KPI FOR KONTEKS RISIKO
    public function konteksRisiko($id)
    {
        // SENDING KPI ID
        $encryptedId    = Crypt::encryptString($id);
        // SENDING ROLE USER
        $encryptedRole  = Crypt::encryptString($this->role);

        $encryptedUnit  = Crypt::encryptString($this->unit_id);
        
        $this->redirect(route('logKonteksRisiko.index', ['role' => $encryptedRole, 'unit' => $encryptedUnit, 'kpi' => $encryptedId]), navigate:true);
    }
    

    // HISTORY PENGEMBALIAN
    public $isOpenHistoryPengembalian = 0;

    public $historyPengembalian;

    // OPEN HISTORY PENGEMBALIAN
    public function openHistoryPengembalian($kpi_id)
    {
        $this->isOpenHistoryPengembalian = 1;

        $kpi = KPI::with(['konteks.historyPengembalian'])
            ->where('unit_id', $this->unit_id)
            ->where('kpi_lockStatus', true)    
            ->where('kpi_activeStatus', true)
            ->find($kpi_id);

        $this->historyPengembalian = $kpi;
    }

    // CLOSE HISTORY PENGEMBALIAN
    public function closeHistoryPengembalian()
    {
        $this->isOpenHistoryPengembalian = 0;
    }
    // CLOSE HISTORY PENGEMBALIAN
    public function closeXHistoryPengembalian()
    {
        $this->isOpenHistoryPengembalian = 0;
    }

    // SORTING DATA KPI
    public $orderBy     = 'kpi_id';
    public $orderAsc    = true;
    public function doSort($column)
    {
        if ($this->orderBy === $column) {
            $this->orderAsc = !$this->orderAsc; // Toggle the sorting order
        } else {
            $this->orderBy = $column;
            $this->orderAsc = true; // Default sorting order when changing the column
        }
    } 
}
