<?php

namespace App\Livewire\RiskOfficer\RiskControl;

use App\Models\ControlRisk;
use App\Models\DerajatRisiko;
use App\Models\DetailEfektifitasKontrol;
use App\Models\EfektifitasKontrol;
use App\Models\JenisPerlakuan;
use App\Models\KonteksRisiko;
use App\Models\KPI;
use App\Models\KriteriaDampak;
use App\Models\KriteriaDeteksiKegagalan;
use App\Models\KriteriaKemungkinan;
use App\Models\PerlakuanRisiko;
use App\Models\PenilaianEfektifitas;
use App\Models\RencanaPerlakuan;
use App\Models\Risk;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class ListRiskOf extends Component
{
    use WithPagination;

    // TITLE COMPONENT
    #[Title('Daftar Kontrol Risiko')]
    public $title;

    // GLOBAL VARIABLES
    public $perPage     = 5;
    public $searchRisk  = '';
    public $searchPeriod = '';

    public $years = [];

    // VARIABLES LIST KPI MODEL
    public $kpi_id, $kpi, $kpi_kode, $kpi_nama, $unit_nama, $user_pemilik;

    // VARIABLES MODEL KONTEKS
    public $konteks, $konteks_id, $konteks_kode, $konteks_kategori, $konteks_desc;

    // VARIABLE KEY
    public $role, $encryptedRole, $encryptedKPI;

    // CONTRUCTOR COMPONENT
    public function mount()
    {
        // RECEIVE ROLE USER
        $this->role = Crypt::decryptString(request()->query('role'));
        
        // RETRIVE ROLE USER
        $this->encryptedRole = Crypt::encryptString($this->role);
        
        // RECEIVE KPI ID
        $encryptedKpi = request()->query('kpi');
        $this->kpi_id = Crypt::decryptString($encryptedKpi);

        // RETRIVE KPI ID
        $this->encryptedKPI = Crypt::encryptString($this->kpi_id);
        
        // FIND UNIT SPECIFIC
        $this->kpi = KPI::with(['unit.user'])->where('kpi_id', $this->kpi_id)->first();

        // SET NAMA DAN KODE KPI
        $this->kpi_nama = $this->kpi->kpi_nama;
        $this->kpi_kode = $this->kpi->kpi_kode;
        $this->title    = $this->kpi->kpi_kode;

        // SET UNIT KPI AND PEMILIK KPI
        $this->unit_nama = $this->kpi->unit->unit_name;

        // FIND USER PEMILIK
        $roleToFind         = "risk owner";
        $usersWithRole      = $this->searchUsersWithRole($this->kpi->unit->user, $roleToFind);
        $this->user_pemilik = ucwords($usersWithRole[0]->name);

        // RECEIVE KONTEKS ID
        $encryptedKonteks = request()->query('konteks');
        $this->konteks_id = Crypt::decryptString($encryptedKonteks);
        
        // FIND UNIT SPECIFIC
        $this->konteks = KonteksRisiko::where('konteks_id', $this->konteks_id)->first();

        // SET KONTEKS KODE AND KONTEKS DESC
        $this->konteks_kode = $this->konteks->konteks_kode;
        $this->konteks_desc = $this->konteks->konteks_desc;
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
        // RISK DATA
        $risks = Risk::with(['dampak', 'kemungkinan', 'deteksiKegagalan', 'controlRisk.perlakuanRisiko'])
            ->where('risk_validateRiskRegister', 1)
            ->where('konteks_id', $this->konteks_id)->search($this->searchRisk)
            ->orderBy($this->orderByRisk, $this->orderAscRisk ? 'asc' : 'desc')
            ->paginate($this->perPage);


        return view('livewire.pages.risk-owner.risk-control.list-risk-control.list-risk-control-content', [
            'risks'                 => $risks,
            'paginationInfoRisks'   => $risks->total() > 0
            ? "Showing " . ($risks->firstItem()) . " to " . ($risks->lastItem()) . " of " . ($risks->total()) . " entries"
            : "No entries found",
        ]);
    }

    // REDIRECT TO RISK CONTROL
    public function riskControl($id)
    {
        // SENDING KPI
        $encryptedKpiId     = Crypt::encryptString($this->kpi_id);

        // SENDING KONTEKS ID
        $encryptedKonteks   = Crypt::encryptString($this->konteks_id);
        
        // SENDING RISK ID
        $encryptedRiskId    = Crypt::encryptString($id);

        $this->redirect(route('officerRiskControlStep.index', ['role' => $this->encryptedRole, 'konteks' => $encryptedKonteks, 'kpi' => $encryptedKpiId, 'risk' => $encryptedRiskId]), navigate:true);
    }

    
    /**
     * RISKS FUNCTIONS
     *
     */

    // VARIABLES IDENTIFIKASI RISIKO
    public $risk_id, $risk_riskDesc, $risk_penyebab;

    // SHOW IDENTIFIKASI RISIKO
    public function showRisk($id)
    {
        // OPEN MODAL
        $this->openModalRisk();

        // IS SHOW
        $this->isShowRisk = true;

        // IS EDIT
        $this->isEditRisk = false;

        // FIND risk
        $risk = Risk::find($id);
        
        // PASSING KPI
        $this->risk_id          = $risk->risk_id;
        $this->risk_riskDesc    = $risk->risk_riskDesc;
        $this->risk_penyebab    = $risk->risk_penyebab;
        
    }

    // SORTING DATA IDENTIFIKASI RISIKO
    public $orderByRisk     = 'risk_id';
    public $orderAscRisk    = true;
    public function doSortRisk($column)
    {
        if ($this->orderByRisk === $column) {
            $this->orderAscRisk = !$this->orderAscRisk; // Toggle the sorting order
        } else {
            $this->orderByRisk = $column;
            $this->orderAscRisk = true; // Default sorting order when changing the column
        }
    }
 
    // LIFE CYCLE HOOKS IDENTIFIKASI RISIKO
    // OPEN MODAL LIVEWIRE 
    public $isOpenRisk = 0;
    public $isOpenConfirmRisk = 0;
    public $isOpenConfirmDeleteRisk = 0;

    public $isEditRisk, $isShowRisk;

    // CREATE IDENTIFIKASI RISIKO
    public function createRisk()
    {
        // IS SHOW
        $this->isShowRisk = false;

        // IS EDIT
        $this->isEditRisk = false;

        $this->openModalRisk();
    }

    // OPEN MODAL IDENTIFIKASI RISIKO
    public function openModalRisk()
    {
        $this->isOpenRisk = true;

        $this->resetFormRisk();
    }

    // CLOSE MODAL IDENTIFIKASI RISIKO
    public function closeModalRisk()
    {
        $this->isOpenRisk = false;

        // IS SHOW
        $this->isShowRisk = false;

        // IS EDIT
        $this->isEditRisk = false;

        // Reset form fields and close the modal
        $this->resetFormRisk();

        // Reset form validation
        $this->resetValidation();
    } 

    // CLOSE MODAL IDENTIFIKASI RISIKO
    public function closeXModalRisk()
    {
        $this->isOpenRisk = false;

        // IS SHOW
        $this->isShowRisk = false;

        // IS EDIT
        $this->isEditRisk = false;

        // Reset form fields and close the modal
        $this->resetFormRisk();

        // Reset form validation
        $this->resetValidation();
    } 

    // OPEN MODAL CONFIRM IDENTIFIKASI RISIKO
    public function openModalConfirmRisk($id)
    {
        // SET KPI
        $this->risk_id       = $id;

        $this->isOpenConfirmRisk    = true;
    }

    // CLOSE MODAL CONFIRM IDENTIFIKASI RISIKO
    public function closeModalConfirmRisk()
    {
        $this->isOpenConfirmRisk = false;

        $this->resetFormRisk();
    } 
  
    // CLOSE MODAL CONFIRM IDENTIFIKASI RISIKO
    public function closeXModalConfirmRisk()
    {
        $this->isOpenConfirmRisk = false;

        $this->resetFormRisk();
    } 

    // OPEN MODAL CONFIRM DELETE IDENTIFIKASI RISIKO
    public function openModalConfirmDeleteRisk($id)
    {
        $this->closeModalRisk();

        // SET KPI
        $this->risk_id = $id;

        $this->isOpenConfirmDeleteRisk = true;
    }

    // CLOSE MODAL CONFIRM DELETE IDENTIFIKASI RISIKO
    public function closeModalConfirmDeleteRisk()
    {
        $this->isOpenConfirmDeleteRisk = false;
    } 

    // CLOSE MODAL CONFIRM DELETE IDENTIFIKASI RISIKO
    public function closeXModalConfirmDeleteRisk()
    {
        $this->isOpenConfirmDeleteRisk = false;
    } 

    // RESET FORM IDENTIFIKASI RISIKO
    public function resetFormRisk()
    {
        $this->risk_id               = '';
        $this->risk_riskDesc         = '';
        $this->risk_penyebab         = '';
        $this->isEditRisk            = false;
    }

    // CLEAR SEARCH IDENTIFIKASI RISIKO
    public function clearSearchRisk()
    {
        $this->searchRisk = '';
    }

    // SET TOGGLE STATUS RTM
    public $isActiveRTM = 'No RTM';

    public function toggleActiveRTM($id)
    {
        // Find the ControlRisk by ID
        $controlRisk = ControlRisk::find($id);

        // Toggle the status between 'RTM' and 'No RTM'
        $newStatus = ($controlRisk->controlRisk_RTM == 'RTM') ? 'No RTM' : 'RTM';
        $controlRisk->update([
            'controlRisk_RTM' => $newStatus,
            'updated_by' => Auth::user()->user_id,
        ]);

        // Update the local state to reflect the change
        $this->isActiveRTM = $newStatus;

        // Flash a success message
        flash()
            ->option('position', 'bottom-right')
            ->option('timeout', 3000)
            ->success('Status has been updated!');
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
}
