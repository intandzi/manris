<?php

namespace App\Livewire;

use Livewire\Attributes\Title;
use Livewire\Component;

class Dashboard extends Component
{
    // TITLE COMPONENT
    #[Title('Dashboard')]

    // RENDER COMPONENT
    public function render()
    {
        return view('livewire.dashboard');
    }
}
