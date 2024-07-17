<?php

use App\Livewire\Dashboard;
use App\Livewire\Lembaga\KPI;
use App\Livewire\Lembaga\KPIUnit;
use App\Livewire\Lembaga\LaporanManajemenRisiko;
use App\Livewire\Lembaga\ListKPILaporan;
use App\Livewire\Lembaga\ListKPIUnit;
use App\Livewire\Lembaga\Stakeholders;
use App\Livewire\Lembaga\VisiMisi;
use App\Livewire\RiskOwner\IdentifikasiRisikoOw;
use App\Livewire\RiskOwner\KonteksRisikoOw;
use App\Livewire\RiskOwner\ListKPIRiskOwner;
use App\Livewire\UMR\ManajemenKategoriStandar;
use App\Livewire\UMR\ManajemenUnit;
use App\Livewire\UMR\ManajemenUser;
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

    // ROUTE UNIT MANAJEMEN RISIKO END ############################################################################


    // ROUTE RISK OWNER END ############################################################################

    // RISK REGISTER
    Route::get('/risk-owner/risk-register-index', ListKPIRiskOwner::class)->name('riskRegisterOw.index');

    // KONTEKS RISIKO
    Route::get('/risk-owner/konteks-risiko', KonteksRisikoOw::class)->name('konteksRisikoOw.index');

    // IDENTIFIKASI RISIKO
    Route::get('/risk-owner/risk-register', IdentifikasiRisikoOw::class)->name('identifikasiRisiko.index');

    
    // ROUTE RISK OWNER ################################################################################

});


// Route::view('dashboard', 'dashboard')
//     ->middleware(['auth', 'verified'])
//     ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
