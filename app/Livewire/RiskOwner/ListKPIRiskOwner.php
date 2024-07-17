<?php

namespace App\Livewire\RiskOwner;

use App\Models\KategoriStandar;
use App\Models\KPI;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Livewire\WithPagination;

class ListKPIRiskOwner extends Component
{
    use WithPagination, WithFileUploads;

    // TITLE COMPONENT
    #[Title('KPI Unit')]
    public $title;

    // GLOBAL VARIABLES
    public $perPage     = 5;
    public $search      = '';
    public $searchPeriod = '';

    public $years = [];

    // VARIABLES LIST UNIT MODEL
    public $unit_id, $unit, $kategoriStandar;

    // VARIABLES KPI MODEL
    public $kpi_id, $kpi_kode, $kpi_nama, $kpi_tanggalMulai, $kpi_tanggalAkhir, $kpi_periode, $kategoriStandar_id, $kpi_kategoriKinerja, $kpi_indikatorKinerja, $kpi_dokumenPendukung, $dokumen;

    public $isShow = false;

    public $isEdit = false;

    public $role, $encryptedRole;
    // CONTRUCTOR COMPONENT
    public function mount()
    {
        // RECEIVE ROLE USER
        $this->role = Crypt::decryptString(request()->query('role'));

        // RETRIVE ROLE USER
        $this->encryptedRole = Crypt::encryptString($this->role);

        // RECEIVE UNIT ID
        $this->unit_id = Auth::user()->unit_id;
        
        // FIND UNIT SPECIFIC
        $this->unit = Unit::with(['kpi.kategoriStandar', 'visimisi'])->where('unit_id', $this->unit_id)->first();

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
        $kpis = KPI::with(['unit', 'kategoriStandar'])->where('unit_id', $this->unit_id)
            ->where('kpi_lockStatus', true)    
            ->where('kpi_activeStatus', true)    
            ->search($this->search)
            ->when($this->searchPeriod, function($query){
                $query->where('kpi_periode', 'like', '%' . $this->searchPeriod . '%');
            })
            ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')
            ->paginate($this->perPage);
        
        return view('livewire.pages.risk-owner.list-kpi.list-kpi-risk-owner', [
            'kpis'             => $kpis,
            'paginationInfo'    => $kpis->total() > 0
            ? "Showing " . ($kpis->firstItem()) . " to " . ($kpis->lastItem()) . " of " . ($kpis->total()) . " entries"
            : "No entries found",
        ]);
    }

    // REDIRECT TO DETAIL KPI FOR KONTEKS RISIKO
    public function konteksRisiko($id)
    {
        // SENDING KPI ID
        $encryptedId    = Crypt::encryptString($id);
        // SENDING ROLE USER
        $encryptedRole  = Crypt::encryptString($this->role);

        $this->redirect(route('konteksRisikoOw.index', ['role' => $encryptedRole, 'kpi' => $encryptedId]), navigate:true);
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
