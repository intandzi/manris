<?php

namespace App\Livewire\RiskOwner\Laporan;

use App\Models\KategoriStandar;
use App\Models\KonteksRisiko;
use App\Models\KPI;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Livewire\Attributes\Title;
use Livewire\Component;
use Dompdf\Dompdf;
use Dompdf\Options;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Livewire\WithPagination;

class ListKPILaporanOw extends Component
{
    use WithPagination, WithFileUploads;

    // TITLE COMPONENT
    #[Title('KPI Unit')]
    public $title;

    // GLOBAL VARIABLES
    public $perPage     = 5;
    public $search      = '';
    public $searchPeriod = '';

    public $years = [];

    // VARIABLES LIST KPI MODEL
    public $kpi_id, $kpi, $kpi_kode, $kpi_nama, $unit, $unit_id, $unit_nama, $user_pemilik;

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

        // RECEIVE UNIT ID
        $this->unit_id = Auth::user()->unit_id;
        
        // FIND UNIT SPECIFIC
        $this->unit = Unit::with(['kpi.kategoriStandar', 'visimisi'])->where('unit_id', $this->unit_id)->first();

        // SET TITLE
        $this->title = $this->unit->unit_name;

        // Get the current year
        $currentYear = Carbon::now()->year;

        // Generate years for the select options
        for ($i = $currentYear - 4; $i <= $currentYear + 4; $i++) {
            $this->years[$i] = $i;
        }
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
        $kpis = KPI::with(['unit', 'kategoriStandar', 'konteks.risk'])->where('unit_id', $this->unit_id)
            ->where('kpi_lockStatus', true)    
            ->where('kpi_activeStatus', true)    
            ->search($this->search)
            ->when($this->searchPeriod, function($query){
                $query->where('kpi_periode', 'like', '%' . $this->searchPeriod . '%');
            })
            ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')
            ->paginate($this->perPage);
        
        return view('livewire.pages.risk-owner.laporan-manrisk.list-kpi-laporan', [
            'kpis'             => $kpis,
            'paginationInfo'    => $kpis->total() > 0
            ? "Showing " . ($kpis->firstItem()) . " to " . ($kpis->lastItem()) . " of " . ($kpis->total()) . " entries"
            : "No entries found",
        ]);
    }

    
    // REDIRECT TO DETAIL KPI FOR RACI
    public function raciIndex($id)
    {
        // SENDING KPI ID
        $encryptedId    = Crypt::encryptString($id);
        // SENDING ROLE USER
        $encryptedRole  = Crypt::encryptString($this->role);

        $this->redirect(route('raciOf.index', ['role' => $encryptedRole, 'kpi' => $encryptedId]), navigate: true);
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

    // CETAK RISK REGISTER
    public $isOpenCetakRiskRegister = 0;

    public $cetakRiskRegister;

    // PRINT RISK REGISTER
    public function printRiskRegister()
    {
        // FIND KPI
        $kpis = KPI::with(['unit', 'kategoriStandar', 'konteks.risk.controlRisk'])
            ->where('unit_id', $this->unit_id)
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
        $html = view('livewire.pages.risk-owner.raci.print-layout.raci-layout', [
            'kpis'          => $kpis,
            'user_pemilik'  => $this->user_pemilik,
            'unit_nama'     => $this->unit_nama,
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

        // CLOSE MODAL CETAK RISK REGISTER
        $this->closeCetakRiskRegister();

        // Option 1: Return the PDF as a download
        // Return the PDF content with appropriate headers
        return response()->streamDownload(function () use ($pdfContent) {
            echo $pdfContent;
        }, 'RISK-REGISTER-' . $kpis->kpi_kode . '.pdf');

    }

    // OPEN CETAK RISK REGISTER
    public function openCetakRiskRegister($kpi_id)
    {
        $this->isOpenCetakRiskRegister = 1;

        $this->kpi_id  = $kpi_id;
    }

    // CLOSE CETAK RISK REGISTER
    public function closeCetakRiskRegister()
    {
        $this->isOpenCetakRiskRegister = 0;
    }
    // CLOSE CETAK RISK REGISTER
    public function closeXCetakRiskRegister()
    {
        $this->isOpenCetakRiskRegister = 0;
    }

    // CETAK RISK REGISTER
    public $isOpenCetakRiskControl = 0;

    public $cetakRiskControl;

    // PRINT RISK CONTROL
    public function printRiskControl()
    {
        // FIND KPI
        $kpis = KPI::with([
            'konteks.risk.controlRisk' => function($query) {
                $query->where('controlRisk_isControl', true);
            },
            'konteks.risk.controlRisk.perlakuanRisiko.jenisPerlakuan',
            'konteks.risk.controlRisk.perlakuanRisiko.rencanaPerlakuan',
            'konteks.risk.controlRisk.efektifitasControl.detailEfektifitasKontrol',
        ])
        ->where('unit_id', $this->unit_id)
        ->where('kpi_lockStatus', true)
        ->where('kpi_activeStatus', true)
        ->find($this->kpi_id);

        dd($kpis);

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
        $html = view('livewire.pages.risk-owner.laporan-manrisk.print-layout.risk-control-layout', [
            'kpis'          => $kpis,
            'user_pemilik'  => $this->user_pemilik,
            'unit_nama'     => $this->unit_nama,
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
    public function openCetakRiskControl($kpi_id)
    {
        $this->isOpenCetakRiskControl = 1;

        $this->kpi_id  = $kpi_id;
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
}
