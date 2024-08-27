<?php

namespace App\Livewire\UMR;

use App\Models\HistoryPengembalian;
use App\Models\KonteksRisiko;
use App\Models\KPI;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class LogKonteksRisiko extends Component
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

    // VARIABLES PENGEMBALIAN
    public $historyPengembalians, $historyPengembalian_tgl, $historyPengembalian_alasan, $historyPengembalian_isRiskRegister, $historyPengembalian_isRiskControl; 

    // VARIABLES URI
    public $role, $encryptedRole, $encryptedKPI, $unit, $unit_id, $encryptedUnit;


    // CONTRUCTOR COMPONENT
    public function mount()
    {
        // RECEIVE ROLE USER
        $this->role = Crypt::decryptString(request()->query('role'));

        // $unit       = Crypt::decryptString(request()->query('unit'));
        
        // RECEIVE UNIT ID
        // $this->unit_id = $unit;
        
        // RETRIVE ROLE UNIT
        $this->encryptedUnit = Crypt::encryptString($this->unit);

        // RETRIVE ROLE USER
        $this->encryptedRole = Crypt::encryptString($this->role);
        
        // RECEIVE KPI ID
        $encryptedId        = request()->query('kpi');
        $this->encryptedKPI = $encryptedId;
        $this->kpi_id       = Crypt::decryptString($encryptedId);

        // RECEIVE UNIT ID
        $this->unit_id = $this->unit;
        
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
        $konteksRisikos = KonteksRisiko::with(['kpi'])->where('kpi_id', $this->kpi_id)->search($this->search)
            ->where('konteks_isSendUMR', true)
            // ->whereHas('kpi', function ($query) {
            //     $query->where('kpi_sendUMRStatus', true); // Adjust the condition as needed
            // })
            ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')
            ->paginate($this->perPage);
        
        return view('livewire.pages.umr.validasi-kpi.risk-register-umr.konteks-risiko.konteks-risiko-ow', [
            'konteksRisikos'    => $konteksRisikos,
            'paginationInfo'    => $konteksRisikos->total() > 0
            ? "Showing " . ($konteksRisikos->firstItem()) . " to " . ($konteksRisikos->lastItem()) . " of " . ($konteksRisikos->total()) . " entries"
            : "No entries found",
        ]);
    }


    // SHOW KONTEKS
    public function showKonteks($id)
    {
        // OPEN MODAL
        $this->openModal();

        // IS SHOW
        $this->isShow = true;

        // IS EDIT
        $this->isEdit = false;

        // FIND KONTEKS
        $konteks = KonteksRisiko::find($id);
        
        // PASSING KPI
        $this->konteks_id       = $konteks->konteks_id;
        $this->konteks_desc     = $konteks->konteks_desc;
        $this->konteks_kategori = $konteks->konteks_kategori;
        
    }

    // REDIRECT TO IDENTIFIKASI RISIKO
    public function identifikasiRisiko($id)
    {
        // SENDING KPI
        $encryptedKpiId     = Crypt::encryptString($this->kpi_id);

        // SENDING KONTEKS ID
        $encryptedKonteks   = Crypt::encryptString($id);

        $encryptedUnit      = Crypt::encryptString($this->unit_id);

        $this->redirect(route('logRiskRegister.index', ['role' => $this->encryptedRole, 'konteks' => $encryptedKonteks, 'kpi' => $encryptedKpiId, 'unit' => $encryptedUnit]), navigate:true);
    }

    // KEMBALIKAN KONTEKS
    // Update all lock statuses to false
    public function kembalikanKonteks()
    {
        // Find KonteksRisiko based on konteks_id and eager load all related models
        $konteksRisiko = KonteksRisiko::with([
                'risk.dampak', 
                'risk.kemungkinan', 
                'risk.deteksiKegagalan', 
                'risk.efektifitasKontrol', 
                'risk.controlRisk.perlakuanRisiko'
            ])
            ->where('konteks_id', $this->konteks_id)
            ->first();

        if (!$konteksRisiko) {
            // send notification error
            flash()
                ->option('position', 'bottom-right')
                ->option('timeout', 3000)
                ->error('KonteksRisiko not found!');
            return;
        }

        // Proceed with return to UMR
        // Update sendUMRStatus as necessary
        $kpi = KPI::find($this->kpi_id);
        $kpi->update(['kpi_sendUMRStatus' => 0]);

        // Store Alasan Pengembalian
        $historyPengembalian = HistoryPengembalian::create([
            'konteks_id'                            => $this->konteks_id,
            'historyPengembalian_alasan'            => $this->historyPengembalian_alasan,
            'historyPengembalian_tgl'               => Carbon::now(),
            'historyPengembalian_isRiskRegister'    => true,
            'historyPengembalian_isRiskControl'     => false,
            'created_by'                            => Auth::user()->user_id,
        ]);

        // Update KonteksRisiko lock status
        $konteksRisiko->update(['konteks_lockStatus' => false, 'konteks_isSendUMR' => false]);

        foreach ($konteksRisiko->risk as $risk) {
            // Check if risk_validateRiskRegister is false
            if (!$risk->risk_validateRiskRegister || !$risk->risk_validateRiskControl) {
                // Update Risk lock statuses
                $risk->update([
                    'risk_lockStatus'         => false,
                    'risk_kriteriaLockStatus' => false,
                    'risk_allPhaseLockStatus' => false,
                    'risk_isSendUMR'          => false,
                ]);

                // Update Dampak lock statuses
                foreach ($risk->dampak as $dampak) {
                    $dampak->update(['dampak_lockStatus' => false]);
                }

                // Update Kemungkinan lock statuses
                foreach ($risk->kemungkinan as $kemungkinan) {
                    $kemungkinan->update(['kemungkinan_lockStatus' => false]);
                }

                // Update DeteksiKegagalan lock statuses
                foreach ($risk->deteksiKegagalan as $deteksiKegagalan) {
                    $deteksiKegagalan->update(['deteksiKegagalan_lockStatus' => false]);
                }

                // Update EfektifitasKontrol lock statuses
                foreach ($risk->efektifitasKontrol as $efektifitasKontrol) {
                    $efektifitasKontrol->update(['efektifitasKontrol_lockStatus' => false]);
                }

                // Update ControlRisk lock statuses
                foreach ($risk->controlRisk as $controlRisk) {
                    $controlRisk->update(['controlRisk_lockStatus' => false]);

                    // Update PerlakuanRisiko lock statuses
                    foreach ($controlRisk->perlakuanRisiko as $perlakuanRisiko) {
                        $perlakuanRisiko->update(['perlakuanRisiko_lockStatus' => false]);
                    }
                }
            }
        }

        // CLOSE MODAL
        $this->closeModalConfirmDelete();

        // Reset Form
        $this->resetForm();

        // send notification success
        flash()
            ->option('position', 'bottom-right')
            ->option('timeout', 3000)
            ->success('Semua status penguncian yang berlaku telah disetel untuk perubahan berdasarkan konteks ditentukan!');
    }


    // VERIFIKASI KONTEKS
    public function verifikasiKonteks()
    {
        // Find KonteksRisiko based on konteks_id and eager load all related models
        $konteksRisiko = KonteksRisiko::with([
                'risk.dampak', 
                'risk.kemungkinan', 
                'risk.deteksiKegagalan', 
                'risk.efektifitasKontrol', 
                'risk.controlRisk.perlakuanRisiko'
            ])
            ->where('konteks_id', $this->konteks_id)
            ->first();

        if (!$konteksRisiko) {
            // send notification error
            flash()
                ->option('position', 'bottom-right')
                ->option('timeout', 3000)
                ->error('KonteksRisiko not found!');
            return;
        }

        // Proceed with return to UMR
        // Update sendUMRStatus as necessary
        $kpi = KPI::find($this->kpi_id);
        $kpi->update(['kpi_sendUMRStatus' => 0]);

        // Update KonteksRisiko lock status
        $konteksRisiko->update(['konteks_isSendUMR' => false]);

        // CHECK IF KONTEKS IS RISK REGISTER
        $isRiskRegister = false;

        foreach ($konteksRisiko->risk as $risk) {

            // dump($risk);
            // Check if risk_validateRiskRegister is false
            if (!$risk->risk_validateRiskRegister || !$risk->risk_validateRiskControl) {
                // dump('check');
                // CHECK IF RISK REGISTER
                if(!$risk->risk_validateRiskRegister){
                    // dump('register');
                    // Update Risk lock statuses
                    $risk->update([
                        'risk_isSendUMR'                    => false,
                        'risk_validateRiskRegister'         => true,
                    ]);
                }elseif($risk->risk_validateRiskRegister){
                    // dump('control');
                    // Update Risk lock statuses
                    $risk->update([
                        'risk_isSendUMR'                    => false,
                        'risk_validateRiskControl'         => true,
                    ]);
                }
            }
        }

        // CLOSE MODAL
        $this->closeModalConfirm();

        // Reset Form
        $this->resetForm();

        // send notification success
        flash()
            ->option('position', 'bottom-right')
            ->option('timeout', 3000)
            ->success('Konteks telah berhasil divalidasi!');
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
        $this->konteks_id                   = '';
        $this->konteks_kategori             = '';
        $this->konteks_desc                 = '';
        $this->historyPengembalian_alasan   = '';
    }

    // CLEAR SEARCH
    public function clearSearch()
    {
        $this->search = '';
    }
}
