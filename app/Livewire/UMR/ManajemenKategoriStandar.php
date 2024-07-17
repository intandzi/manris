<?php

namespace App\Livewire\UMR;

use App\Models\KategoriStandar;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class ManajemenKategoriStandar extends Component
{
    use WithPagination;

    // TITLE COMPONENT
    #[Title('Manajemen Kategori Standar')]

    public $title = 'Manajemen Kategori Standar';

    // GLOBAL VARIABLES
    public $perPage     = 5;
    public $search      = '';

    // VARIABLE GLOBAL CREATE KATEGORI STANDAR
    public $kategoriStandar_id, $kategoriStandar_desc;

    public function render()
    {
        $kategoriStandars = KategoriStandar::search($this->search)
            ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')
            ->paginate($this->perPage);
        
        return view('livewire.pages.umr.manajemen-kategori-standar.manajemen-kategori-standar', [
            'kategoriStandars'  => $kategoriStandars,
            'paginationInfo'    => $kategoriStandars->total() > 0
            ? "Showing " . ($kategoriStandars->firstItem()) . " to " . ($kategoriStandars->lastItem()) . " of " . ($kategoriStandars->total()) . " entries"
            : "No entries found",
        ]);
    }

    // SANITIZE INPUTS STORE
    protected function sanitizeInputsStore()
    {
        $this->kategoriStandar_desc           = filter_var($this->kategoriStandar_desc, FILTER_SANITIZE_STRING,  FILTER_FLAG_ALLOW_FRACTION);
    }

    // VALIDATING INPUTS
    protected function validatingInputs()
    {
        $validated = $this->validate([
            'kategoriStandar_desc'              => ['required', 'string', 'max:255'],
        ],[
            'kategoriStandar_desc.required'     => 'Kategori Standar Deskripsi Wajib di Isi!',
        ]);
    }

    // STORE NEW DATA KATEGORI STANDAR
    public function storeKategoriStandar()
    {
        // SANITIZE INPUTS
        $this->sanitizeInputsStore();

        // VALIDATING DATA
        $this->validatingInputs();

        $kategoriStandar = KategoriStandar::updateOrCreate([
            'kategoriStandar_id'       => $this->kategoriStandar_id,
        ],
        [
            'kategoriStandar_desc'      => $this->kategoriStandar_desc,
            'created_by'                => Auth::user()->user_id,
            'updated_by'                => Auth::user()->user_id,
        ]);

        // close modal
        $this->closeModal();

        // send notification success
        flash()
            ->option('position', 'bottom-right')
            ->option('timeout', 3000)
            ->success('Data Anda telah disimpan!');
    }

    // UPDATE DATA KATEGORI STANDAR
    public function editKategoriStandar($id)
    {
        // OPEN MODAL
        $this->isOpen = true;

        $this->resetForm();
        $this->resetValidation(); // This will clear any validation errors

        $kategoriStandar   = KategoriStandar::find($id);

        // ASSIGN DATA TO VARIABLES
        $this->kategoriStandar_id   = $kategoriStandar->kategoriStandar_id;
        $this->kategoriStandar_desc = $kategoriStandar->kategoriStandar_desc;
    }


    // SORTING DATA KATEGORI STANDAR
    public $orderBy     = 'kategoriStandar_id';
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
    public function toggleActive($kategoriStandar_id)
    {
        // Find the kategoriStandar by kategoriStandar_id
        $kategoriStandar = KategoriStandar::find($kategoriStandar_id);
        
        // Toggle the status between 0 and 1 (assuming 0 represents inactive and 1 represents active)
        $kategoriStandar->update(['kategoriStandar_activeStatus' => !$kategoriStandar->kategoriStandar_activeStatus, 'updated_by'   => Auth::user()->user_id]);

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
        $this->kategoriStandar_id       = '';
        $this->kategoriStandar_desc     = '';
    }

    // CLEAR SEARCH
    public function clearSearch()
    {
        $this->search = ''; 
    }
}
