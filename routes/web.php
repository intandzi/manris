<?php

use App\Livewire\Dashboard;
use App\Livewire\Lembaga\KPI;
use App\Livewire\Lembaga\KPIUnit;
use App\Livewire\Lembaga\LaporanManajemenRisiko;
use App\Livewire\Lembaga\ListKPILaporan;
use App\Livewire\Lembaga\ListKPIUnit;
use App\Livewire\Lembaga\Stakeholders;
use App\Livewire\Lembaga\VisiMisi;
use App\Livewire\RiskOfficer\Laporan\ListKPILaporanOf;
use App\Livewire\RiskOfficer\Raci\ListKPIRACIOf;
use App\Livewire\RiskOfficer\Raci\RaciIndexOf;
use App\Livewire\RiskOfficer\RiskControl\KonteksRisikoOfRiskControl;
use App\Livewire\RiskOfficer\RiskControl\ListKPIRiskOfficerRiskControl;
use App\Livewire\RiskOfficer\RiskControl\ListRiskOf;
use App\Livewire\RiskOfficer\RiskControl\RiskControlOf;
use App\Livewire\RiskOfficer\RiskRegister\KonteksRisikoOfRiskRegister;
use App\Livewire\RiskOfficer\RiskRegister\ListKPIRiskOfficerRiskRegister;
use App\Livewire\RiskOfficer\RiskRegister\RiskRegisterOf;
use App\Livewire\RiskOwner\Laporan\ListKPILaporanOw;
use App\Livewire\RiskOwner\Raci\ListKPIRACIOw;
use App\Livewire\RiskOwner\Raci\RaciIndexOw;
use App\Livewire\RiskOwner\RiskControl\KonteksRisikoOwRiskControl;
use App\Livewire\RiskOwner\RiskControl\ListKPIRiskOwnerRiskControl;
use App\Livewire\RiskOwner\RiskControl\ListRiskOw;
use App\Livewire\RiskOwner\RiskControl\RiskControlOw;
use App\Livewire\RiskOwner\RiskRegister\KonteksRisikoOwRiskRegister;
use App\Livewire\RiskOwner\RiskRegister\ListKPIRiskOwnerRiskRegister;
use App\Livewire\RiskOwner\RiskRegister\RiskRegisterOw;
use App\Livewire\UMR\Laporan\LaporanManajemenRisikoUMR;
use App\Livewire\UMR\Laporan\ListKPILaporanUMR;
use App\Livewire\UMR\ListKPIUMR;
use App\Livewire\UMR\ListUnitUMR;
use App\Livewire\UMR\LogKonteksRisiko;
use App\Livewire\UMR\LogRiskRegister;
use App\Livewire\UMR\ManajemenKategoriStandar;
use App\Livewire\UMR\ManajemenPenilaianEfektifitas;
use App\Livewire\UMR\ManajemenPenilaianRisiko;
use App\Livewire\UMR\ManajemenSeleraRisiko;
use App\Livewire\UMR\ManajemenUnit;
use App\Livewire\UMR\ManajemenUser;
use App\Livewire\UMR\SeleraRisiko;
use App\Livewire\UserRolePortal;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Volt::route('/', 'pages.auth.login')
    ->name('login');

Route::group(['middleware' => ['auth', 'role:lembaga|unit manajemen risiko|risk owner|risk officer']], function(){
        
    // Route::get('/portal-login', UserRolePortal::class)->middleware('auth')->name('select-role');

    // DASHBOARD ROUTE
    Route::get('/lembaga/dashboard', Dashboard::class)->name('lembaga.dashboard');
    Route::get('/unit-manajemen-risiko/dashboard', Dashboard::class)->name('unit manajemen risiko.dashboard');
    Route::get('/risk-owner/dashboard', Dashboard::class)->name('risk owner.dashboard');
    Route::get('/risk-officer/dashboard', Dashboard::class)->name('risk officer.dashboard');


    // ROUTE LEMBAGA ##################################################################################################

    // VISI MISI
    Route::get('/lembaga/visi-misi', VisiMisi::class)->name('visimisi.index');

    // KPI UNIT
    Route::get('/lembaga/kpi-unit', KPI::class)->name('kpiUnit.index');

    // LIST KPI UNIT
    Route::get('/lembaga/list-kpi-unit', ListKPIUnit::class)->name('listKPIUnit.index');

    // LAPORAN MANAJEMEN RISIKO
    Route::get('/lembaga/laporan-manajemen-risiko', LaporanManajemenRisiko::class)->name('laporan-mrisk.index');

    // LIST LAPORAN KPI
    Route::get('/lembaga/laporan-daftar-kpi', ListKPILaporan::class)->name('listKPILaporan.index');

    // STAKEHOLDERS
    Route::get('/lembaga/pemangku-kepentingan', Stakeholders::class)->name('stakeholders.index');

    // ROUTE LEMBAGA END ##############################################################################################


    // ROUTE UNIT MANAJEMEN RISIKO ###############################################################################

    // MANAJEMEN USER
    Route::get('/unit-manajemen-risiko/manajemen-user', ManajemenUser::class)->name('manajemenUser.index');

    // MANAJEMEN UNIT
    Route::get('/unit-manajemen-risiko/manajemen-unit', ManajemenUnit::class)->name('manajemenUnit.index');

    // MANAJEMEN KATEGORI STANDAR
    Route::get('/unit-manajemen-risiko/manajemen-kategori-standar', ManajemenKategoriStandar::class)->name('manajemenKategoriStandar.index');

    // MANAJEMEN EFEKTIFITAS ASESSMENT
    Route::get('/unit-manajemen-risiko/manajemen-assesment-kontrol-risiko', ManajemenPenilaianEfektifitas::class)->name('manajemenPenilaianEfektifitas.index');

    // MANAJEMEN SELERA RISIKO
    Route::get('/unit-manajemen-risiko/manajemen-selera-risiok', ManajemenSeleraRisiko::class)->name('manajemenSeleraRisiko.index');

    // KPI UNIT
    Route::get('/unit-manajemen-risiko/kpi-unit', ListUnitUMR::class)->name('kpiUnitUMR.index');

    // LIST KPI UNIT
    Route::get('/unit-manajemen-risiko/list-kpi-unit', ListKPIUMR::class)->name('listKPIUMR.index');

    // KONTEKS RISIKO
    Route::get('/unit-manajemen-risiko/konteks-risiko', LogKonteksRisiko::class)->name('logKonteksRisiko.index');

    // RISK REGISTER
    Route::get('/unit-manajemen-risiko/risk-register', LogRiskRegister::class)->name('logRiskRegister.index');

    // LAPORAN MANAJEMEN RISIKO
    Route::get('/unit-manajemen-risiko/laporan-manajemen-risiko', LaporanManajemenRisikoUMR::class)->name('laporan-mrisk-umr.index');

    // LIST LAPORAN KPI
    Route::get('/unit-manajemen-risiko/laporan-daftar-kpi', ListKPILaporanUMR::class)->name('listKPILaporan-umr.index');


    // ROUTE UNIT MANAJEMEN RISIKO END ############################################################################


    // ROUTE RISK OWNER START ############################################################################

    // RISK REGISTER
    Route::get('/risk-owner/risk-register-index', ListKPIRiskOwnerRiskRegister::class)->name('riskRegisterOw.index');

    // KONTEKS RISIKO
    Route::get('/risk-owner/risk-register-konteks-risiko', KonteksRisikoOwRiskRegister::class)->name('registerKonteksRisikoOw.index');

    // RISK REGISTER STEPS
    Route::get('/risk-owner/risk-register', RiskRegisterOw::class)->name('riskRegisterStepOw.index');


    // RISK CONTROL
    Route::get('/risk-owner/risk-control-index', ListKPIRiskOwnerRiskControl::class)->name('riskControlOw.index');

    // KONTEKS RISIKO
    Route::get('/risk-owner/risk-control-konteks-risiko', KonteksRisikoOwRiskControl::class)->name('controlKonteksRisikoOw.index');

    // LIST RISIKO
    Route::get('/risk-owner/list-risk', ListRiskOw::class)->name('listRiskOw.index');

    // RISK CONTROL STEPS
    Route::get('/risk-owner/risk-control', RiskControlOw::class)->name('riskControlStep.index');

    // LIST KPI RACI
    Route::get('/risk-owner/raci/list-kpi', ListKPIRACIOw::class)->name('liskKpiRaciOw.index');

    // RACI INDEX
    Route::get('/risk-owner/raci/raci-index', RaciIndexOw::class)->name('raciOw.index');

    // LAPORAN INDEX
    Route::get('/risk-owner/laporan/laporan-index', ListKPILaporanOw::class)->name('laporanOw.index');

    // ROUTE RISK OWNER END ################################################################################


    // ROUTE RISK OFFICER START ############################################################################

    // RISK REGISTER
    Route::get('/risk-officer/risk-register-index', ListKPIRiskOfficerRiskRegister::class)->name('officerRiskRegisterOf.index');

    // KONTEKS RISIKO
    // KONTEKS RISIKO
    Route::get('/risk-officer/risk-register-konteks-risiko', KonteksRisikoOfRiskRegister::class)->name('officerRegisterKonteksRisikoOf.index');


    // RISK REGISTER STEPS
    Route::get('/risk-officer/risk-register', RiskRegisterOf::class)->name('officerRiskRegisterStep.index');


    // RISK CONTROL
    Route::get('/risk-officer/risk-control-index', ListKPIRiskOfficerRiskControl::class)->name('officerRiskControl.index');

    // KONTEKS RISIKO
    Route::get('/risk-officer/risk-control-konteks-risiko', KonteksRisikoOfRiskControl::class)->name('officerControlKonteksRisiko.index');

    // LIST RISIKO
    Route::get('/risk-officer/list-risk', ListRiskOf::class)->name('officerListRisk.index');

    // RISK CONTROL STEPS
    Route::get('/risk-officer/risk-control', RiskControlOf::class)->name('officerRiskControlStep.index');

    // LIST KPI RACI
    Route::get('/risk-officer/raci/list-kpi', ListKPIRACIOf::class)->name('liskKpiRaciOf.index');

    // RACI INDEX
    Route::get('/risk-officer/raci/raci-index', RaciIndexOf::class)->name('raciOf.index');

    // LAPORAN INDEX
    Route::get('/risk-officer/laporan/laporan-index', ListKPILaporanOf::class)->name('laporanOf.index');

    
    // ROUTE RISK OWNER END ################################################################################

});


// Route::view('dashboard', 'dashboard')
//     ->middleware(['auth', 'verified'])
//     ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
