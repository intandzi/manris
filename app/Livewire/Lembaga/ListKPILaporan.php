<?php

namespace App\Livewire\Lembaga;

use App\Models\KategoriStandar;
use App\Models\KPI;
use App\Models\Unit;
use Illuminate\Support\Facades\Crypt;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class ListKPILaporan extends Component
{
    use WithPagination;

    // TITLE COMPONENT
    #[Title('KPI Unit')]
    public $title;

    // GLOBAL VARIABLES
    public $perPage     = 5;
    public $search      = '';

    // VARIABLES LIST UNIT MODEL
    public $unit_id, $unit, $kategoriStandar;

    // VARIABLES KPI MODEL
    public $kpi_id, $kpi_kode, $kpi_nama, $kpi_tanggalMulai, $kpi_tanggalAkhir, $kategoriStandar_id, $kpi_kategoriKinerja, $kpi_indikatorKinerja, $kpi_dokumenPendukung;

    public $isShow = false;

    public $role, $encryptedRole;

    // CONTRUCTOR COMPONENT
    public function mount()
    {
        // RECEIVE ROLE USER
        $this->role = Crypt::decryptString(request()->query('role'));

        // RETRIVE ROLE USER
        $this->encryptedRole = Crypt::encryptString($this->role);

        // RETRIVE UNIT ID
        $encryptedId = request()->query('unit');
        $this->unit_id = Crypt::decryptString($encryptedId);
        
        // FIND UNIT SPECIFIC
        $this->unit = Unit::with(['kpi.kategoriStandar', 'visimisi'])->where('unit_id', $this->unit_id)->first();

        // PASSING KATEGORI STANDAR
        $this->kategoriStandar = KategoriStandar::where('kategoriStandar_activeStatus', true)->get();

        // SET TITLE
        $this->title = $this->unit->unit_name;
    }

    // COMPONENT RENDER
    public function render()
    {
        $kpis = KPI::with(['unit', 'kategoriStandar'])->where('unit_id', $this->unit_id)->search($this->search)
            ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')
            ->paginate($this->perPage);
        
        return view('livewire.pages.lembaga.laporan-mrisk.list-kpi-laporan.list-kpi-laporan', [
            'kpis'             => $kpis,
            'paginationInfo'    => $kpis->total() > 0
            ? "Showing " . ($kpis->firstItem()) . " to " . ($kpis->lastItem()) . " of " . ($kpis->total()) . " entries"
            : "No entries found",
        ]);
    }

    // SORTING DATA VISI MISI
    public $orderBy     = 'unit_id';
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


    // RESET PAGINATION AFTER SEARCH
    public function updatedPerPage()
    {
        $this->resetPage();
    }

    // RESET PAGINATION IF AFTER SEARCH
    public function updatedSearch()
    {
        $this->resetPage();
    }

    // CLEAR SEARCH
    public function clearSearch()
    {
        $this->search = '';
    }
}
