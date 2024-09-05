<?php

namespace App\Livewire\RiskOfficer\RiskControl;

use App\Models\ControlRisk;
use App\Models\DerajatRisiko;
use App\Models\DetailEfektifitasKontrol;
use App\Models\EfektifitasKontrol;
use App\Models\JenisPerlakuan;
use App\Models\KonteksRisiko;
use App\Models\KPI;
use App\Models\Komunikasi;
use App\Models\KomunikasiStakeholder;
use App\Models\Konsultasi;
use App\Models\KonsultasiStakeholder;
use App\Models\KriteriaDampak;
use App\Models\KriteriaDeteksiKegagalan;
use App\Models\KriteriaKemungkinan;
use App\Models\PemantauanTinjauan;
use App\Models\PerlakuanRisiko;
use App\Models\PenilaianEfektifitas;
use App\Models\RencanaPerlakuan;
use App\Models\RaciModel;
use App\Models\Risk;
use App\Models\Stakeholders;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class RiskControlOf extends Component
{
    use WithPagination, WithFileUploads;

    // TITLE COMPONENT
    #[Title('Risk Control')]
    public $title;
    public $title2 = 'Kontrol Perlakuan Risiko';

    // GLOBAL VARIABLES
    public $perPage     = 5;
    public $searchControlRisk  = '';
    public $searchPeriod = '';

    public $years = [];

    // VARIABLES LIST KPI MODEL
    public $kpi_id, $kpi, $kpi_kode, $kpi_nama, $unit_nama, $user_pemilik;

    // VARIABLES MODEL KONTEKS
    public $konteks, $konteks_id, $konteks_kode, $konteks_kategori, $konteks_desc;

    // VARIABLES MODEL KONTEKS
    public $risk_kode;

    // VARIABLE KEY
    public $role, $encryptedRole, $encryptedKPI, $encryptedKonteks, $encryptedRisk;

    // VARIABLE TOOGLE TABS
    public $tabActive                           = 'kontrolPerlakuanRisikoContent';
    public $showKontrolPerlakuanRisikoContent   = true;
    public $showEvaluasiKontrolRisikoContent    = false;
    public $showPemantauanTinjauanContent       = false;
    public $showRACIContent                     = false;
    public $showKomunikasiKonsultasiContent     = false;

    // TOGGLE TAB
    public function toggleTab($tab)
    {
        // DYNAMIC TAB
        $this->tabActive                                = $tab;
        $this->showKontrolPerlakuanRisikoContent        = $tab === 'kontrolPerlakuanRisikoContent';
        $this->showEvaluasiKontrolRisikoContent         = $tab === 'evaluasiKontrolRisikoContent';
        $this->showPemantauanTinjauanContent            = $tab === 'pemantauanTinjauanContent';
        $this->showRACIContent                          = $tab === 'RACIContent';
        $this->showKomunikasiKonsultasiContent          = $tab === 'komunikasiKonsultasiContent';

        // DYNAMIC TITLE
        if($tab === 'evaluasiKontrolRisikoContent'){
            $this->title2 = 'Evaluasi Kontrol Risiko';
        }elseif($tab === 'pemantauanTinjauanContent'){
            $this->title2 = 'Pemantauan dan Tinjauan';
        }elseif($tab === 'RACIContent'){
            $this->title2 = 'RACI';
        }elseif($tab === 'komunikasiKonsultasiContent'){
            $this->title2 = 'Komunikasi dan Konsultasi';
        }else{
            $this->title2 = 'Kontrol Perlakuan Risiko';   
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

        // RETRIVE KONTEKS ID
        $this->encryptedKonteks = Crypt::encryptString($this->konteks_id);
        
        // FIND UNIT SPECIFIC
        $this->konteks = KonteksRisiko::where('konteks_id', $this->konteks_id)->first();

        // SET KONTEKS KODE AND KONTEKS DESC
        $this->konteks_kode = $this->konteks->konteks_kode;
        $this->konteks_desc = $this->konteks->konteks_desc;

         // RECEIVE KONTEKS ID
        $encryptedRisk      = request()->query('risk');
        $this->risk_id      = Crypt::decryptString($encryptedRisk);
        
        // RETRIVE KONTEKS ID
        $this->encryptedKonteks = Crypt::encryptString($this->konteks_id);

        // PASSING DATA JENIS PERLAKUAN
        $this->jenisPerlakuans  = JenisPerlakuan::where('jenisPerlakuan_activeStatus', true)->get();

        // PASSING DATA STAKEHOLDERS
        $this->stakeholders = Stakeholders::where('stakeholder_activeStatus', true)->get();
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
        // CONTROL RISK DATA
        $controlRisks = Risk::with([
                'dampak', 
                'kemungkinan', 
                'deteksiKegagalan', 
                'controlRisk.dampak', 
                'controlRisk.kemungkinan', 
                'controlRisk.deteksiKegagalan', 
                'controlRisk.perlakuanRisiko.jenisPerlakuan', 
                'controlRisk.perlakuanRisiko.rencanaPerlakuan',
                'controlRisk.perlakuanRisiko.pemantauanKajian',
                'controlRisk.derajatRisiko.seleraRisiko',
                'efektifitasKontrol',
                'controlRisk.raci',
                'raci',
            ])
            ->where('risk_id', $this->risk_id)
            ->where('risk_validateRiskRegister', 1)
            ->where('konteks_id', $this->konteks_id)->search($this->searchControlRisk)
            ->orderBy($this->orderByControlRisk, $this->orderAscControlRisk ? 'asc' : 'desc')
            ->paginate($this->perPage);


        // EVALUASI RISIKO DATA
        $evaluasis = Risk::whereHas('controlRisk', function ($query) {
            $query->where('controlRisk_lockStatus', true);
        })
        ->with([
            'controlRisk.derajatRisiko.seleraRisiko',
            'controlRisk.perlakuanRisiko.rencanaPerlakuan',
        ])
        ->where('konteks_id', $this->konteks_id)
        ->where('risk_lockStatus', true)
        ->where('risk_kriteriaLockStatus', true)
        ->search($this->searchControlRisk)
        ->orderByControlRiskRPN($this->orderAscControlRisk ? 'desc' : 'asc')
        ->paginate($this->perPage);


        // PEMANTAUAN TINJAUAN DATA
        $pemantauanTinjauans = Risk::whereHas('controlRisk', function ($query) {
                $query->where('controlRisk_lockStatus', true);
            })
            ->with([
                'dampak', 
                'kemungkinan', 
                'deteksiKegagalan', 
                'controlRisk.dampak', 
                'controlRisk.kemungkinan', 
                'controlRisk.deteksiKegagalan', 
                'controlRisk.perlakuanRisiko.jenisPerlakuan', 
                'controlRisk.perlakuanRisiko.rencanaPerlakuan',
                'controlRisk.perlakuanRisiko.pemantauanKajian',
                'controlRisk.derajatRisiko.seleraRisiko',
                'efektifitasKontrol',
                'controlRisk.raci',
                'raci',
            ])
            ->where('risk_id', $this->risk_id)
            ->where('risk_validateRiskRegister', 1)
            ->where('konteks_id', $this->konteks_id)->search($this->searchControlRisk)
            ->orderBy($this->orderByControlRisk, $this->orderAscControlRisk ? 'asc' : 'desc')
            ->paginate($this->perPage);

        
        // RACI DATA
        $racis = Risk::whereHas('controlRisk', function ($query) {
                $query->where('controlRisk_lockStatus', true)
                    ->whereHas('perlakuanRisiko', function ($query) {
                        $query->where('pemantauanKajian_lockStatus', true);
                    });
            })
            ->with([
                'dampak', 
                'kemungkinan', 
                'deteksiKegagalan', 
                'controlRisk.dampak', 
                'controlRisk.kemungkinan', 
                'controlRisk.deteksiKegagalan', 
                'controlRisk.perlakuanRisiko.jenisPerlakuan', 
                'controlRisk.perlakuanRisiko.rencanaPerlakuan',
                'controlRisk.perlakuanRisiko.pemantauanKajian',
                'controlRisk.derajatRisiko.seleraRisiko',
                'efektifitasKontrol',
                'controlRisk.raci',
                'raci',
            ])
            ->where('risk_id', $this->risk_id)
            ->where('risk_validateRiskRegister', 1)
            ->where('konteks_id', $this->konteks_id)->search($this->searchControlRisk)
            ->orderBy($this->orderByControlRisk, $this->orderAscControlRisk ? 'asc' : 'desc')
            ->paginate($this->perPage);

        // KOMUNIKASI DATA
        $komunikasis = Komunikasi::with([
            'komunikasiStakeholder.stakeholder',
        ])
        ->where('risk_id', $this->risk_id)
        ->search($this->searchControlRisk)
        ->paginate($this->perPage);

        // KONSULTASI DATA
        $konsultasis = Konsultasi::with([
            'konsultasiStakeholder.stakeholder',
        ])
        ->where('risk_id', $this->risk_id)
        ->search($this->searchControlRisk)
        ->paginate($this->perPage);

        return view('livewire.pages.risk-owner.risk-control.risk-control.risk-control-content', [
            'controlRisks'                 => $controlRisks,
            'paginationInfocontrolRisks'   => $controlRisks->total() > 0
            ? "Showing " . ($controlRisks->firstItem()) . " to " . ($controlRisks->lastItem()) . " of " . ($controlRisks->total()) . " entries"
            : "No entries found",
            'evaluasis'                 => $evaluasis,
            'paginationInfoEvaluasis'   => $evaluasis->total() > 0
            ? "Showing " . ($evaluasis->firstItem()) . " to " . ($evaluasis->lastItem()) . " of " . ($evaluasis->total()) . " entries"
            : "No entries found",
            'pemantauanTinjauans'                 => $pemantauanTinjauans,
            'paginationInfoPemantauanTinjauans'   => $pemantauanTinjauans->total() > 0
            ? "Showing " . ($pemantauanTinjauans->firstItem()) . " to " . ($pemantauanTinjauans->lastItem()) . " of " . ($pemantauanTinjauans->total()) . " entries"
            : "No entries found",
            'racis'                 => $racis,
            'paginationInfoRacis'   => $racis->total() > 0
            ? "Showing " . ($racis->firstItem()) . " to " . ($racis->lastItem()) . " of " . ($racis->total()) . " entries"
            : "No entries found",
            'komunikasis'                  => $komunikasis,
            'paginationInfoKomunikasis'    => $komunikasis->total() > 0
            ? "Showing " . ($komunikasis->firstItem()) . " to " . ($komunikasis->lastItem()) . " of " . ($komunikasis->total()) . " entries"
            : "No entries found",
            'konsultasis'                  => $konsultasis,
            'paginationInfoKonsultasis'    => $konsultasis->total() > 0
            ? "Showing " . ($konsultasis->firstItem()) . " to " . ($konsultasis->lastItem()) . " of " . ($konsultasis->total()) . " entries"
            : "No entries found",
        ]);
    }

    /**
     * ANALISIS FUNCTIONS
     *
     */

    // VARIABLES ANALISIS
    public $analisis, $lastAnalisis;
    // VARIABLES ANALISIS
    public $controlRisk_id, $kemungkinan_id, $dampak_id, $deteksiKegagalan_id, $derajatRisiko_id;
    public $dataKemungkinan, $dataDampak, $dataDeteksi;

    // VARIABLES EFEKTIFITAS KONTROL
    public $penilaianEfektifitas, 
           $efektifitasKontrol_id, 
           $efektifitasKontrol_kontrolStatus, 
           $efektifitasKontrol_kontrolDesc, 
           $efektifitasKontrol_totalEfektifitas = 0;

    public $penilaianEfektifitas_id = [], 
           $penilaianEfektifitas_jawaban, 
           $penilaianEfektifitas_skor = [];

    
    // VALIDATE INPUT CONTROL RISK
    protected function validatingInputsControlRisk()
    {
        $rules = [
            // Validation rules for input control risk
            'kemungkinan_id'                 => ['required'],
            'dampak_id'                      => ['required'],
            'deteksiKegagalan_id'            => ['required'],

            // Validation rules for effectiveness control
            'efektifitasKontrol_kontrolStatus' => ['required'],
            'efektifitasKontrol_kontrolDesc'   => ['required'],

            // Validation rules for risk treatment plan
            'jenisPerlakuan_id'              => ['required'],
            'plans'                          => ['required'],
        ];

        $messages = [
            // Messages for input control risk
            'kemungkinan_id.required'        => 'Kriteria Kemungkinan wajib diisi!',
            'dampak_id.required'             => 'Kriteria Dampak wajib diisi!',
            'deteksiKegagalan_id.required'   => 'Kriteria Deteksi Kegagalan  wajib diisi!',

            // Messages for effectiveness control
            'efektifitasKontrol_kontrolStatus.required' => 'Pengendalian Efektifitas wajib diisi!',
            'efektifitasKontrol_kontrolDesc.required'   => 'Uraian Pengendalian wajib diisi!',

            // Messages for risk treatment plan
            'jenisPerlakuan_id.required'     => 'Jenis Perlakuan wajib diisi!',
            'plans.required'                 => 'Rencana Perlakuan wajib diisi!',
        ];

        // Add dynamic validation for penilaianEfektifitas_skor if applicable
        if (!empty($this->penilaianEfektifitas)) {
            foreach ($this->penilaianEfektifitas as $index => $item) {
                $rules["penilaianEfektifitas_skor.$index"] = ['required'];
                $messages["penilaianEfektifitas_skor.$index.required"] = "Pilihan Jawaban untuk pertanyaan ke-" . ($index + 1) . " wajib diisi!";
            }
        }

        // Validate using the consolidated rules and messages
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

    // VARIABLES RENCANA PERLAKUAN
    public $rencanaPerlakuan_id, 
           $rtm, 
           $perlakuanRisiko_id, 
           $jenisPerlakuan_id, 
           $jenisPerlakuan_desc, 
           $jenisPerlakuans, 
           $rencanaPerlakuan_desc;

    
    /**
     * CONTROL RISKS FUNCTIONS
     *
     */

    // VARIABLES CONTROL RISIKO
    public $risk_id, $risk_riskDesc, $risk_penyebab, $risk_spesific;

    // STORE NEW DATA CONTROL RISIKO
    public function storeControlRisk()
    {    
        // SANITIZE INPUTS

        // VALIDATING DATA INPUTS
        $this->validatingInputsControlRisk();
        

        // STORE ANALISIS RISK

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
            'seleraRisiko_id'       => $data[0]['seleraRisiko_id'] ?? 0,
            'created_by'            => Auth::user()->user_id,
            'updated_by'            => Auth::user()->user_id,
        ]);

        ///////////////////////////////////////////

        // STORE EFEKTIFITAS

        // FIND OR CREATE EFEKTIFITAS KONTROL
        $efektifitasKontrol = EfektifitasKontrol::updateOrCreate(
            [
                'risk_id'                             => $this->risk_id,
                'controlRisk_id'                      => $controlRisk->controlRisk_id,
            ],
            [
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
                        'efektifitasKontrol_id'   => $this->efektifitasKontrol_id,
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

        //////////////////////////////////////////


        // STORE RENCANA PERLAKUAN RISIKO

        // STORE PERLAKUAN RISIKO
        $perlakuanRisiko = PerlakuanRisiko::updateOrCreate(
            [
                'perlakuanRisiko_id' => $this->perlakuanRisiko_id,
                'controlRisk_id'     => $controlRisk->controlRisk_id ?? $this->controlRisk_id,
            ],
            [
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

        /////////////////////////////////////////

        // close modal
        $this->closeModalControlRisk();

        // Reset form fields and close the modal
        $this->resetFormControlRisk();

        // send notification success
        flash()
            ->option('position', 'bottom-right')
            ->option('timeout', 3000)
            ->success('Data Anda telah disimpan!');
    }

    // DYNAMIC PLAN RENCANA PERLAKUAN
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

    // EDIT CONTROL RISIKO
    public function editControlRisk($id, $controlRisk_id)
    {
        // OPEN MODAL
        $this->openModalControlRisk();

        // IS SHOW
        $this->isShowControlRisk = false;

        // IS EDIT
        $this->isEditControlRisk = true;

        // FIND RISK
        $risk                   = Risk::with(['kemungkinan', 'dampak', 'deteksiKegagalan'])->where('risk_id', $id)->first();

        // PASSING DATA KRITERIA
        $this->dataKemungkinan  = $risk->kemungkinan;
        $this->dataDampak       = $risk->dampak;
        $this->dataDeteksi      = $risk->deteksiKegagalan;

        // PASSING PENILAIAN EFEKTIFITAS
        $this->penilaianEfektifitas     = PenilaianEfektifitas::where('penilaianEfektifitas_activeStatus', true)->get();

        $this->penilaianEfektifitas_id  = PenilaianEfektifitas::where('penilaianEfektifitas_activeStatus', true)
                                            ->pluck('penilaianEfektifitas_id');

        // FIND CONTROL RISK ID
        $controlRisk = ControlRisk::with(['perlakuanRisiko.rencanaPerlakuan'])->find($controlRisk_id);

        // PASSING CONTROL RISK ID
        $this->controlRisk_id       = $controlRisk->controlRisk_id;
        // PASSING ID KRITERIA
        $this->kemungkinan_id       = $controlRisk->kemungkinan_id;
        $this->dampak_id            = $controlRisk->dampak_id;
        $this->deteksiKegagalan_id  = $controlRisk->deteksiKegagalan_id;

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
        
    }

    // SHOW CONTROL RISIKO
    public function showControlRisk($id, $controlRisk_id)
    {
        // OPEN MODAL
        $this->openModalControlRisk();

        // IS SHOW
        $this->isShowControlRisk = true;

        // IS EDIT
        $this->isEditControlRisk = false;

        // FIND risk
        $risk = Risk::find($id);
        
        // PASSING KPI
        $this->risk_id          = $risk->risk_id;
        $this->risk_riskDesc    = $risk->risk_riskDesc;
        $this->risk_penyebab    = $risk->risk_penyebab;


        // FIND CONTROL RISK ID
        $controlRisk = ControlRisk::with(['kemungkinan', 'dampak', 'deteksiKegagalan'])->where('controlRisk_id', $controlRisk_id)->first();

        // PASSING LAST ANALISIS
        $this->lastAnalisis        = $controlRisk;
        // PASSING CONTROL RISK ID
        $this->controlRisk_id       = $controlRisk->controlRisk_id;
        // PASSING ID KRITERIA
        $this->kemungkinan_id       = $controlRisk->kemungkinan_id;
        $this->dampak_id            = $controlRisk->dampak_id;
        $this->deteksiKegagalan_id  = $controlRisk->deteksiKegagalan_id;


        // RENCANA PERLAKUAN RISIKO
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
        
    }

    // LOCK CONTROL RISIKO
    public function lockControlRisk()
    {
        // FIND CONTROL RISK ID
        $controlRisk = ControlRisk::with(['perlakuanRisiko.rencanaPerlakuan'])->find($this->controlRisk_id);

        // UPDATE LOCK STATUS CONTROL RISK
        $controlRisk->update(['controlRisk_lockStatus'   => 1]);

        // FIND EFEKTIFITAS CONTROL ID
        $efekifitasKontrol = EfektifitasKontrol::where('controlRisk_id', $this->controlRisk_id)->first();

        // UPDATE LOCK STATUS EFEKTIFITAS CONTROL
        $efekifitasKontrol->update(['efektifitasKontrol_lockStatus'   => 1]);

        // UPDATE LOCK STATUS RENCANA PERLAKUAN RISIKO
        $this->perlakuanRisiko_id = $controlRisk->perlakuanRisiko->first()->perlakuanRisiko_id;

        $perlakuanRisiko = PerlakuanRisiko::find($this->perlakuanRisiko_id);
        $perlakuanRisiko->update(['perlakuanRisiko_lockStatus'   => 1]);

        // close modal
        $this->closeModalConfirmControlRisk();

        // send notification success
        flash()
            ->option('position', 'bottom-right')
            ->option('timeout', 3000)
            ->success('Data Anda telah disimpan!');
    }

    // DELETE CONTROL RISIKO
    public function deleteControlRisk()
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

    // SORTING DATA CONTROL RISIKO
    public $orderByControlRisk     = 'risk_id';
    public $orderAscControlRisk    = true;
    public function doSortRisk($column)
    {
        if ($this->orderByControlRisk === $column) {
            $this->orderAscControlRisk = !$this->orderAscControlRisk; // Toggle the sorting order
        } else {
            $this->orderByControlRisk = $column;
            $this->orderAscControlRisk = true; // Default sorting order when changing the column
        }
    }
 
    // LIFE CYCLE HOOKS CONTROL RISIKO
    // OPEN MODAL LIVEWIRE 
    public $isOpenControlRisk = 0;
    public $isOpenConfirmControlRisk = 0;
    public $isOpenConfirmDeleteControlRisk = 0;

    public $isEditControlRisk, $isShowControlRisk;

    // CREATE CONTROL RISIKO
    public function createControlRisk()
    {
        // IS SHOW
        $this->isShowControlRisk = false;

        // IS EDIT
        $this->isEditControlRisk = false;

        // FIND RISK
        $risk                   = Risk::with(['kemungkinan', 'dampak', 'deteksiKegagalan'])->where('risk_id', $this->risk_id)->first();

        // PASSING DATA KRITERIA
        $this->dataKemungkinan  = $risk->kemungkinan;
        $this->dataDampak       = $risk->dampak;
        $this->dataDeteksi      = $risk->deteksiKegagalan;

        // PASSING PENILAIAN EFEKTIFITAS
        $this->penilaianEfektifitas     = PenilaianEfektifitas::where('penilaianEfektifitas_activeStatus', true)->get();

        $this->penilaianEfektifitas_id  = PenilaianEfektifitas::where('penilaianEfektifitas_activeStatus', true)
                                            ->pluck('penilaianEfektifitas_id');
        $this->openModalControlRisk();
    }

    // OPEN MODAL CONTROL RISIKO
    public function openModalControlRisk()
    {
        $this->isOpenControlRisk = true;

        $this->resetFormControlRisk();
    }

    // CLOSE MODAL CONTROL RISIKO
    public function closeModalControlRisk()
    {
        $this->isOpenControlRisk = false;

        // IS SHOW
        $this->isShowControlRisk = false;

        // IS EDIT
        $this->isEditControlRisk = false;

        // Reset form fields and close the modal
        $this->resetFormControlRisk();

        // Reset form validation
        $this->resetValidation();
    } 

    // CLOSE MODAL CONTROL RISIKO
    public function closeXModalControlRisk()
    {
        $this->isOpenControlRisk = false;

        // IS SHOW
        $this->isShowControlRisk = false;

        // IS EDIT
        $this->isEditControlRisk = false;

        // Reset form fields and close the modal
        $this->resetFormControlRisk();

        // Reset form validation
        $this->resetValidation();
    } 

    // OPEN MODAL CONFIRM CONTROL RISIKO
    public function openModalConfirmControlRisk($id, $controlRisk_id)
    {
        // SET RISK ID
        $this->risk_id       = $id;

        // SET CONTROL RISK ID
        $this->controlRisk_id = $controlRisk_id;

        $this->isOpenConfirmControlRisk    = true;
    }

    // CLOSE MODAL CONFIRM CONTROL RISIKO
    public function closeModalConfirmControlRisk()
    {
        $this->isOpenConfirmControlRisk = false;

        $this->resetFormControlRisk();
    } 
  
    // CLOSE MODAL CONFIRM CONTROL RISIKO
    public function closeXModalConfirmControlRisk()
    {
        $this->isOpenConfirmControlRisk = false;

        $this->resetFormControlRisk();
    } 

    // OPEN MODAL CONFIRM DELETE CONTROL RISIKO
    public function openModalConfirmDeleteControlRisk($id)
    {
        $this->closeModalRisk();

        // SET KPI
        $this->risk_id = $id;

        $this->isOpenConfirmDeleteControlRisk = true;
    }

    // CLOSE MODAL CONFIRM DELETE CONTROL RISIKO
    public function closeModalConfirmDeleteControlRisk()
    {
        $this->isOpenConfirmDeleteControlRisk = false;
    } 

    // CLOSE MODAL CONFIRM DELETE CONTROL RISIKO
    public function closeXModalConfirmDeleteControlRisk()
    {
        $this->isOpenConfirmDeleteControlRisk = false;
    } 

    // RESET FORM CONTROL RISIKO
    public function resetFormControlRisk()
    {
        $this->isEditControlRisk     = false;

        // CLEAR CONTROL RISK
        $this->controlRisk_id       = '';

        // CLEAR PERLAKUAN RISIKO ID
        $this->perlakuanRisiko_id   = '';

        // ANALISIS CLEAR
        // CLEAR DATA KRITERIA
        $this->kemungkinan_id       = '';
        $this->dampak_id            = '';
        $this->deteksiKegagalan_id  = ''; 

        // CLEAR DATA EFEKTIFITAS
        $this->efektifitasKontrol_kontrolStatus     = '';
        $this->efektifitasKontrol_kontrolDesc       = '';
        $this->efektifitasKontrol_id                = '';
        $this->penilaianEfektifitas_skor            = [];
        $this->efektifitasKontrol_totalEfektifitas  = 0;

        // RENCANA PERLAKUAN CLEAR
        // CLEAR DATA KRITERIA
        $this->jenisPerlakuan_id            = '';
        // $this->rencanaPerlakuan_desc        = '';
        $this->plans                        = [];  
    }

    // CLEAR SEARCH CONTROL RISIKO
    public function clearSearchControlRisk()
    {
        $this->searchControlRisk = '';
    }


    /**
     * PEMANTAUAN TINJAUAN FUNCTIONS
     *
     */

    // VARIABLES PEMANTAUAN TINJAUAN
    public $pemantauanTinjauan;
    
    // CREATE KEMUNGKINAN PEMANTAUAN TINJAUAN
    public function createPemantauanTinjauan($id)
    {
        // IS SHOW
        $this->isShowPemantauanTinjauan = false;

        // IS EDIT
        $this->isEditPemantauanTinjauan = false;

        // FIND RISK
        $risk                   = Risk::with(['kemungkinan', 'dampak', 'deteksiKegagalan'])->find($this->risk_id);

        // SET RISK DESC
        $this->risk_riskDesc    = $risk->risk_riskDesc;
        $this->risk_penyebab    = $risk->risk_penyebab;

        // FIND CONTROL RISK ID
        $controlRisk = ControlRisk::with(['perlakuanRisiko.rencanaPerlakuan'])->find($id);
        
        // RENCANA PERLAKUAN RISIKO
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

        // SET CONTROL RISK ID
        $this->controlRisk_id   = $controlRisk->controlRisk_id;

        // OPEN MODAL PEMANTAUAN TINJAUAN
        $this->openModalPemantauanTinjauan();
    }

    // VARIABLES PEMANTAUAN TINJAUAN
    public $pemantauanTinjauan_id, 
           $pemantauanTinjauan_pemantauanDesc, 
           $pemantauanTinjauan_tinjauanDesc, 
           $pemantauanTinjauan_buktiPemantauan,
           $pemantauanTinjauan_buktiTinjauan, 
           $pemantauanTinjauan_freqPemantauan, 
           $pemantauanTinjauan_freqPelaporan,
           $bukti_pemantauan, 
           $bukti_tinjauan;

    // VALIDATE INPUT PEMANTAUAN TINJAUAN
    protected function validatingInputsPemantauanTinjauan()
    {
        $validated = $this->validate([
            'pemantauanTinjauan_pemantauanDesc'     => ['required'],
            'pemantauanTinjauan_tinjauanDesc'       => ['required'],
            'pemantauanTinjauan_buktiPemantauan'    => ['required', 'max:5048'], // Validate image
            'pemantauanTinjauan_buktiTinjauan'      => ['required', 'max:5048'], // Validate image
            'pemantauanTinjauan_freqPemantauan'     => ['required'],
            'pemantauanTinjauan_freqPelaporan'      => ['required'],
        ], [
            'pemantauanTinjauan_pemantauanDesc.required'    => 'Deskripsi Pemantauan wajib diisi!',
            'pemantauanTinjauan_tinjauanDesc.required'      => 'Deskripsi Tinjauan wajib diisi!',
            'pemantauanTinjauan_buktiPemantauan.required'   => 'Bukti Pemantauan wajib diisi!',
            'pemantauanTinjauan_buktiPemantauan.max'        => 'Bukti Pemantauan tidak boleh lebih dari 5MB!',
            'pemantauanTinjauan_buktiTinjauan.required'     => 'Bukti Tinjauan wajib diisi!',
            'pemantauanTinjauan_buktiTinjauan.max'          => 'Bukti Tinjauan tidak boleh lebih dari 5MB!',
            'pemantauanTinjauan_freqPemantauan.required'    => 'Frequensi Pemantauan wajib diisi!',
            'pemantauanTinjauan_freqPelaporan.required'     => 'Frequensi Pelaporan wajib diisi!',
        ]);
    }


    // STORE RACI
    public function storePemantauanTinjauan()
    {
        // VALIDATION INPUTS PEMANTAUAN TINJAUAN
        $this->validatingInputsPemantauanTinjauan();

        // FIND PERLAKUAN ID
        $perlakuanRisiko = PerlakuanRisiko::where('controlRisk_id', $this->controlRisk_id)->first();

        // Initialize paths for buktiPemantauan and buktiTinjauan
        $buktiPemantauanPath = $this->pemantauanTinjauan_buktiPemantauan instanceof \Illuminate\Http\UploadedFile
            ? Crypt::encrypt($this->pemantauanTinjauan_buktiPemantauan->storeAs('pemantauanTinjauan/dokumen', $this->pemantauanTinjauan_buktiPemantauan->getClientOriginalName(), 'public'))
            : $this->pemantauanTinjauan_buktiPemantauan; // Keep existing path if no new file uploaded

        $buktiTinjauanPath = $this->pemantauanTinjauan_buktiTinjauan instanceof \Illuminate\Http\UploadedFile
            ? Crypt::encrypt($this->pemantauanTinjauan_buktiTinjauan->storeAs('pemantauanTinjauan/dokumen', $this->pemantauanTinjauan_buktiTinjauan->getClientOriginalName(), 'public'))
            : $this->pemantauanTinjauan_buktiTinjauan; // Keep existing path if no new file uploaded

        // STORE PEMANTAUAN TINJAUAN
        $pemantauanTinjauan = PemantauanTinjauan::updateOrCreate([
            'perlakuanRisiko_id'    => $perlakuanRisiko->perlakuanRisiko_id,
            'pemantauanTinjauan_id' => $this->pemantauanTinjauan_id,
        ],[
            'pemantauanTinjauan_pemantauanDesc'     => $this->pemantauanTinjauan_pemantauanDesc,
            'pemantauanTinjauan_tinjauanDesc'       => $this->pemantauanTinjauan_tinjauanDesc,
            'pemantauanTinjauan_freqPemantauan'     => $this->pemantauanTinjauan_freqPemantauan,
            'pemantauanTinjauan_freqPelaporan'      => $this->pemantauanTinjauan_freqPelaporan,
            'pemantauanTinjauan_buktiPemantauan'    => $buktiPemantauanPath,
            'pemantauanTinjauan_buktiTinjauan'      => $buktiTinjauanPath,
            'created_by'                            => Auth::user()->user_id,
            'updated_by'                            => Auth::user()->user_id,
        ]);

        // CLOSE MODAL
        $this->closeModalPemantauanTinjauan();

        // send notification success
        flash()
            ->option('position', 'bottom-right')
            ->option('timeout', 3000)
            ->success('Data Anda telah disimpan!');
    }

    // EDIT RACI
    public function editPemantauanTinjauan($id)
    {
        // IS SHOW
        $this->isShowPemantauanTinjauan = false;

        // IS EDIT
        $this->isEditPemantauanTinjauan = true;

        // FIND RISK
        $risk                   = Risk::with(['kemungkinan', 'dampak', 'deteksiKegagalan'])->find($this->risk_id);

        // SET RISK DESC
        $this->risk_riskDesc    = $risk->risk_riskDesc;
        $this->risk_penyebab    = $risk->risk_penyebab;

        // PASSING CONTROL RISK ID
        $this->controlRisk_id   = $id;

        // FIND CONTROL RISK ID
        $controlRisk = ControlRisk::with(['perlakuanRisiko.rencanaPerlakuan', 'perlakuanRisiko.pemantauanTinjauan'])->find($id);
        
        // RENCANA PERLAKUAN RISIKO
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

        $pemantauanTinjauan = $controlRisk->perlakuanRisiko->first()->pemantauanTinjauan->first();

        $this->pemantauanTinjauan_id                = $pemantauanTinjauan->pemantauanTinjauan_id;
        $this->pemantauanTinjauan_pemantauanDesc    = $pemantauanTinjauan->pemantauanTinjauan_pemantauanDesc;
        $this->pemantauanTinjauan_tinjauanDesc      = $pemantauanTinjauan->pemantauanTinjauan_tinjauanDesc;
        $this->pemantauanTinjauan_freqPemantauan    = $pemantauanTinjauan->pemantauanTinjauan_freqPemantauan;
        $this->pemantauanTinjauan_freqPelaporan     = $pemantauanTinjauan->pemantauanTinjauan_freqPelaporan;

        // Decrypt and pass the paths
        $this->pemantauanTinjauan_buktiPemantauan   = $pemantauanTinjauan->pemantauanTinjauan_buktiPemantauan;
        $this->pemantauanTinjauan_buktiTinjauan     = $pemantauanTinjauan->pemantauanTinjauan_buktiTinjauan;

        // OPEN MODAL
        $this->openModalPemantauanTinjauan();
    }

    // SHOW RACI
    public function showPemantauanTinjauan($id)
    {
        // IS SHOW
        $this->isShowPemantauanTinjauan = true;

        // IS EDIT
        $this->isEditPemantauanTinjauan = false;

        // FIND RISK
        $risk                   = Risk::with(['kemungkinan', 'dampak', 'deteksiKegagalan'])->find($this->risk_id);

        // SET RISK DESC
        $this->risk_riskDesc    = $risk->risk_riskDesc;
        $this->risk_penyebab    = $risk->risk_penyebab;

        // PASSING CONTROL RISK ID
        $this->controlRisk_id   = $id;

        // FIND CONTROL RISK ID
        $controlRisk = ControlRisk::with(['perlakuanRisiko.rencanaPerlakuan', 'perlakuanRisiko.pemantauanTinjauan'])->find($id);
        
        // RENCANA PERLAKUAN RISIKO
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

        $pemantauanTinjauan = $controlRisk->perlakuanRisiko->first()->pemantauanTinjauan->first();

        $this->pemantauanTinjauan_id                = $pemantauanTinjauan->pemantauanTinjauan_id;
        $this->pemantauanTinjauan_pemantauanDesc    = $pemantauanTinjauan->pemantauanTinjauan_pemantauanDesc;
        $this->pemantauanTinjauan_tinjauanDesc      = $pemantauanTinjauan->pemantauanTinjauan_tinjauanDesc;
        $this->pemantauanTinjauan_freqPemantauan    = $pemantauanTinjauan->pemantauanTinjauan_freqPemantauan;
        $this->pemantauanTinjauan_freqPelaporan     = $pemantauanTinjauan->pemantauanTinjauan_freqPelaporan;

        // Decrypt and pass the paths
        $this->pemantauanTinjauan_buktiPemantauan   = $pemantauanTinjauan->pemantauanTinjauan_buktiPemantauan;
        $this->pemantauanTinjauan_buktiTinjauan     = $pemantauanTinjauan->pemantauanTinjauan_buktiTinjauan;

        // OPEN MODAL
        $this->openModalPemantauanTinjauan();
    }

    // DELETE RACI
    public function deletePemantauanTinjauan()
    {
        // FIND CONTROL RISK ID
        $controlRisk = ControlRisk::with(['perlakuanRisiko.pemantauanTinjauan'])->find($this->controlRisk_id);

        if ($controlRisk && $controlRisk->perlakuanRisiko->isNotEmpty()) {
            // Get the first associated pemantauanTinjauan
            $pemantauanTinjauan = $controlRisk->perlakuanRisiko->first()->pemantauanTinjauan->first();

            // Check if pemantauanTinjauan exists before attempting to delete
            if ($pemantauanTinjauan) {
                $pemantauanTinjauan->delete();
                // Optionally: You can add a success notification here
                flash()
                    ->option('position', 'bottom-right')
                    ->option('timeout', 3000)
                    ->success('Pemantauan Tinjauan has been deleted successfully!');
            } else {
                // Optionally: Handle the case where pemantauanTinjauan was not found
                flash()
                    ->option('position', 'bottom-right')
                    ->option('timeout', 3000)
                    ->error('Pemantauan Tinjauan not found!');
            }
        } else {
            // Optionally: Handle the case where controlRisk or perlakuanRisiko was not found
            flash()
                ->option('position', 'bottom-right')
                ->option('timeout', 3000)
                ->error('Control Risk or Perlakuan Risiko not found!');
        }

        // CLOSE MODAL CONFIRM DELETE PEMANTAUAN TINJAUAN
        $this->closeModalConfirmDeletePemantauanTinjauan();
    }

    // LOCK PEMANTAUAN TINJAUAN
    public function lockPemantauanTinjauan()
    {
        // FIND CONTROL RISK ID
        $controlRisk = ControlRisk::with(['perlakuanRisiko.pemantauanTinjauan'])->find($this->controlRisk_id);

        if ($controlRisk && $controlRisk->perlakuanRisiko->isNotEmpty()) {
            // Get the first associated pemantauanTinjauan
            $pemantauanTinjauan = $controlRisk->perlakuanRisiko->first();

            // Check if pemantauanTinjauan exists before attempting to update
            if ($pemantauanTinjauan) {
                $pemantauanTinjauan->update(['pemantauanKajian_lockStatus' => 1]);
                // Optionally: You can add a success notification here
                flash()
                    ->option('position', 'bottom-right')
                    ->option('timeout', 3000)
                    ->success('Pemantauan Tinjauan has been locked successfully!');
            } else {
                // Optionally: Handle the case where pemantauanTinjauan was not found
                flash()
                    ->option('position', 'bottom-right')
                    ->option('timeout', 3000)
                    ->error('Pemantauan Tinjauan not found!');
            }
        } else {
            // Optionally: Handle the case where controlRisk or perlakuanRisiko was not found
            flash()
                ->option('position', 'bottom-right')
                ->option('timeout', 3000)
                ->error('Control Risk or Perlakuan Risiko not found!');
        }

        // close modal
        $this->closeModalConfirmPemantauanTinjauan();
    }

    // LIFE CYCLE HOOKS PEMANTAUAN TINJAUAN
    // OPEN MODAL LIVEWIRE 
    public $isOpenPemantauanTinjauan              = 0;
    public $isOpenConfirmPemantauanTinjauan       = 0;
    public $isOpenConfirmDeletePemantauanTinjauan = 0;

    public $isEditPemantauanTinjauan, $isShowPemantauanTinjauan;

    // OPEN MODAL PEMANTAUAN TINJAUAN
    public function openModalPemantauanTinjauan()
    {
        $this->isOpenPemantauanTinjauan = true;
    }

    // CLOSE MODAL PEMANTAUAN TINJAUAN
    public function closeModalPemantauanTinjauan()
    {
        $this->isOpenPemantauanTinjauan = false;

        // IS SHOW
        $this->isShowPemantauanTinjauan = false;

        // IS EDIT
        $this->isEditPemantauanTinjauan = false;

        // Reset form fields and close the modal
        $this->resetFormPemantauanTinjauan();

        // Reset form validation
        $this->resetValidation();
    } 

    // CLOSE MODAL PEMANTAUAN TINJAUAN
    public function closeXModalPemantauanTinjauan()
    {
        $this->isOpenPemantauanTinjauan = false;

        // IS SHOW
        $this->isShowPemantauanTinjauan = false;

        // IS EDIT
        $this->isEditPemantauanTinjauan = false;

        // Reset form fields and close the modal
        $this->resetFormPemantauanTinjauan();

        // Reset form validation
        $this->resetValidation();
    } 

    // OPEN MODAL CONFIRM PEMANTAUAN TINJAUAN
    public function openModalConfirmPemantauanTinjauan($id)
    {
        // SET KPI
        $this->controlRisk_id                     = $id;

        $this->isOpenConfirmPemantauanTinjauan    = true;
    }

    // CLOSE MODAL CONFIRM PEMANTAUAN TINJAUAN
    public function closeModalConfirmPemantauanTinjauan()
    {
        $this->isOpenConfirmPemantauanTinjauan = false;
    } 
  
    // CLOSE MODAL CONFIRM PEMANTAUAN TINJAUAN
    public function closeXModalConfirmPemantauanTinjauan()
    {
        $this->isOpenConfirmPemantauanTinjauan = false;
    } 
    
    // OPEN MODAL CONFIRM DELETE PEMANTAUAN TINJAUAN
    public function openModalConfirmDeletePemantauanTinjauan($id)
    {
        $this->closeModalPemantauanTinjauan();

        // SET KPI
        $this->controlRisk_id = $id;

        $this->isOpenConfirmDeletePemantauanTinjauan = true;
    }

    // CLOSE MODAL CONFIRM DELETE PEMANTAUAN TINJAUAN
    public function closeModalConfirmDeletePemantauanTinjauan()
    {
        $this->isOpenConfirmDeletePemantauanTinjauan = false;
    } 

    // CLOSE MODAL CONFIRM DELETE PEMANTAUAN TINJAUAN
    public function closeXModalConfirmDeletePemantauanTinjauan()
    {
        $this->isOpenConfirmDeletePemantauanTinjauan = false;
    } 

    // CETAK PEMANTAUAN TINJAUAN
    public $isOpenCetakPemantauanTinjauan = 0;

    // OPEN CETAK PEMANTAUAN TINJAUAN
    public function openCetakPemantauanTinjauan($kpi_id)
    {
        $this->isOpenCetakPemantauanTinjauan = 1;

        $this->kpi_id  = $kpi_id;
    }

    // CLOSE CETAK PEMANTAUAN TINJAUAN
    public function closeCetakPemantauanTinjauan()
    {
        $this->isOpenCetakPemantauanTinjauan = 0;
    }
    // CLOSE CETAK PEMANTAUAN TINJAUAN
    public function closeXCetakPemantauanTinjauan()
    {
        $this->isOpenCetakPemantauanTinjauan = 0;
    }

    // PRINT PEMANTAUAN TINJAUAN
    public function printPemantauanTinjauan()
    {
        // KPIS FIND
        $kpis = KPI::with([
            'unit',
            'konteks.risk.controlRisk.perlakuanRisiko.jenisPerlakuan',
            'konteks.risk.controlRisk.derajatRisiko.seleraRisiko',
            'konteks.risk.controlRisk.perlakuanRisiko.rencanaPerlakuan',
            'konteks.risk.controlRisk.perlakuanRisiko.pemantauanTinjauan',
            'konteks.risk.controlRisk.efektifitasControl.detailEfektifitasKontrol',
        ])
        ->where('kpi_lockStatus', true)
        ->where('kpi_activeStatus', true)
        ->whereHas('konteks.risk', function ($query) {
            $query->where('risk_id', $this->risk_id);
        })
        ->find($this->kpi_id);
        
        // FIND UNIT
        // SET UNIT KPI AND PEMILIK KPI
        $this->unit_nama = $kpis->unit->unit_name;

        // FIND RISK OWNER
        $roleToFind         = "risk owner";
        $usersWithRole      = $this->searchUsersWithRole($kpis->unit->user, $roleToFind);
        $this->user_pemilik = ucwords($usersWithRole[0]->name);
        
        // Create Dompdf instance
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true); // Enable HTML5 parser
        $options->set('isPhpEnabled', true); // Enable PHP

        $formatPaper = 'landscape'; // Corrected spelling

        // Set the page size and margins
        $options->set('size', 'A4'); // Use standard A4 size
        $options->set('margin-left', 0);
        $options->set('margin-right', 0);
        $options->set('margin-top', 0);
        $options->set('margin-bottom', 0);

        // Create a new Dompdf instance
        $dompdf = new Dompdf($options);

        // Load the HTML view with the data
        $html = view('livewire.pages.risk-owner.risk-control.pemantauan-tinjauan.print-layout.pemantauan-tinjauan-layout', [
            'kpis'          => $kpis,
            'user_pemilik'  => $this->user_pemilik,
            'unit_nama'     => $this->unit_nama,
            'risk_id'       => $this->risk_id,
        ])->render();

        // Load the HTML into Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Set paper size and orientation
        $dompdf->setPaper('A4', $formatPaper);

        // Render the HTML as PDF
        $dompdf->render();

        // Get the PDF content as a string
        $pdfContent = $dompdf->output();

        // Return the PDF inline in the browser
        // return response($pdfContent)
        //     ->header('Content-Type', 'application/pdf')
        //     ->header('Content-Disposition', 'inline; filename="raci-' . $kpis->kpi_kode . '.pdf"');

        // CLOSE MODAL CETAK PEMANTAUAN TINJAUAN
        $this->closeCetakPemantauanTinjauan();

        // Option 1: Return the PDF as a download
        // Return the PDF content with appropriate headers
        return response()->streamDownload(function () use ($pdfContent) {
            echo $pdfContent;
        }, 'PEMANTAUAN-TINJAUAN-' . $kpis->kpi_kode . '.pdf');

    }

    // RESET FORM PEMANTAUAN TINJAUAN
    public function resetFormPemantauanTinjauan()
    {
        $this->isEditPemantauanTinjauan       = false;
        $this->isShowPemantauanTinjauan       = false;

        // CLEAR PEMANTAUAN TINJAUAN
        $this->pemantauanTinjauan_pemantauanDesc    = '';
        $this->pemantauanTinjauan_tinjauanDesc      = '';
        $this->pemantauanTinjauan_buktiPemantauan   = '';
        $this->pemantauanTinjauan_buktiTinjauan     = '';
        $this->pemantauanTinjauan_freqPemantauan    = '';
        $this->pemantauanTinjauan_freqPelaporan     = '';      
    }




     /**
     * RACI FUNCTIONS
     *
     */

    // VARIABLES RACI
    public $raci, $raci_id, $stakeholders;
    
    // CREATE RACI
    public function createRACI($id)
    {
        // IS SHOW
        $this->isShowRACI = false;

        // IS EDIT
        $this->isEditRACI = false;

        // FIND RISK
        $risk                   = Risk::with(['kemungkinan', 'dampak', 'deteksiKegagalan'])->find($this->risk_id);

        // SET RISK DESC
        $this->risk_riskDesc    = $risk->risk_riskDesc;
        $this->risk_penyebab    = $risk->risk_penyebab;

        // FIND CONTROL RISK ID
        $controlRisk = ControlRisk::with(['perlakuanRisiko.rencanaPerlakuan'])->find($id);

        // SET CONTROL RISK ID
        $this->controlRisk_id   = $controlRisk->controlRisk_id;

        // OPEN MODAL RACI
        $this->openModalRACI();
    }

    // VARIABLES RACI
    public $searchR = '';
    public $searchA = '';
    public $searchC = '';
    public $searchI = '';

    public $searchResultsR = [];
    public $searchResultsA = [];
    public $searchResultsC = [];
    public $searchResultsI = [];

    public $RACI_R = [];
    public $RACI_A = [];
    public $RACI_C = [];
    public $RACI_I = [];

    public $searchActiveR   = false; // New property to track if search is active
    public $searchActiveA   = false; // New property to track if search is active
    public $searchActiveC   = false; // New property to track if search is active
    public $searchActiveI   = false; // New property to track if search is active


    // SEARCH DYNAMIC RACI
    // SEARCH RESPONSIBLE
    public function updatedSearchR()
    {
        $this->searchResultsR = Stakeholders::where('stakeholder_activeStatus', true)
            ->where('stakeholder_jabatan', 'like', '%' . $this->searchR . '%')
            ->orWhere('stakeholder_singkatan', 'like', '%' . $this->searchR . '%')
            ->get();
    }

    // LOAD FIRST IF FOCUS ON RACI R
    public function activateSearchR()
    {
        $this->searchActiveR = true;

        $this->searchResultsR = Stakeholders::where('stakeholder_activeStatus', true)
                ->where(function ($query) {
                    $query->where('stakeholder_jabatan', 'like', '%' . $this->searchR . '%')
                          ->orWhere('stakeholder_singkatan', 'like', '%' . $this->searchR . '%');
                })
                ->get();
    }
 
    // CLEAR SEARCH RESULTS RACI R
    public function deactivateSearchR()
    {
        $this->searchActiveR = false;
        $this->searchResultsR = []; // Clear search results when deactivating
    }

    // SEARCH ACCOUNTABLE
    public function updatedSearchA()
    {
        $this->searchResultsA = Stakeholders::where('stakeholder_activeStatus', true)
            ->where('stakeholder_jabatan', 'like', '%' . $this->searchA . '%')
            ->orWhere('stakeholder_singkatan', 'like', '%' . $this->searchA . '%')
            ->get();
    }

    // LOAD FIRST IF FOCUS ON RACI A
    public function activateSearchA()
    {
        $this->searchActiveA = true;

        $this->searchResultsA = Stakeholders::where('stakeholder_activeStatus', true)
                ->where(function ($query) {
                    $query->where('stakeholder_jabatan', 'like', '%' . $this->searchA . '%')
                          ->orWhere('stakeholder_singkatan', 'like', '%' . $this->searchA . '%');
                })
                ->get();
    }
 
    // CLEAR SEARCH RESULTS RACI R
    public function deactivateSearchA()
    {
        $this->searchActiveA = false;
        $this->searchResultsA = []; // Clear search results when deactivating
    }

    // SEARCH CONSULTED
    public function updatedSearchC()
    {
        $this->searchResultsC = Stakeholders::where('stakeholder_activeStatus', true)
            ->where('stakeholder_jabatan', 'like', '%' . $this->searchC . '%')
            ->orWhere('stakeholder_singkatan', 'like', '%' . $this->searchC . '%')
            ->get();
    }

    // LOAD FIRST IF FOCUS ON RACI C
    public function activateSearchC()
    {
        $this->searchActiveC = true;

        $this->searchResultsC = Stakeholders::where('stakeholder_activeStatus', true)
                ->where(function ($query) {
                    $query->where('stakeholder_jabatan', 'like', '%' . $this->searchC . '%')
                          ->orWhere('stakeholder_singkatan', 'like', '%' . $this->searchC . '%');
                })
                ->get();
    }
 
    // CLEAR SEARCH RESULTS RACI R
    public function deactivateSearchC()
    {
        $this->searchActiveC = false;
        $this->searchResultsR = []; // Clear search results when deactivating
    }

    // SEARCH INFORMED
    public function updatedSearchI()
    {
        $this->searchResultsI = Stakeholders::where('stakeholder_activeStatus', true)
            ->where('stakeholder_jabatan', 'like', '%' . $this->searchI . '%')
            ->orWhere('stakeholder_singkatan', 'like', '%' . $this->searchI . '%')
            ->get();
    }

    // LOAD FIRST IF FOCUS ON RACI I
    public function activateSearchI()
    {
        $this->searchActiveI = true;

        $this->searchResultsI = Stakeholders::where('stakeholder_activeStatus', true)
                ->where(function ($query) {
                    $query->where('stakeholder_jabatan', 'like', '%' . $this->searchI . '%')
                          ->orWhere('stakeholder_singkatan', 'like', '%' . $this->searchI . '%');
                })
                ->get();
    }
 
    // CLEAR SEARCH RESULTS RACI R
    public function deactivateSearchI()
    {
        $this->searchActiveI = false;
        $this->searchResultsR = []; // Clear search results when deactivating
    }

    // CLEAR SEARCH RESPONSIBLE
    public function clearSearchR()
    {
        $this->searchR = '';
    }
    // CLEAR SEARCH ACCOUNTABLE
    public function clearSearchA()
    {
        $this->searchA = '';
    }
    // CLEAR SEARCH CONSULTED
    public function clearSearchC()
    {
        $this->searchC = '';
    }
    // CLEAR SEARCH INFORMED
    public function clearSearchI()
    {
        $this->searchI = '';
    }


    // SELECT RESPONSIBLE
    public function selectRaci_R($stakeholder_id)
    {
        // Check if the stakeholder is already in the RACI_R array
        foreach ($this->RACI_R as $raci) {
            if ($raci['stakeholder_id'] === $stakeholder_id) {
                // Set a validation error message for RACI_R
                $this->addError('RACI_R_duplicate', 'Stakeholder sudah dipilih.');
                session()->flash('error_R', 'Stakeholder sudah dipilih.');
                $this->searchR = '';
                return;
            }
        }

        $stakeholder = Stakeholders::find($stakeholder_id);
        $this->RACI_R[] = [
            // 'raci_id'               => 0,
            'raci_desc'             => 'r',
            'stakeholder_id'        => $stakeholder->stakeholder_id,
            'stakeholder_jabatan'   => $stakeholder->stakeholder_jabatan,
            'stakeholder_singkatan' => $stakeholder->stakeholder_singkatan,
        ];
        $this->searchR = '';
        $this->searchResultsR = [];

        // Clear any previous duplicate errors
        $this->resetErrorBag('RACI_R_duplicate');
    }

    // SELECT ACCOUNTABLE
    public function selectRaci_A($stakeholder_id)
    {
        // Check if the stakeholder is already in the RACI_R array
        foreach ($this->RACI_A as $raci) {
            if ($raci['stakeholder_id'] === $stakeholder_id) {
                // Set a validation error message for RACI_R
                $this->addError('RACI_A_duplicate', 'Stakeholder sudah dipilih.');
                session()->flash('error_A', 'Stakeholder sudah dipilih.');
                $this->searchA = '';
                return;
            }
        }

        $stakeholder = Stakeholders::find($stakeholder_id);
        $this->RACI_A[] = [
            // 'raci_id'               => 0,
            'raci_desc'             => 'a',
            'stakeholder_id'        => $stakeholder->stakeholder_id,
            'stakeholder_jabatan'   => $stakeholder->stakeholder_jabatan,
            'stakeholder_singkatan' => $stakeholder->stakeholder_singkatan,
        ];
        $this->searchA = '';
        $this->searchResultsA = [];

        // Clear any previous duplicate errors
        $this->resetErrorBag('RACI_A_duplicate');
    }

    // SELECT CONSULTED
    public function selectRaci_C($stakeholder_id)
    {
        // Check if the stakeholder is already in the RACI_R array
        foreach ($this->RACI_C as $raci) {
            if ($raci['stakeholder_id'] === $stakeholder_id) {
                // Set a validation error message for RACI_R
                $this->addError('RACI_C_duplicate', 'Stakeholder sudah dipilih.');
                session()->flash('error_C', 'Stakeholder sudah dipilih.');
                $this->searchC = '';
                return;
            }
        }

        $stakeholder = Stakeholders::find($stakeholder_id);
        $this->RACI_C[] = [
            // 'raci_id'               => 0,
            'raci_desc'             => 'c',
            'stakeholder_id'        => $stakeholder->stakeholder_id,
            'stakeholder_jabatan'   => $stakeholder->stakeholder_jabatan,
            'stakeholder_singkatan' => $stakeholder->stakeholder_singkatan,
        ];
        $this->searchC = '';
        $this->searchResultsC = [];

        // Clear any previous duplicate errors
        $this->resetErrorBag('RACI_C_duplicate');
    }

    // SELECT INFORMED
    public function selectRaci_I($stakeholder_id)
    {
        // Check if the stakeholder is already in the RACI_R array
        foreach ($this->RACI_I as $raci) {
            if ($raci['stakeholder_id'] === $stakeholder_id) {
                // Set a validation error message for RACI_R
                $this->addError('RACI_I_duplicate', 'Stakeholder sudah dipilih.');
                session()->flash('error_I', 'Stakeholder sudah dipilih.');
                $this->searchR = '';
                return;
            }
        }

        $stakeholder = Stakeholders::find($stakeholder_id);
        $this->RACI_I[] = [
            // 'raci_id'               => 0,
            'raci_desc'             => 'i',
            'stakeholder_id'        => $stakeholder->stakeholder_id,
            'stakeholder_jabatan'   => $stakeholder->stakeholder_jabatan,
            'stakeholder_singkatan' => $stakeholder->stakeholder_singkatan,
        ];
        $this->searchI = '';
        $this->searchResultsI = [];

        // Clear any previous duplicate errors
        $this->resetErrorBag('RACI_I_duplicate');
    }


    // REMOVE RESPONSIBLE
    public function removeRaci_R($index)
    {
        unset($this->RACI_R[$index]);
        $this->RACI_R = array_values($this->RACI_R); // Re-index the array
    }

    // REMOVE ACCOUNTABLE
    public function removeRaci_A($index)
    {
        unset($this->RACI_A[$index]);
        $this->RACI_A = array_values($this->RACI_A); // Re-index the array
    }

    // REMOVE CONSULTED
    public function removeRaci_C($index)
    {
        unset($this->RACI_C[$index]);
        $this->RACI_C = array_values($this->RACI_C); // Re-index the array
    }

    // REMOVE INFORMED
    public function removeRaci_I($index)
    {
        unset($this->RACI_I[$index]);
        $this->RACI_I = array_values($this->RACI_I); // Re-index the array
    }

    // VALIDATE INPUT RACI
    protected function validatingInputsRACI()
    {
        $validated = $this->validate([
            'RACI_R'    => ['required'],
            'RACI_A'    => ['required'],
            'RACI_C'    => ['required'], 
            'RACI_I'    => ['required'], 
        ], [
            'RACI_R.required'   => 'Pihak Responsible wajib diisi!',
            'RACI_A.required'   => 'Pihak Accountable wajib diisi!',
            'RACI_C.required'   => 'Pihak Consulted wajib diisi!',
            'RACI_I.required'   => 'Pihak Informed wajib diisi!',
        ]);
    }

    // STORE RACI
    public function storeRACI()
    {
        // VALIDATION INPUTS RACI
        $this->validatingInputsRACI();

        // Array to keep track of the RACI combinations (stakeholder_id and raci_desc) that have been updated or created
        $updatedOrCreatedRaciCombos = [];

        // Define the RACI types and corresponding arrays
        $raciTypes = [
            'r' => $this->RACI_R, // Responsible
            'a' => $this->RACI_A, // Accountable
            'c' => $this->RACI_C, // Consulted
            'i' => $this->RACI_I, // Informed
        ];

        // Loop through each RACI type and its corresponding array
        foreach ($raciTypes as $desc => $raciArray) {
            if ($raciArray) {
                foreach ($raciArray as $raci) {
                    // Update or create RACI records
                    $raciRecord = RaciModel::updateOrCreate(
                        [
                            'controlRisk_id' => $this->controlRisk_id,
                            'raci_desc'      => $desc, // Using the RACI type key here
                            'stakeholder_id' => $raci['stakeholder_id'],
                        ],
                        [
                            'risk_id'        => $this->risk_id,
                            'created_by'     => Auth::user()->user_id,
                            'updated_by'     => Auth::user()->user_id,
                        ]
                    );

                    // Add the combination of stakeholder_id and raci_desc to the array
                    $updatedOrCreatedRaciCombos[] = [
                        'stakeholder_id' => $raci['stakeholder_id'],
                        'raci_desc'      => $desc,
                    ];
                }
            }
        }

        // Ensure the delete query is only executed if there are records to compare against
        if (!empty($updatedOrCreatedRaciCombos)) {
            // Create a raw query to exclude specific records based on stakeholder_id and raci_desc
            $rawConditions = collect($updatedOrCreatedRaciCombos)
                ->map(fn($combo) => "('{$combo['stakeholder_id']}', '{$combo['raci_desc']}')")
                ->implode(', ');

            // Delete RACI records that are not in the updatedOrCreatedRaciCombos array
            DB::table('raci_models')
                ->where('risk_id', $this->risk_id)
                ->where('controlRisk_id', $this->controlRisk_id)
                ->whereRaw("(stakeholder_id, raci_desc) NOT IN ({$rawConditions})")
                ->delete();
        }

        // CLOSE MODAL
        $this->closeModalRACI();

        // Send notification success
        flash()
            ->option('position', 'bottom-right')
            ->option('timeout', 3000)
            ->success('Data Anda telah disimpan!');
    }

    // EDIT RACI
    public function editRACI($id)
    {
        // IS SHOW
        $this->isShowRACI = false;

        // IS EDIT
        $this->isEditRACI = true;

        // FIND RISK
        $risk                   = Risk::with(['kemungkinan', 'dampak', 'deteksiKegagalan'])->find($this->risk_id);

        // SET RISK DESC
        $this->risk_riskDesc    = $risk->risk_riskDesc;
        $this->risk_penyebab    = $risk->risk_penyebab;

        // PASSING CONTROL RISK ID
        $this->controlRisk_id   = $id;

        // FIND CONTROL RISK ID
        $controlRisk = ControlRisk::with([
            'perlakuanRisiko.rencanaPerlakuan', 
            'perlakuanRisiko.pemantauanTinjauan', 
            'raci.stakeholder'
        ])->find($id);

        // Initialize arrays for RACI types
        $this->RACI_R = [];
        $this->RACI_A = [];
        $this->RACI_C = [];
        $this->RACI_I = [];

        // PASSING PLANS
        foreach ($controlRisk->raci as $raci) {
            $stakeholder = $raci->stakeholder;
            $data = [
                'raci_id'               => $raci->raci_id,
                'raci_desc'             => $raci->raci_desc,
                'stakeholder_id'        => $stakeholder->stakeholder_id,
                'stakeholder_jabatan'   => $stakeholder->stakeholder_jabatan,
                'stakeholder_singkatan' => $stakeholder->stakeholder_singkatan,
            ];

            switch ($raci->raci_desc) {
                case 'r':
                    $this->RACI_R[] = $data;
                    break;
                case 'a':
                    $this->RACI_A[] = $data;
                    break;
                case 'c':
                    $this->RACI_C[] = $data;
                    break;
                case 'i':
                    $this->RACI_I[] = $data;
                    break;
            }
        }

        // OPEN MODAL
        $this->openModalRACI();
    }

    // SHOW RACI
    public function showRACI($id)
    {
        // IS SHOW
        $this->isShowRACI = true;

        // IS EDIT
        $this->isEditRACI = false;

        // FIND RISK
        $risk                   = Risk::with(['kemungkinan', 'dampak', 'deteksiKegagalan'])->find($this->risk_id);

        // SET RISK DESC
        $this->risk_riskDesc    = $risk->risk_riskDesc;
        $this->risk_penyebab    = $risk->risk_penyebab;

        // PASSING CONTROL RISK ID
        $this->controlRisk_id   = $id;

        // FIND CONTROL RISK ID
        $controlRisk = ControlRisk::with([
            'perlakuanRisiko.rencanaPerlakuan', 
            'perlakuanRisiko.pemantauanTinjauan', 
            'raci.stakeholder'
        ])->find($id);

        // Initialize arrays for RACI types
        $this->RACI_R = [];
        $this->RACI_A = [];
        $this->RACI_C = [];
        $this->RACI_I = [];

        // PASSING PLANS
        foreach ($controlRisk->raci as $raci) {
            $stakeholder = $raci->stakeholder;
            $data = [
                'raci_id'               => $raci->raci_id,
                'stakeholder_id'        => $stakeholder->stakeholder_id,
                'stakeholder_jabatan'   => $stakeholder->stakeholder_jabatan,
                'stakeholder_singkatan' => $stakeholder->stakeholder_singkatan,
            ];

            switch ($raci->raci_desc) {
                case 'r':
                    $this->RACI_R[] = $data;
                    break;
                case 'a':
                    $this->RACI_A[] = $data;
                    break;
                case 'c':
                    $this->RACI_C[] = $data;
                    break;
                case 'i':
                    $this->RACI_I[] = $data;
                    break;
            }
        }

        // OPEN MODAL
        $this->openModalRACI();
    }

    // DELETE RACI
    public function deleteRACI()
    {
        // FIND CONTROL RISK ID
        $controlRisk = ControlRisk::with(['perlakuanRisiko.pemantauanTinjauan', 'raci'])->find($this->controlRisk_id);

        if ($controlRisk && $controlRisk->raci->isNotEmpty()) {
            // Loop through each RACI record associated with this controlRisk
            foreach ($controlRisk->raci as $raci) {
                $raci->delete();
            }

            // Optionally: You can add a success notification here
            flash()
                ->option('position', 'bottom-right')
                ->option('timeout', 3000)
                ->success('All RACI records have been deleted successfully!');
        } else {
            // Optionally: Handle the case where controlRisk or RACI was not found
            flash()
                ->option('position', 'bottom-right')
                ->option('timeout', 3000)
                ->error('Control Risk or RACI records not found!');
        }

        // CLOSE MODAL CONFIRM DELETE RACI
        $this->closeModalConfirmDeleteRACI();
    }

    // LOCK RACI
    public function lockRACI()
    {
        // FIND CONTROL RISK ID
        $controlRisk = ControlRisk::with(['raci'])->find($this->controlRisk_id);

        if ($controlRisk && $controlRisk->raci->isNotEmpty()) {
            // Loop through each RACI record associated with this controlRisk
            foreach ($controlRisk->raci as $raci) {
                $raci->update(['raci_lockStatus' => true]);
            }

            // Optionally: You can add a success notification here
            flash()
                ->option('position', 'bottom-right')
                ->option('timeout', 3000)
                ->success('All RACI records have been locked successfully!');
        } else {
            // Optionally: Handle the case where controlRisk or RACI was not found
            flash()
                ->option('position', 'bottom-right')
                ->option('timeout', 3000)
                ->error('Control Risk or RACI records not found!');
        }

        // CLOSE MODAL CONFIRM LOCK RACI
        $this->closeModalConfirmRACI();
    }


    // LIFE CYCLE HOOKS RACI
    // OPEN MODAL LIVEWIRE 
    public $isOpenRACI              = 0;
    public $isOpenConfirmRACI       = 0;
    public $isOpenConfirmDeleteRACI = 0;

    public $isEditRACI, $isShowRACI;

    // OPEN MODAL RACI
    public function openModalRACI()
    {
        $this->isOpenRACI = true;
    }

    // CLOSE MODAL RACI
    public function closeModalRACI()
    {
        $this->isOpenRACI = false;

        // IS SHOW
        $this->isShowRACI = false;

        // IS EDIT
        $this->isEditRACI = false;

        // Reset form fields and close the modal
        $this->resetFormRACI();

        // Reset form validation
        $this->resetValidation();
    } 

    // CLOSE MODAL RACI
    public function closeXModalRACI()
    {
        $this->isOpenRACI = false;

        // IS SHOW
        $this->isShowRACI = false;

        // IS EDIT
        $this->isEditRACI = false;

        // Reset form fields and close the modal
        $this->resetFormRACI();

        // Reset form validation
        $this->resetValidation();
    } 

    // OPEN MODAL CONFIRM RACI
    public function openModalConfirmRACI($id)
    {
        // SET KPI
        $this->controlRisk_id       = $id;

        $this->isOpenConfirmRACI    = true;
    }

    // CLOSE MODAL CONFIRM RACI
    public function closeModalConfirmRACI()
    {
        $this->isOpenConfirmRACI = false;
    } 
  
    // CLOSE MODAL CONFIRM RACI
    public function closeXModalConfirmRACI()
    {
        $this->isOpenConfirmRACI = false;
    } 
    
    // OPEN MODAL CONFIRM DELETE RACI
    public function openModalConfirmDeleteRACI($id)
    {
        $this->closeModalRACI();

        // SET KPI
        $this->controlRisk_id = $id;

        $this->isOpenConfirmDeleteRACI = true;
    }

    // CLOSE MODAL CONFIRM DELETE RACI
    public function closeModalConfirmDeleteRACI()
    {
        $this->isOpenConfirmDeleteRACI = false;
    } 

    // CLOSE MODAL CONFIRM DELETE RACI
    public function closeXModalConfirmDeleteRACI()
    {
        $this->isOpenConfirmDeleteRACI = false;
    }

    // CETAK RACI
    public $isOpenCetakRaci = 0;
    
    // OPEN CETAK RACI
    public function openCetakRaci($kpi_id)
    {
        $this->isOpenCetakRaci = 1;

        $this->kpi_id  = $kpi_id;
    }

    // CLOSE CETAK RACI
    public function closeCetakRaci()
    {
        $this->isOpenCetakRaci = 0;
    }
    // CLOSE CETAK RACI
    public function closeXCetakRaci()
    {
        $this->isOpenCetakRaci = 0;
    }

    // PRINT RACI
    public function printRaci()
    {
        // KPIS FIND
        $kpis = KPI::with([
            'unit',
            'konteks.risk.controlRisk.perlakuanRisiko.jenisPerlakuan',
            'konteks.risk.controlRisk.derajatRisiko.seleraRisiko',
            'konteks.risk.controlRisk.perlakuanRisiko.rencanaPerlakuan',
            'konteks.risk.controlRisk.perlakuanRisiko.pemantauanTinjauan',
            'konteks.risk.controlRisk.efektifitasControl.detailEfektifitasKontrol',
            'konteks.risk.raci.stakeholder',
        ])
        ->where('kpi_lockStatus', true)
        ->where('kpi_activeStatus', true)
        ->whereHas('konteks.risk', function ($query) {
            $query->where('risk_id', $this->risk_id);
        })
        ->find($this->kpi_id);
        
        // FIND UNIT
        // SET UNIT KPI AND PEMILIK KPI
        $this->unit_nama = $kpis->unit->unit_name;

        // FIND RISK OWNER
        $roleToFind         = "risk owner";
        $usersWithRole      = $this->searchUsersWithRole($kpis->unit->user, $roleToFind);
        $this->user_pemilik = ucwords($usersWithRole[0]->name);
        
        // Create Dompdf instance
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true); // Enable HTML5 parser
        $options->set('isPhpEnabled', true); // Enable PHP

        $formatPaper = 'landscape'; // Corrected spelling

        // Set the page size and margins
        $options->set('size', 'A4'); // Use standard A4 size
        $options->set('margin-left', 0);
        $options->set('margin-right', 0);
        $options->set('margin-top', 0);
        $options->set('margin-bottom', 0);

        // Create a new Dompdf instance
        $dompdf = new Dompdf($options);

        // Load the HTML view with the data
        $html = view('livewire.pages.risk-owner.risk-control.raci.print-layout.raci-layout', [
            'kpis'          => $kpis,
            'user_pemilik'  => $this->user_pemilik,
            'unit_nama'     => $this->unit_nama,
            'risk_id'       => $this->risk_id,
        ])->render();

        // Load the HTML into Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Set paper size and orientation
        $dompdf->setPaper('A4', $formatPaper);

        // Render the HTML as PDF
        $dompdf->render();

        // Get the PDF content as a string
        $pdfContent = $dompdf->output();

        // Return the PDF inline in the browser
        // return response($pdfContent)
        //     ->header('Content-Type', 'application/pdf')
        //     ->header('Content-Disposition', 'inline; filename="raci-' . $kpis->kpi_kode . '.pdf"');

        // CLOSE MODAL CETAK RACI
        $this->closeCetakRaci();

        // Option 1: Return the PDF as a download
        // Return the PDF content with appropriate headers
        return response()->streamDownload(function () use ($pdfContent) {
            echo $pdfContent;
        }, 'PENGELOLAAN-RACI-' . $kpis->kpi_kode . '.pdf');

    } 

    // RESET FORM RACI
    public function resetFormRACI()
    {
        $this->isEditRACI       = false;
        $this->isShowRACI       = false;

        // CLEAR RACI
        $this->RACI_R   = [];
        $this->RACI_A   = [];
        $this->RACI_C   = [];
        $this->RACI_I   = [];    
    }



    /**
     * KOMUNIKASI FUNCTIONS
     *
     */

    // VARIABLES KOMUNIKASI
    public $komunikasi;
    
    // CREATE KOMUNIKASI
    public function createKomunikasi()
    {
        // IS SHOW
        $this->isShowKomunikasi = false;

        // IS EDIT
        $this->isEditKomunikasi = false;

        // FIND RISK
        $risk                   = Risk::with(['kemungkinan', 'dampak', 'deteksiKegagalan'])->find($this->risk_id);

        // SET RISK DESC
        $this->risk_riskDesc    = $risk->risk_riskDesc;
        $this->risk_penyebab    = $risk->risk_penyebab;

        // Reset form fields and close the modal
        $this->resetFormKomunikasi();

        // OPEN MODAL KOMUNIKASI
        $this->openModalKomunikasi();

    }

    // VARIABLES KOMUNIKASI
    public $komunikasi_id, 
           $komunikasi_tujuan, 
           $komunikasi_konten, 
           $komunikasi_media, 
           $komunikasi_metode, 
           $komunikasi_pemilihanWaktu, 
           $komunikasi_frekuensi;
    public $searchStakeholder   = '';
    public $searchPerantara     = '';
    public $searchFasil         = '';

    public $searchResultsStakeholder    = [];
    public $searchResultsPerantara      = [];
    public $searchResultsFasil          = [];

    public $komunikasi_stakeholder  = [];
    public $komunikasi_perantara    = [];
    public $komunikasi_fasil        = [];

    public $searchActiveStakeholder = false; // New property to track if search is active
    public $searchActivePerantara   = false; // New property to track if search is active
    public $searchActiveFasil       = false; // New property to track if search is active

    // SEARCH DYNAMIC KOMUNIKASI
    // SEARCH STAKEHOLDER
    public function updatedSearchStakeholder()
    {
        if ($this->searchActiveStakeholder) { // Only search if active
            $query = RaciModel::where('risk_id', $this->risk_id)
                ->where('raci_lockStatus', true)
                ->with(['stakeholder' => function ($query) {
                    $query->select('stakeholder_id', 'stakeholder_jabatan', 'stakeholder_singkatan');
                }]);

            if (!empty($this->searchStakeholder)) {
                $query->whereHas('stakeholder', function ($query) {
                    $query->where('stakeholder_jabatan', 'like', '%' . $this->searchStakeholder . '%')
                        ->orWhere('stakeholder_singkatan', 'like', '%' . $this->searchStakeholder . '%');
                });
            }

            $this->searchResultsStakeholder = $query->get()
                ->groupBy(function($item) {
                    return $item->stakeholder_id;
                })
                ->map(function($group) {
                    return $group->first()->stakeholder;
                });
        }
    }

    // LOAD FIRST IF FOCUS ON PEMANGKU KEPENTINGAN
    public function activateSearchStakeholder()
    {
        $this->searchActiveStakeholder = true;
        $query = RaciModel::where('risk_id', $this->risk_id)
            ->where('raci_lockStatus', true)
            ->with(['stakeholder' => function ($query) {
                $query->select('stakeholder_id', 'stakeholder_jabatan', 'stakeholder_singkatan');
            }]);

        if (!empty($this->searchStakeholder)) {
            $query->whereHas('stakeholder', function ($query) {
                $query->where('stakeholder_jabatan', 'like', '%' . $this->searchStakeholder . '%')
                    ->orWhere('stakeholder_singkatan', 'like', '%' . $this->searchStakeholder . '%');
            });
        }

        $this->searchResultsStakeholder = $query->get()
            ->groupBy(function($item) {
                return $item->stakeholder_id;
            })
            ->map(function($group) {
                return $group->first()->stakeholder;
            });
    }

    // CLEAR SEARCH RESULTS PEMANGKU KEPENTINGAN
    public function deactivateSearchStakeholder()
    {
        $this->searchActiveStakeholder = false;
        $this->searchResultsStakeholder = []; // Clear search results when deactivating
    }

    // SEARCH PERANTARA
    public function updatedSearchPerantara()
    {
        if($this->searchActivePerantara){
            $this->searchResultsPerantara = Stakeholders::where('stakeholder_activeStatus', true)
                ->where(function ($query) {
                    $query->where('stakeholder_jabatan', 'like', '%' . $this->searchPerantara . '%')
                          ->orWhere('stakeholder_singkatan', 'like', '%' . $this->searchPerantara . '%');
                })
                ->get();
        }
    }

    // LOAD FIRST IF FOCUS ON PERANTARA
    public function activateSearchPerantara()
    {
        $this->searchActivePerantara = true;

        $this->searchResultsPerantara = Stakeholders::where('stakeholder_activeStatus', true)
                ->where(function ($query) {
                    $query->where('stakeholder_jabatan', 'like', '%' . $this->searchPerantara . '%')
                          ->orWhere('stakeholder_singkatan', 'like', '%' . $this->searchPerantara . '%');
                })
                ->get();
    }
 
    // CLEAR SEARCH RESULTS PERANTARA
    public function deactivateSearchPerantara()
    {
        $this->searchActivePerantara = false;
        $this->searchResultsPerantara = []; // Clear search results when deactivating
    }

    // SEARCH DISIAPKAN OLEH
    public function updatedSearchFasil()
    {
        $this->searchResultsFasil = Stakeholders::where('stakeholder_activeStatus', true)
            ->where(function ($query) {
                $query->where('stakeholder_jabatan', 'like', '%' . $this->searchFasil . '%')
                      ->orWhere('stakeholder_singkatan', 'like', '%' . $this->searchFasil . '%');
            })
            ->get();
    }

    // LOAD FIRST IF FOCUS ON DISIAPKAN OLEH
    public function activateSearchFasil()
    {
        $this->searchActiveFasil = true;

        $this->searchResultsFasil = Stakeholders::where('stakeholder_activeStatus', true)
                ->where(function ($query) {
                    $query->where('stakeholder_jabatan', 'like', '%' . $this->searchFasil . '%')
                          ->orWhere('stakeholder_singkatan', 'like', '%' . $this->searchFasil . '%');
                })
                ->get();
    }
 
    // CLEAR SEARCH RESULTS DISIAPKAN OLEH
    public function deactivateSearchFasil()
    {
        $this->searchActiveFasil  = false;
        $this->searchResultsFasil = []; // Clear search results when deactivating
    }

    // CLEAR SEARCH STAKEHOLDER
    public function clearSearchStakeholder()
    {
        $this->searchStakeholder = '';
        $this->searchResultsStakeholder = [];
    }

    // CLEAR SEARCH PERANTARA
    public function clearSearchPerantara()
    {
        $this->searchPerantara = '';
        $this->searchResultsPerantara = [];
    }

    // CLEAR SEARCH DISIAPKAN OLEH
    public function clearSearchFasil()
    {
        $this->searchFasil = '';
        $this->searchResultsFasil = [];
    }

    // SELECT STAKEHOLDER
    public function select_stakeholder($stakeholder_id)
    {
        // Check if the stakeholder is already in the komunikasi_stakeholder array
        foreach ($this->komunikasi_stakeholder as $raci) {
            if ($raci['stakeholder_id'] === $stakeholder_id) {
                session()->flash('komunikasi_stakeholder_duplicate', 'Stakeholder sudah dipilih.');
                $this->searchStakeholder = '';
                return;
            }
        }

        $stakeholder = Stakeholders::find($stakeholder_id);
        $this->komunikasi_stakeholder[] = [
            'komunikasiStakeholder_ket' => 'stakeholder',
            'stakeholder_id'            => $stakeholder->stakeholder_id,
            'stakeholder_jabatan'       => $stakeholder->stakeholder_jabatan,
            'stakeholder_singkatan'     => $stakeholder->stakeholder_singkatan,
        ];

        $this->clearSearchStakeholder();
        $this->resetErrorBag('komunikasi_stakeholder_duplicate');
    }

    // SELECT PERANTARA
    public function select_perantara($stakeholder_id)
    {
        // Check if the stakeholder is already in the komunikasi_perantara array
        foreach ($this->komunikasi_perantara as $raci) {
            if ($raci['stakeholder_id'] === $stakeholder_id) {
                session()->flash('komunikasi_perantara_duplicate', 'Stakeholder sudah dipilih.');
                $this->searchPerantara = '';
                return;
            }
        }

        $stakeholder = Stakeholders::find($stakeholder_id);
        $this->komunikasi_perantara[] = [
            'komunikasiStakeholder_ket' => 'perantara',
            'stakeholder_id'            => $stakeholder->stakeholder_id,
            'stakeholder_jabatan'       => $stakeholder->stakeholder_jabatan,
            'stakeholder_singkatan'     => $stakeholder->stakeholder_singkatan,
        ];

        $this->clearSearchPerantara();
        $this->resetErrorBag('komunikasi_perantara_duplicate');
    }

    // SELECT DISIAPKAN OLEH
    public function select_fasil($stakeholder_id)
    {
        // Check if the stakeholder is already in the komunikasi_fasil array
        foreach ($this->komunikasi_fasil as $raci) {
            if ($raci['stakeholder_id'] === $stakeholder_id) {
                session()->flash('komunikasi_fasil_duplicate', 'Stakeholder sudah dipilih.');
                $this->searchFasil = '';
                return;
            }
        }

        $stakeholder = Stakeholders::find($stakeholder_id);
        $this->komunikasi_fasil[] = [
            'komunikasiStakeholder_ket' => 'fasil',
            'stakeholder_id'            => $stakeholder->stakeholder_id,
            'stakeholder_jabatan'       => $stakeholder->stakeholder_jabatan,
            'stakeholder_singkatan'     => $stakeholder->stakeholder_singkatan,
        ];

        $this->clearSearchFasil();
        $this->resetErrorBag('komunikasi_fasil_duplicate');
    }

    // REMOVE STAKEHOLDER
    public function remove_stakeholder($index)
    {
        unset($this->komunikasi_stakeholder[$index]);
        $this->komunikasi_stakeholder = array_values($this->komunikasi_stakeholder); // Re-index the array
    }

    // REMOVE PERANTARA
    public function remove_perantara($index)
    {
        unset($this->komunikasi_perantara[$index]);
        $this->komunikasi_perantara = array_values($this->komunikasi_perantara); // Re-index the array
    }

    // REMOVE DISIAPKAN OLEH
    public function remove_fasil($index)
    {
        unset($this->komunikasi_fasil[$index]);
        $this->komunikasi_fasil = array_values($this->komunikasi_fasil); // Re-index the array
    }

    // VALIDATE INPUT KOMUNIKASI
    protected function validatingInputsKomunikasi()
    {
        $validated = $this->validate([
            'komunikasi_stakeholder'    => ['required'],
            'komunikasi_perantara'      => ['required'],
            'komunikasi_fasil'          => ['required'], 
            'komunikasi_tujuan'         => ['required'], 
            'komunikasi_konten'         => ['required'], 
            'komunikasi_media'          => ['required'], 
            'komunikasi_metode'         => ['required'], 
            'komunikasi_pemilihanWaktu' => ['required'], 
            'komunikasi_frekuensi'      => ['required'], 
        ], [
            'komunikasi_stakeholder.required'   => 'Pihak Pemangku Kepentingan wajib diisi!',
            'komunikasi_perantara.required'     => 'Pihak Perantara wajib diisi!',
            'komunikasi_fasil.required'         => 'Pihak Fasilitator wajib diisi!',
            'komunikasi_tujuan.required'        => 'Komunikasi Tujuan wajib diisi!',
            'komunikasi_konten.required'        => 'Komunikasi Konten wajib diisi!',
            'komunikasi_media.required'         => 'Komunikasi Media wajib diisi!',
            'komunikasi_metode.required'        => 'Komunikasi Metode wajib diisi!',
            'komunikasi_pemilihanWaktu.required'=> 'Komunikasi Pemilihan Waktu wajib diisi!',
            'komunikasi_frekuensi.required'     => 'Komunikasi Frekuensi wajib diisi!',
        ]);
    }

    // STORE KOMUNIKASI
    public function storeKomunikasi()
    {
        // VALIDATION INPUTS KOMUNIKASI
        $this->validatingInputsKomunikasi();

        // Array to keep track of the Komunikasi combinations (stakeholder_id and komunikasi_ket) that have been updated or created
        $updatedOrCreatedKomunikasiCombos = [];

        // Create Komunikasi
        $komunikasi = Komunikasi::updateOrCreate([
            'komunikasi_id'             => $this->komunikasi_id,
        ],[
            'risk_id'                   => $this->risk_id,
            'komunikasi_tujuan'         => $this->komunikasi_tujuan,
            'komunikasi_konten'         => $this->komunikasi_konten,
            'komunikasi_media'          => $this->komunikasi_media,
            'komunikasi_metode'         => $this->komunikasi_metode,
            'komunikasi_pemilihanWaktu' => $this->komunikasi_pemilihanWaktu,
            'komunikasi_frekuensi'      => $this->komunikasi_frekuensi,
            'created_by'                => Auth::user()->user_id,
            'updated_by'                => Auth::user()->user_id,
        ]);

        // Define the Komunikasi types and corresponding arrays
        $komunikasis = [
            'stakeholder'   => $this->komunikasi_stakeholder, // Stakeholder
            'perantara'     => $this->komunikasi_perantara, // Perantara
            'fasil'         => $this->komunikasi_fasil, // Disiapkan Oleh
        ];

        // Loop through each Komunikasi type and its corresponding array
        foreach ($komunikasis as $desc => $komunikasiArray) {
            if ($komunikasiArray) {
                foreach ($komunikasiArray as $item) {
                    // Update or create RACI records
                    $komunikasiStakeholder = KomunikasiStakeholder::updateOrCreate(
                        [
                            'komunikasiStakeholder_ket'      => $desc, // Using the Komunikasi type key here
                            'stakeholder_id'                 => $item['stakeholder_id'],
                            'komunikasi_id'                  => $komunikasi->komunikasi_id,
                        ],
                        [
                            'created_by'     => Auth::user()->user_id,
                            'updated_by'     => Auth::user()->user_id,
                        ]
                    );

                    // Add the combination of stakeholder_id and raci_desc to the array
                    $updatedOrCreatedKomunikasiCombos[] = [
                        'stakeholder_id'                 => $item['stakeholder_id'],
                        'komunikasiStakeholder_ket'      => $desc,
                    ];
                }
            }
        }


        // Ensure the delete query is only executed if there are records to compare against
        if (!empty($updatedOrCreatedKomunikasiCombos) && !empty($this->komunikasi_id)) {
            // Create a raw query to exclude specific records based on stakeholder_id and komunikasiStakeholder_ket
            $rawConditions = collect($updatedOrCreatedKomunikasiCombos)
                ->map(fn($combo) => "('{$combo['stakeholder_id']}', '{$combo['komunikasiStakeholder_ket']}')")
                ->implode(', ');

            // Delete KomunikasiStakeholder records that are not in the updatedOrCreatedKomunikasiCombos array
            DB::table('komunikasi_stakeholders')
                ->where('komunikasi_id', $this->komunikasi_id)
                ->whereRaw("(stakeholder_id, komunikasiStakeholder_ket) NOT IN ({$rawConditions})")
                ->delete();
        }


        // CLOSE MODAL
        $this->closeModalKomunikasi();

        // Send notification success
        flash()
            ->option('position', 'bottom-right')
            ->option('timeout', 3000)
            ->success('Data Anda telah disimpan!');
    }

    // EDIT KOMUNIKASI
    public function editKomunikasi($id)
    {
        // IS SHOW
        $this->isShowKomunikasi = false;

        // IS EDIT
        $this->isEditKomunikasi = true;

        // FIND RISK
        $risk                   = Risk::with(['kemungkinan', 'dampak', 'deteksiKegagalan'])->find($this->risk_id);

        // SET RISK DESC
        $this->risk_riskDesc    = $risk->risk_riskDesc;
        $this->risk_penyebab    = $risk->risk_penyebab;

        // PASSING CONTROL RISK ID
        $this->komunikasi_id   = $id;

        // FIND KOMUNIKASI
        $komunikasi = Komunikasi::with('komunikasiStakeholder.stakeholder')->find($id);
        
        // PASSING DATA KOMUNIKASI
        $this->komunikasi_tujuan            = $komunikasi->komunikasi_tujuan;
        $this->komunikasi_konten            = $komunikasi->komunikasi_konten;
        $this->komunikasi_media             = $komunikasi->komunikasi_media;
        $this->komunikasi_metode            = $komunikasi->komunikasi_metode;
        $this->komunikasi_pemilihanWaktu    = $komunikasi->komunikasi_pemilihanWaktu;
        $this->komunikasi_frekuensi         = $komunikasi->komunikasi_frekuensi;

        // PASSING PLANS
        foreach ($komunikasi->komunikasiStakeholder as $item) {
            $stakeholder = $item->stakeholder;
            $data = [
                'komunikasiStakeholder_ket' => $item->komunikasiStakeholder_ket,
                'stakeholder_id'            => $item->stakeholder_id,
                'stakeholder_jabatan'       => $stakeholder->stakeholder_jabatan,
                'stakeholder_singkatan'     => $stakeholder->stakeholder_singkatan,
            ];

            switch ($item->komunikasiStakeholder_ket) {
                case 'stakeholder':
                    $this->komunikasi_stakeholder[] = $data;
                    break;
                case 'perantara':
                    $this->komunikasi_perantara[] = $data;
                    break;
                case 'fasil':
                    $this->komunikasi_fasil[] = $data;
                    break;
            }
        }

        // OPEN MODAL KOMUNIKASI
        $this->openModalKomunikasi();
    }

    // SHOW KOMUNIKASI
    public function showKomunikasi($id)
    {
        // IS SHOW
        $this->isShowKomunikasi = true;

        // IS EDIT
        $this->isEditKomunikasi = false;

        // FIND RISK
        $risk                   = Risk::with(['kemungkinan', 'dampak', 'deteksiKegagalan'])->find($this->risk_id);

        // SET RISK DESC
        $this->risk_riskDesc    = $risk->risk_riskDesc;
        $this->risk_penyebab    = $risk->risk_penyebab;

        // PASSING CONTROL RISK ID
        $this->komunikasi_id   = $id;

        // FIND KOMUNIKASI
        $komunikasi = Komunikasi::with('komunikasiStakeholder.stakeholder')->find($id);
        
        // PASSING DATA KOMUNIKASI
        $this->komunikasi_tujuan            = $komunikasi->komunikasi_tujuan;
        $this->komunikasi_konten            = $komunikasi->komunikasi_konten;
        $this->komunikasi_media             = $komunikasi->komunikasi_media;
        $this->komunikasi_metode            = $komunikasi->komunikasi_metode;
        $this->komunikasi_pemilihanWaktu    = $komunikasi->komunikasi_pemilihanWaktu;
        $this->komunikasi_frekuensi         = $komunikasi->komunikasi_frekuensi;

        // PASSING PLANS
        foreach ($komunikasi->komunikasiStakeholder as $item) {
            $stakeholder = $item->stakeholder;
            $data = [
                'komunikasiStakeholder_ket' => $item->komunikasiStakeholder_ket,
                'stakeholder_id'            => $item->stakeholder_id,
                'stakeholder_jabatan'       => $stakeholder->stakeholder_jabatan,
                'stakeholder_singkatan'     => $stakeholder->stakeholder_singkatan,
            ];

            switch ($item->komunikasiStakeholder_ket) {
                case 'stakeholder':
                    $this->komunikasi_stakeholder[] = $data;
                    break;
                case 'perantara':
                    $this->komunikasi_perantara[] = $data;
                    break;
                case 'fasil':
                    $this->komunikasi_fasil[] = $data;
                    break;
            }
        }

        // OPEN MODAL KOMUNIKASI
        $this->openModalKomunikasi();
    }

    // DELETE KOMUNIKASI
    public function deleteKomunikasi()
    {
        // FIND KOMUNIKASI RECORD
        $komunikasi = Komunikasi::with(['komunikasiStakeholder'])->find($this->komunikasi_id);

        if ($komunikasi) {
            // Delete the Komunikasi record, which will also delete related KomunikasiStakeholder records if cascading is set up
            $komunikasi->delete();

            // Optional: Add a success notification
            flash()
                ->option('position', 'bottom-right')
                ->option('timeout', 3000)
                ->success('Komunikasi and related records have been deleted successfully!');
        } else {
            // Optional: Handle the case where komunikasi was not found
            flash()
                ->option('position', 'bottom-right')
                ->option('timeout', 3000)
                ->error('Komunikasi record not found!');
        }

        // CLOSE MODAL CONFIRM DELETE KOMUNIKASI
        $this->closeModalConfirmDeleteKomunikasi();
    }

    // LOCK KOMUNIKASI
    public function lockKomunikasi()
    {
        // FIND KOMUNIKASI RECORD
        $komunikasi = Komunikasi::with(['komunikasiStakeholder'])->find($this->komunikasi_id);

        if ($komunikasi) {
            // Delete the Komunikasi record, which will also delete related KomunikasiStakeholder records if cascading is set up
            $komunikasi->update(['komunikasi_lockStatus' => 1]);

            // Optional: Add a success notification
            flash()
                ->option('position', 'bottom-right')
                ->option('timeout', 3000)
                ->success('Komunikasi and related records have been locked successfully!');
        } else {
            // Optional: Handle the case where komunikasi was not found
            flash()
                ->option('position', 'bottom-right')
                ->option('timeout', 3000)
                ->error('Komunikasi record not found!');
        }

        // CLOSE MODAL CONFIRM LOCK KOMUNIKASI
        $this->closeModalConfirmKomunikasi();
    }


    // LIFE CYCLE HOOKS KOMUNIKASI
    // OPEN MODAL LIVEWIRE 
    public $isOpenKomunikasi              = 0;
    public $isOpenConfirmKomunikasi       = 0;
    public $isOpenConfirmDeleteKomunikasi = 0;

    public $isEditKomunikasi, $isShowKomunikasi;

    // OPEN MODAL Komunikasi
    public function openModalKomunikasi()
    {
        $this->isOpenKomunikasi = true;
    }

    // CLOSE MODAL Komunikasi
    public function closeModalKomunikasi()
    {
        $this->isOpenKomunikasi = false;

        // IS SHOW
        $this->isShowKomunikasi = false;

        // IS EDIT
        $this->isEditKomunikasi = false;

        // Reset form fields and close the modal
        $this->resetFormKomunikasi();        

        // Reset form validation
        $this->resetValidation();
    } 

    // CLOSE MODAL Komunikasi
    public function closeXModalKomunikasi()
    {
        $this->isOpenKomunikasi = false;

        // IS SHOW
        $this->isShowKomunikasi = false;

        // IS EDIT
        $this->isEditKomunikasi = false;

        // Reset form fields and close the modal
        $this->resetFormKomunikasi();

        // Reset form validation
        $this->resetValidation();
    } 

    // OPEN MODAL CONFIRM Komunikasi
    public function openModalConfirmKomunikasi($id)
    {
        // SET KOMUNIKASI ID
        $this->komunikasi_id              = $id;

        $this->isOpenConfirmKomunikasi    = true;
    }

    // CLOSE MODAL CONFIRM Komunikasi
    public function closeModalConfirmKomunikasi()
    {
        $this->isOpenConfirmKomunikasi = false;
    } 
  
    // CLOSE MODAL CONFIRM Komunikasi
    public function closeXModalConfirmKomunikasi()
    {
        $this->isOpenConfirmKomunikasi = false;
    } 
    
    // OPEN MODAL CONFIRM DELETE Komunikasi
    public function openModalConfirmDeleteKomunikasi($id)
    {
        $this->closeModalKomunikasi();

        // SET KPI
        $this->controlRisk_id = $id;

        $this->isOpenConfirmDeleteKomunikasi = true;
    }

    // CLOSE MODAL CONFIRM DELETE Komunikasi
    public function closeModalConfirmDeleteKomunikasi()
    {
        $this->isOpenConfirmDeleteKomunikasi = false;
    } 

    // CLOSE MODAL CONFIRM DELETE Komunikasi
    public function closeXModalConfirmDeleteKomunikasi()
    {
        $this->isOpenConfirmDeleteKomunikasi = false;
    } 

    // CETAK KOMUNIKASI
    public $isOpenCetakKomunikasi = 0;
    
    // OPEN CETAK KOMUNIKASI
    public function openCetakKomunikasi($kpi_id)
    {
        $this->isOpenCetakKomunikasi = 1;

        $this->kpi_id  = $kpi_id;
    }

    // CLOSE CETAK KOMUNIKASI
    public function closeCetakKomunikasi()
    {
        $this->isOpenCetakKomunikasi = 0;
    }
    // CLOSE CETAK KOMUNIKASI
    public function closeXCetakKomunikasi()
    {
        $this->isOpenCetakKomunikasi = 0;
    }

    // PRINT KOMUNIKASI
    public function printKomunikasi()
    {
        // KPIS FIND
        $kpis = KPI::with([
            'unit',
            'konteks.risk.komunikasi.komunikasiStakeholder.stakeholder',
        ])
        ->where('kpi_lockStatus', true)
        ->where('kpi_activeStatus', true)
        ->whereHas('konteks.risk', function ($query) {
            $query->where('risk_id', $this->risk_id);
        })
        ->find($this->kpi_id);
        
        // FIND UNIT
        // SET UNIT KPI AND PEMILIK KPI
        $this->unit_nama = $kpis->unit->unit_name;

        // FIND RISK OWNER
        $roleToFind         = "risk owner";
        $usersWithRole      = $this->searchUsersWithRole($kpis->unit->user, $roleToFind);
        $this->user_pemilik = ucwords($usersWithRole[0]->name);
        
        // Create Dompdf instance
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true); // Enable HTML5 parser
        $options->set('isPhpEnabled', true); // Enable PHP

        $formatPaper = 'landscape'; // Corrected spelling

        // Set the page size and margins
        $options->set('size', 'A4'); // Use standard A4 size
        $options->set('margin-left', 0);
        $options->set('margin-right', 0);
        $options->set('margin-top', 0);
        $options->set('margin-bottom', 0);

        // Create a new Dompdf instance
        $dompdf = new Dompdf($options);

        // Load the HTML view with the data
        $html = view('livewire.pages.risk-owner.risk-control.komunikasi-konsultasi.print-layout.komunikasi-layout', [
            'kpis'          => $kpis,
            'user_pemilik'  => $this->user_pemilik,
            'unit_nama'     => $this->unit_nama,
            'risk_id'       => $this->risk_id,
        ])->render();

        // Load the HTML into Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Set paper size and orientation
        $dompdf->setPaper('A4', $formatPaper);

        // Render the HTML as PDF
        $dompdf->render();

        // Get the PDF content as a string
        $pdfContent = $dompdf->output();

        // Return the PDF inline in the browser
        // return response($pdfContent)
        //     ->header('Content-Type', 'application/pdf')
        //     ->header('Content-Disposition', 'inline; filename="raci-' . $kpis->kpi_kode . '.pdf"');

        // CLOSE MODAL CETAK KOMUNIKASI
        $this->closeCetakKomunikasi();

        // Option 1: Return the PDF as a download
        // Return the PDF content with appropriate headers
        return response()->streamDownload(function () use ($pdfContent) {
            echo $pdfContent;
        }, 'KOMUNIKASI-' . $kpis->kpi_kode . '.pdf');

    }

    // RESET FORM Komunikasi
    public function resetFormKomunikasi()
    {
        $this->isEditKomunikasi       = false;
        $this->isShowKomunikasi       = false;

        // CLEAR KOMUNIKASI
        $this->komunikasi_id                = '';
        $this->komunikasi_stakeholder       = [];   
        $this->komunikasi_perantara         = [];   
        $this->komunikasi_fasil             = [];   
        $this->komunikasi_tujuan            = '';   
        $this->komunikasi_media             = '';   
        $this->komunikasi_konten            = '';   
        $this->komunikasi_metode            = '';   
        $this->komunikasi_pemilihanWaktu    = '';   
        $this->komunikasi_frekuensi         = '';   
    }




    /**
     * KONSULTASI FUNCTIONS
     *
     */

    // VARIABLES KONSULTASI
    public $konsultasi;
    
    // CREATE KONSULTASI
    public function createKonsultasi()
    {
        // IS SHOW
        $this->isShowKonsultasi = false;

        // IS EDIT
        $this->isEditKonsultasi = false;

        // FIND RISK
        $risk                   = Risk::with(['kemungkinan', 'dampak', 'deteksiKegagalan'])->find($this->risk_id);

        // SET RISK DESC
        $this->risk_riskDesc    = $risk->risk_riskDesc;
        $this->risk_penyebab    = $risk->risk_penyebab;

        // Reset form fields and close the modal
        $this->resetFormKonsultasi();

        // OPEN MODAL KONSULTASI
        $this->openModalKonsultasi();
    }

    // VARIABLES KONSULTASI
    public $konsultasi_id, 
           $konsultasi_tujuan, 
           $konsultasi_konten, 
           $konsultasi_media, 
           $konsultasi_metode, 
           $konsultasi_pemilihanWaktu, 
           $konsultasi_frekuensi;
    public $searchStakeholderKonsultasi  = '';
    public $searchFasilKonsultasi        = '';

    public $searchResultsStakeholderKonsultasi    = [];
    public $searchResultsFasilKonsultasi          = [];

    public $konsultasi_stakeholder  = [];
    public $konsultasi_fasil        = [];

    public $searchActiveStakeholderKonsultasi = false; // New property to track if search is active
    public $searchActiveFasilKonsultasi       = false; // New property to track if search is active

    // SEARCH DYNAMIC KONSULTASI
    // SEARCH STAKEHOLDER
    public function updatedSearchStakeholderKonsultasi()
    {
        if ($this->searchActiveStakeholderKonsultasi) { // Only search if active
            $query = RaciModel::where('risk_id', $this->risk_id)
                ->where('raci_lockStatus', true)
                ->where('raci_desc', 'c')
                ->with(['stakeholder' => function ($query) {
                    $query->select('stakeholder_id', 'stakeholder_jabatan', 'stakeholder_singkatan');
                }]);

            if (!empty($this->searchStakeholder)) {
                $query->whereHas('stakeholder', function ($query) {
                    $query->where('stakeholder_jabatan', 'like', '%' . $this->searchStakeholder . '%')
                        ->orWhere('stakeholder_singkatan', 'like', '%' . $this->searchStakeholder . '%');
                });
            }

            $this->searchResultsStakeholder = $query->get()
                ->groupBy(function($item) {
                    return $item->stakeholder_id;
                })
                ->map(function($group) {
                    return $group->first()->stakeholder;
                });
        }
    }

    // LOAD FIRST IF FOCUS ON PEMANGKU KEPENTINGAN KONSULTASI
    public function activateSearchStakeholderKonsultasi()
    {
        $this->searchActiveStakeholderKonsultasi = true;
        $query = RaciModel::where('risk_id', $this->risk_id)
            ->where('raci_lockStatus', true)
            ->where('raci_desc', 'c')
            ->with(['stakeholder' => function ($query) {
                $query->select('stakeholder_id', 'stakeholder_jabatan', 'stakeholder_singkatan');
            }]);

        if (!empty($this->searchStakeholderKonsultasi)) {
            $query->whereHas('stakeholder', function ($query) {
                $query->where('stakeholder_jabatan', 'like', '%' . $this->searchStakeholderKonsultasi . '%')
                    ->orWhere('stakeholder_singkatan', 'like', '%' . $this->searchStakeholderKonsultasi . '%');
            });
        }

        $this->searchResultsStakeholderKonsultasi = $query->get()
            ->groupBy(function($item) {
                return $item->stakeholder_id;
            })
            ->map(function($group) {
                return $group->first()->stakeholder;
            });
    }

    // CLEAR SEARCH RESULTS PEMANGKU KEPENTINGAN KONSULTASI
    public function deactivateSearchStakeholderKonsultasi()
    {
        $this->searchActiveStakeholderKonsultasi  = false;
        $this->searchResultsStakeholderKonsultasi = []; // Clear search results when deactivating
    }

    // SEARCH DISIAPKAN OLEH KONSULTASI
    public function updatedSearchFasilKonsultasi()
    {
        $this->searchResultsFasilKonsultasi = Stakeholders::where('stakeholder_activeStatus', true)
            ->where(function ($query) {
                $query->where('stakeholder_jabatan', 'like', '%' . $this->searchFasilKonsultasi . '%')
                      ->orWhere('stakeholder_singkatan', 'like', '%' . $this->searchFasilKonsultasi . '%');
            })
            ->get();
    }

    // LOAD FIRST IF FOCUS ON DISIAPKAN OLEH KONSULTASI
    public function activateSearchFasilKonsultasi()
    {
        $this->searchActiveFasilKonsultasi = true;

        $this->searchResultsFasilKonsultasi = Stakeholders::where('stakeholder_activeStatus', true)
                ->where(function ($query) {
                    $query->where('stakeholder_jabatan', 'like', '%' . $this->searchFasilKonsultasi . '%')
                          ->orWhere('stakeholder_singkatan', 'like', '%' . $this->searchFasilKonsultasi . '%');
                })
                ->get();
    }
 
    // CLEAR SEARCH RESULTS DISIAPKAN OLEH KONSULTASI
    public function deactivateSearchFasilKonsultasi()
    {
        $this->searchActiveFasilKonsultasi  = false;
        $this->searchResultsFasilKonsultasi = []; // Clear search results when deactivating
    }

    // CLEAR SEARCH STAKEHOLDER KONSULTASI
    public function clearSearchStakeholderKonsultasi()
    {
        $this->searchStakeholderKonsultasi        = '';
        $this->searchResultsStakeholderKonsultasi = [];
    }

    // CLEAR SEARCH DISIAPKAN OLEH KONSULTASI
    public function clearSearchFasilKonsultasi()
    {
        $this->searchFasil = '';
        $this->searchResultsFasil = [];
    }

    // SELECT STAKEHOLDER KONSULTASI
    public function select_stakeholderKonsultasi($stakeholder_id)
    {
        // Check if the stakeholder is already in the konsultasi_stakeholder array
        foreach ($this->konsultasi_stakeholder as $raci) {
            if ($raci['stakeholder_id'] === $stakeholder_id) {
                session()->flash('konsultasi_stakeholder_duplicate', 'Stakeholder sudah dipilih.');
                $this->searchStakeholder = '';
                return;
            }
        }

        $stakeholder = Stakeholders::find($stakeholder_id);
        $this->konsultasi_stakeholder[] = [
            'konsultasiStakeholder_ket' => 'stakeholder',
            'stakeholder_id'            => $stakeholder->stakeholder_id,
            'stakeholder_jabatan'       => $stakeholder->stakeholder_jabatan,
            'stakeholder_singkatan'     => $stakeholder->stakeholder_singkatan,
        ];

        $this->clearSearchStakeholder();
        $this->resetErrorBag('konsultasi_stakeholder_duplicate');
    }

    // SELECT DISIAPKAN OLEH KONSULTASI
    public function select_fasilKonsultasi($stakeholder_id)
    {
        // Check if the stakeholder is already in the konsultasi_fasil array
        foreach ($this->konsultasi_fasil as $raci) {
            if ($raci['stakeholder_id'] === $stakeholder_id) {
                session()->flash('konsultasi_fasil_duplicate', 'Stakeholder sudah dipilih.');
                $this->searchFasil = '';
                return;
            }
        }

        $stakeholder = Stakeholders::find($stakeholder_id);
        $this->konsultasi_fasil[] = [
            'konsultasiStakeholder_ket' => 'fasil',
            'stakeholder_id'            => $stakeholder->stakeholder_id,
            'stakeholder_jabatan'       => $stakeholder->stakeholder_jabatan,
            'stakeholder_singkatan'     => $stakeholder->stakeholder_singkatan,
        ];

        $this->clearSearchFasil();
        $this->resetErrorBag('konsultasi_fasil_duplicate');
    }

    // REMOVE STAKEHOLDER KONSULTASI
    public function remove_stakeholderKonsultasi($index)
    {
        unset($this->konsultasi_stakeholder[$index]);
        $this->konsultasi_stakeholder = array_values($this->konsultasi_stakeholder); // Re-index the array
    }

    // REMOVE DISIAPKAN OLEH KONSULTASI
    public function remove_fasilKonsultasi($index)
    {
        unset($this->konsultasi_fasil[$index]);
        $this->konsultasi_fasil = array_values($this->konsultasi_fasil); // Re-index the array
    }

    // VALIDATE INPUT KONSULTASI
    protected function validatingInputsKonsultasi()
    {
        $validated = $this->validate([
            'konsultasi_stakeholder'    => ['required'],
            'konsultasi_fasil'          => ['required'], 
            'konsultasi_tujuan'         => ['required'], 
            'konsultasi_konten'         => ['required'], 
            'konsultasi_media'          => ['required'], 
            'konsultasi_metode'         => ['required'], 
        ], [
            'konsultasi_stakeholder.required'   => 'Pihak Pemangku Kepentingan wajib diisi!',
            'konsultasi_fasil.required'         => 'Pihak Fasilitator wajib diisi!',
            'konsultasi_tujuan.required'        => 'Konsultasi Tujuan wajib diisi!',
            'konsultasi_konten.required'        => 'Konsultasi Konten wajib diisi!',
            'konsultasi_media.required'         => 'Konsultasi Media wajib diisi!',
            'konsultasi_metode.required'        => 'Konsultasi Metode wajib diisi!',
        ]);
    }

    // STORE KONSULTASI
    public function storeKonsultasi()
    {
        // VALIDATION INPUTS KONSULTASI
        $this->validatingInputsKonsultasi();

        // Array to keep track of the konsultasi combinations (stakeholder_id and konsultasi_ket) that have been updated or created
        $updatedOrCreatedKonsultasiCombos = [];

        // Create Konsultasi
        $konsultasi = Konsultasi::updateOrCreate([
            'konsultasi_id'             => $this->konsultasi_id,
        ],[
            'risk_id'                   => $this->risk_id,
            'konsultasi_tujuan'         => $this->konsultasi_tujuan,
            'konsultasi_konten'         => $this->konsultasi_konten,
            'konsultasi_media'          => $this->konsultasi_media,
            'konsultasi_metode'         => $this->konsultasi_metode,
            'created_by'                => Auth::user()->user_id,
            'updated_by'                => Auth::user()->user_id,
        ]);

        // Define the konsultasi types and corresponding arrays
        $konsultasis = [
            'stakeholder'   => $this->konsultasi_stakeholder, // Stakeholder
            'fasil'         => $this->konsultasi_fasil, // Disiapkan Oleh
        ];

        // Loop through each Konsultasi type and its corresponding array
        foreach ($konsultasis as $desc => $konsultasiArray) {
            if ($konsultasiArray) {
                foreach ($konsultasiArray as $item) {
                    // Update or create RACI records
                    $konsultasiStakeholder = KonsultasiStakeholder::updateOrCreate(
                        [
                            'konsultasiStakeholder_ket'      => $desc, // Using the konsultasi type key here
                            'stakeholder_id'                 => $item['stakeholder_id'],
                            'konsultasi_id'                  => $konsultasi->konsultasi_id,
                        ],
                        [
                            'created_by'     => Auth::user()->user_id,
                            'updated_by'     => Auth::user()->user_id,
                        ]
                    );

                    // Add the combination of stakeholder_id and raci_desc to the array
                    $updatedOrCreatedKonsultasiCombos[] = [
                        'stakeholder_id'                 => $item['stakeholder_id'],
                        'konsultasiStakeholder_ket'      => $desc,
                    ];
                }
            }
        }


        // Ensure the delete query is only executed if there are records to compare against
        if (!empty($updatedOrCreatedKonsultasiCombos) && !empty($this->konsultasi_id)) {
            // Create a raw query to exclude specific records based on stakeholder_id and konsultasiStakeholder_ket
            $rawConditions = collect($updatedOrCreatedKonsultasiCombos)
                ->map(fn($combo) => "('{$combo['stakeholder_id']}', '{$combo['konsultasiStakeholder_ket']}')")
                ->implode(', ');

            // Delete konsultasiStakeholder records that are not in the updatedOrCreatedKonsultasiCombos array
            DB::table('konsultasi_stakeholders')
                ->where('konsultasi_id', $this->konsultasi_id)
                ->whereRaw("(stakeholder_id, konsultasiStakeholder_ket) NOT IN ({$rawConditions})")
                ->delete();
        }


        // CLOSE MODAL
        $this->closeModalKonsultasi();

        // Send notification success
        flash()
            ->option('position', 'bottom-right')
            ->option('timeout', 3000)
            ->success('Data Anda telah disimpan!');
    }

    // EDIT KONSULTASI
    public function editKonsultasi($id)
    {
        // IS SHOW
        $this->isShowKonsultasi = false;

        // IS EDIT
        $this->isEditKonsultasi = true;

        // FIND RISK
        $risk                   = Risk::with(['kemungkinan', 'dampak', 'deteksiKegagalan'])->find($this->risk_id);

        // SET RISK DESC
        $this->risk_riskDesc    = $risk->risk_riskDesc;
        $this->risk_penyebab    = $risk->risk_penyebab;

        // PASSING KONSULTASI ID
        $this->konsultasi_id    = $id;

        // FIND KONSULTASI
        $konsultasi = Konsultasi::with('konsultasiStakeholder.stakeholder')->find($id);

        // PASSING DATA KONSULTASI
        $this->konsultasi_tujuan            = $konsultasi->konsultasi_tujuan;
        $this->konsultasi_konten            = $konsultasi->konsultasi_konten;
        $this->konsultasi_media             = $konsultasi->konsultasi_media;
        $this->konsultasi_metode            = $konsultasi->konsultasi_metode;

        // PASSING PLANS
        foreach ($konsultasi->konsultasiStakeholder as $item) {
            $stakeholder = $item->stakeholder;
            $data = [
                'konsultasiStakeholder_ket' => $item->konsultasiStakeholder_ket,
                'stakeholder_id'            => $item->stakeholder_id,
                'stakeholder_jabatan'       => $stakeholder->stakeholder_jabatan,
                'stakeholder_singkatan'     => $stakeholder->stakeholder_singkatan,
            ];

            switch ($item->konsultasiStakeholder_ket) {
                case 'stakeholder':
                    $this->konsultasi_stakeholder[] = $data;
                    break;
                case 'fasil':
                    $this->konsultasi_fasil[] = $data;
                    break;
            }
        }

        // OPEN MODAL KONSULTASI
        $this->openModalKonsultasi();
    }

    // SHOW KONSULTASI
    public function showKonsultasi($id)
    {
        // IS SHOW
        $this->isShowKonsultasi = true;

        // IS EDIT
        $this->isEditKonsultasi = false;

        // FIND RISK
        $risk                   = Risk::with(['kemungkinan', 'dampak', 'deteksiKegagalan'])->find($this->risk_id);

        // SET RISK DESC
        $this->risk_riskDesc    = $risk->risk_riskDesc;
        $this->risk_penyebab    = $risk->risk_penyebab;

        // PASSING KONSULTASI ID
        $this->konsultasi_id    = $id;

        // FIND KONSULTASI
        $konsultasi = Konsultasi::with('konsultasiStakeholder.stakeholder')->find($id);

        // PASSING DATA KONSULTASI
        $this->konsultasi_tujuan            = $konsultasi->konsultasi_tujuan;
        $this->konsultasi_konten            = $konsultasi->konsultasi_konten;
        $this->konsultasi_media             = $konsultasi->konsultasi_media;
        $this->konsultasi_metode            = $konsultasi->konsultasi_metode;

        // PASSING PLANS
        foreach ($konsultasi->konsultasiStakeholder as $item) {
            $stakeholder = $item->stakeholder;
            $data = [
                'konsultasiStakeholder_ket' => $item->konsultasiStakeholder_ket,
                'stakeholder_id'            => $item->stakeholder_id,
                'stakeholder_jabatan'       => $stakeholder->stakeholder_jabatan,
                'stakeholder_singkatan'     => $stakeholder->stakeholder_singkatan,
            ];

            switch ($item->konsultasiStakeholder_ket) {
                case 'stakeholder':
                    $this->konsultasi_stakeholder[] = $data;
                    break;
                case 'fasil':
                    $this->konsultasi_fasil[] = $data;
                    break;
            }
        }

        // OPEN MODAL KONSULTASI
        $this->openModalKonsultasi();
    }

    // DELETE KONSULTASI
    public function deleteKonsultasi()
    {
        // FIND KONSULTASI RECORD
        $konsultasi = Konsultasi::with(['konsultasiStakeholder'])->find($this->konsultasi_id);

        if ($konsultasi) {
            // Delete the Konsultasi record, which will also delete related konsultasiStakeholder records if cascading is set up
            $konsultasi->delete();

            // Optional: Add a success notification
            flash()
                ->option('position', 'bottom-right')
                ->option('timeout', 3000)
                ->success('Konsultasi and related records have been deleted successfully!');
        } else {
            // Optional: Handle the case where Konsultasi was not found
            flash()
                ->option('position', 'bottom-right')
                ->option('timeout', 3000)
                ->error('Konsultasi record not found!');
        }

        // CLOSE MODAL CONFIRM DELETE KONSULTASI
        $this->closeModalConfirmDeleteKonsultasi();
    }

    // LOCK KONSULTASI
    public function lockKonsultasi()
    {
        // FIND KONSULTASI RECORD
        $konsultasi = Konsultasi::with(['konsultasiStakeholder'])->find($this->konsultasi_id);

        if ($konsultasi) {
            // Delete the Konsultasi record, which will also delete related konsultasiStakeholder records if cascading is set up
            $konsultasi->update(['konsultasi_lockStatus' => 1]);

            // Optional: Add a success notification
            flash()
                ->option('position', 'bottom-right')
                ->option('timeout', 3000)
                ->success('Konsultasi and related records have been deleted successfully!');
        } else {
            // Optional: Handle the case where Konsultasi was not found
            flash()
                ->option('position', 'bottom-right')
                ->option('timeout', 3000)
                ->error('Konsultasi record not found!');
        }

        // CLOSE MODAL CONFIRM LOCK KONSULTASI
        $this->closeModalConfirmKonsultasi();
    }


    // LIFE CYCLE HOOKS KONSULTASI
    // OPEN MODAL LIVEWIRE 
    public $isOpenKonsultasi              = 0;
    public $isOpenConfirmKonsultasi       = 0;
    public $isOpenConfirmDeleteKonsultasi = 0;

    public $isEditKonsultasi, $isShowKonsultasi;

    // OPEN MODAL Konsultasi
    public function openModalKonsultasi()
    {
        $this->isOpenKonsultasi = true;
    }

    // CLOSE MODAL KONSULTASI
    public function closeModalKonsultasi()
    {
        $this->isOpenKonsultasi = false;

        // IS SHOW
        $this->isShowKonsultasi = false;

        // IS EDIT
        $this->isEditKonsultasi = false;

        // Reset form fields and close the modal
        $this->resetFormKonsultasi();

        // Reset form validation
        $this->resetValidation();
    } 

    // CLOSE MODAL KONSULTASI
    public function closeXModalKonsultasi()
    {
        $this->isOpenKonsultasi = false;

        // IS SHOW
        $this->isShowKonsultasi = false;

        // IS EDIT
        $this->isEditKonsultasi = false;

        // Reset form fields and close the modal
        $this->resetFormKonsultasi();

        // Reset form validation
        $this->resetValidation();
    } 

    // OPEN MODAL CONFIRM KONSULTASI
    public function openModalConfirmKonsultasi($id)
    {
        // SET KONSULTASI ID
        $this->konsultasi_id              = $id;

        $this->isOpenConfirmKonsultasi    = true;
    }

    // CLOSE MODAL CONFIRM KONSULTASI
    public function closeModalConfirmKonsultasi()
    {
        $this->isOpenConfirmKonsultasi = false;
    } 
  
    // CLOSE MODAL CONFIRM Konsultasi
    public function closeXModalConfirmKonsultasi()
    {
        $this->isOpenConfirmKonsultasi = false;
    } 
    
    // OPEN MODAL CONFIRM DELETE Konsultasi
    public function openModalConfirmDeleteKonsultasi($id)
    {
        $this->closeModalKonsultasi();

        // SET KPI
        $this->konsultasi_id = $id;

        $this->isOpenConfirmDeleteKonsultasi = true;
    }

    // CLOSE MODAL CONFIRM DELETE Konsultasi
    public function closeModalConfirmDeleteKonsultasi()
    {
        $this->isOpenConfirmDeleteKonsultasi = false;
    } 

    // CLOSE MODAL CONFIRM DELETE Konsultasi
    public function closeXModalConfirmDeleteKonsultasi()
    {
        $this->isOpenConfirmDeleteKonsultasi = false;
    } 

    // CETAK KONSULTASI
    public $isOpenCetakKonsultasi = 0;
    
    // OPEN CETAK KONSULTASI
    public function openCetakKonsultasi($kpi_id)
    {
        $this->isOpenCetakKonsultasi = 1;

        $this->kpi_id  = $kpi_id;
    }

    // CLOSE CETAK KONSULTASI
    public function closeCetakKonsultasi()
    {
        $this->isOpenCetakKonsultasi = 0;
    }
    // CLOSE CETAK KONSULTASI
    public function closeXCetakKonsultasi()
    {
        $this->isOpenCetakKonsultasi = 0;
    }

    // PRINT KONSULTASI
    public function printKonsultasi()
    {
        // KPIS FIND
        $kpis = KPI::with([
            'unit',
            'konteks.risk.konsultasi.konsultasiStakeholder.stakeholder',
        ])
        ->where('kpi_lockStatus', true)
        ->where('kpi_activeStatus', true)
        ->whereHas('konteks.risk', function ($query) {
            $query->where('risk_id', $this->risk_id);
        })
        ->find($this->kpi_id);
        
        // FIND UNIT
        // SET UNIT KPI AND PEMILIK KPI
        $this->unit_nama = $kpis->unit->unit_name;

        // FIND RISK OWNER
        $roleToFind         = "risk owner";
        $usersWithRole      = $this->searchUsersWithRole($kpis->unit->user, $roleToFind);
        $this->user_pemilik = ucwords($usersWithRole[0]->name);
        
        // Create Dompdf instance
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true); // Enable HTML5 parser
        $options->set('isPhpEnabled', true); // Enable PHP

        $formatPaper = 'landscape'; // Corrected spelling

        // Set the page size and margins
        $options->set('size', 'A4'); // Use standard A4 size
        $options->set('margin-left', 0);
        $options->set('margin-right', 0);
        $options->set('margin-top', 0);
        $options->set('margin-bottom', 0);

        // Create a new Dompdf instance
        $dompdf = new Dompdf($options);

        // Load the HTML view with the data
        $html = view('livewire.pages.risk-owner.risk-control.komunikasi-konsultasi.print-layout.konsultasi-layout', [
            'kpis'          => $kpis,
            'user_pemilik'  => $this->user_pemilik,
            'unit_nama'     => $this->unit_nama,
            'risk_id'       => $this->risk_id,
        ])->render();

        // Load the HTML into Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Set paper size and orientation
        $dompdf->setPaper('A4', $formatPaper);

        // Render the HTML as PDF
        $dompdf->render();

        // Get the PDF content as a string
        $pdfContent = $dompdf->output();

        // Return the PDF inline in the browser
        // return response($pdfContent)
        //     ->header('Content-Type', 'application/pdf')
        //     ->header('Content-Disposition', 'inline; filename="raci-' . $kpis->kpi_kode . '.pdf"');

        // CLOSE MODAL CETAK KONSULTASI
        $this->closeCetakKonsultasi();

        // Option 1: Return the PDF as a download
        // Return the PDF content with appropriate headers
        return response()->streamDownload(function () use ($pdfContent) {
            echo $pdfContent;
        }, 'KONSULTASI-' . $kpis->kpi_kode . '.pdf');

    }

    // RESET FORM Konsultasi
    public function resetFormKonsultasi()
    {
        $this->isEditKonsultasi       = false;
        $this->isShowKonsultasi       = false;

        // CLEAR KONSULTASI
        $this->konsultasi_id                = '';
        $this->konsultasi_stakeholder       = [];     
        $this->konsultasi_fasil             = [];   
        $this->konsultasi_tujuan            = '';   
        $this->konsultasi_media             = '';   
        $this->konsultasi_konten            = '';   
        $this->konsultasi_metode            = '';   
    }




    // SET TOGGLE STATUS RTM
    public $isActiveRTM = 'No RTM';

    // TOGGLE ACTIVE RTM RISK
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
