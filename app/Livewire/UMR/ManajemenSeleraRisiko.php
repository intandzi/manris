<?php

namespace App\Livewire\UMR;

use App\Models\DerajatRisiko;
use App\Models\SeleraRisiko;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class ManajemenSeleraRisiko extends Component
{
    use WithPagination;

    // TITLE COMPONENT
    #[Title('Manajemen Selera Risiko')]

    public $title = 'Manajemen Selera Risiko';

    // GLOBAL VARIABLES
    public $perPage     = 5;
    public $search      = '';

    // VARIABLE GLOBAL CREATE SELERA RISIKO
    public $seleraRisiko, $riwayatSeleraRisiko, $seleraRisiko_id, $seleraRisiko_desc, $seleraRisiko_tindakLanjut; 
    // VARIABLE DERAJAT RISIKO  
    public $derajatRisk, $derajatRisikos, $derajatRisiko_id, $derajatRisiko_desc, $derajatRisiko_nilaiTingkatMin, $derajatRisiko_nilaiTingkatMax;

    // VARIABLE EDIT
    public $isEdit = 0, $isShow = 0 ;

    // CONSTRUCTOR COMPONENT
    public function mount()
    {
        $this->derajatRisikos = DerajatRisiko::where('derajatRisiko_activeStatus', true)->get();
    }

    // RENDER COMPONENT
    public function render()
    {
        $derajatRisiko = DerajatRisiko::with(['seleraRisiko'])
                        // ->groupBy('derajatRisiko_id')
                        ->search($this->search) // Apply the search filter
                        ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc') // Order by the specified column and direction
                        ->paginate($this->perPage); // Paginate the results

        // PASSING RIWAYAT SELERA RISIKO
        $riwayatSelera = SeleraRisiko::where('derajatRisiko_id', $this->derajatRisiko_id)
                            ->orderBy('seleraRisiko_id', $this->orderAsc ? 'desc' : 'asc')
                            ->paginate($this->perPage);

        
        
        return view('livewire.pages.umr.manajemen-selera-risiko.manajemen-selera-risiko', [
            'derajatRisikos'        => $derajatRisiko,
            'paginationInfo'        => $derajatRisiko->total() > 0
            ? "Showing " . ($derajatRisiko->firstItem()) . " to " . ($derajatRisiko->lastItem()) . " of " . ($derajatRisiko->total()) . " entries"
            : "No entries found",
            'riwayatSelera'         => $riwayatSelera,
            'paginationInfoRiwayat' => $riwayatSelera->total() > 0
            ? "Showing " . ($riwayatSelera->firstItem()) . " to " . ($riwayatSelera->lastItem()) . " of " . ($riwayatSelera->total()) . " entries"
            : "No entries found",
        ]);
    }

    // SANITIZE INPUTS STORE
    protected function sanitizeInputsStore()
    {
        $this->seleraRisiko_desc   = filter_var($this->seleraRisiko_desc, FILTER_SANITIZE_STRING,  FILTER_FLAG_ALLOW_FRACTION);
        $this->seleraRisiko_tindakLanjut   = filter_var($this->seleraRisiko_tindakLanjut, FILTER_SANITIZE_STRING,  FILTER_FLAG_ALLOW_FRACTION);
    }

    // VALIDATING INPUTS
    protected function validatingInputs()
    {
        $validated = $this->validate([
            'seleraRisiko_desc'             => ['required', 'string'],
            'seleraRisiko_tindakLanjut'     => ['required', 'string'],
            'derajatRisiko_id'              => ['required', 'integer'],
        ],[
            'seleraRisiko_desc.required'            => 'Selera Risiko Deskripsi Wajib di Isi!',
            'seleraRisiko_tindakLanjut.required'    => 'Selera Risiko Tindak Lanjut Wajib di Isi!',
            'derajatRisiko_id.required'             => 'Derajat Risiko Wajib di Isi!',
        ]);
    }

    // STORE NEW DATA SELERA RISIKO
    public function storeSeleraRisiko()
    {
        // SANITIZE INPUTS
        $this->sanitizeInputsStore();

        // VALIDATING DATA
        $this->validatingInputs();

        $seleraRisiko = SeleraRisiko::create([
            'derajatRisiko_id'            => $this->derajatRisiko_id,
            'seleraRisiko_desc'           => $this->seleraRisiko_desc,
            'seleraRisiko_tindakLanjut'   => $this->seleraRisiko_tindakLanjut,
            'seleraRisiko_activeStatus'   => 0,
            'created_by'                  => Auth::user()->user_id,
            'updated_by'                  => Auth::user()->user_id,
        ]);

        // close modal
        $this->closeModal();

        // send notification success
        flash()
            ->option('position', 'bottom-right')
            ->option('timeout', 3000)
            ->success('Data Anda telah disimpan!');
    }

    // UPDATE DATA SELERA RISIKO
    public function editSeleraRisiko($id)
    {
        // OPEN MODAL
        $this->isOpen = true;

        // EDIT
        $this->isEdit = true;

        // IS SHOW
        $this->isShow = false;

        $this->resetForm();
        $this->resetValidation(); // This will clear any validation errors

        // SELECTED DERAJAT RISIKO
        $derajatRisiko = DerajatRisiko::with(['seleraRisiko'])
                                        ->find($id);

        // FIND SELERA RISIKO ACTIVE
        foreach($derajatRisiko->seleraRisiko as $item){
            // IF ACTIVE PASSING TO VARIABLE MODEL
            if($item->seleraRisiko_activeStatus){
                // ASSIGN DATA TO VARIABLES
                $this->seleraRisiko_id              = $item->seleraRisiko_id ?? '-';
                $this->seleraRisiko_desc            = $item->seleraRisiko_desc ?? '-';
                $this->seleraRisiko_tindakLanjut    = $item->seleraRisiko_tindakLanjut ?? '-';
            }
        }

        // PASSING DERAJAT RISIKO
        $this->derajatRisiko_id             = $id;
        $this->derajatRisk                  = $derajatRisiko;
    }

    // UPDATE DATA SELERA RISIKO
    public function showSeleraRisiko($id)
    {
        // OPEN MODAL
        $this->isOpen = true;
        
        // EDIT
        $this->isEdit = false;

        // IS SHOW
        $this->isShow = true;

        $this->resetForm();
        $this->resetValidation(); // This will clear any validation errors

        // SELECTED DERAJAT RISIKO
        $derajatRisiko = DerajatRisiko::with(['seleraRisiko'])
                                        ->find($id);

        // FIND SELERA RISIKO ACTIVE
        foreach($derajatRisiko->seleraRisiko as $item){
            // IF ACTIVE PASSING TO VARIABLE MODEL
            if($item->seleraRisiko_activeStatus){
                // ASSIGN DATA TO VARIABLES
                $this->seleraRisiko_id              = $item->seleraRisiko_id ?? '-';
                $this->seleraRisiko_desc            = $item->seleraRisiko_desc ?? '-';
                $this->seleraRisiko_tindakLanjut    = $item->seleraRisiko_tindakLanjut ?? '-';
            }
        }

        // PASSING DERAJAT RISIKO
        $this->derajatRisiko_id             = $id;
        $this->derajatRisk                  = $derajatRisiko;
    }

    // SORTING DATA SELERA RISIKO
    public $orderBy     = 'derajatRisiko_id';
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

    public function toggleActive($seleraRisiko_id, $derajatRisiko_id)
    {
        // Reset error messages
        $this->resetErrorBag('alreadyActive');


        // CHECK FIRST IS IT ACTIVE
        // Find the seleraRisiko by seleraRisiko_id
        $seleraRisiko = SeleraRisiko::find($seleraRisiko_id);

        if($seleraRisiko->seleraRisiko_activeStatus){
            // UPDATE STATUS SELERA RISIKO
            $this->updateStatusSeleraRisiko($seleraRisiko_id);
        }else{

            $active = false;
            // SEARCH SELERA RISIKO BASED ON DERAJAT RISIKO
            $allSeleraRisikoData = SeleraRisiko::where('derajatRisiko_id', $derajatRisiko_id)->get();

            // CHECK IF ALREADY HAVE THAT ACTIVE
            foreach($allSeleraRisikoData as $item) {
                if($item->seleraRisiko_activeStatus) {
                    $this->addError('alreadyActive', 'Non-aktifkan terlebih dahulu selera risiko yang lain!');
                    $active = true;
                    return;
                }
            }

            if(!$active){
                // UPDATE STATUS SELERA RISIKO
                $this->updateStatusSeleraRisiko($seleraRisiko_id);
            }
        }
    }

    // UPDATE STATUS SELERA RISIKO
    public function updateStatusSeleraRisiko($seleraRisiko_id)
    {
        // Find the seleraRisiko by seleraRisiko_id
        $seleraRisiko = SeleraRisiko::find($seleraRisiko_id);
        
        // Toggle the status between 0 and 1 (assuming 0 represents inactive and 1 represents active)
        $seleraRisiko->update([
            'seleraRisiko_activeStatus' => !$seleraRisiko->seleraRisiko_activeStatus,
            'updated_by' => Auth::user()->user_id
        ]);

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
        $this->seleraRisiko_id              = '';
        $this->seleraRisiko_desc            = '';
        $this->seleraRisiko_tindakLanjut    = '';
        $this->derajatRisiko_id             = '';
    }

    // CLEAR SEARCH
    public function clearSearch()
    {
        $this->search = ''; 
    }
}
