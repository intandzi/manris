<?php

namespace App\Livewire\RiskOwner\RiskRegister;

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

class RiskRegisterOw extends Component
{
    use WithPagination;

    // TITLE COMPONENT
    #[Title('Identifikasi Risiko')]
    public $title;
    public $title2 = 'Identifikasi Risiko';
    public $titleDesc = 'Tahap ini  Anda diminta melakukan identifikasi  peristiwa risiko dan penyebab risiko dengan  memperhatikan KPI dan konteks yang telah dipilih';

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

    // VARIABLE TOOGLE TABS
    public $tabActive                   = 'identifikasiContent';
    public $showIdentifikasiContent     = true;
    public $showKriteriaContent         = false;
    public $showAnalisisContent         = false;
    public $showEvaluasiContent         = false;
    public $showRencanaPerlakuanContent = false;

    // TOGGLE TAB
    public function toggleTab($tab)
    {
        // DYNAMIC TAB
        $this->tabActive                    = $tab;
        $this->showIdentifikasiContent      = $tab === 'identifikasiContent';
        $this->showKriteriaContent          = $tab === 'kriteriaContent';
        $this->showAnalisisContent          = $tab === 'analisisContent';
        $this->showEvaluasiContent          = $tab === 'evaluasiContent';
        $this->showRencanaPerlakuanContent  = $tab === 'rencanaPerlakuanContent';

        // DYNAMIC TITLE
        if($tab === 'kriteriaContent'){
            $this->title2       = 'Kriteria Risiko';
            $this->titleDesc    = 'Pada tahap ini Anda diminta  untuk membuat kriteria risiko  dengan cara melakukan pemeringkatan dampak, kemungkinan risiko, dan deteksi kegagalan dengan memperhatikan parameter pengukuran dalam Key Performance Indicator atau target';
            
        }elseif($tab === 'analisisContent'){
            $this->title2 = 'Analisis Risiko';
            $this->titleDesc    = 'Pada tahap ini Anda diminta  untuk melakukan evaluasi apakah kendali dilaksanankan dengan efektif atau bahkan tidak ada kendali sama sekali yang dilakukan terhadap risiko yang telah diidentifikasi. Melakukan penentuan dampak dan kemungkinan berdasarkan kriteria risiko yang telah dibuat pada tahap sebelumnya.';
        }elseif($tab === 'evaluasiContent'){
            $this->title2 = 'Evaluasi Risiko';
            $this->titleDesc    = 'Pada tahap ini Anda mendapatkan hasil <span style="color:red;">PRIORITAS RISIKO</span> dan tindak lanjut yang sudah ditetapkan sesuai dengan perhitungan pada tahap analisis risiko.';
        }elseif($tab === 'rencanaPerlakuanContent'){
            $this->title2 = 'Rencana Perlakuan Risiko';
            $this->titleDesc    = 'Pada tahap ini Anda diminta  untuk membuat rencana perlakuan risiko pada perhitungan sebelumnya, dan menentukan jenis perlakuan risiko ';
        }else{
            $this->title2 = 'Identifikasi Risiko';            
            $this->titleDesc    = 'Tahap ini  Anda diminta melakukan identifikasi  peristiwa risiko dan penyebab risiko dengan  memperhatikan KPI dan konteks yang telah dipilih';
        }
    }

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

        // KRITERIA DAMPAK
        $this->kemungkinan = array_fill(0, 10, [
            'kemungkinan_desc'  => '',
            'kemungkinan_id'    => null,
        ]);

        // KRITERIA DAMPAK
        $this->dampak = array_fill(0, 10, [
            'dampak_desc'  => '',
            'dampak_id'    => null,
        ]);

        // KRITERIA DETEKSI
        $this->deteksi = array_fill(0, 10, [
            'deteksi_desc'  => '',
            'deteksi_id'    => null,
        ]);

        // PASSING DATA JENIS PERLAKUAN
        $this->jenisPerlakuans  = JenisPerlakuan::where('jenisPerlakuan_activeStatus', true)->get();
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
        $risks = Risk::with(['dampak', 'kemungkinan', 'deteksiKegagalan'])
            ->where('konteks_id', $this->konteks_id)->search($this->searchRisk)
            ->orderBy($this->orderByRisk, $this->orderAscRisk ? 'asc' : 'desc')
            ->paginate($this->perPage);

        // KRITERIA RISIKO DATA
        $kriterias = Risk::with(['dampak', 'kemungkinan', 'deteksiKegagalan'])->where('konteks_id', $this->konteks_id)
            ->where('risk_lockStatus', true)
            ->search($this->searchRisk)
            ->orderBy($this->orderByRisk, $this->orderAscRisk ? 'asc' : 'desc')
            ->paginate($this->perPage);

        // ANALISIS RISIKO DATA    
        $dataAnalisis = Risk::with(['controlRisk.dampak', 'controlRisk.kemungkinan', 'controlRisk.deteksiKegagalan', 'efektifitasKontrol'])
        ->where('konteks_id', $this->konteks_id)
        ->where('risk_lockStatus', true)
        ->where('risk_kriteriaLockStatus', true)
        ->search($this->searchRisk)
        ->orderBy($this->orderByRisk, $this->orderAscRisk ? 'asc' : 'desc')
        ->paginate($this->perPage);

        // EVALUASI RISIKO DATA
        $evaluasis = Risk::whereHas('controlRisk', function ($query) {
            $query->where('controlRisk_lockStatus', true)->where('controlRisk_isControl', 0);
        })
        ->with([
            'controlRisk.derajatRisiko.seleraRisiko'
        ])
        ->where('konteks_id', $this->konteks_id)
        ->where('risk_lockStatus', true)
        ->where('risk_kriteriaLockStatus', true)
        ->search($this->searchRisk)
        ->orderByControlRiskRPN($this->orderAscRisk ? 'desc' : 'asc')
        ->paginate($this->perPage);


        // RENCANA PERLAKUAN RISIKO DATA    
        $rencanaPerlakuans = Risk::whereHas('controlRisk', function ($query) {
            $query->where('controlRisk_lockStatus', true)->where('controlRisk_isControl', 0);
        })
        ->with([
            'controlRisk.dampak',
            'controlRisk.kemungkinan', 
            'controlRisk.deteksiKegagalan',
            'controlRisk.perlakuanRisiko',
            'controlRisk.perlakuanRisiko.jenisPerlakuan',
        ])
        ->where('konteks_id', $this->konteks_id)
        ->where('risk_lockStatus', true)
        ->where('risk_kriteriaLockStatus', true)
        ->search($this->searchRisk)
        ->orderByControlRiskRPN($this->orderAscRisk ? 'desc' : 'asc')
        ->paginate($this->perPage);

        return view('livewire.pages.risk-owner.risk-register.risk-register.risk-register-ow', [
            'risks'                 => $risks,
            'paginationInfoRisks'   => $risks->total() > 0
            ? "Showing " . ($risks->firstItem()) . " to " . ($risks->lastItem()) . " of " . ($risks->total()) . " entries"
            : "No entries found",

            'kriterias'                 => $kriterias,
            'paginationInfoKriterias'   => $kriterias->total() > 0
            ? "Showing " . ($kriterias->firstItem()) . " to " . ($kriterias->lastItem()) . " of " . ($kriterias->total()) . " entries"
            : "No entries found",

            'dataAnalisis'               => $dataAnalisis,
            'paginationInfoDataAnalisis' => $dataAnalisis->total() > 0
            ? "Showing " . ($dataAnalisis->firstItem()) . " to " . ($dataAnalisis->lastItem()) . " of " . ($dataAnalisis->total()) . " entries"
            : "No entries found",
            'evaluasis'                 => $evaluasis,
            'paginationInfoEvaluasis'   => $evaluasis->total() > 0
            ? "Showing " . ($evaluasis->firstItem()) . " to " . ($evaluasis->lastItem()) . " of " . ($evaluasis->total()) . " entries"
            : "No entries found",
            'rencanaPerlakuans'                 => $rencanaPerlakuans,
            'paginationInfoRencanaPerlakuans'   => $rencanaPerlakuans->total() > 0
            ? "Showing " . ($rencanaPerlakuans->firstItem()) . " to " . ($rencanaPerlakuans->lastItem()) . " of " . ($rencanaPerlakuans->total()) . " entries"
            : "No entries found",
        ]);
    }

    
    /**
     * RISKS FUNCTIONS
     *
     */

    // VARIABLES IDENTIFIKASI RISIKO
    public $risk_id, $risk_riskDesc, $risk_penyebab;

    // SANITIZE INPUTS STORE RISKS
    protected function sanitizeInputsStoreRisk()
    {
        $this->risk_riskDesc             = filter_var($this->risk_riskDesc, FILTER_SANITIZE_STRING,  FILTER_FLAG_ALLOW_FRACTION);
        $this->risk_penyebab             = filter_var($this->risk_penyebab, FILTER_SANITIZE_STRING,  FILTER_FLAG_ALLOW_FRACTION);
    }

    // VALIDATING INPUTS IDENTIFIKASI RISIKO
    protected function validatingInputsRisk()
    {
        $validated = $this->validate([
            'risk_riskDesc'              => ['required'],
            'risk_penyebab'              => ['required'],
        ], [
            'risk_riskDesc.required'     => 'Risiko wajib diisi!',
            'risk_penyebab.required'     => 'Penyebab Risiko wajib diisi!',
        ]);
    }

    // STORE NEW DATA IDENTIFIKASI RISIKO
    public function storeRisk()
    {    
        // SANITIZE INPUTS
        $this->sanitizeInputsStoreRisk();

        // VALIDATING DATA
        $this->validatingInputsRisk();
        
        // Check if is Edit
        if (!$this->isEditRisk) {
            // Generate the risk and penyebab code
            $risikoKode     = Risk::generateRiskCode('R', $this->konteks_id);
            $penyebabKode   = Risk::generatePenyebabCode('P', $this->konteks_id);
        } else {
            $risk            = Risk::find($this->risk_id);

            $risikoKode      = $risk->risk_kode;
            $penyebabKode    = $risk->risk_penyebabKode;
        }
        
        $risk = Risk::updateOrCreate([
            'risk_id'            => $this->risk_id,
        ],[
            'konteks_id'                => $this->konteks_id,
            'risk_kode'                 => $risikoKode ?? $this->risk_kode,
            'risk_penyebabKode'         => $penyebabKode ?? $this->risk_penyebabKode,
            'risk_riskDesc'             => $this->risk_riskDesc,
            'risk_penyebab'             => $this->risk_penyebab,
            'created_by'                => Auth::user()->user_id,
            'updated_by'                => Auth::user()->user_id,
        ]);

        // close modal
        $this->closeModalRisk();

        // Reset form fields and close the modal
        $this->resetFormRisk();

        // send notification success
        flash()
            ->option('position', 'bottom-right')
            ->option('timeout', 3000)
            ->success('Data Anda telah disimpan!');
    }

    // EDIT IDENTIFIKASI RISIKO
    public function editRisk($id)
    {
        // OPEN MODAL
        $this->openModalRisk();

        // IS SHOW
        $this->isShowRisk = false;

        // IS EDIT
        $this->isEditRisk = true;

        // FIND risk
        $risk = Risk::find($id);
        
        // PASSING KPI
        $this->risk_id          = $risk->risk_id;
        $this->risk_riskDesc    = $risk->risk_riskDesc;
        $this->risk_penyebab    = $risk->risk_penyebab;
        
    }

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

    // LOCK IDENTIFIKASI RISIKO
    public function lockRisk()
    {
        // FIND RISK
        $risk = Risk::find($this->risk_id);
        $risk->update(['risk_lockStatus' => true]);

        // close modal
        $this->closeModalConfirmRisk();

        // send notification success
        flash()
            ->option('position', 'bottom-right')
            ->option('timeout', 3000)
            ->success('Data Anda telah disimpan!');
    }

    // DELETE IDENTIFIKASI RISIKO
    public function deleteRisk()
    {
        // FIND RISK
        $risk = Risk::find($this->risk_id);
        $risk->delete();

        // close modal
        $this->closeModalConfirmDeleteRisk();

        // send notification success
        flash()
            ->option('position', 'bottom-right')
            ->option('timeout', 3000)
            ->success('Data Anda telah dihapus!');
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


    /**
     * KRITERIA FUNCTIONS
     *
     */

    // VARIABLES KRITERIA
    public $risk_spesific;

    // LOCK KRITERIA
    public function lockKriteria()
    {
        // FIND AND UPDATE KEMUNGKINAN
        $kemungkinan = KriteriaKemungkinan::where('risk_id', $this->risk_id)->get();
        
        foreach ($kemungkinan as $item) {
            $item->update(['kemungkinan_lockStatus' => true]);
        }

        // FIND AND UPDATE DAMPAK
        $dampak = KriteriaDampak::where('risk_id', $this->risk_id)->get();
        foreach ($dampak as $item) {
            $item->update(['dampak_lockStatus' => true]);
        }

        // FIND AND UPDATE DETEKSI KEGAGALAN
        $deteksiKegagalan = KriteriaDeteksiKegagalan::where('risk_id', $this->risk_id)->get();
        foreach ($deteksiKegagalan as $item) {
            $item->update(['deteksiKegagalan_lockStatus' => true]);
        }

        // UPDATE RISK KRITERIA LOCK TO TRUE
        Risk::where('risk_id', $this->risk_id)->update(['risk_kriteriaLockStatus' => true]);

        // close modal
        $this->closeModalConfirmKriteria();

        // send notification success
        flash()
            ->option('position', 'bottom-right')
            ->option('timeout', 3000)
            ->success('Data Anda telah disimpan!');
    }

    // LIFE CYCLE HOOKS
    // OPEN MODAL LIVEWIRE 
    public $isOpenConfirmKriteria = 0;

    // OPEN MODAL CONFIRM
    public function openModalConfirmKriteria($id)
    {
        // SET KPI
        $this->risk_id       = $id;

        $this->isOpenConfirmKriteria    = true;
    }

    // CLOSE MODAL CONFIRM
    public function closeModalConfirmKriteria()
    {
        $this->isOpenConfirmKriteria = false;
    } 
  
    // CLOSE MODAL CONFIRM
    public function closeXModalConfirmKriteria()
    {
        $this->isOpenConfirmKriteria = false;
    } 

    // KEMUNGKINAN KRITERIA RISIKO
    public $kemungkinan;

    // SANITIZE INPUTS STORE KEMUNGKINAN KRITERIA
    protected function sanitizeInputsStoreKemungkinan()
    {
        $this->kemungkinan = array_map(function($item) {
            $item['kemungkinan_desc'] = filter_var($item['kemungkinan_desc'], FILTER_SANITIZE_STRING, FILTER_FLAG_ALLOW_FRACTION);
            return $item;
        }, $this->kemungkinan);
    }

    // VALIDATING INPUTS KEMUNGKINAN KRITERIA
    protected function validatingInputsKemungkinan()
    {
        $rules = [];
        $messages = [];

        // SET DATA TO 10 KEMUNGKINAN
        $data = range(0, 9); // Adjust range to match index (0 to 9 for 10 items)

        // Define validation rules and messages for each index
        foreach ($data as $index) {
            $rules["kemungkinan.$index.kemungkinan_desc"] = 'required';
            
            $messages["kemungkinan.$index.kemungkinan_desc.required"] = "Keterangan Kemungkinan skala ke-".($index + 1)." wajib diisi!";
        }

        $this->validate($rules, $messages);
    }

    // STORE NEW DATA KEMUNGKINAN KRITERIA
    public function storeKemungkinan()
    {   
        // SANITIZE INPUTS
        $this->sanitizeInputsStoreKemungkinan();

        // VALIDATING DATA
        $this->validatingInputsKemungkinan();
        
        foreach ($this->kemungkinan as $index => $item) {
            KriteriaKemungkinan::updateOrCreate([
                'kemungkinan_id'    => $item['kemungkinan_id'],
            ],[
                'risk_id'           => $this->risk_id,
                'kemungkinan_scale' => $index + 1,
                'kemungkinan_desc'  => $item['kemungkinan_desc'],
                'created_by'        => Auth::user()->user_id,
                'updated_by'        => Auth::user()->user_id,
            ]);
        }

        // close modal
        $this->closeModalKemungkinan();

        // Reset form fields and close the modal
        $this->resetFormKemungkinan();

        // send notification success
        flash()
            ->option('position', 'bottom-right')
            ->option('timeout', 3000)
            ->success('Data Anda telah disimpan!');
    }

    // EDIT KEMUNGKINAN KRITERIA
    public function editKemungkinan($id)
    {
        // OPEN MODAL
        $this->openModalKemungkinan($id);

        // IS SHOW
        $this->isShowKemungkinan = false;

        // IS EDIT
        $this->isEditKemungkinan = true;

        // FIND KEMUNGKINAN
        $kemungkinan = KriteriaKemungkinan::where('risk_id', $id)->get();

        // PASSING EXISTING DATA
        $data = [];

        foreach ($kemungkinan as $index => $value) {
            $data[] = [
                'kemungkinan_desc' => $value->kemungkinan_desc,
                'kemungkinan_id' => $value->kemungkinan_id,
                'risk_id' => $value->risk_id,
            ];
        }

        // PASSING DATA TO THE COMPONENT
        $this->kemungkinan = $data;
        
    }    

    // SHOW KEMUNGKINAN KRITERIA
    public function showKemungkinan($id)
    {
        // OPEN MODAL
        $this->openModalKemungkinan($id);

        // IS SHOW
        $this->isShowKemungkinan = true;

        // IS EDIT
        $this->isEditKemungkinan = false;

        // FIND KEMUNGKINAN
        $kemungkinan = KriteriaKemungkinan::where('risk_id', $id)->get();

        // PASSING EXISTING DATA
        $data = [];

        foreach ($kemungkinan as $index => $value) {
            $data[] = [
                'kemungkinan_desc' => $value->kemungkinan_desc,
                'kemungkinan_id' => $value->kemungkinan_id,
                'risk_id' => $value->risk_id,
            ];
        }

        // PASSING DATA TO THE COMPONENT
        $this->kemungkinan = $data;
        
    }    

    // DELETE KEMUNGKINAN KRITERIA
    public function deleteKemungkinan()
    {
        // FIND KEMUNGKINAN
        $kemungkinan = KriteriaKemungkinan::where('risk_id', $this->risk_id);
        $kemungkinan->delete();

        // close modal
        $this->closeModalConfirmDeleteKemungkinan();

        // send notification success
        flash()
            ->option('position', 'bottom-right')
            ->option('timeout', 3000)
            ->success('Data Anda telah dihapus!');
    }

    // LIFE CYCLE HOOKS KEMUNGKINAN KRITERIA
    // OPEN MODAL LIVEWIRE 
    public $isOpenKemungkinan = 0;
    public $isOpenConfirmKemungkinan = 0;
    public $isOpenConfirmDeleteKemungkinan = 0;

    public $isEditKemungkinan, $isShowKemungkinan;

    // OPEN MODAL KEMUNGKINAN KRITERIA
    public function openModalKemungkinan($id)
    {
        $this->isOpenKemungkinan = true;

        // FIND RISK
        $risk                   = Risk::find($id);

        // SET RISK DESC
        $this->risk_spesific    = $risk->risk_riskDesc;

        // SET RISK ID
        $this->risk_id          = $risk->risk_id;
    }

    // CLOSE MODAL KEMUNGKINAN KRITERIA
    public function closeModalKemungkinan()
    {
        $this->isOpenKemungkinan = false;

        // IS SHOW
        $this->isShowKemungkinan = false;

        // IS EDIT
        $this->isEditKemungkinan = false;

        // Reset form fields and close the modal
        $this->resetFormKemungkinan();

        // Reset form validation
        $this->resetValidation();
    } 

    // CLOSE MODAL KEMUNGKINAN KRITERIA
    public function closeXModalKemungkinan()
    {
        $this->isOpenKemungkinan = false;

        // IS SHOW
        $this->isShowKemungkinan = false;

        // IS EDIT
        $this->isEditKemungkinan = false;

        // Reset form fields and close the modal
        $this->resetFormKemungkinan();

        // Reset form validation
        $this->resetValidation();
    } 

    // OPEN MODAL CONFIRM KEMUNGKINAN KRITERIA
    public function openModalConfirmKemungkinan($id)
    {
        // SET KPI
        $this->risk_id       = $id;

        $this->isOpenConfirmKemungkinan    = true;
    }

    // CLOSE MODAL CONFIRM KEMUNGKINAN KRITERIA
    public function closeModalConfirmKemungkinan()
    {
        $this->isOpenConfirmKemungkinan = false;
    } 
  
    // CLOSE MODAL CONFIRM KEMUNGKINAN KRITERIA
    public function closeXModalConfirmKemungkinan()
    {
        $this->isOpenConfirmKemungkinan = false;
    } 
    
    // OPEN MODAL CONFIRM DELETE KEMUNGKINAN KRITERIA
    public function openModalConfirmDeleteKemungkinan($id)
    {
        $this->closeModalKemungkinan();

        // SET KPI
        $this->risk_id = $id;

        $this->isOpenConfirmDeleteKemungkinan = true;
    }

    // CLOSE MODAL CONFIRM DELETE KEMUNGKINAN KRITERIA
    public function closeModalConfirmDeleteKemungkinan()
    {
        $this->isOpenConfirmDeleteKemungkinan = false;
    } 

    // CLOSE MODAL CONFIRM DELETE KEMUNGKINAN KRITERIA
    public function closeXModalConfirmDeleteKemungkinan()
    {
        $this->isOpenConfirmDeleteKemungkinan = false;
    } 

    // RESET FORM KEMUNGKINAN KRITERIA
    public function resetFormKemungkinan()
    {
        $this->risk_id               = '';
        $this->kemungkinan = array_fill(0, 10, [
            'kemungkinan_desc'  => '',
            'kemungkinan_id'    => null,
        ]);
        $this->isEditKemungkinan     = false;
    }


    // DAMPAK KRITERIA RISIKO
    public $dampak;

    // SANITIZE INPUTS STORE DAMPAK KRITERIA
    protected function sanitizeInputsStoreDampak()
    {
        $this->dampak = array_map(function($item) {
            $item['dampak_desc'] = filter_var($item['dampak_desc'], FILTER_SANITIZE_STRING, FILTER_FLAG_ALLOW_FRACTION);
            return $item;
        }, $this->dampak);
    }

    // VALIDATING INPUTS DAMPAK KRITERIA
    protected function validatingInputsDampak()
    {
        $rules = [];
        $messages = [];

        // SET DATA TO 10 DAMPAK
        $data = range(0, 9); // Adjust range to match index (0 to 9 for 10 items)

        // Define validation rules and messages for each index
        foreach ($data as $index) {
            $rules["dampak.$index.dampak_desc"] = 'required';
            $messages["dampak.$index.dampak_desc.required"] = "Keterangan dampak skala ke-".($index + 1)." wajib diisi!";
        }

        $this->validate($rules, $messages);
    }

    // STORE NEW DATA DAMPAK KRITERIA
    public function storeDampak()
    {
        try {
            // SANITIZE INPUTS
            $this->sanitizeInputsStoreDampak();

            // VALIDATING DATA
            $this->validatingInputsDampak();

            foreach ($this->dampak as $index => $item) {
                KriteriaDampak::updateOrCreate([
                    'dampak_id'     => $item['dampak_id'],
                ],[
                    'risk_id'       => $this->risk_id,
                    'dampak_scale'  => $index + 1,
                    'dampak_desc'   => $item['dampak_desc'],
                    'created_by'    => Auth::user()->user_id,
                    'updated_by'    => Auth::user()->user_id,
                ]);
            }

            // close modal
            $this->closeModalDampak();

            // Reset form fields
            $this->resetFormDampak();

            // send success notification
            flash()
                ->option('position', 'bottom-right')
                ->option('timeout', 3000)
                ->success('Data Anda telah disimpan!');

        } catch (\Exception $e) {

            // send error notification
            flash()
                ->option('position', 'bottom-right')
                ->option('timeout', 3000)
                ->error('Terjadi kesalahan saat menyimpan data Anda: ' . $e->getMessage());

            // VALIDATING DATA
            $this->validatingInputsDampak();
        }
    }


    // EDIT DAMPAK KRITERIA
    public function editDampak($id)
    {
        // OPEN MODAL
        $this->openModalDampak($id);

        // IS SHOW
        $this->isShowDampak = false;

        // IS EDIT
        $this->isEditDampak = true;

        // FIND DAMPAK
        $dampak = KriteriaDampak::where('risk_id', $id)->get();

        // PASSING EXISTING DATA
        $data = [];

        foreach ($dampak as $index => $value) {
            $data[] = [
                'dampak_desc'   => $value->dampak_desc,
                'dampak_id'     => $value->dampak_id,
                'risk_id'       => $value->risk_id,
            ];
        }

        // PASSING DATA TO THE COMPONENT
        $this->dampak = $data;
        
    }   

    // SHOW DAMPAK KRITERIA
    public function showDampak($id)
    {
        // OPEN MODAL
        $this->openModalDampak($id);

        // IS SHOW
        $this->isShowDampak = true;

        // IS EDIT
        $this->isEditDampak = false;

        // FIND DAMPAK
        $dampak = KriteriaDampak::where('risk_id', $id)->get();

        // PASSING EXISTING DATA
        $data = [];

        foreach ($dampak as $index => $value) {
            $data[] = [
                'dampak_desc'   => $value->dampak_desc,
                'dampak_id'     => $value->dampak_id,
                'risk_id'       => $value->risk_id,
            ];
        }

        // PASSING DATA TO THE COMPONENT
        $this->dampak = $data;
        
    }    

    // DELETE DAMPAK KRITERIA
    public function deleteDampak()
    {
        // FIND DAMPAK
        $dampak = KriteriaDampak::where('risk_id', $this->risk_id);
        $dampak->delete();

        // close modal
        $this->closeModalConfirmDeleteDampak();

        // send notification success
        flash()
            ->option('position', 'bottom-right')
            ->option('timeout', 3000)
            ->success('Data Anda telah dihapus!');
    }


    // LIFE CYCLE HOOKS DAMPAK KRITERIA
    // OPEN MODAL LIVEWIRE 
    public $isOpenDampak = 0;
    public $isOpenConfirmDampak = 0;
    public $isOpenConfirmDeleteDampak = 0;

    public $isEditDampak, $isShowDampak;

    // OPEN MODAL DAMPAK KRITERIA
    public function openModalDampak($id)
    {
        $this->isOpenDampak = true;

        // FIND RISK
        $risk                   = Risk::find($id);

        // SET RISK DESC
        $this->risk_spesific    = $risk->risk_riskDesc;

        // SET RISK ID
        $this->risk_id          = $risk->risk_id;

    }

    // CLOSE MODAL DAMPAK KRITERIA
    public function closeModalDampak()
    {
        $this->isOpenDampak = false;

        // IS SHOW
        $this->isShowDampak = false;

        // IS EDIT
        $this->isEditDampak = false;

        // Reset form fields and close the modal
        $this->resetFormDampak();

        // Reset form validation
        $this->resetValidation();
    } 

    // CLOSE MODAL DAMPAK KRITERIA
    public function closeXModalDampak()
    {
        $this->isOpenDampak = false;

        // IS SHOW
        $this->isShowDampak = false;

        // IS EDIT
        $this->isEditDampak = false;

        // Reset form fields and close the modal
        $this->resetFormDampak();

        // Reset form validation
        $this->resetValidation();
    } 

    // OPEN MODAL CONFIRM DAMPAK KRITERIA
    public function openModalConfirmDampak($id)
    {
        // SET KPI
        $this->risk_id       = $id;

        $this->isOpenConfirmDampak    = true;
    }

    // CLOSE MODAL CONFIRM DAMPAK KRITERIA
    public function closeModalConfirmDampak()
    {
        $this->isOpenConfirmDampak = false;
    } 
  
    // CLOSE MODAL CONFIRM DAMPAK KRITERIA
    public function closeXModalConfirmDampak()
    {
        $this->isOpenConfirmDampak = false;
    } 
    // OPEN MODAL CONFIRM DELETE DAMPAK KRITERIA
    public function openModalConfirmDeleteDampak($id)
    {
        $this->closeModalDampak();

        // SET KPI
        $this->risk_id = $id;

        $this->isOpenConfirmDeleteDampak = true;
    }

    // CLOSE MODAL CONFIRM DELETE DAMPAK KRITERIA
    public function closeModalConfirmDeleteDampak()
    {
        $this->isOpenConfirmDeleteDampak = false;
    } 

    // CLOSE MODAL CONFIRM DELETE DAMPAK KRITERIA
    public function closeXModalConfirmDeleteDampak()
    {
        $this->isOpenConfirmDeleteDampak = false;
    } 

    // RESET FORM DAMPAK KRITERIA
    public function resetFormDampak()
    {
        $this->risk_id               = '';
        $this->dampak = array_fill(0, 10, [
            'dampak_desc'  => '',
            'dampak_id'    => null,
        ]);
        $this->isEditDampak     = false;
    }


    // DETEKSI RISIKO
    public $deteksi;

    // SANITIZE INPUTS STORE DETEKSI KRITERIA
    protected function sanitizeInputsStoreDeteksi()
    {
        $this->deteksi = array_map(function($item) {
            $item['deteksi_desc'] = filter_var($item['deteksi_desc'], FILTER_SANITIZE_STRING, FILTER_FLAG_ALLOW_FRACTION);
            return $item;
        }, $this->deteksi);
    }

    // VALIDATING INPUTS DETEKSI KRITERIA
    protected function validatingInputsDeteksi()
    {
        $rules = [];
        $messages = [];

        // SET DATA TO 10 DETEKSI
        $data = range(0, 9); // Adjust range to match index (0 to 9 for 10 items)

        // Define validation rules and messages for each index
        foreach ($data as $index) {
            $rules["deteksi.$index.deteksi_desc"] = 'required';
            $messages["deteksi.$index.deteksi_desc.required"] = "Keterangan deteksi skala ke-".($index + 1)." wajib diisi!";
        }

        $this->validate($rules, $messages);
    }

    // STORE NEW DATA DETEKSI KRITERIA
    public function storeDeteksi()
    {   
        // SANITIZE INPUTS
        $this->sanitizeInputsStoreDeteksi();

        // VALIDATING DATA
        $this->validatingInputsDeteksi();
        
        foreach ($this->deteksi as $index => $item) {
            KriteriaDeteksiKegagalan::updateOrCreate([
                'deteksiKegagalan_id'     => $item['deteksi_id'],
            ],[
                'risk_id'                   => $this->risk_id,
                'deteksiKegagalan_scale'    => $index + 1,
                'deteksiKegagalan_desc'     => $item['deteksi_desc'],
                'created_by'                => Auth::user()->user_id,
                'updated_by'                => Auth::user()->user_id,
            ]);
        }

        // close modal
        $this->closeModalDeteksi();

        // Reset form fields and close the modal
        $this->resetFormDeteksi();

        // send notification success
        flash()
            ->option('position', 'bottom-right')
            ->option('timeout', 3000)
            ->success('Data Anda telah disimpan!');
    }

    // EDIT DETEKSI KRITERIA
    public function editDeteksi($id)
    {
        // OPEN MODAL
        $this->openModalDeteksi($id);

        // IS SHOW
        $this->isShowDeteksi = false;

        // IS EDIT
        $this->isEditDeteksi = true;

        // FIND DETEKSI
        $deteksi = KriteriaDeteksiKegagalan::where('risk_id', $id)->get();
        
        // PASSING EXISTING DATA
        $data = [];

        foreach ($deteksi as $index => $value) {
            $data[] = [
                'deteksi_desc'   => $value->deteksiKegagalan_desc,
                'deteksi_id'     => $value->deteksiKegagalan_id,
                'risk_id'        => $value->risk_id,
            ];
        }

        // PASSING DATA TO THE COMPONENT
        $this->deteksi = $data;
        
    }  

    // SHOW DETEKSI KRITERIA
    public function showDeteksi($id)
    {
        // OPEN MODAL
        $this->openModalDeteksi($id);

        // IS SHOW
        $this->isShowDeteksi = true;

        // IS EDIT
        $this->isEditDeteksi = false;

        // FIND DETEKSI
        $deteksi = KriteriaDeteksiKegagalan::where('risk_id', $id)->get();
        
        // PASSING EXISTING DATA
        $data = [];

        foreach ($deteksi as $index => $value) {
            $data[] = [
                'deteksi_desc'   => $value->deteksiKegagalan_desc,
                'deteksi_id'     => $value->deteksiKegagalan_id,
                'risk_id'        => $value->risk_id,
            ];
        }

        // PASSING DATA TO THE COMPONENT
        $this->deteksi = $data;
        
    }    

    // DELETE DETEKSI KRITERIA
    public function deleteDeteksi()
    {
        // FIND DETEKSI
        $deteksi = KriteriaDeteksiKegagalan::where('risk_id', $this->risk_id);
        $deteksi->delete();

        // close modal
        $this->closeModalConfirmDeleteDeteksi();

        // send notification success
        flash()
            ->option('position', 'bottom-right')
            ->option('timeout', 3000)
            ->success('Data Anda telah dihapus!');
    }


    // LIFE CYCLE HOOKS DETEKSI KRITERIA
    // OPEN MODAL LIVEWIRE 
    public $isOpenDeteksi = 0;
    public $isOpenConfirmDeteksi = 0;
    public $isOpenConfirmDeleteDeteksi = 0;

    public $isEditDeteksi, $isShowDeteksi;

    // OPEN MODAL DETEKSI KRITERIA
    public function openModalDeteksi($id)
    {
        $this->isOpenDeteksi = true;

        // FIND RISK
        $risk                   = Risk::find($id);

        // SET RISK DESC
        $this->risk_spesific    = $risk->risk_riskDesc;

        // SET RISK ID
        $this->risk_id          = $risk->risk_id;

    }

    // CLOSE MODAL DETEKSI KRITERIA
    public function closeModalDeteksi()
    {
        $this->isOpenDeteksi = false;

        // IS SHOW
        $this->isShowDeteksi = false;

        // IS EDIT
        $this->isEditDeteksi = false;

        // Reset form fields and close the modal
        $this->resetFormDeteksi();

        // Reset form validation
        $this->resetValidation();
    } 

    // CLOSE MODAL DETEKSI KRITERIA
    public function closeXModalDeteksi()
    {
        $this->isOpenDeteksi = false;

        // IS SHOW
        $this->isShowDeteksi = false;

        // IS EDIT
        $this->isEditDeteksi = false;

        // Reset form fields and close the modal
        $this->resetFormDeteksi();

        // Reset form validation
        $this->resetValidation();
    } 

    // OPEN MODAL CONFIRM DETEKSI KRITERIA
    public function openModalConfirmDeteksi($id)
    {
        // SET KPI
        $this->risk_id       = $id;

        $this->isOpenConfirmDeteksi    = true;
    }

    // CLOSE MODAL CONFIRM DETEKSI KRITERIA
    public function closeModalConfirmDeteksi()
    {
        $this->isOpenConfirmDeteksi = false;
    } 
  
    // CLOSE MODAL CONFIRM DETEKSI KRITERIA
    public function closeXModalConfirmDeteksi()
    {
        $this->isOpenConfirmDeteksi = false;
    } 
    // OPEN MODAL CONFIRM DELETE DETEKSI KRITERIA
    public function openModalConfirmDeleteDeteksi($id)
    {
        $this->closeModalDeteksi();

        // SET KPI
        $this->risk_id = $id;

        $this->isOpenConfirmDeleteDeteksi = true;
    }

    // CLOSE MODAL CONFIRM DELETE DETEKSI KRITERIA
    public function closeModalConfirmDeleteDeteksi()
    {
        $this->isOpenConfirmDeleteDeteksi = false;
    } 

    // CLOSE MODAL CONFIRM DELETE DETEKSI KRITERIA
    public function closeXModalConfirmDeleteDeteksi()
    {
        $this->isOpenConfirmDeleteDeteksi = false;
    } 

    // RESET FORM DETEKSI KRITERIA
    public function resetFormDeteksi()
    {
        $this->risk_id               = '';
        $this->deteksi = array_fill(0, 10, [
            'deteksi_desc'  => '',
            'deteksi_id'    => null,
        ]);
        $this->isEditDeteksi     = false;
    }


    /**
     * ANALISIS FUNCTIONS
     *
     */

    // VARIABLES ANALISIS
    public $analisis, $lastAnalisis;
    // VARIABLES ANALISIS
    public $controlRisk_id, $kemungkinan_id, $dampak_id, $deteksiKegagalan_id, $derajatRisiko_id;
    // CREATE KEMUNGKINAN ANALISIS
    public function createAnalisis($id)
    {
        // FIND RISK
        $risk                   = Risk::with(['kemungkinan', 'dampak', 'deteksiKegagalan'])->where('risk_id', $id)->first();

        // SET RISK DESC
        $this->risk_spesific    = $risk->risk_riskDesc;

        // SET RISK ID
        $this->risk_id          = $risk->risk_id;

        // IS EFEKTIFITAS
        $this->isEfektifitas    = 0;

        // PASSING DATA KRITERIA
        $this->dataKemungkinan  = $risk->kemungkinan;
        $this->dataDampak       = $risk->dampak;
        $this->dataDeteksi      = $risk->deteksiKegagalan;

        // OPEN MODAL ANALISIS
        $this->openModalAnalisis();
    }

    // VARIABLES EFEKTIFITAS KONTROL
    public $penilaianEfektifitas, $efektifitasKontrol_id, $efektifitasKontrol_kontrolStatus, $efektifitasKontrol_kontrolDesc, $efektifitasKontrol_totalEfektifitas = 0;
    public $penilaianEfektifitas_id = [], $penilaianEfektifitas_jawaban, $penilaianEfektifitas_skor = [];
    
    // CREATE EFEKTIFITAS KONTROL
    public function createEfektifitas($id)
    {
        // FIND RISK
        $risk                   = Risk::with(['kemungkinan', 'dampak', 'deteksiKegagalan'])->where('risk_id', $id)->first();

        // SET RISK DESC
        $this->risk_spesific    = $risk->risk_riskDesc;

        // SET RISK ID
        $this->risk_id          = $risk->risk_id;
        
        // IS EFEKTIFITAS
        $this->isEfektifitas    = 1;

        // PASSING PENILAIAN EFEKTIFITAS
        $this->penilaianEfektifitas     = PenilaianEfektifitas::where('penilaianEfektifitas_activeStatus', true)->get();

        $this->penilaianEfektifitas_id  = PenilaianEfektifitas::where('penilaianEfektifitas_activeStatus', true)
                                            ->pluck('penilaianEfektifitas_id');
        // OPEN MODAL ANALISIS
        $this->openModalAnalisis();
    }

    // VALIDATE INPUT ANALISIS RISIKO
    protected function validatingInputsAnalisis()
    {
        $validated = $this->validate([
            'kemungkinan_id'                => ['required'],
            'dampak_id'                     => ['required'],
            'deteksiKegagalan_id'           => ['required'],
        ], [
            'kemungkinan_id.required'       => 'Kriteria Kemungkinan wajib diisi!',
            'dampak_id.required'            => 'Kriteria Dampak wajib diisi!',
            'deteksiKegagalan_id.required'  => 'Kriteria Deteksi Kegagalan  wajib diisi!',
        ]);
    }

    // STORE ANALISIS RISIKO
    public function storeAnalisis()
    {
        // VALIDATION INPUTS ANALISIS
        $this->validatingInputsAnalisis();

        // FIND KEMUNGKINAN
        $kemungkinan    = KriteriaKemungkinan::find($this->kemungkinan_id);
        $dampak         = KriteriaDampak::find($this->dampak_id);
        $deteksi        = KriteriaDeteksiKegagalan::find($this->deteksiKegagalan_id);

        // CALCULATE RPN
        $rpn = $kemungkinan->kemungkinan_scale * $dampak->dampak_scale * $deteksi->deteksiKegagalan_scale;

        // FIND DERAJAT RISIKO BASED ON RPN
        $derajatRisiko = DerajatRisiko::with(['seleraRisiko'])->where('derajatRisiko_nilaiTingkatMin', '<=', $rpn)
                                    ->where('derajatRisiko_nilaiTingkatMax', '>=', $rpn)
                                    ->first();
        
        // CHECK DERAJAT RISIKO
        if ($derajatRisiko) {
            $derajatRisiko_id = $derajatRisiko->derajatRisiko_id;
            // You can now use $derajatRisiko_id as needed

            $data = [];
            // FIND SELERA RISIKO THAT ACTIVE
            foreach($derajatRisiko->seleraRisiko as $item){
                if($item->seleraRisiko_activeStatus){
                    $data[] = $item;
                    break;
                }
            }
        } else {
            // Handle the case where no matching record is found
            $derajatRisiko_id = null;
        }

        // STORE ANALISIS
        $controlRisk = ControlRisk::updateOrCreate([
            'controlRisk_id'    => $this->controlRisk_id,
        ],[
            'risk_id'               => $this->risk_id,
            'kemungkinan_id'        => $this->kemungkinan_id,
            'dampak_id'             => $this->dampak_id,
            'deteksiKegagalan_id'   => $this->deteksiKegagalan_id,
            'controlRisk_RPN'       => $rpn,
            'derajatRisiko_id'      => $derajatRisiko_id,
            'seleraRisiko_id'       => $data[0]['seleraRisiko_id'],
            'created_by'            => Auth::user()->user_id,
            'updated_by'            => Auth::user()->user_id,
        ]);

        // CLOSE MODAL
        $this->closeModalAnalisis();

        // send notification success
        flash()
            ->option('position', 'bottom-right')
            ->option('timeout', 3000)
            ->success('Data Anda telah disimpan!');
    }

    // EDIT ANALISIS
    public function editAnalisis($id)
    {
        // RECALL CREATE FUNCTION BUT ALSO SET ID KRITERIA
        $this->createAnalisis($id);

         // IS EDIT
        $this->isEditAnalisis = true;

        // IS SHOW ANALISIS
        $this->isShowAnalisis = false;

        // FIND CONTROL RISK ID
        $controlRisk = ControlRisk::where('risk_id', $id)->orderBy('controlRisk_id', 'desc')->first();

        // PASSING CONTROL RISK ID
        $this->controlRisk_id       = $controlRisk->controlRisk_id;
        // PASSING ID KRITERIA
        $this->kemungkinan_id       = $controlRisk->kemungkinan_id;
        $this->dampak_id            = $controlRisk->dampak_id;
        $this->deteksiKegagalan_id  = $controlRisk->deteksiKegagalan_id;
    }

    // VALIDATE INPUT EFEKTIFITAS KONTROL
    protected function validatingInputsEfektifitas()
    {
        $rules = [];
        $messages = [];

        // Define validation rules and messages for each index in penilaianEfektifitas_skor
        foreach ($this->penilaianEfektifitas as $index => $item) {
            $rules["penilaianEfektifitas_skor.$index"] = ['required'];
            $messages["penilaianEfektifitas_skor.$index.required"] = "Pilihan Jawaban untuk pertanyaan ke-" . ($index + 1) . " wajib diisi!";
        }

        // Common rules and messages
        $rules['efektifitasKontrol_kontrolStatus'] = ['required'];
        $rules['efektifitasKontrol_kontrolDesc'] = ['required'];

        $messages['efektifitasKontrol_kontrolStatus.required'] = 'Pengendalian Efektifitas wajib diisi!';
        $messages['efektifitasKontrol_kontrolDesc.required'] = 'Uraian Pengendalian wajib diisi!';

        // Validate using the dynamically created rules and messages
        $validated = $this->validate($rules, $messages);

        return $validated;
    }

    // CALCULATING TOTAL NILAI EFEKTIFITAS CONTROL
    public function updatedPenilaianEfektifitasSkor($value, $index)
    {
        $this->calculateTotalEfektifitas();
    }

    // EQUATION TO CALCULATE
    public function calculateTotalEfektifitas()
    {
        // Ensure penilaianEfektifitas_skor is an array
        if (!is_array($this->penilaianEfektifitas_skor)) {
            $this->penilaianEfektifitas_skor = [];
        }

        // Filter out non-numeric values and sum up the numeric ones
        $total = array_sum(array_filter($this->penilaianEfektifitas_skor, function($value) {
            return is_numeric($value);
        }));

        // Update total efektivitas
        $this->efektifitasKontrol_totalEfektifitas = $total;
    }

    // STORE EFEKTIFITAS KONTROL
    public function storeEfektifitas()
    {
        // VALIDATE INPUT EFEKTIFITAS
        $this->validatingInputsEfektifitas();

        // GET LATEST CONTROL ID
        $controlRisk = ControlRisk::where('risk_id', $this->risk_id)->orderBy('controlRisk_id', 'desc')->first();

        // FIND OR CREATE EFEKTIFITAS KONTROL
        $efektifitasKontrol = EfektifitasKontrol::updateOrCreate(
            [
                'risk_id'               => $this->risk_id,
                'efektifitasKontrol_id' => $this->efektifitasKontrol_id,
            ],
            [
                'controlRisk_id'                      => $controlRisk->controlRisk_id,
                'efektifitasKontrol_kontrolStatus'    => $this->efektifitasKontrol_kontrolStatus,
                'efektifitasKontrol_kontrolDesc'      => $this->efektifitasKontrol_kontrolDesc,
                'efektifitasKontrol_totalEfektifitas' => $this->efektifitasKontrol_totalEfektifitas,
                'created_by'                          => Auth::user()->user_id,
                'updated_by'                          => Auth::user()->user_id,
            ]
        );

        // UPDATE EFEKTIFITAS KONTROL ID
        $this->efektifitasKontrol_id = $efektifitasKontrol->efektifitasKontrol_id;

        // UPDATE OR CREATE DETAIL EFEKTIFITAS KONTROL
        if (!empty($this->penilaianEfektifitas_id)) {
            foreach ($this->penilaianEfektifitas_id as $index => $penilaianId) {
                DetailEfektifitasKontrol::updateOrCreate(
                    [
                        'efektifitasKontrol_id' => $this->efektifitasKontrol_id,
                        'penilaianEfektifitas_id' => $penilaianId,
                    ],
                    [
                        'detailEfektifitasKontrol_skor' => $this->penilaianEfektifitas_skor[$index] ?? null,
                        'created_by'                    => Auth::user()->user_id,
                        'updated_by'                    => Auth::user()->user_id,
                    ]
                );
            }
        }

        // CLOSE MODAL AND SEND SUCCESS NOTIFICATION
        $this->closeModalAnalisis();

        flash()
            ->option('position', 'bottom-right')
            ->option('timeout', 3000)
            ->success('Data Anda telah disimpan!');
    }

    // EDIT EFEKTIFITAS
    public function editEfektifitas($id)
    {
        // RECALL CREATE FUNCTION BUT ALSO SET ID KRITERIA
        $this->createEfektifitas($id);

        // IS EDIT
        $this->isEditEfektifitas = true;

        // IS SHOW ANALISIS
        $this->isShowEfektifitas = false;

        // FIND EFEKTIFITAS KONTROL ID
        $efektifitasKontrol = EfektifitasKontrol::with(['detailEfektifitasKontrol'])->where('risk_id', $id)->first();

        if ($efektifitasKontrol) {
            // PASSING EFEKTIFITAS KONTROL ID
            $this->efektifitasKontrol_id                = $efektifitasKontrol->efektifitasKontrol_id;
            $this->efektifitasKontrol_kontrolStatus     = $efektifitasKontrol->efektifitasKontrol_kontrolStatus;
            $this->efektifitasKontrol_kontrolDesc       = $efektifitasKontrol->efektifitasKontrol_kontrolDesc;
            $this->efektifitasKontrol_totalEfektifitas  = $efektifitasKontrol->efektifitasKontrol_totalEfektifitas;

            // PASSING DETAIL EFEKTIFITAS
            $penilaianEfektifitas_id    = [];
            $penilaianEfektifitas_skor  = [];
            
            foreach ($efektifitasKontrol->detailEfektifitasKontrol as $item) {
                $penilaianEfektifitas_id[]      = $item->penilaianEfektifitas_id;
                $penilaianEfektifitas_skor[]    = $item->detailEfektifitasKontrol_skor;
            }

            // PASSING PENILAIAN EFEKTIFITAS
            $this->penilaianEfektifitas_id      = $penilaianEfektifitas_id;
            $this->penilaianEfektifitas_skor    = $penilaianEfektifitas_skor;
        }
    }

    // SHOW ANALISIS
    public function showAnalisis($id)
    {
        // RECALL CREATE FUNCTION BUT ALSO SET ID KRITERIA
        $this->createAnalisis($id);

        // IS EDIT
        $this->isEditAnalisis = false;

        // IS SHOW ANALISIS
        $this->isShowAnalisis = true;

        // FIND CONTROL RISK ID
        $controlRisk = ControlRisk::with(['kemungkinan', 'dampak', 'deteksiKegagalan'])->where('risk_id', $id)->first();

        // PASSING LAST ANALISIS
        $this->lastAnalisis        = $controlRisk;
        // PASSING CONTROL RISK ID
        $this->controlRisk_id       = $controlRisk->controlRisk_id;
        // PASSING ID KRITERIA
        $this->kemungkinan_id       = $controlRisk->kemungkinan_id;
        $this->dampak_id            = $controlRisk->dampak_id;
        $this->deteksiKegagalan_id  = $controlRisk->deteksiKegagalan_id;
    }

    // LOCK ANALISIS RISIKO
    public function lockAnalisisRisiko()
    {
        // FIND CONTROL RISK ID
        $controlRisk = ControlRisk::where('risk_id', $this->risk_id)->first();

        // UPDATE LOCK STATUS CONTROL RISK
        $controlRisk->update(['controlRisk_lockStatus'   => 1]);

        // FIND EFEKtIFITAS CONTROL ID
        $efekifitasKontrol = EfektifitasKontrol::where('risk_id', $this->risk_id)->first();

        // UPDATE LOCK STATUS EFEKtIFITAS CONTROL
        $efekifitasKontrol->update(['efektifitasKontrol_lockStatus'   => 1]);

        // close modal
        $this->closeModalConfirmAnalisis();

        // send notification success
        flash()
            ->option('position', 'bottom-right')
            ->option('timeout', 3000)
            ->success('Data Anda telah disimpan!');
    }

    // LIFE CYCLE HOOKS ANALISIS
    // OPEN MODAL LIVEWIRE 
    public $isOpenAnalisis              = 0;
    public $isOpenConfirmAnalisis       = 0;
    public $isOpenConfirmDeleteAnalisis = 0;

    public $dataKemungkinan, $dataDampak, $dataDeteksi;

    // IS SHOW OR EDIT ANALISIS
    public $isEditAnalisis, $isShowAnalisis;

    // IS KRITERIA OR EFEKTIFITAS
    public $isEfektifitas               = 0;

    // IS SHOW OR EDIT EFEKTIFITAS
    public $isEditEfektifitas, $isShowEfektifitas;

    // OPEN MODAL ANALISIS
    public function openModalAnalisis()
    {
        $this->isOpenAnalisis = true;
    }

    // CLOSE MODAL ANALISIS
    public function closeModalAnalisis()
    {
        $this->isOpenAnalisis = false;

        // IS SHOW
        $this->isShowAnalisis = false;

        // IS EDIT
        $this->isEditAnalisis = false;

        // Reset form fields and close the modal
        $this->resetFormAnalisis();

        // Reset form validation
        $this->resetValidation();
    } 

    // CLOSE MODAL ANALISIS
    public function closeXModalAnalisis()
    {
        $this->isOpenAnalisis = false;

        // IS SHOW
        $this->isShowAnalisis = false;

        // IS EDIT
        $this->isEditAnalisis = false;

        // Reset form fields and close the modal
        $this->resetFormAnalisis();

        // Reset form validation
        $this->resetValidation();
    } 

    // OPEN MODAL CONFIRM ANALISIS
    public function openModalConfirmAnalisis($id)
    {
        // SET KPI
        $this->risk_id       = $id;

        $this->isOpenConfirmAnalisis    = true;
    }

    // CLOSE MODAL CONFIRM ANALISIS
    public function closeModalConfirmAnalisis()
    {
        $this->isOpenConfirmAnalisis = false;
    } 
  
    // CLOSE MODAL CONFIRM ANALISIS
    public function closeXModalConfirmAnalisis()
    {
        $this->isOpenConfirmAnalisis = false;
    } 
    
    // OPEN MODAL CONFIRM DELETE ANALISIS
    public function openModalConfirmDeleteAnalisis($id)
    {
        $this->closeModalAnalisis();

        // SET KPI
        $this->risk_id = $id;

        $this->isOpenConfirmDeleteAnalisis = true;
    }

    // CLOSE MODAL CONFIRM DELETE ANALISIS
    public function closeModalConfirmDeleteAnalisis()
    {
        $this->isOpenConfirmDeleteAnalisis = false;
    } 

    // CLOSE MODAL CONFIRM DELETE ANALISIS
    public function closeXModalConfirmDeleteAnalisis()
    {
        $this->isOpenConfirmDeleteAnalisis = false;
    } 

    // RESET FORM ANALISIS
    public function resetFormAnalisis()
    {
        $this->risk_id              = '';
        $this->controlRisk_id       = '';
        $this->isEditAnalisis       = false;
        $this->isShowAnalisis       = false;

        // CLEAR DATA KRITERIA
        $this->dataKemungkinan      = '';
        $this->kemungkinan_id       = '';
        $this->dataDampak           = '';
        $this->dampak_id            = '';
        $this->dataDeteksi          = '';
        $this->deteksiKegagalan_id  = ''; 

        // CLEAR DATA EFEKTIFITAS
        $this->efektifitasKontrol_kontrolStatus     = '';
        $this->efektifitasKontrol_kontrolDesc       = '';
        $this->efektifitasKontrol_id                = '';
        $this->penilaianEfektifitas_skor            = [];
        $this->efektifitasKontrol_totalEfektifitas  = 0;
    }


    /**
     * RENCANA PERLAKUAN FUNCTIONS
     *
     */

    // VARIABLES RENCANA PERLAKUAN
    public $rencanaPerlakuan;
    
    // CREATE KEMUNGKINAN RENCANA PERLAKUAN
    public function createRencanPerlakuan($id)
    {
        // FIND RISK
        $risk                   = Risk::with(['kemungkinan', 'dampak', 'deteksiKegagalan'])->where('risk_id', $id)->first();

        // SET RISK DESC
        $this->risk_spesific    = $risk->risk_riskDesc;

        // SET RISK ID
        $this->risk_id          = $risk->risk_id;

         // FIND CONTROL RISK ID
        $controlRisk = ControlRisk::where('risk_id', $id)->first();

        // SET CONTROL RISK ID
        $this->controlRisk_id   = $controlRisk->controlRisk_id;

        // OPEN MODAL RENCANA PERLAKUAN
        $this->openModalRencanaPerlakuan();
    }

    // VARIABLES RENCANA PERLAKUAN
    public $rencanaPerlakuan_id, $rtm, $perlakuanRisiko_id, $jenisPerlakuan_id, $jenisPerlakuan_desc, $jenisPerlakuans, $rencanaPerlakuan_desc;

    // VALIDATE INPUT RENCANA PERLAKUAN RISIKO
    protected function validatingInputsRencanaPerlakuan()
    {
        $validated = $this->validate([
            'jenisPerlakuan_id'                => ['required'],
            'plans'                            => ['required'],
        ], [
            'jenisPerlakuan_id.required'       => 'Jenis Perlakuan wajib diisi!',
            'plans.required'                   => 'Rencana Perlakuan wajib diisi!',
        ]);
    }

    // STORE RENCANA PERLAKUAN RISIKO
    public function storeRencanaPerlakuan()
    {
        // VALIDATION INPUTS RENCANA PERLAKUAN
        $this->validatingInputsRencanaPerlakuan();

        // STORE PERLAKUAN RISIKO
        $perlakuanRisiko = PerlakuanRisiko::updateOrCreate(
            [
                'perlakuanRisiko_id' => $this->perlakuanRisiko_id,
            ],
            [
                'controlRisk_id'     => $this->controlRisk_id,
                'jenisPerlakuan_id'  => $this->jenisPerlakuan_id,
                'created_by'         => Auth::user()->user_id,
                'updated_by'         => Auth::user()->user_id,
            ]
        );

        // Update perlakuanRisiko_id with the created/updated ID
        $this->perlakuanRisiko_id = $perlakuanRisiko->perlakuanRisiko_id;

        // Find existing RencanaPerlakuan records for the given perlakuanRisiko_id
        $existingRencanaPerlakuan = RencanaPerlakuan::where('perlakuanRisiko_id', $this->perlakuanRisiko_id)->get();

        // Array to keep track of rencanaPerlakuan IDs that have been updated or created
        $updatedOrCreatedRencanaIds = [];

        // Iterate through plans and update or create RencanaPerlakuan
        foreach ($this->plans as $item) {
            $rencanaPerlakuan = RencanaPerlakuan::updateOrCreate(
                [
                    'rencanaPerlakuan_id'   => $item['id'] ?? null,
                    'perlakuanRisiko_id'    => $this->perlakuanRisiko_id,
                ],
                [
                    'rencanaPerlakuan_desc' => $item['desc'],
                    'created_by'            => Auth::user()->user_id,
                    'updated_by'            => Auth::user()->user_id,
                ]
            );

            // Add rencanaPerlakuan ID to the array
            $updatedOrCreatedRencanaIds[] = $rencanaPerlakuan->rencanaPerlakuan_id;
        }

        // Delete RencanaPerlakuan that are not in the updatedOrCreatedRencanaIds array
        RencanaPerlakuan::where('perlakuanRisiko_id', $this->perlakuanRisiko_id)
            ->whereNotIn('rencanaPerlakuan_id', $updatedOrCreatedRencanaIds)
            ->delete();

        // CLOSE MODAL
        $this->closeModalRencanaPerlakuan();

        // send notification success
        flash()
            ->option('position', 'bottom-right')
            ->option('timeout', 3000)
            ->success('Data Anda telah disimpan!');
    }

    // EDIT RENCANA PERLAKUAN
    public function editRencanaPerlakuan($id)
    {
        // IS EDIT
        $this->isEditRencanaPerlakuan = true;

        // IS SHOW
        $this->isShowRencanaPerlakuan = true;

        // FIND CONTROL RISK ID
        $controlRisk = ControlRisk::with(['perlakuanRisiko.rencanaPerlakuan'])->where('risk_id', $id)->first();

        // PASSING CONTROL RISK ID
        $this->controlRisk_id = $controlRisk->controlRisk_id;

        // PASSING PERLAKUAN ID
        $this->perlakuanRisiko_id = $controlRisk->perlakuanRisiko->first()->perlakuanRisiko_id;

        // PASSING PLANS
        $this->plans = [];
        foreach ($controlRisk->perlakuanRisiko as $perlakuan) {
            foreach ($perlakuan->rencanaPerlakuan as $rencana) {
                $this->plans[] = [
                    'desc'  => $rencana->rencanaPerlakuan_desc,
                    'id'    => $rencana->rencanaPerlakuan_id,
                ];
            }
        }

        // PASSING JENIS PERLAKUAN
        $this->jenisPerlakuan_id = $controlRisk->perlakuanRisiko->first()->jenisPerlakuan_id;

        // OPEN MODAL
        $this->openModalRencanaPerlakuan();
    }

    // EDIT RENCANA PERLAKUAN
    public function showRencanaPerlakuan($id)
    {
         // IS EDIT
        $this->isEditRencanaPerlakuan = false;

        // IS SHOW
        $this->isShowRencanaPerlakuan = true;


        // FIND CONTROL RISK ID
        $controlRisk = ControlRisk::with(['perlakuanRisiko.rencanaPerlakuan', 'perlakuanRisiko.jenisPerlakuan'])->where('risk_id', $id)->first();

        // PASSING CONTROL RISK ID
        $this->controlRisk_id = $controlRisk->controlRisk_id;

        // PASSING PERLAKUAN ID
        $this->perlakuanRisiko_id = $controlRisk->perlakuanRisiko->first()->perlakuanRisiko_id;

        // PASSING PLANS
        $this->plans = [];
        foreach ($controlRisk->perlakuanRisiko as $perlakuan) {
            foreach ($perlakuan->rencanaPerlakuan as $rencana) {
                $this->plans[] = [
                    'desc'  => $rencana->rencanaPerlakuan_desc,
                    'id'    => $rencana->rencanaPerlakuan_id
                ];
            }
        }
        
        // PSSING RTM
        $this->rtm                  = $controlRisk->controlRisk_RTM;

        // PASSING JENIS PERLAKUAN
        $this->jenisPerlakuan_id    = $controlRisk->perlakuanRisiko->first()->jenisPerlakuan_id;
        $this->jenisPerlakuan_desc  = $controlRisk->perlakuanRisiko->first()->jenisPerlakuan->jenisPerlakuan_desc;

        // OPEN MODAL
        $this->openModalRencanaPerlakuan();
    }

    // LOCK RENCANA PERLAKUAN RISIKO
    public function lockRencanaPerlakuan()
    {
        // LOCK ALL PHASE RISK
        // FIND RISK
        $risk = Risk::find($this->risk_id);
        $risk->update(['risk_allPhaseLockStatus' => 1]);

        // FIND CONTROL RISK ID
        $controlRisk = ControlRisk::with(['perlakuanRisiko.rencanaPerlakuan'])->where('risk_id', $this->risk_id)->first();

        // UPDATE LOCK STATUS RENCANA PERLAKUAN RISIKO
        $this->perlakuanRisiko_id = $controlRisk->perlakuanRisiko->first()->perlakuanRisiko_id;

        $perlakuanRisiko = PerlakuanRisiko::find($this->perlakuanRisiko_id);
        $perlakuanRisiko->update(['perlakuanRisiko_lockStatus'   => 1]);

        // close modal
        $this->closeModalConfirmRencanaPerlakuan();

        // send notification success
        flash()
            ->option('position', 'bottom-right')
            ->option('timeout', 3000)
            ->success('Data Anda telah disimpan!');
    }

    // LIFE CYCLE HOOKS RENCANA PERLAKUAN
    // OPEN MODAL LIVEWIRE 
    public $isOpenRencanaPerlakuan              = 0;
    public $isOpenConfirmRencanaPerlakuan       = 0;
    public $isOpenConfirmDeleteRencanaPerlakuan = 0;

    public $isEditRencanaPerlakuan, $isShowRencanaPerlakuan;

    // DYNAMIC PLANS RENCANA PERLAKUAN
    public $plans = [];

    // ADD RENCANA PERLAKUAN RISIKO
    public function addPlan()
    {
        $validated = $this->validate([
            'rencanaPerlakuan_desc' => ['required'],
        ], [
            'rencanaPerlakuan_desc.required' => 'Rencana Perlakuan wajib diisi!',
        ]);

        $this->plans[] = ['desc' => $this->rencanaPerlakuan_desc, 'id' => null];
        $this->rencanaPerlakuan_desc = '';
    }

    // REMOVE RENCANA PERLAKUAN RISIKO
    public function removePlan($index)
    {
        unset($this->plans[$index]);
        $this->plans = array_values($this->plans); // Re-index the array
    }

    // OPEN MODAL RENCANA PERLAKUAN
    public function openModalRencanaPerlakuan()
    {
        $this->isOpenRencanaPerlakuan = true;
    }

    // CLOSE MODAL RENCANA PERLAKUAN
    public function closeModalRencanaPerlakuan()
    {
        $this->isOpenRencanaPerlakuan = false;

        // IS SHOW
        $this->isShowRencanaPerlakuan = false;

        // IS EDIT
        $this->isEditRencanaPerlakuan = false;

        // Reset form fields and close the modal
        $this->resetFormRencanaPerlakuan();

        // Reset form validation
        $this->resetValidation();
    } 

    // CLOSE MODAL RENCANA PERLAKUAN
    public function closeXModalRencanaPerlakuan()
    {
        $this->isOpenRencanaPerlakuan = false;

        // IS SHOW
        $this->isShowRencanaPerlakuan = false;

        // IS EDIT
        $this->isEditRencanaPerlakuan = false;

        // Reset form fields and close the modal
        $this->resetFormRencanaPerlakuan();

        // Reset form validation
        $this->resetValidation();
    } 

    // OPEN MODAL CONFIRM RENCANA PERLAKUAN
    public function openModalConfirmRencanaPerlakuan($id)
    {
        // SET KPI
        $this->risk_id                          = $id;

        $this->isOpenConfirmRencanaPerlakuan    = true;
    }

    // CLOSE MODAL CONFIRM RENCANA PERLAKUAN
    public function closeModalConfirmRencanaPerlakuan()
    {
        $this->isOpenConfirmRencanaPerlakuan = false;
    } 
  
    // CLOSE MODAL CONFIRM RENCANA PERLAKUAN
    public function closeXModalConfirmRencanaPerlakuan()
    {
        $this->isOpenConfirmRencanaPerlakuan = false;
    } 
    
    // OPEN MODAL CONFIRM DELETE RENCANA PERLAKUAN
    public function openModalConfirmDeleteRencanaPerlakuan($id)
    {
        $this->closeModalRencanaPerlakuan();

        // SET KPI
        $this->risk_id = $id;

        $this->isOpenConfirmDeleteRencanaPerlakuan = true;
    }

    // CLOSE MODAL CONFIRM DELETE RENCANA PERLAKUAN
    public function closeModalConfirmDeleteRencanaPerlakuan()
    {
        $this->isOpenConfirmDeleteRencanaPerlakuan = false;
    } 

    // CLOSE MODAL CONFIRM DELETE RENCANA PERLAKUAN
    public function closeXModalConfirmDeleteRencanaPerlakuan()
    {
        $this->isOpenConfirmDeleteRencanaPerlakuan = false;
    } 

    // RESET FORM RENCANA PERLAKUAN
    public function resetFormRencanaPerlakuan()
    {
        $this->risk_id                      = '';
        $this->controlRisk_id               = '';
        $this->perlakuanRisiko_id           = '';
        $this->isEditRencanaPerlakuan       = false;
        $this->isShowRencanaPerlakuan       = false;

        // // CLEAR DATA KRITERIA
        $this->jenisPerlakuan_id            = '';
        // $this->rencanaPerlakuan_desc        = '';
        $this->plans                        = [];        
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
