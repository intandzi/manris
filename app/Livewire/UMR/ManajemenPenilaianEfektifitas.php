<?php

namespace App\Livewire\UMR;

use App\Models\PenilaianEfektifitas;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class ManajemenPenilaianEfektifitas extends Component
{
    use WithPagination;

    // TITLE COMPONENT
    #[Title('Manajemen Assesment Efektifitas Kontrol Risiko')]

    public $title = 'Manajemen Assesment Efektifitas Kontrol Risiko';

    // GLOBAL VARIABLES
    public $perPage     = 5;
    public $search      = '';

    // VARIABLE GLOBAL CREATE PENILAIAN EFEKTIFITAS
    public $penilaianEfektifitas_id, $penilaianEfektifitas_pertanyaan, $penilaianEfektifitas_ya, $penilaianEfektifitas_sebagian, $penilaianEfektifitas_tidak;

    // VARIABLE EDIT
    public $isEdit = 0, $isShow = 0;

    // RENDER COMPONENT
    public function render()
    {
        $penilaianEfektifitas = PenilaianEfektifitas::search($this->search)
            ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')
            ->paginate($this->perPage);
        
        return view('livewire.pages.umr.manajemen-penilaian-efektifitas.manajemen-penilaian-efektifitas', [
            'penilaianEfektifitas'  => $penilaianEfektifitas,
            'paginationInfo'        => $penilaianEfektifitas->total() > 0
            ? "Showing " . ($penilaianEfektifitas->firstItem()) . " to " . ($penilaianEfektifitas->lastItem()) . " of " . ($penilaianEfektifitas->total()) . " entries"
            : "No entries found",
        ]);
    }

    // SANITIZE INPUTS STORE
    protected function sanitizeInputsStore()
    {
        $this->penilaianEfektifitas_pertanyaan   = filter_var($this->penilaianEfektifitas_pertanyaan, FILTER_SANITIZE_STRING,  FILTER_FLAG_ALLOW_FRACTION);
        $this->penilaianEfektifitas_ya           = filter_var($this->penilaianEfektifitas_ya, FILTER_SANITIZE_NUMBER_INT,  FILTER_FLAG_ALLOW_FRACTION);
        $this->penilaianEfektifitas_sebagian     = filter_var($this->penilaianEfektifitas_sebagian, FILTER_SANITIZE_NUMBER_INT,  FILTER_FLAG_ALLOW_FRACTION);
        $this->penilaianEfektifitas_tidak        = filter_var($this->penilaianEfektifitas_tidak, FILTER_SANITIZE_NUMBER_INT,  FILTER_FLAG_ALLOW_FRACTION);
    }

    // VALIDATING INPUTS
    protected function validatingInputs()
    {
        $validated = $this->validate([
            'penilaianEfektifitas_pertanyaan'      => ['required', 'string'],
            'penilaianEfektifitas_ya'              => ['required', 'string'],
            'penilaianEfektifitas_sebagian'        => ['required', 'string'],
            'penilaianEfektifitas_tidak'           => ['required', 'string'],
        ],[
            'penilaianEfektifitas_pertanyaan.required'  => 'Pertanyaan Wajib di Isi!',
            'penilaianEfektifitas_ya.required'          => 'Skor Jawaban Ya Wajib di Isi!',
            'penilaianEfektifitas_sebagian.required'    => 'Skor Jawaban Sebagian Wajib di Isi!',
            'penilaianEfektifitas_tidak.required'       => 'Skor Jawaban Tidak Wajib di Isi!',
        ]);
    }

    // STORE NEW DATA PENILAIAN EFEKTIFITAS
    public function storePenilaianEfektifitas()
    {
        // SANITIZE INPUTS
        $this->sanitizeInputsStore();

        // VALIDATING DATA
        $this->validatingInputs();

        $penilaianEfektifitas = PenilaianEfektifitas::updateOrCreate([
            'penilaianEfektifitas_id'   => $this->penilaianEfektifitas_id,
        ],
        [
            'penilaianEfektifitas_pertanyaan'   => $this->penilaianEfektifitas_pertanyaan,
            'penilaianEfektifitas_ya'           => $this->penilaianEfektifitas_ya,
            'penilaianEfektifitas_sebagian'     => $this->penilaianEfektifitas_sebagian,
            'penilaianEfektifitas_tidak'        => $this->penilaianEfektifitas_tidak,
            'created_by'                        => Auth::user()->user_id,
            'updated_by'                        => Auth::user()->user_id,
        ]);

        // close modal
        $this->closeModal();

        // send notification success
        flash()
            ->option('position', 'bottom-right')
            ->option('timeout', 3000)
            ->success('Data Anda telah disimpan!');
    }

    // UPDATE DATA PENILAIAN EFEKTIFITAS
    public function editPenilaianEfektifitas($id)
    {
        // OPEN MODAL
        $this->isOpen = true;

        // IS EDIT
        $this->isEdit = true;

        // IS SHOW
        $this->isShow = false;

        $this->resetForm();
        $this->resetValidation(); // This will clear any validation errors

        $penilaianEfektifitas   = PenilaianEfektifitas::find($id);

        // ASSIGN DATA TO VARIABLES
        $this->penilaianEfektifitas_id              = $penilaianEfektifitas->penilaianEfektifitas_id;
        $this->penilaianEfektifitas_pertanyaan      = $penilaianEfektifitas->penilaianEfektifitas_pertanyaan;
        $this->penilaianEfektifitas_ya              = $penilaianEfektifitas->penilaianEfektifitas_ya;
        $this->penilaianEfektifitas_sebagian        = $penilaianEfektifitas->penilaianEfektifitas_sebagian;
        $this->penilaianEfektifitas_tidak           = $penilaianEfektifitas->penilaianEfektifitas_tidak;
    }

    // UPDATE DATA PENILAIAN EFEKTIFITAS
    public function showPenilaianEfektifitas($id)
    {
        // OPEN MODAL
        $this->isOpen = true;

        // IS EDIT
        $this->isEdit = false;

        // IS SHOW
        $this->isShow = true;

        $this->resetForm();
        $this->resetValidation(); // This will clear any validation errors

        $penilaianEfektifitas   = PenilaianEfektifitas::find($id);

        // ASSIGN DATA TO VARIABLES
        $this->penilaianEfektifitas_id              = $penilaianEfektifitas->penilaianEfektifitas_id;
        $this->penilaianEfektifitas_pertanyaan      = $penilaianEfektifitas->penilaianEfektifitas_pertanyaan;
        $this->penilaianEfektifitas_ya              = $penilaianEfektifitas->penilaianEfektifitas_ya;
        $this->penilaianEfektifitas_sebagian        = $penilaianEfektifitas->penilaianEfektifitas_sebagian;
        $this->penilaianEfektifitas_tidak           = $penilaianEfektifitas->penilaianEfektifitas_tidak;
    }

    // SORTING DATA PENILAIAN EFEKTIFITAS
    public $orderBy     = 'penilaianEfektifitas_id';
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
    public function toggleActive($id)
    {
        // Find the penilaianEfektifitas by penilaianEfektifitas_id
        $penilaianEfektifitas = PenilaianEfektifitas::find($id);
        
        // Toggle the status between 0 and 1 (assuming 0 represents inactive and 1 represents active)
        $penilaianEfektifitas->update([
            'penilaianEfektifitas_activeStatus' => !$penilaianEfektifitas->penilaianEfektifitas_activeStatus, 
            'updated_by'   => Auth::user()->user_id]);

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
        $this->penilaianEfektifitas_id          = '';
        $this->penilaianEfektifitas_pertanyaan  = '';
        $this->penilaianEfektifitas_ya          = '';
        $this->penilaianEfektifitas_sebagian    = '';
        $this->penilaianEfektifitas_tidak       = '';
        // IS EDIT
        $this->isEdit = false;
        // IS SHOW
        $this->isShow = false;
    }

    // CLEAR SEARCH
    public function clearSearch()
    {
        $this->search = ''; 
    }
}
