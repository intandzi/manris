<?php

namespace App\Livewire\RiskOwner;

use App\Models\KonteksRisiko;
use App\Models\KPI;
use App\Models\Unit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class KonteksRisikoOw extends Component
{
    use WithPagination;

    // TITLE COMPONENT
    #[Title('Konteks Risiko')]
    public $title;

    // GLOBAL VARIABLES
    public $perPage     = 5;
    public $search      = '';
    public $searchPeriod = '';

    public $years = [];

    // VARIABLES LIST KPI MODEL
    public $kpi_id, $kpi, $kpi_kode, $kpi_nama, $unit_nama, $user_pemilik;

    // VARIABLES MODEL KONTEKS
    public $konteks_id, $konteks_kategori, $konteks_desc;

    public $role, $encryptedRole;

    // CONTRUCTOR COMPONENT
    public function mount()
    {
        // RECEIVE ROLE USER
        $this->role = Crypt::decryptString(request()->query('role'));

        // RETRIVE ROLE USER
        $this->encryptedRole = Crypt::encryptString($this->role);

        // RECEIVE KPI ID
        $encryptedId = request()->query('kpi');
        $this->kpi_id = Crypt::decryptString($encryptedId);
        
        // FIND UNIT SPECIFIC
        $this->kpi = KPI::with(['unit.user'])->where('kpi_id', $this->kpi_id)->first();

        // SET NAMA DAN KODE KPI
        $this->kpi_nama = $this->kpi->kpi_nama;
        $this->kpi_kode = $this->kpi->kpi_kode;
        $this->title    = $this->kpi->kpi_kode;

        // SET UNIT KPI AND PEMILIK KPI
        $this->unit_nama = $this->kpi->unit->unit_name;

        // Usage
        $roleToFind         = "risk owner";
        $usersWithRole      = $this->searchUsersWithRole($this->kpi->unit->user, $roleToFind);
        $this->user_pemilik = ucwords($usersWithRole[0]->name);
    }

    // Function to search for users with a specific role
    protected function searchUsersWithRole($users, $roleToFind) {
        $result = [];

        foreach ($users as $user) {
            $roles = json_decode($user['role'], true);
            if (in_array($roleToFind, $roles)) {
                $result[] = $user;
            }
        }

        return $result;
    }

    // RENDER COMPONENT
    public function render()
    {
        $konteksRisikos = KonteksRisiko::where('kpi_id', $this->kpi_id)->search($this->search)
            ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')
            ->paginate($this->perPage);
        
        return view('livewire.pages.risk-owner.risk-register.konteks-risiko.konteks-risiko-ow', [
            'konteksRisikos'    => $konteksRisikos,
            'paginationInfo'    => $konteksRisikos->total() > 0
            ? "Showing " . ($konteksRisikos->firstItem()) . " to " . ($konteksRisikos->lastItem()) . " of " . ($konteksRisikos->total()) . " entries"
            : "No entries found",
        ]);
    }

    // SANITIZE INPUTS STORE
    protected function sanitizeInputsStore()
    {
        $this->konteks_desc             = filter_var($this->konteks_desc, FILTER_SANITIZE_STRING,  FILTER_FLAG_ALLOW_FRACTION);
    }

    // VALIDATING INPUTS
    protected function validatingInputs()
    {
        $validated = $this->validate([
            'konteks_kategori'              => ['required'],
            'konteks_desc'                  => ['required'],
        ], [
            'konteks_kategori.required'     => 'Kategori Konteks wajib diisi!',
            'konteks_desc.required'         => 'Deskripsi wajib diisi!',
        ]);
    }

    // STORE NEW DATA KONTEKS
    public function storeKonteks()
    {    
        // SANITIZE INPUTS
        $this->sanitizeInputsStore();

        // VALIDATING DATA
        $this->validatingInputs();
        
        // Check if is Edit
        if (!$this->isEdit) {
            // Generate the KPI code
            $konteksKode = KonteksRisiko::generateKonteksCode($this->kpi_id);
        } else {
            $konteks            = KonteksRisiko::find($this->konteks_id);
            $konteksKode        = $konteks->konteks_kode;
        }
        
        $konteks = KonteksRisiko::updateOrCreate([
            'konteks_id'            => $this->konteks_id,
        ],[
            'kpi_id'                    => $this->kpi_id,
            'konteks_kode'              => $konteksKode ?? $this->konteks_kode,
            'konteks_kategori'          => $this->konteks_kategori,
            'konteks_desc'              => $this->konteks_desc,
            'created_by'                => Auth::user()->user_id,
            'updated_by'                => Auth::user()->user_id,
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

    // EDIT KONTEKS
    public function editKonteks($id)
    {
        // OPEN MODAL
        $this->openModal();

        // IS SHOW
        $this->isShow = false;

        // IS EDIT
        $this->isEdit = true;

        // FIND KONTEKS
        $konteks = KonteksRisiko::find($id);
        
        // PASSING KPI
        $this->konteks_id       = $konteks->konteks_id;
        $this->konteks_desc     = $konteks->konteks_desc;
        $this->konteks_kategori = $konteks->konteks_kategori;
        
    }

    // LOCK KONTEKS
    public function lockKonteks()
    {
        // FIND KONTEKS
        $konteks = KonteksRisiko::find($this->konteks_id);
        $konteks->update(['konteks_lockStatus' => true]);

        // close modal
        $this->closeModalConfirm();

        // send notification success
        flash()
            ->option('position', 'bottom-right')
            ->option('timeout', 3000)
            ->success('Data Anda telah disimpan!');
    }

    // DELETE KONTEKS
    public function deleteKonteks()
    {
        // FIND KONTEKS
        $konteks = KonteksRisiko::find($this->konteks_id);
        $konteks->delete();

        // close modal
        $this->closeModalConfirmDelete();

        // send notification success
        flash()
            ->option('position', 'bottom-right')
            ->option('timeout', 3000)
            ->success('Data Anda telah dihapus!');
    }

    // REDIRECT TO IDENTIFIKASI RISIKO
    public function identifikasiRisiko($id)
    {
        // SENDING KPI
        $encryptedKpiId     = Crypt::encryptString($this->kpi_id);

        // SENDING KONTEKS ID
        $encryptedKonteks   = Crypt::encryptString($id);

        $this->redirect(route('identifikasiRisiko.index', ['role' => $this->encryptedRole, 'konteks' => $encryptedKonteks, 'kpi' => $encryptedKpiId]), navigate:true);
    }

    // SORTING DATA KONTEKS RISIKO
    public $orderBy     = 'konteks_id';
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
    public $isOpenConfirm = 0;
    public $isOpenConfirmDelete = 0;

    public $isEdit, $isShow;
    public function create()
    {
        // IS SHOW
        $this->isShow = false;

        // IS EDIT
        $this->isEdit = false;

        $this->openModal();
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

        // IS SHOW
        $this->isShow = false;

        // IS EDIT
        $this->isEdit = false;

        // Reset form fields and close the modal
        $this->resetForm();

        // Reset form validation
        $this->resetValidation();
    } 

    // CLOSE MODAL
    public function closeXModal()
    {
        $this->isOpen = false;

        // IS SHOW
        $this->isShow = false;

        // IS EDIT
        $this->isEdit = false;

        // Reset form fields and close the modal
        $this->resetForm();

        // Reset form validation
        $this->resetValidation();
    } 

    // OPEN MODAL CONFIRM
    public function openModalConfirm($id)
    {
        // SET KPI
        $this->konteks_id       = $id;

        $this->isOpenConfirm    = true;
    }

    // CLOSE MODAL CONFIRM
    public function closeModalConfirm()
    {
        $this->isOpenConfirm = false;
    } 
  
    // CLOSE MODAL CONFIRM
    public function closeXModalConfirm()
    {
        $this->isOpenConfirm = false;
    } 
    // OPEN MODAL CONFIRM DELETE
    public function openModalConfirmDelete($id)
    {
        $this->closeModal();

        // SET KPI
        $this->konteks_id = $id;

        $this->isOpenConfirmDelete = true;
    }

    // CLOSE MODAL CONFIRM DELETE
    public function closeModalConfirmDelete()
    {
        $this->isOpenConfirmDelete = false;
    } 

    // CLOSE MODAL CONFIRM DELETE
    public function closeXModalConfirmDelete()
    {
        $this->isOpenConfirmDelete = false;
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
        $this->konteks_id               = '';
        $this->konteks_kategori         = '';
        $this->konteks_desc             = '';
    }

    // CLEAR SEARCH
    public function clearSearch()
    {
        $this->search = '';
    }
}
