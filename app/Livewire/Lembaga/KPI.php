<?php

namespace App\Livewire\Lembaga;

use App\Models\Unit;
use Illuminate\Support\Facades\Crypt;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class KPI extends Component
{
    use WithPagination;

    // TITLE COMPONENT
    #[Title('Daftar Unit')]
    public $title = 'Daftar Unit';

    // GLOBAL VARIABLES
    public $perPage     = 5;
    public $search      = '';

    // VARIABLE GLOBAL CREATE UNIT
    public $unit_id, $unit_name, $visimisi_id, $visimisi_visi, $visimisi_misi, $created_at;
    public $isShow = false;

    public $role;

    // CONSTRUCTOR COMPONENT
    public function mount()
    {
        // RETRIVE ROLE USER
        $role = Crypt::decryptString(request()->query('role'));
        $this->role = Crypt::encryptString($role);
    }

    public function render()
    {
        $units = Unit::with(['visimisi'])->search($this->search)
            ->where('unit_activeStatus', true)
            ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')
            ->paginate($this->perPage);
        
        return view('livewire.pages.lembaga.kpi.kpi', [
            'units'             => $units,
            'paginationInfo'    => $units->total() > 0
            ? "Showing " . ($units->firstItem()) . " to " . ($units->lastItem()) . " of " . ($units->total()) . " entries"
            : "No entries found",
        ]);
    }

    // REDIRECT TO LIST KPI FOR EACH UNIT
    public function listKPIUnit($id)
    {
        // SENDING UNIT ID
        $encryptedId = Crypt::encryptString($id);
        $this->redirect(route('listKPIUnit.index', ['role' => $this->role, 'unit' => $encryptedId]), navigate:true);
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
