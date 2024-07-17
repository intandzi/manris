<?php

namespace App\Livewire\Lembaga;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class Stakeholders extends Component
{

    use WithPagination;

    // TITLE COMPONENT
    #[Title('Manajemen Pemangku Kepentingan')]
    public $title = 'Manajemen Pemangku Kepentingan';

    // GLOBAL VARIABLES
    public $roles       = [];
    public $units       = [];
    public $perPage     = 5;
    public $search      = '';

    // VARIABLE GLOBAL CREATE STAKEHOLDER
    public $stakeholder_id, $stakeholder_jabatan, $stakeholder_singkatan;

    // RENDER COMPONENT
    public function render()
    {
        $stakeholders = \App\Models\Stakeholders::search($this->search)
            ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')
            ->paginate($this->perPage);
        
        return view('livewire.pages.lembaga.stakeholders.stakeholders', [
            'stakeholders'             => $stakeholders,
            'paginationInfo'    => $stakeholders->total() > 0
            ? "Showing " . ($stakeholders->firstItem()) . " to " . ($stakeholders->lastItem()) . " of " . ($stakeholders->total()) . " entries"
            : "No entries found",
        ]);
    }

    // SANITIZE INPUTS STORE
    protected function sanitizeInputsStore()
    {
        $this->stakeholder_jabatan           = filter_var($this->stakeholder_jabatan, FILTER_SANITIZE_STRING,  FILTER_FLAG_ALLOW_FRACTION);
        $this->stakeholder_singkatan         = filter_var($this->stakeholder_singkatan, FILTER_SANITIZE_STRING,  FILTER_FLAG_ALLOW_FRACTION);
    }

    // VALIDATING INPUTS
    protected function validatingInputs()
    {
        $validated = $this->validate([
            'stakeholder_jabatan'   => ['required', 'string', 'max:255'],
            'stakeholder_singkatan' => ['required', 'string', 'max:5'],
        ],[
            'stakeholder_jabatan.required'      => 'Jabatan Pemangku Kepentingan Wajib di Isi!',
            'stakeholder_singkatan.required'    => 'Singkatan Pemangku Kepentingan Wajib di Isi!',
            'stakeholder_singkatan.max'         => 'Singkatan Pemangku Kepentingan Maksimal 5 digits!',
        ]);
    }

    // STORE NEW DATA STAKEHOLDER
    public function storeStakeholder()
    {
        // SANITIZE INPUTS
        $this->sanitizeInputsStore();

        // VALIDATING DATA
        $this->validatingInputs();

        $stakeholder = \App\Models\Stakeholders::updateOrCreate([
            'stakeholder_id'       => $this->stakeholder_id,
        ],
        [
            'stakeholder_jabatan'   => $this->stakeholder_jabatan,
            'stakeholder_singkatan' => $this->stakeholder_singkatan,
            'created_by'            => Auth::user()->user_id,
            'updated_by'            => Auth::user()->user_id,
        ]);

        // close modal
        $this->closeModal();

        // send notification success
        flash()
            ->option('position', 'bottom-right')
            ->option('timeout', 3000)
            ->success('Data Anda telah disimpan!');
    }

    // UPDATE DATA STAKEHOLDER
    public function editStakeholder($id)
    {
        // OPEN MODAL
        $this->isOpen = true;

        $this->resetForm();
        $this->resetValidation(); // This will clear any validation errors

        $stakeholder   = \App\Models\Stakeholders::find($id);

        // ASSIGN DATA TO VARIABLES
        $this->stakeholder_id           = $stakeholder->stakeholder_id;
        $this->stakeholder_jabatan      = $stakeholder->stakeholder_jabatan;
        $this->stakeholder_singkatan    = $stakeholder->stakeholder_singkatan;
    }

    // SORTING DATA STAKEHOLDER
    public $orderBy     = 'stakeholder_id';
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

    // ACTIVE OR NON-ACITVE DATA STAKEHOLDER
    public bool $isActive = false;
    public function toggleActive($stakeholderId)
    {
        // Find the user by user_id
        $stakeholder = \App\Models\Stakeholders::find($stakeholderId);
        
        // Toggle the status between 0 and 1 (assuming 0 represents inactive and 1 represents active)
        $stakeholder->update(['stakeholder_activeStatus' => !$stakeholder->stakeholder_activeStatus, 'updated_by' => Auth::user()->user_id]);

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
        $this->stakeholder_id           = '';
        $this->stakeholder_jabatan      = '';
        $this->stakeholder_singkatan    = '';
    }

    // CLEAR SEARCH
    public function clearSearch()
    {
        $this->search = '';
    }
}
