<?php

use App\Livewire\Dashboard;
use App\Livewire\UMR\ManajemenUser;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Volt::route('/', 'pages.auth.login')
    ->name('login');

Route::group(['middleware' => ['auth']], function(){

    // DASHBOARD ROUTE
    Route::get('/dashboard', Dashboard::class)->name('dashboard.index');

    // MANAJEMEN USER
    Route::get('/manajemen-user', ManajemenUser::class)->name('manajemenUser.index');

});


// Route::view('dashboard', 'dashboard')
//     ->middleware(['auth', 'verified'])
//     ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
