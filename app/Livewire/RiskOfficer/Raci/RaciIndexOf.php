<?php

namespace App\Livewire\RiskOfficer\Raci;

use App\Models\KPI;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Livewire\WithPagination;

class RaciIndexOf extends Component
{
    use WithPagination, WithFileUploads;

    // TITLE COMPONENT
    #[Title('RACI')]
    public $title;

    // GLOBAL VARIABLES
    public $perPage     = 5;
    public $search      = '';
    public $searchPeriod = '';

    public $years = [];

    // VARIABLES LIST KPI MODEL
    public $kpi_id, $kpi, $kpi_kode, $kpi_nama, $unit_id, $unit_nama, $user_pemilik;

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

        // RECEIVE KPI ID
        $encryptedId = request()->query('kpi');
        $this->kpi_id = Crypt::decryptString($encryptedId);
        
        // FIND UNIT SPECIFIC
        $this->kpi = KPI::with(['unit.user'])->where('kpi_id', $this->kpi_id)->first();

        // SET NAMA DAN KODE KPI
        $this->kpi_nama = $this->kpi->kpi_nama;
        $this->kpi_kode = $this->kpi->kpi_kode;
        $this->title    = $this->kpi->kpi_kode;

        // PASSING UNIT ID
        $this->unit_id  = $this->kpi->unit->unit_id;

        // SET UNIT KPI AND PEMILIK KPI
        $this->unit_nama = $this->kpi->unit->unit_name;

        // Usage
        $roleToFind         = "risk owner";
        $usersWithRole      = $this->searchUsersWithRole($this->kpi->unit->user, $roleToFind);
        $this->user_pemilik = ucwords($usersWithRole[0]->name);
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

    // COMPONENT RENDER
    public function render()
    {
        $kpis = KPI::with(['unit', 'kategoriStandar', 'konteks.risk.raci.stakeholder'])->where('unit_id', $this->unit_id)
            ->where('kpi_lockStatus', true)    
            ->where('kpi_activeStatus', true)  
            ->find($this->kpi->kpi_id);
        
        return view('livewire.pages.risk-owner.raci.raci-index', [
            'kpis'             => $kpis,
        ]);
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
}
