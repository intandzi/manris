<?php

namespace App\Livewire\UMR;

use App\Models\Unit;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class ManajemenUnit extends Component
{
    use WithPagination;

    // TITLE COMPONENT
    #[Title('Manajemen Unit')]
    public $title = 'Manajemen Unit';

    // GLOBAL VARIABLES
    public $perPage     = 5;
    public $search      = '';

    // VARIABLE GLOBAL CREATE UNIT
    public $unit_id, $unit_name;

    public function render()
    {
        $units = Unit::search($this->search)
            ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')
            ->paginate($this->perPage);
        
        return view('livewire.pages.umr.manajemen-unit.manajemen-unit', [
            'units'             => $units,
            'paginationInfo'    => $units->total() > 0
            ? "Showing " . ($units->firstItem()) . " to " . ($units->lastItem()) . " of " . ($units->total()) . " entries"
            : "No entries found",
        ]);
    }

    // SANITIZE INPUTS STORE
    protected function sanitizeInputsStore()
    {
        $this->unit_name           = filter_var($this->unit_name, FILTER_SANITIZE_STRING,  FILTER_FLAG_ALLOW_FRACTION);
    }

    // VALIDATING INPUTS
    protected function validatingInputs()
    {
        $validated = $this->validate([
            'unit_name'              => ['required', 'string', 'max:255'],
        ],[
            'unit_name.required'     => 'Nama Unit Wajib di Isi!',
        ]);
    }

    // STORE NEW DATA UNIT
    public function storeUnit()
    {
        // SANITIZE INPUTS
        $this->sanitizeInputsStore();

        // VALIDATING DATA
        $this->validatingInputs();

        $unit = Unit::updateOrCreate([
            'unit_id'       => $this->unit_id,
        ],
        [
            'unit_name'    => $this->unit_name,
            'created_by'   => Auth::user()->user_id,
            'updated_by'   => Auth::user()->user_id,
        ]);

        // close modal
        $this->closeModal();

        // send notification success
        flash()
            ->option('position', 'bottom-right')
            ->option('timeout', 3000)
            ->success('Data Anda telah disimpan!');
    }

    // UPDATE DATA UNIT
    public function editUnit($id)
    {
        // OPEN MODAL
        $this->isOpen = true;

        $this->resetForm();
        $this->resetValidation(); // This will clear any validation errors

        $unit   = Unit::find($id);

        // ASSIGN DATA TO VARIABLES
        $this->unit_id          = $unit->unit_id;
        $this->unit_name        = $unit->unit_name;// Assign the user's roles to selectedRoles
    }


    // SORTING DATA UNIT
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

    // ACTIVE OR NON-ACITVE DATA UNIT
    public bool $isActive = false;
    public function toggleActive($unitId)
    {
        // Find the unit by unit_id
        $unit = Unit::find($unitId);
        
        // Toggle the status between 0 and 1 (assuming 0 represents inactive and 1 represents active)
        $unit->update(['unit_activeStatus' => !$unit->unit_activeStatus, 'updated_by'   => Auth::user()->user_id]);

        flash()
            ->option('position', 'bottom-right')
            ->option('timeout', 3000)
            ->success('Data Anda telah disimpan!');
    }

    // LIFE CYCLE HOOKS
    // OPEN MODAL LIVEWIRE 
    public $isOpen = 0;
    public function create()
    {
        $this->openModal();
    }

    // OPEN MODAL
    public function openModal()
    {
        $this->resetForm();
        $this->resetValidation(); // This will clear any validation errors

        $this->isOpen = true;
    }

    // CLOSE MODAL
    public function closeModal()
    {
        $this->isOpen = false;

        // Reset form fields and close the modal
        $this->resetForm();

        // Reset form validation
        $this->resetValidation();
    } 

    // CLOSE MODAL
    public function closeXModal()
    {
        $this->isOpen = false;

        // Reset form fields and close the modal
        $this->resetForm();

        // Reset form validation
        $this->resetValidation();
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

    // RESET FORM
    public function resetForm()
    {
        $this->unit_id      = '';
        $this->unit_name    = '';
    }

    // CLEAR SEARCH
    public function clearSearch()
    {
        $this->search = '';
    }
}
