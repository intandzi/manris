<?php

namespace App\Livewire\Lembaga;

use App\Models\KategoriStandar;
use App\Models\KPI;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class ListKPIUnit extends Component
{
    use WithPagination, WithFileUploads;

    // TITLE COMPONENT
    #[Title('KPI Unit')]
    public $title;

    // GLOBAL VARIABLES
    public $perPage     = 5;
    public $search      = '';
    public $searchPeriod = '';

    public $years = [], $periodYears = [];

    // VARIABLES LIST UNIT MODEL
    public $unit_id, $unit, $kategoriStandar;

    // VARIABLES KPI MODEL
    public $kpi_id, 
           $kpi_kode, 
           $kpi_nama, 
           $kpi_tanggalMulai, 
           $kpi_tanggalAkhir, 
           $kpi_periode, 
           $kategoriStandar_id, 
           $kategoriStandar_desc, 
           $kpi_kategoriKinerja, 
           $kpi_indikatorKinerja, 
           $dokumen, 
           $dokumenPendukung;
    public $kpi_dokumenPendukung;

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
        $encryptedId = request()->query('unit');
        $this->unit_id = Crypt::decryptString($encryptedId);
        
        // FIND UNIT SPECIFIC
        $this->unit = Unit::with(['kpi.kategoriStandar', 'visimisi'])->where('unit_id', $this->unit_id)->first();

        // PASSING KATEGORI STANDAR
        $this->kategoriStandar = KategoriStandar::where('kategoriStandar_activeStatus', true)->get();

        // SET TITLE
        $this->title = $this->unit->unit_name;

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
    }

    // COMPONENT RENDER
    public function render()
    {
        $kpis = KPI::with(['unit', 'kategoriStandar'])->where('unit_id', $this->unit_id)->search($this->search)
            ->when($this->searchPeriod, function($query){
                $query->where('kpi_periode', 'like', '%' . $this->searchPeriod . '%');
            })
            ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')
            ->paginate($this->perPage);
        
        return view('livewire.pages.lembaga.kpi.list-kpi-unit.list-kpi-unit', [
            'kpis'             => $kpis,
            'paginationInfo'    => $kpis->total() > 0
            ? "Showing " . ($kpis->firstItem()) . " to " . ($kpis->lastItem()) . " of " . ($kpis->total()) . " entries"
            : "No entries found",
        ]);
    }

    // SANITIZE INPUTS STORE
    protected function sanitizeInputsStore()
    {
        $this->kpi_kode             = filter_var($this->kpi_kode, FILTER_SANITIZE_STRING,  FILTER_FLAG_ALLOW_FRACTION);
        $this->kpi_nama             = filter_var($this->kpi_nama, FILTER_SANITIZE_STRING,  FILTER_FLAG_ALLOW_FRACTION);
        $this->kpi_kategoriKinerja  = filter_var($this->kpi_kategoriKinerja, FILTER_SANITIZE_STRING,  FILTER_FLAG_ALLOW_FRACTION);
        $this->kpi_indikatorKinerja = filter_var($this->kpi_indikatorKinerja, FILTER_SANITIZE_STRING,  FILTER_FLAG_ALLOW_FRACTION);
    }

    // VALIDATING INPUTS
    protected function validatingInputs()
    {
        $validated = $this->validate([
            'kpi_nama'              => ['required', 'string'],
            'kpi_tanggalMulai'      => ['required', 'date',], // Ensure date is today or in the future
            'kpi_tanggalAkhir'      => ['required', 'date', 'after_or_equal:kpi_tanggalMulai'], // Ensure date is today or in the future
            'kpi_periode'           => ['required',],
            'kpi_kategoriKinerja'   => ['required', 'string'],
            'kpi_indikatorKinerja'  => ['required', 'string'],
            'kategoriStandar_id'    => ['required'],
            // 'dokumenPendukung'      => ['nullable', 'mimes:pdf,docx,xlsx,jpg,jpeg,png', 'max:5048'], // Optional file validation rules
        ], [
            'kpi_nama.required'                 => 'Nama KPI wajib diisi!',
            'kpi_tanggalMulai.required'         => 'Tanggal Mulai wajib diisi!',
            'kpi_tanggalMulai.date'             => 'Format Tanggal Mulai tidak valid!',
            'kpi_periode.required'              => 'KPI Periode wajib diisi!',
            'kpi_tanggalAkhir.required'         => 'Tanggal Akhir wajib diisi!',
            'kpi_tanggalAkhir.date'             => 'Format Tanggal Akhir tidak valid!',
            'kpi_tanggalAkhir.after_or_equal'   => 'Tanggal Akhir harus tanggal hari ini atau melebihi Tanggal Mulai!',
            'kpi_kategoriKinerja.required'      => 'Kategori Kinerja wajib diisi!',
            'kpi_indikatorKinerja.required'     => 'Indikator Kinerja wajib diisi!',
            'kategoriStandar_id.required'       => 'Kategori Standar wajib diisi!',
            // 'dokumenPendukung.mimes'            => 'Dokumen pendukung harus dalam format PDF, DOCX, XLSX, JPG, JPEG, atau PNG!',
            // 'dokumenPendukung.max'              => 'Ukuran maksimal dokumen pendukung adalah 5048 KB!',
        ]);
    }

    // STORE NEW DATA KPI UNIT
    public function storeKPI()
    {    
        // dd($this->kpi_dokumenPendukung);
        // SANITIZE INPUTS
        $this->sanitizeInputsStore();

        // VALIDATING DATA
        $this->validatingInputs();
        
        // Check if is Edit
        if (!$this->isEdit) {
            // Generate the KPI code
            $kpiKode = Kpi::generateKpiKode($this->unit_id, $this->kpi_periode);
        } else {
            $kpi                = Kpi::find($this->kpi_id);
            $kpiKode            = $kpi->kpi_kode;
        }

        // if ($this->kpi_dokumenPendukung && $this->kpi_dokumenPendukung instanceof \Illuminate\Http\UploadedFile) {
        // Check if dokumen pendukung is present and is a valid uploaded file
        $path = '-'; // Default path

        if ($this->kpi_dokumenPendukung && is_object($this->kpi_dokumenPendukung)) {
            // Validate the uploaded file
            $this->validate([
                'kpi_dokumenPendukung' => 'file|max:10240', // Adjust max size as needed
            ]);

            // Store the uploaded file
            if ($this->kpi_dokumenPendukung instanceof \Illuminate\Http\UploadedFile) {
                $storedPath = $this->kpi_dokumenPendukung->storeAs(
                    'kpi/dokumenPendukung',
                    $this->kpi_dokumenPendukung->getClientOriginalName(),
                    'public'
                );

                if (!$storedPath) {
                    throw new \Exception("Failed to store the file.");
                }

                // Encrypt the stored path
                $path = Crypt::encrypt($storedPath);
            } else {
                // If no new file is uploaded, use the existing path
                $path = $this->kpi_dokumenPendukung;
            }
        }

        
        // STORE OR UPDATE KPI DATA
        $kpi = KPI::updateOrCreate([
            'kpi_id'    => $this->kpi_id,
        ],[
            'unit_id'               => $this->unit_id,
            'kategoriStandar_id'    => $this->kategoriStandar_id,
            'kpi_kode'              => $kpiKode ?? $this->kpi_kode,
            'kpi_nama'              => $this->kpi_nama,
            'kpi_tanggalMulai'      => $this->kpi_tanggalMulai,
            'kpi_tanggalAkhir'      => $this->kpi_tanggalAkhir,
            'kpi_periode'           => $this->kpi_periode,
            'kpi_kategoriKinerja'   => $this->kpi_kategoriKinerja,
            'kpi_indikatorKinerja'  => $this->kpi_indikatorKinerja,
            'kpi_dokumenPendukung'  => $path,
            'created_by'            => Auth::user()->user_id,
            'updated_by'            => Auth::user()->user_id,
        ]);

        // close modal
        $this->isOpen = false;

        // Reset form fields and close the modal
        $this->resetForm();

        // send notification success
        flash()
            ->option('position', 'bottom-right')
            ->option('timeout', 3000)
            ->success('Data Anda telah disimpan!');
    }

    // EDIT KPI
    public function editKPI($id)
    {
        // OPEN MODAL
        $this->openModal();

        // IS SHOW
        $this->isShow = false;

        // IS EDIT
        $this->isEdit = true;

        // FIND KPI
        $kpi = KPI::find($id);

        // PASSING KPI
        $this->kpi_id               = $kpi->kpi_id;
        $this->kategoriStandar_id   = $kpi->kategoriStandar_id;
        $this->kpi_kode             = $kpi->kpi_kode;
        $this->kpi_nama             = $kpi->kpi_nama;
        $this->kpi_tanggalMulai     = $kpi->kpi_tanggalMulai;
        $this->kpi_tanggalAkhir     = $kpi->kpi_tanggalAkhir;
        $this->kpi_periode          = $kpi->kpi_periode;
        $this->kpi_kategoriKinerja  = $kpi->kpi_kategoriKinerja;
        $this->kpi_indikatorKinerja = $kpi->kpi_indikatorKinerja;
        $this->dokumen              = $kpi->kpi_dokumenPendukung ?? null;
    }

    // SHOW KPI
    public function showKPI($id)
    {
        // OPEN MODAL
        $this->openModal();

        // IS SHOW
        $this->isShow = true;

        // FIND KPI
        $kpi = KPI::with(['kategoriStandar'])->find($id);
        
        // PASSING KPI
        $this->kpi_id               = $kpi->kpi_id;
        $this->kategoriStandar_id   = $kpi->kategoriStandar_id;
        $this->kategoriStandar_desc = $kpi->kategoriStandar->kategoriStandar_desc;
        $this->kpi_kode             = $kpi->kpi_kode;
        $this->kpi_nama             = $kpi->kpi_nama;
        $this->kpi_tanggalMulai     = $kpi->kpi_tanggalMulai;
        $this->kpi_tanggalAkhir     = $kpi->kpi_tanggalAkhir;
        $this->kpi_periode          = $kpi->kpi_periode;
        $this->kpi_kategoriKinerja  = $kpi->kpi_kategoriKinerja;
        $this->kpi_indikatorKinerja = $kpi->kpi_indikatorKinerja;
        $this->dokumen              = $kpi->kpi_dokumenPendukung ?? null;
    }

    // LOCK KPI
    public function lockKPI()
    {
        // FIND KPI
        $kpi = KPI::find($this->kpi_id);
        $kpi->update(['kpi_lockStatus' => true]);

        // close modal
        $this->closeModalConfirm();

        // send notification success
        flash()
            ->option('position', 'bottom-right')
            ->option('timeout', 3000)
            ->success('Data Anda telah disimpan!');
    }

    // ACTIVE OR NON-ACITVE DATA KPI
    public bool $isActive = false;
    public function toggleActive($kpiID)
    {
        // Find the kpi by kpi_id
        $kpi = KPI::find($kpiID);
        
        // Toggle the status between 0 and 1 (assuming 0 represents inactive and 1 represents active)
        $kpi->update(['kpi_activeStatus' => !$kpi->kpi_activeStatus, 'updated_by'   => Auth::user()->user_id]);

        flash()
            ->option('position', 'bottom-right')
            ->option('timeout', 3000)
            ->success('Data Anda telah disimpan!');
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

    // LIFE CYCLE HOOKS
    // OPEN MODAL LIVEWIRE 
    public $isOpen = 0;
    public $isOpenConfirm = 0;
    public function create()
    {
        // IS SHOW
        $this->isShow = false;

        // IS EDIT
        $this->isEdit = false;

        $this->openModal();
    }

    // OPEN MODAL
    public function openModal()
    {
        $this->isOpen = true;
    }

    // CLOSE MODAL
    public function closeModal()
    {
        $this->isOpen = false;

        // IS SHOW
        $this->isShow = false;

        // IS EDIT
        $this->isEdit = false;

        // Reset form fields and close the modal
        $this->resetForm();

        // Reset form validation
        $this->resetValidation();
    } 

    // CLOSE MODAL
    public function closeXModal()
    {
        $this->isOpen = false;

        // IS SHOW
        $this->isShow = false;

        // IS EDIT
        $this->isEdit = false;

        // Reset form fields and close the modal
        $this->resetForm();

        // Reset form validation
        $this->resetValidation();
    } 

    // OPEN MODAL CONFIRM
    public function openModalConfirm($id)
    {
        // SET KPI
        $this->kpi_id = $id;

        $this->isOpenConfirm = true;
    }

    // CLOSE MODAL
    public function closeModalConfirm()
    {
        $this->isOpenConfirm = false;
    } 

    // CLOSE MODAL
    public function closeXModalConfirm()
    {
        $this->isOpenConfirm = false;
    } 

    // RESET PAGINATION AFTER SEARCH
    public function updatedPerPage()
    {
        $this->resetPage();
    }

    // RESET PAGINATION IF AFTER SEARCH
    public function updatedSearch()
    {
        $this->resetPage();
    }

    // RESET FORM
    public function resetForm()
    {
        $this->kpi_id               = '';
        $this->kpi_kode             = '';
        $this->kpi_nama             = '';
        $this->kpi_tanggalMulai     = '';
        $this->kpi_tanggalAkhir     = '';
        $this->kpi_periode          = '';
        $this->kpi_kategoriKinerja  = '';
        $this->kpi_indikatorKinerja = '';
        $this->kategoriStandar_id   = '';
        $this->kpi_dokumenPendukung = '';
        $this->dokumenPendukung     = '';

        $this->isEdit               = false;
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
