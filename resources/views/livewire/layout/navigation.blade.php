<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component {
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<div class="leftside-menu">

    <!-- Logo Light -->
    <a href="index.html" class="logo logo-light">
        <span class="logo-lg">
            <img src="{{ asset('assets/images/logo.png') }}" alt="logo">
        </span>
        <span class="logo-sm">
            <img src="{{ asset('assets/images/logo-sm.png') }}" alt="small logo">
        </span>
    </a>

    <!-- Logo Dark -->
    <a href="index.html" class="logo logo-dark">
        <span class="logo-lg">
            <img src="{{ asset('assets/images/logo-dark.png') }}" alt="dark logo">
        </span>
        <span class="logo-sm">
            <img src="{{ asset('assets/images/logo-sm.png') }}" alt="small logo">
        </span>
    </a>

    <!-- Sidebar -->
    <div data-simplebar>
        <ul class="side-nav">

            <li class="side-nav-title">Main</li>

            <li class="side-nav-item {{ request()->routeIs('dashboard.index') ? 'menuitem-active' : '' }}">
                <a href="{{ route('dashboard.index') }}" class="side-nav-link {{ request()->routeIs('dashboard.index') ? 'active' : '' }}" wire:navigate>
                    <i class="ri-dashboard-2-line"></i>
                    <span> Dashboard </span>
                    <span class="badge bg-success float-end">9+</span>
                </a>
            </li>

            <li class="side-nav-title">App</li>

            <li class="side-nav-item {{ request()->routeIs('manajemenUser.index') ? 'menuitem-active' : '' }}">
                <a href="{{ route('manajemenUser.index') }}" class="side-nav-link {{ request()->routeIs('manajemenUser.index') ? 'active' : '' }}" wire:navigate>
                    <i class="ri-user-3-line"></i>
                    <span> Manajemen User</span>
                </a>
            </li>
            <li class="side-nav-item {{ request()->routeIs('manajemenUser.index') ? 'menuitem-active' : '' }}">
                <a href="{{ route('manajemenUser.index') }}" class="side-nav-link {{ request()->routeIs('manajemenUser.index') ? 'active' : '' }}" wire:navigate>
                    <i class="ri-community-line"></i>
                    <span> Manajemen Unit</span>
                </a>
            </li>

        </ul>
    </div>
</div>
