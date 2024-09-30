<?php

namespace App\Livewire\RiskOwner\RiskRegister;

use App\Models\KategoriStandar;
use App\Models\KonteksRisiko;
use App\Models\KPI;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Livewire\WithPagination;

class ListKPIRiskOwnerRiskRegister extends Component
{
    use WithPagination, WithFileUploads;

    // TITLE COMPONENT
    #[Title('KPI Unit')]
    public $title;

    // GLOBAL VARIABLES
    public $perPage     = 5;
    public $search      = '';
    public $searchPeriod = '';

    public $years = [], $periodYears = [];

    // VARIABLES LIST UNIT MODEL
    public $unit_id, $unit, $kategoriStandar;

    // VARIABLES KPI MODEL
    public $kpi_id, $kpi_kode, $kpi_nama, $kpi_tanggalMulai, $kpi_tanggalAkhir, $kpi_periode, $kategoriStandar_id, $kpi_kategoriKinerja, $kpi_indikatorKinerja, $kpi_dokumenPendukung, $dokumen;

    public $isShow = false;

    public $isEdit = false;

    public $role, $encryptedRole;
    // CONTRUCTOR COMPONENT
    public function mount()
    {
        // RECEIVE ROLE USER
        $this->role = Crypt::decryptString(request()->query('role'));

        // RETRIVE ROLE USER
        $this->encryptedRole = Crypt::encryptString($this->role);

        // RECEIVE UNIT ID
        $this->unit_id = Auth::user()->unit_id;
        
        // FIND UNIT SPECIFIC
        $this->unit = Unit::with(['kpi.kategoriStandar', 'visimisi'])->where('unit_id', $this->unit_id)->first();

        // SET TITLE
        $this->title = $this->unit->unit_name;

        // Get the current year
        $currentYear = Carbon::now()->year;

        // Generate years for the select options
        for ($i = $currentYear - 4; $i <= $currentYear + 4; $i++) {
            $this->years[$i] = $i;
        }

        // Generate period years for the select options
        for ($i = $currentYear - 20; $i <= $currentYear + 10; $i++) {
            $this->periodYears[$i] = $i;
        }
    }

    // COMPONENT RENDER
    public function render()
    {
        $kpis = KPI::with(['unit', 'kategoriStandar', 'konteks.risk'])->where('unit_id', $this->unit_id)
            ->where('kpi_lockStatus', true)    
            ->where('kpi_activeStatus', true)    
            ->search($this->search)
            ->when($this->searchPeriod, function($query){
                $query->where('kpi_periode', 'like', '%' . $this->searchPeriod . '%');
            })
            ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')
            ->paginate($this->perPage);
        
        return view('livewire.pages.risk-owner.risk-register.list-kpi.list-kpi-risk-owner-RR', [
            'kpis'             => $kpis,
            'paginationInfo'    => $kpis->total() > 0
            ? "Showing " . ($kpis->firstItem()) . " to " . ($kpis->lastItem()) . " of " . ($kpis->total()) . " entries"
            : "No entries found",
        ]);
    }

    
    // REDIRECT TO DETAIL KPI FOR KONTEKS RISIKO
    public function konteksRisiko($id)
    {
        // SENDING KPI ID
        $encryptedId    = Crypt::encryptString($id);
        // SENDING ROLE USER
        $encryptedRole  = Crypt::encryptString($this->role);

        $this->redirect(route('registerKonteksRisikoOw.index', ['role' => $encryptedRole, 'kpi' => $encryptedId]), navigate: true);
    }


    // SORTING DATA KPI
    public $orderBy     = 'kpi_id';
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

    // SEND TO UMR
    public function sendToUMR()
    {
        // FIND KONTEKS BASED ON KPI
        $konteks = KonteksRisiko::with(['risk'])->where('kpi_id', $this->kpi_id)->get();

        // count konteks risiko
        $countKonteks = $konteks->count();

        // count konteks risiko with risk
        $konteksWithRiskCount = 0;
        foreach ($konteks as $konteksRisiko) {
            if ($konteksRisiko->risk->isNotEmpty()) {
                $konteksWithRiskCount++;
            }
        }

        // Check konteks to risk
        if ($countKonteks !== $konteksWithRiskCount) {
            // send notification success
            flash()
                ->option('position', 'bottom-right')
                ->option('timeout', 3000)
                ->error('Silahkan selesaikan pengisian semua konteks risiko!');

            // CLOSE MODAL
            $this->closeModalConfirmSendUMR();
            return;
        }

        foreach ($konteks as $konteksRisiko) {
            if (!$this->checkKonteksLockStatus($konteksRisiko)) {
                // send notification success
                flash()
                    ->option('position', 'bottom-right')
                    ->option('timeout', 3000)
                    ->error('Risk Register belum selesai, harap selesaikan terlebih dahulu!');

                // CLOSE MODAL
                $this->closeModalConfirmSendUMR();
                return;
            }
        }
        
        // Proceed with sending to UMR
        // Update sendUMRStatus as necessary
        $kpi = KPI::find($this->kpi_id);
        $kpi->update(['kpi_sendUMRStatus' => 1]);

        // UPDATE SEND UMR IN RISK
        // FIND RISK
        if($konteks){
            // UPDATE SEND UMR IN RISK
            foreach ($konteks as $konteksRisiko) {
                $konteksRisiko->update(['konteks_isSendUMR' => 1]);
                foreach ($konteksRisiko->risk as $risk) {
                    $risk->update(['risk_isSendUMR' => 1]);
                }
            }
        }

        // send notification success
        flash()
            ->option('position', 'bottom-right')
            ->option('timeout', 3000)
            ->success('Data Anda telah di kirim ke UMR!');
        // CLOSE MODAL
        $this->closeModalConfirmSendUMR();
    }

    // CHECK KONTEKS LOCK STATUS
    private function checkKonteksLockStatus($konteksRisiko)
    {
        // check lock status konteks risiko
        if (!$konteksRisiko->konteks_lockStatus) {
            return false;
        }

        // check all lock status in risk
        foreach ($konteksRisiko->risk as $risk) {
            if (!$this->checkRiskLockStatus($risk)) {
                return false;
            }
        }

        return true;
    }

    // CHECK RISK LOCK STATUS
    private function checkRiskLockStatus($risk)
    {
        // CHECK RISK LOCK STATUS
        if (
            !$risk->risk_lockStatus ||
            !$risk->risk_kriteriaLockStatus ||
            !$risk->risk_allPhaseLockStatus
        ) {
            return false;
        }

        // CHECK KRITERIA DAMPAK LOCK STATUS
        foreach ($risk->dampak as $dampak) {
            if (!$dampak->dampak_lockStatus) {
                return false;
            }
        }

        // CHECK KRITERIA KEMUNGKINAN LOCK STATUS
        foreach ($risk->kemungkinan as $kemungkinan) {
            if (!$kemungkinan->kemungkinan_lockStatus) {
                return false;
            }
        }

        // CHECK KRITERIA DETEKSI KEGAGALAN LOCK STATUS
        foreach ($risk->deteksiKegagalan as $deteksiKegagalan) {
            if (!$deteksiKegagalan->deteksiKegagalan_lockStatus) {
                return false;
            }
        }

        // CHECK CONTROL RISK LOCK STATUS
        foreach ($risk->controlRisk as $controlRisk) {
            if (!$this->checkControlRiskLockStatus($controlRisk)) {
                return false;
            }
        }

        return true;
    }

    // CHECK CONTROL RISK LOCK STATUS
    private function checkControlRiskLockStatus($controlRisk)
    {
        // CONTROL RISK LOCK STATUS
        if (!$controlRisk->controlRisk_lockStatus) {
            return false;
        }

        // CHECK PERLAKUAN RISIKO LOCK STATUS
        foreach ($controlRisk->perlakuanRisiko as $perlakuanRisiko) {
            if (!$perlakuanRisiko->perlakuanRisiko_lockStatus) {
                return false;
            }
        }

        return true;
    }


    // LIFE CYCLE HOOKS IDENTIFIKASI RISIKO
    // OPEN MODAL LIVEWIRE 
    public $isSendUMR = 0;

    // OPEN MODAL CONFIRM IDENTIFIKASI RISIKO
    public function openModalConfirmSendUMR($id)
    {
        $this->isSendUMR    = true;

        // PASSING KPI ID
        $this->kpi_id = $id;
    }

    // CLOSE MODAL CONFIRM IDENTIFIKASI RISIKO
    public function closeModalConfirmSendUMR()
    {
        $this->isSendUMR = false;
    } 
  
    // CLOSE MODAL CONFIRM IDENTIFIKASI RISIKO
    public function closeXModalConfirmSendUMR()
    {
        $this->isSendUMR = false;
    } 

    // HISTORY PENGEMBALIAN
    public $isOpenHistoryPengembalian = 0;

    public $historyPengembalian;

    // OPEN HISTORY PENGEMBALIAN
    public function openHistoryPengembalian($kpi_id)
    {
        $this->isOpenHistoryPengembalian = 1;

        $kpi = KPI::with(['konteks.historyPengembalian'])
            ->where('unit_id', $this->unit_id)
            ->where('kpi_lockStatus', true)    
            ->where('kpi_activeStatus', true)
            ->find($kpi_id);

        $this->historyPengembalian = $kpi;
    }

    // CLOSE HISTORY PENGEMBALIAN
    public function closeHistoryPengembalian()
    {
        $this->isOpenHistoryPengembalian = 0;
    }
    // CLOSE HISTORY PENGEMBALIAN
    public function closeXHistoryPengembalian()
    {
        $this->isOpenHistoryPengembalian = 0;
    }

    // CLEAR SEARCH
    public function clearSearch()
    {
        $this->search = '';
    }
}
