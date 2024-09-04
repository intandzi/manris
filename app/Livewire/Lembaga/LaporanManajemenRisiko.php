<?php

namespace App\Livewire\Lembaga;

use App\Models\Unit;
use Illuminate\Support\Facades\Crypt;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class LaporanManajemenRisiko extends Component
{
    use WithPagination;

    // TITLE COMPONENT
    #[Title('Laporan Manajemen Risiko')]
    public $title = 'Laporan Manajemen Risiko';

    // GLOBAL VARIABLES
    public $perPage     = 5;
    public $search      = '';

    // VARIABLE GLOBAL CREATE UNIT
    public $unit_id, $unit_name, $visimisi_id, $visimisi_visi, $visimisi_misi, $created_at;
    public $isShow = false;

    public $role, $decryptedRole;

    // CONSTRUCTOR COMPONENT
    public function mount()
    {
        // RETRIVE ROLE USER
        $role = Crypt::decryptString(request()->query('role'));
        $this->role = Crypt::encryptString($role);

        $this->decryptedRole = $role;
    }

    public function render()
    {
        $units = Unit::with(['visimisi'])->search($this->search)
            ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')
            ->paginate($this->perPage);
        
        return view('livewire.pages.lembaga.laporan-mrisk.laporan-mrisk', [
            'units'             => $units,
            'paginationInfo'    => $units->total() > 0
            ? "Showing " . ($units->firstItem()) . " to " . ($units->lastItem()) . " of " . ($units->total()) . " entries"
            : "No entries found",
        ]);
    }

    public function listKPILaporan($id)
    {
        // SENDING UNIT ID
        $encryptedId = Crypt::encryptString($id);
        $this->redirect(route('listKPILaporan.index', ['role' => $this->role, 'unit' => $encryptedId]), navigate:true);
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
