<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component {
    // CONSTRUCTOR CLASS
    public function mount()
    {
        $this->role = Crypt::decryptString(request()->query('role'));
        // try {
        //     $this->role = Crypt::decryptString($request->query('role'));
        // } catch (\Exception $e) {
        //     // Jika terjadi kesalahan saat dekripsi, redirect ke halaman login
        //     // return redirect()->route('login');
        //     return 'gagal';
        // }
    }

    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>


{{-- REQUEST QUERY ROLE --}}
@php
    $encryptedRole = request()->query('role');
    $role = $encryptedRole ? Crypt::decryptString($encryptedRole) : null;
@endphp

<div class="leftside-menu">

    <!-- Logo Light -->
    <a href="#" class="logo logo-light">
        <span class="logo-lg">
            <img src="{{ asset('assets/images/logo2.png') }}" alt="logo">
        </span>
        <span class="logo-sm">
            <img src="{{ asset('assets/images/logo-sm.png') }}" alt="small logo">
        </span>
    </a>

    <!-- Logo Dark -->
    <a href="#" class="logo logo-dark">
        <span class="logo-lg">
            <img src="{{ asset('assets/images/logo-dark2.png') }}" alt="dark logo">
        </span>
        <span class="logo-sm">
            <img src="{{ asset('assets/images/logo-sm.png') }}" alt="small logo">
        </span>
    </a>

    <!-- Sidebar -->
    <div data-simplebar>

        <div style="color: aliceblue" class="p-2">
            <p>Selamat Datang, <span style="font-weight: bold;">{{ ucwords(Auth::user()->name) }},
                </span> {{ ucwords(Auth::user()->jabatan) }}</p>
        </div>

        <ul class="side-nav">

            <li class="side-nav-title">Main</li>

            <li
                class="side-nav-item {{ request()->routeIs('lembaga.dashboard', 'unit manajemen risiko.dashboard', 'risk owner.dashboard', 'risk officer.dashboard') ? 'menuitem-active' : '' }}">
                <a href="{{ route('lembaga.dashboard', ['role' => request()->query('role')]) }}"
                    class="side-nav-link {{ request()->routeIs('lembaga.dashboard', 'unit manajemen risiko.dashboard', 'risk owner.dashboard', 'risk officer.dashboard') ? 'active' : '' }}"
                    wire:navigate>
                    <i class="ri-dashboard-2-line"></i>
                    <span> Dashboard </span>
                    <span class="badge bg-success float-end">9+</span>
                </a>
            </li>

            <li class="side-nav-title">App</li>

            @if ($role === 'lembaga')
                <li class="side-nav-item {{ request()->routeIs('visimisi.index') ? 'menuitem-active' : '' }}">
                    <a href="{{ route('visimisi.index', ['role' => request()->query('role')]) }}"
                        class="side-nav-link {{ request()->routeIs('visimisi.index') ? 'active' : '' }}" wire:navigate>
                        <i class="ri-task-line"></i>
                        <span> Visi Misi</span>
                    </a>
                </li>
                <li
                    class="side-nav-item {{ request()->routeIs('kpiUnit.index', 'listKPIUnit.index') ? 'menuitem-active' : '' }}">
                    <a href="{{ route('kpiUnit.index', ['role' => request()->query('role')]) }}"
                        class="side-nav-link {{ request()->routeIs('kpiUnit.index', 'listKPIUnit.index') ? 'active' : '' }}"
                        wire:navigate>
                        <i class="ri-stack-line"></i>
                        <span> KPI</span>
                    </a>
                </li>
                <li class="side-nav-item {{ request()->routeIs('stakeholders.index') ? 'menuitem-active' : '' }}">
                    <a href="{{ route('stakeholders.index', ['role' => request()->query('role')]) }}"
                        class="side-nav-link {{ request()->routeIs('stakeholders.index') ? 'active' : '' }}"
                        wire:navigate>
                        <i class="ri-team-line"></i>
                        <span> Pemangku Kepentingan</span>
                    </a>
                </li>
                <li
                    class="side-nav-item {{ request()->routeIs('laporan-mrisk.index', 'listKPILaporan.index') ? 'menuitem-active' : '' }}">
                    <a href="{{ route('laporan-mrisk.index', ['role' => request()->query('role')]) }}"
                        class="side-nav-link {{ request()->routeIs('laporan-mrisk.index', 'listKPILaporan.index') ? 'active' : '' }}"
                        wire:navigate>
                        <i class="ri-book-open-line"></i>
                        <span> Laporan Manajemen Risiko</span>
                    </a>
                </li>
            @endif


            @if ($role === 'unit manajemen risiko')
                <li class="side-nav-item {{ request()->routeIs('manajemenUser.index') ? 'menuitem-active' : '' }}">
                    <a href="{{ route('manajemenUser.index', ['role' => request()->query('role')]) }}"
                        class="side-nav-link {{ request()->routeIs('manajemenUser.index') ? 'active' : '' }}"
                        wire:navigate>
                        <i class="ri-user-3-line"></i>
                        <span> Manajemen User</span>
                    </a>
                </li>
                <li class="side-nav-item {{ request()->routeIs('manajemenUnit.index') ? 'menuitem-active' : '' }}">
                    <a href="{{ route('manajemenUnit.index', ['role' => request()->query('role')]) }}"
                        class="side-nav-link {{ request()->routeIs('manajemenUnit.index') ? 'active' : '' }}"
                        wire:navigate>
                        <i class="ri-community-line"></i>
                        <span> Manajemen Unit</span>
                    </a>
                </li>
                <li
                    class="side-nav-item {{ request()->routeIs('manajemenPenilaianEfektifitas.index') ? 'menuitem-active' : '' }}">
                    <a href="{{ route('manajemenPenilaianEfektifitas.index', ['role' => request()->query('role')]) }}"
                        class="side-nav-link {{ request()->routeIs('manajemenPenilaianEfektifitas.index') ? 'active' : '' }}"
                        wire:navigate>
                        <i class="ri-community-line"></i>
                        <span> Kelola Efektifitas Kontrol</span>
                    </a>
                </li>
                <li
                    class="side-nav-item {{ request()->routeIs('manajemenSeleraRisiko.index') ? 'menuitem-active' : '' }}">
                    <a href="{{ route('manajemenSeleraRisiko.index', ['role' => request()->query('role')]) }}"
                        class="side-nav-link {{ request()->routeIs('manajemenSeleraRisiko.index') ? 'active' : '' }}"
                        wire:navigate>
                        <i class="ri-community-line"></i>
                        <span> Kelola Selera Risiko</span>
                    </a>
                </li>
                <li
                    class="side-nav-item {{ request()->routeIs('manajemenKategoriStandar.index') ? 'menuitem-active' : '' }}">
                    <a href="{{ route('manajemenKategoriStandar.index', ['role' => request()->query('role')]) }}"
                        class="side-nav-link {{ request()->routeIs('manajemenKategoriStandar.index') ? 'active' : '' }}"
                        wire:navigate>
                        <i class="ri-community-line"></i>
                        <span> Kelola Kategori Standar</span>
                    </a>
                </li>
                <li class="side-nav-item {{ request()->routeIs('kpiUnitUMR.index', 'listKPIUMR.index', 'logKonteksRisiko.index', 'logRiskRegister.index') ? 'menuitem-active' : '' }}">
                    <a href="{{ route('kpiUnitUMR.index', ['role' => request()->query('role')]) }}"
                        class="side-nav-link {{ request()->routeIs('kpiUnitUMR.index') ? 'active' : '' }}"
                        wire:navigate>
                        <i class="ri-stack-line"></i>
                        <span> Validasi Risiko Unit</span>
                    </a>
                </li>
                <li class="side-nav-item {{ request()->routeIs('laporan-mrisk-umr.index', 'listKPILaporan-umr.index') ? 'menuitem-active' : '' }}">
                    <a href="{{ route('laporan-mrisk-umr.index', ['role' => request()->query('role')]) }}"
                        class="side-nav-link {{ request()->routeIs('laporan-mrisk-umr.index') ? 'active' : '' }}"
                        wire:navigate>
                        <i class="ri-book-line"></i>
                        <span> Laporan Manajemen Risiko</span>
                    </a>
                </li>
            @endif

            @if ($role === 'risk owner')
                <li
                    class="side-nav-item {{ request()->routeIs('riskRegisterOw.index', 'registerKonteksRisikoOw.index', 'riskRegisterStepOw.index') ? 'menuitem-active' : '' }}">
                    <a href="{{ route('riskRegisterOw.index', ['role' => request()->query('role')]) }}"
                        class="side-nav-link {{ request()->routeIs('riskRegisterOw.index', 'registerKonteksRisikoOw.index', 'riskRegisterStepOw.index') ? 'active' : '' }}"
                        wire:navigate>
                        <i class="ri-stack-line"></i>
                        <span> Risk Register</span>
                    </a>
                </li>
                <li
                    class="side-nav-item {{ request()->routeIs('riskControlOw.index', 'controlKonteksRisikoOw.index', 'riskControlStep.index', 'listRiskOw.index') ? 'menuitem-active' : '' }}">
                    <a href="{{ route('riskControlOw.index', ['role' => request()->query('role')]) }}"
                        class="side-nav-link {{ request()->routeIs('riskControlOw.index', 'controlKonteksRisikoOw.index', 'riskControlStep.index', 'listRiskOw.index') ? 'active' : '' }}"
                        wire:navigate>
                        <i class="ri-draft-line"></i>
                        <span> Risk Control</span>
                    </a>
                </li>
                <li
                    class="side-nav-item {{ request()->routeIs('liskKpiRaciOw.index', 'raciOw.index') ? 'menuitem-active' : '' }}">
                    <a href="{{ route('liskKpiRaciOw.index', ['role' => request()->query('role')]) }}"
                        class="side-nav-link {{ request()->routeIs('liskKpiRaciOw.index', 'raciOw.index') ? 'active' : '' }}"
                        wire:navigate>
                        <i class="ri-team-line"></i>
                        <span> RACI</span>
                    </a>
                </li>
                <li
                    class="side-nav-item {{ request()->routeIs('laporanOw.index') ? 'menuitem-active' : '' }}">
                    <a href="{{ route('laporanOw.index', ['role' => request()->query('role')]) }}"
                        class="side-nav-link {{ request()->routeIs('laporanOw.index') ? 'active' : '' }}"
                        wire:navigate>
                        <i class="ri-book-open-line"></i>
                        <span> Laporan Manajemen Risiko</span>
                    </a>
                </li>
            @endif

            @if ($role === 'risk officer')
                <li
                    class="side-nav-item {{ request()->routeIs('officerRiskRegisterOf.index', 'officerRegisterKonteksRisikoOf.index', 'officerRiskRegisterStep.index') ? 'menuitem-active' : '' }}">
                    <a href="{{ route('officerRiskRegisterOf.index', ['role' => request()->query('role')]) }}"
                        class="side-nav-link {{ request()->routeIs('officerRiskRegisterOf.index', 'officerRegisterKonteksRisikoOf.index', 'officerRiskRegisterStep.index') ? 'active' : '' }}"
                        wire:navigate>
                        <i class="ri-stack-line"></i>
                        <span> Risk Register</span>
                    </a>
                </li>
                <li
                    class="side-nav-item {{ request()->routeIs('officerRiskControl.index', 'officerControlKonteksRisiko.index', 'officerRiskControlStep.index', 'officerListRisk.index') ? 'menuitem-active' : '' }}">
                    <a href="{{ route('officerRiskControl.index', ['role' => request()->query('role')]) }}"
                        class="side-nav-link {{ request()->routeIs('officerRiskControl.index', 'officerControlKonteksRisiko.index', 'officerRiskControlStep.index', 'officerListRisk.index') ? 'active' : '' }}"
                        wire:navigate>
                        <i class="ri-draft-line"></i>
                        <span> Risk Control</span>
                    </a>
                </li>
                <li
                    class="side-nav-item {{ request()->routeIs('liskKpiRaciOf.index', 'raciOf.index') ? 'menuitem-active' : '' }}">
                    <a href="{{ route('liskKpiRaciOf.index', ['role' => request()->query('role')]) }}"
                        class="side-nav-link {{ request()->routeIs('liskKpiRaciOf.index', 'raciOf.index') ? 'active' : '' }}"
                        wire:navigate>
                        <i class="ri-team-line"></i>
                        <span> RACI</span>
                    </a>
                </li>
                <li
                    class="side-nav-item {{ request()->routeIs('laporanOf.index') ? 'menuitem-active' : '' }}">
                    <a href="{{ route('laporanOf.index', ['role' => request()->query('role')]) }}"
                        class="side-nav-link {{ request()->routeIs('laporanOf.index') ? 'active' : '' }}"
                        wire:navigate>
                        <i class="ri-book-open-line"></i>
                        <span> Laporan Manajemen Risiko</span>
                    </a>
                </li>
            @endif

            <li class="side-nav-title">Settings</li>
            <li class="side-nav-item">
                <a href="{{ route('select-role') }}" class="side-nav-link" wire:navigate>
                    <i class="ri-user-search-line"></i>
                    <span> Portal Peran</span>
                </a>
            </li>
            <li class="side-nav-item">
                <a href="#" wire:click.prevent="logout" type="button" class="side-nav-link">
                    <i class="ri-logout-box-r-line"></i>
                    <span>Logout</span>
                </a>
            </li>
        </ul>
    </div>
</div>
