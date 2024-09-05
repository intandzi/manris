<?php

namespace App\Livewire;

use App\Models\KPI;
use App\Models\Risk;
use App\Models\Unit;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class Dashboard extends Component
{
    use WithPagination;

    // TITLE COMPONENT
    #[Title('Dashboard')]

    public $title;

    // GLOBAL VARIABLES
    public $perPage     = 10;
    public $search      = '';
    public $searchPeriod = '';

    public $years = [], $periodYears = [];

    // VARIABLES LIST KPI MODEL
    public $kpi_id, $kpi, $kpi_kode, $kpi_nama, $unit_nama, $user_pemilik;

    // VARIABLES MODEL KONTEKS
    public $konteks, $konteks_id, $konteks_kode, $konteks_kategori, $konteks_desc;

    // VARIABLES RISK
    public $risk_id;

    // VARIABLE KEY
    public $role, $encryptedRole, $encryptedKPI;

    public $totalKPI       = 0, $totalRisk      = 0, $totalControl   = 0;

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


    // CONTRUCTOR COMPONENT
    public function mount()
    {
        // RECEIVE ROLE USER
        $this->role = Crypt::decryptString(request()->query('role'));
        
        // RETRIVE ROLE USER
        $this->encryptedRole = Crypt::encryptString($this->role);

        // Get the current year
        $currentYear = Carbon::now()->year;

        // Generate years for the select options
        for ($i = $currentYear - 4; $i <= $currentYear + 4; $i++) {
            $this->years[$i] = $i;
        }

        // Generate period years for the select options
        for ($i = $currentYear - 20; $i <= $currentYear + 10; $i++) {
            $this->periodYears[$i] = $i;
        }

        // Determine the query base
        $query = KPI::with([
            'unit',
            'konteks.risk.controlRisk.perlakuanRisiko.jenisPerlakuan',
            'konteks.risk.controlRisk.derajatRisiko.seleraRisiko',
            'konteks.risk.controlRisk.perlakuanRisiko.rencanaPerlakuan',
            'konteks.risk.controlRisk.efektifitasControl.detailEfektifitasKontrol', // Ensure detailEfektifitasKontrol exists
        ])
        ->where('kpi_lockStatus', true)
        ->where('kpi_activeStatus', true);

        // If the role matches 'risk owner' or 'risk officer', filter by unit_id
        if (in_array($this->role, ['risk owner', 'risk officer'])) {
            $unit_id = Auth::user()->unit_id;
            $query->where('unit_id', $unit_id); // search by unit
        }

        // Paginate the results
        $kpis = $query->get();

        // Calculate the counts
        foreach ($kpis as $kpi) {
            $this->totalKPI++; // Count each KPI
            foreach ($kpi->konteks as $konteks) {
                $this->totalRisk += $konteks->risk->count(); // Count risks
                foreach ($konteks->risk as $risk) {
                    $this->totalControl += $risk->controlRisk->count(); // Count controls
                }
            }
        }
        
        // Unit
        $unit = Unit::find(Auth::user()->unit_id);

        // FIND USER PEMILIK
        // $roleToFind         = "risk owner";
        // $usersWithRole      = $this->searchUsersWithRole($this->kpi->unit->user, $roleToFind);
        // $this->user_pemilik = ucwords($usersWithRole[0]->name);
    }

    // RENDER COMPONENT
    public function render()
    {
        // Determine the query base
        $query = KPI::with([
            'unit',
            'konteks.risk.controlRisk.perlakuanRisiko.jenisPerlakuan',
            'konteks.risk.controlRisk.derajatRisiko.seleraRisiko',
            'konteks.risk.controlRisk.perlakuanRisiko.rencanaPerlakuan',
            'konteks.risk.controlRisk.efektifitasControl.detailEfektifitasKontrol', // Ensure detailEfektifitasKontrol exists
        ])
        ->whereHas('konteks.risk', function ($query) {
            $query->where(function ($query) {
                $query->where('risk_kode', 'like', '%' . $this->search . '%')
                    ->orWhere('risk_riskDesc', 'like', '%' . $this->search . '%')
                    ->orWhere('risk_penyebabKode', 'like', '%' . $this->search . '%')
                    ->orWhere('risk_penyebab', 'like', '%' . $this->search . '%');
            });
        })
        // ->whereHas('konteks.risk.controlRisk', function ($query) {
        //     $query->where(function ($query) {
        //         $query->where('controlRisk_RPN', 'like', '%' . $this->search . '%')
        //             ->orWhereHas('derajatRisiko', function ($query) {
        //                 $query->where('derajatRisiko_desc', 'like', '%' . $this->search . '%');
        //             });
        //     });
        // })
        ->when($this->searchPeriod, function($query) {
            $query->where('kpi_periode', 'like', '%' . $this->searchPeriod . '%');
        })
        ->where('kpi_lockStatus', true)
        ->where('kpi_activeStatus', true);

        // If the role matches 'risk owner' or 'risk officer', filter by unit_id
        if (in_array($this->role, ['risk owner', 'risk officer'])) {
            $unit_id = Auth::user()->unit_id;
            $query->where('unit_id', $unit_id); // search by unit
        }

        // Paginate the results
        $kpis = $query->paginate($this->perPage);
    
        // Unit
        $unit = Unit::find(Auth::user()->unit_id);

        return view('livewire.pages.dashboard.dashboard', [
            'kpis'              => $kpis,
            'unit'              => $unit,
            'paginationInfo'    => $kpis->total() > 0
                ? "Showing " . ($kpis->firstItem()) . " to " . ($kpis->lastItem()) . " of " . ($kpis->total()) . " entries"
                : "No entries found",
        ]);
    }


    // SORTING DATA IDENTIFIKASI RISIKO
    public $orderBy     = 'risk_id';
    public $orderAsc    = true;
    public function doSortRisk($column)
    {
        if ($this->orderBy === $column) {
            $this->orderAsc = !$this->orderAsc; // Toggle the sorting order
        } else {
            $this->orderBy = $column;
            $this->orderAsc = true; // Default sorting order when changing the column
        }
    }

    // CETAK RISK CONTROL
    public $isOpenCetakRiskControl = 0;

    public $cetakRiskControl;

    // PRINT RISK CONTROL
    public function printRiskControl()
    {
        // FIND KPI
        $kpis = KPI::with([
            'unit',
            'konteks.risk.controlRisk.perlakuanRisiko.jenisPerlakuan',
            'konteks.risk.controlRisk.derajatRisiko.seleraRisiko',
            'konteks.risk.controlRisk.perlakuanRisiko.rencanaPerlakuan',
            'konteks.risk.controlRisk.efektifitasControl.detailEfektifitasKontrol', // Ensure detailEfektifitasKontrol exists
        ])
        ->where('kpi_lockStatus', true)
        ->where('kpi_activeStatus', true)
        ->find($this->kpi_id);

        // FIND UNIT
        // SET UNIT KPI AND PEMILIK KPI
        $this->unit_nama = $kpis->unit->unit_name;

        // FIND RISK OWNER
        $roleToFind         = "risk owner";
        $usersWithRole      = $this->searchUsersWithRole($kpis->unit->user, $roleToFind);
        $this->user_pemilik = ucwords($usersWithRole[0]->name);

        // FIND RISK
        $risk               = Risk::find($this->risk_id);
        
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
        $html = view('livewire.pages.dashboard.print-layout.risk-control-layout', [
            'kpis'          => $kpis,
            'dataRisk'      => $risk,
            'user_pemilik'  => $this->user_pemilik,
            'unit_nama'     => $kpis->unit->unit_name,
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

        // CLOSE MODAL CETAK RISK CONTROL
        $this->closeCetakRiskControl();

        // Option 1: Return the PDF as a download
        // Return the PDF content with appropriate headers
        return response()->streamDownload(function () use ($pdfContent) {
            echo $pdfContent;
        }, 'RISK-CONTROL-' . $kpis->kpi_kode . '.pdf');

    }

    // OPEN CETAK RISK REGISTER
    public function openCetakRiskControl($kpi_id, $risk_id)
    {
        $this->isOpenCetakRiskControl = 1;

        $this->kpi_id  = $kpi_id;

        $this->risk_id = $risk_id;
    }

    // CLOSE CETAK RISK REGISTER
    public function closeCetakRiskControl()
    {
        $this->isOpenCetakRiskControl = 0;
    }
    // CLOSE CETAK RISK REGISTER
    public function closeXCetakRiskControl()
    {
        $this->isOpenCetakRiskControl = 0;
    }


    // Reset pagination when search or searchPeriod changes
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSearchPeriod()
    {
        $this->resetPage();
    }

    // CLEAR SEARCH
    public function clearSearch()
    {
        $this->search = '';
    }

    // CLEAR SEARCH
    public function clearSearchPeriod()
    {
        $this->searchPeriod = '';
    }
}
