<?php

namespace App\Livewire\Lembaga;

use App\Models\Unit;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class VisiMisi extends Component
{
    use WithPagination;

    // TITLE COMPONENT
    #[Title('Visi Misi')]
    public $title = 'Visi Misi';

    // GLOBAL VARIABLES
    public $perPage     = 5;
    public $search      = '';

    // VARIABLE GLOBAL CREATE UNIT
    public $unit_id, $unit_name, $visimisi_id, $visimisi_visi, $visimisi_misi, $created_at;

    public $contentVisi, $contentMisi;
    public $isShow = false;

    // Mount method to set initial values
    public function mount()
    {
        $this->contentMisi = $this->visimisi_misi;
        $this->contentVisi = $this->visimisi_visi;
    }

    // Update contentMisi when visimisi_misi changes
    public function updatedVisiMisi_Misi()
    {
        $this->contentMisi = $this->visimisi_misi;
    }
    
    // Update contentVisi when visimisi_visi changes
    public function updatedVisiMisi_Visi()
    {
        $this->contentVisi = $this->visimisi_visi;
    }

    public function render()
    {
        $units = Unit::with(['visimisi'])->search($this->search)
            ->where('unit_activeStatus', true)
            ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')
            ->paginate($this->perPage);
        
        return view('livewire.pages.lembaga.visi-misi.visi-misi', [
            'units'             => $units,
            'paginationInfo'    => $units->total() > 0
            ? "Showing " . ($units->firstItem()) . " to " . ($units->lastItem()) . " of " . ($units->total()) . " entries"
            : "No entries found",
        ]);
    }

    // SANITIZE INPUTS STORE
    // protected function sanitizeInputsStore()
    // {
    //     $this->visimisi_visi           = filter_var($this->visimisi_visi, FILTER_SANITIZE_STRING,  FILTER_FLAG_ALLOW_FRACTION);
    //     $this->visimisi_misi           = filter_var($this->visimisi_misi, FILTER_SANITIZE_STRING,  FILTER_FLAG_ALLOW_FRACTION);
    // }

    // VALIDATING INPUTS
    protected function validatingInputs()
    {
        $validated = $this->validate([
            'visimisi_visi'              => ['required', 'string'],
            'visimisi_misi'              => ['required', 'string'],
        ],[
            'visimisi_visi.required'     => 'Visi Unit Wajib di Isi!',
            'visimisi_misi.required'     => 'Misi Unit Wajib di Isi!',
        ]);
    }

    // STORE NEW DATA VISI MISI
    public function storeVisiMisi()
    {
        // SANITIZE INPUTS
        // $this->sanitizeInputsStore();

        // VALIDATING DATA
        $this->validatingInputs();

        $visimisi = \App\Models\VisiMisi::create([
            'unit_id'           => $this->unit_id,
            'visimisi_visi'     => $this->visimisi_visi,
            'visimisi_misi'     => $this->visimisi_misi,
            'created_by'        => Auth::user()->user_id,
            'updated_by'        => Auth::user()->user_id,
        ]);

        // close modal
        $this->isOpen = false;

        // Reset form fields and close the modal
        $this->resetForm();

        // send notification success
        flash()
            ->option('position', 'bottom-right')
            ->option('timeout', 3000)
            ->success('Data Anda telah disimpan!');
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

    // LIFE CYCLE HOOKS
    // OPEN MODAL LIVEWIRE 
    public $isOpen = 0;

    // CREATE NEW VISI MISI
    public function create($id)
    {
        // OPEN MODAL
        $this->openModal();

        $this->isShow = false;

        // FIND UNIT
        $unit = Unit::find($id);

        $this->unit_id      = $unit->unit_id;
        $this->unit_name    = $unit->unit_name;
    }

    // SHOW VISI MISI
    public function show($id)
    {
        // OPEN MODAL
        $this->openModal();
        
        $this->isShow = true;

        $visimisi = \App\Models\VisiMisi::where('unit_id', $id)->latest()->first();

        $this->unit_name        = $visimisi->unit->unit_name;
        $this->visimisi_visi    = $visimisi->visimisi_visi;
        $this->visimisi_misi    = $visimisi->visimisi_misi;
        $this->created_at       = $visimisi->created_at;

    }

    // OPEN MODAL
    public function openModal()
    {

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
        $this->visimisi_id   = '';
        $this->visimisi_visi = '';
        $this->visimisi_misi = '';
        $this->created_at    = '';
    }

    // CLEAR SEARCH
    public function clearSearch()
    {
        $this->search = '';
    }
}
