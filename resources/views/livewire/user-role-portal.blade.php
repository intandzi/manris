<?php

use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.portal')] class extends Component {
    public $userRoles = [];

    public function mount()
    {
        $this->userRoles = json_decode(Auth::user()->role);
    }
}; ?>
<div>
    <div class="container mt-4">
        <div class="text-center mb-4">
            <img src="{{ asset('assets/images/logo-sm.png') }}" alt="Logo Untag" class="mb-3" style="height: 50px;">
            <h2>Pilih Peran Login</h2>
        </div>
        <div class="row justify-content-center">
            @foreach ($userRoles as $role)
                @php
                    $encryptedRole = Crypt::encryptString($role);
                @endphp
                <div class="col-6 mb-2">
                    <a href="{{ route(str_replace('_', '-', $role) . '.dashboard', ['role' => $encryptedRole]) }}"
                        class="text-decoration-none" wire:navigate>
                        <div class="card text-center p-2 {{ $loop->first ? 'border-primary' : '' }}"
                            style="cursor: pointer;">
                            <div class="card-body">
                                <div class="icon" style="font-size: 50px;">
                                    @switch($role)
                                        @case('lembaga')
                                            <i class="ri-user-follow-line"></i>
                                        @break

                                        @case('unit manajemen resiko')
                                            <i class="ri-user-2-line"></i>
                                        @break

                                        @case('risk owner')
                                            <i class="ri-group-line"></i>
                                        @break

                                        @case('risk officer')
                                            <i class="ri-team-line"></i>
                                        @break

                                        @default
                                            <i class="ri-team-line"></i>
                                    @endswitch
                                </div>
                                <h3 class="card-title">{{ ucfirst(str_replace('_', ' ', $role)) }}</h3>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
        {{-- <div class="text-center mt-2 mb-4">
            <a href="{{ url()->previous() }}" class="btn btn-secondary mr-2">Back</a>
            <a href="{{ route('lembaga.dashboard') }}" class="btn btn-primary">Continue</a>
        </div> --}}
    </div>
</div>
