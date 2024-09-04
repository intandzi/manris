<div>

    <div class="container-fluid">

        <!-- breadcrumbs component -->
        <nav aria-label="breadcrumb" class="mb-2">
            @if ($this->role === 'lembaga')
                <ol class="breadcrumb mb-0 p-2">
                    <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-apps"></i>
                            App</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('laporan-mrisk.index', ['role' => $encryptedRole]) }}"
                            wire:navigate>
                            Laporan Manajemen Risiko</a></li>
                    <li class="breadcrumb-item active"><a href="#">KPI Unit
                            {{ $title }}</a>
                    </li>
                </ol>
            @else
                <ol class="breadcrumb mb-0 p-2">
                    <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-apps"></i>
                            App</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('laporan-mrisk-umr.index', ['role' => $encryptedRole]) }}"
                            wire:navigate>
                            Laporan Manajemen Risiko</a></li>
                    <li class="breadcrumb-item active"><a href="#">KPI Unit
                            {{ $title }}</a>
                    </li>
                </ol>
            @endif
        </nav>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <h4>Visi</h4>
                                @if ($unit->visimisi && $unit->visimisi->isEmpty())
                                    <div class="alert alert-danger mt-2 mb-2">
                                        No data available.
                                    </div>
                                @else
                                    @if ($unit->visimisi)
                                        <p>
                                            {!! $unit->visimisi->last()->visimisi_visi !!}
                                        </p>
                                    @endif
                                @endif
                            </div>
                            <div class="col-md-12">
                                <h4>Misi</h4>
                                @if ($unit->visimisi && $unit->visimisi->isEmpty())
                                    <div class="alert alert-danger mt-2 mb-2">
                                        No data available.
                                    </div>
                                @else
                                    <p>
                                        {!! $unit->visimisi->last()->visimisi_misi !!}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div> <!-- end card body-->
                </div> <!-- end card -->
            </div><!-- end col-->
        </div><!-- end row -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title">KPI Unit {{ $title }}</h4>
                            <p class="text-muted mb-0">
                                Unit Management is essential for system, It involves creating, editing, and managing
                                units.
                            </p>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-1 me-2">
                                <label for="" class="form-label">Show Data</label>
                                <select wire:model.live="perPage" class="form-control" id="">
                                    <option selected value="5">--</option>
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                            </div>
                            <div class="col-1 ms-auto">
                                <label for="" class="form-label">Periode</label>
                                <div class="input-group">
                                    <select class="form-control" wire:model.live.debounce.100ms="searchPeriod">
                                        <option selected value="">--</option>
                                        @foreach ($periodYears as $year)
                                            <option value="{{ $year }}">{{ $year }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-3">
                                <label for="" class="form-label">Search</label>
                                <div class="input-group">
                                    <input wire:model.live.debounce.100ms="search" type="text" class="form-control"
                                        placeholder="Search...">
                                    @if ($search)
                                        <button type="button" wire:click.prevent="clearSearch"
                                            class="btn btn-primary d-flex align-items-center btn-sm"
                                            title="Cancel Edit">
                                            <i class="ri-close-line">
                                            </i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive mt-2">
                            <table class="table table-centered mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No</th>
                                        <th wire:click.live="doSort('unit_name')" style="cursor: pointer;">
                                            Kode
                                        </th>
                                        <th wire:click.live="doSort('unit_activeStatus')"
                                            style="cursor: pointer; width:300px;">
                                            Nama KPI
                                            <x-sorting-table :orderAsc="$orderAsc" :orderBy="$orderBy" :columnName="'status'" />
                                        </th>
                                        <th>
                                            Tanggal Mulai
                                        </th>
                                        <th wire:click.live="doSort('unit_activeStatus')" style="cursor: pointer;">
                                            Tanggal Akhir
                                        </th>
                                        <th wire:click.live="doSort('unit_activeStatus')" style="cursor: pointer;">
                                            Periode
                                        </th>
                                        <th>Profil Risiko</th>
                                        <th>Kontrol Risiko</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @forelse ($kpis as $index => $kpi)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>
                                                {{ $kpi->kpi_kode }}
                                            </td>
                                            <td style="word-break: break-word;">
                                                {{ ucwords($kpi->kpi_nama) }}
                                            </td>
                                            <td>
                                                {{ $kpi->kpi_tanggalMulai }}
                                            </td>
                                            <td>
                                                {{ $kpi->kpi_tanggalAkhir }}
                                            </td>
                                            <td>
                                                {{ $kpi->kpi_periode }}
                                            </td>
                                            <td>
                                                <div
                                                    class="d-flex justify-content-center align-items-center gap-2 flex-column">
                                                    <div class="btn-group gap-2 d-flex flex-column" role="group">
                                                        <button type="button"
                                                            wire:click.prevent="openCetakRiskRegister({{ $kpi->kpi_id }})"
                                                            wire:loading.attr="disabled"
                                                            wire:target="openCetakRiskRegister({{ $kpi->kpi_id }})"
                                                            class="btn btn-dark btn-sm">
                                                            <i class="ri-printer-line me-1" wire:loading.remove
                                                                wire:target='openCetakRiskRegister({{ $kpi->kpi_id }})'>
                                                            </i>
                                                            Cetak
                                                            <span class="ms-2" wire:loading
                                                                wire:target="openCetakRiskRegister({{ $kpi->kpi_id }})">
                                                                <span class="spinner-border spinner-border-sm"
                                                                    role="status" aria-hidden="true"></span>
                                                            </span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div
                                                    class="d-flex justify-content-center align-items-center gap-2 flex-column">
                                                    <div class="btn-group gap-2 d-flex flex-column" role="group">
                                                        <button type="button"
                                                            wire:click.prevent="openCetakRiskControl({{ $kpi->kpi_id }})"
                                                            wire:loading.attr="disabled"
                                                            wire:target="openCetakRiskControl({{ $kpi->kpi_id }})"
                                                            class="btn btn-dark btn-sm">
                                                            <i class="ri-printer-line me-1" wire:loading.remove
                                                                wire:target='openCetakRiskControl({{ $kpi->kpi_id }})'>
                                                            </i>
                                                            Cetak
                                                            <span class="ms-2" wire:loading
                                                                wire:target="openCetakRiskControl({{ $kpi->kpi_id }})">
                                                                <span class="spinner-border spinner-border-sm"
                                                                    role="status" aria-hidden="true"></span>
                                                            </span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <div class="alert alert-danger mt-2 mb-2">
                                            No data available.
                                        </div>
                                    @endforelse
                                </tbody>
                            </table>
                        </div> <!-- end table-responsive-->
                        <div class="row mt-2">
                            <div class="col-md-12 text-end">
                                {!! $kpis->links() !!}
                            </div>
                        </div>

                    </div> <!-- end card body-->
                </div> <!-- end card -->
            </div><!-- end col-->
        </div><!-- end row -->

    </div> <!-- container -->


    {{-- CETAK RISK REGISTER --}}
    @if ($isOpenCetakRiskRegister)
        <div class="modal" tabindex="-1" role="dialog" style="display: block;" aria-modal="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Menunggu Konfirmasi</h5>
                        <button type="button" class="btn-close" aria-label="Close"
                            wire:click="closeXCetakRiskRegister"></button>
                    </div>
                    <div class="modal-body">
                        Apakah Anda yakin ingin
                        mencetak Risk Register
                        ini?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click='closeCetakRiskRegister'
                            wire:loading.attr="disabled" wire:target="closeCetakRiskRegister">
                            Tutup
                            <span wire:loading class="ms-2" wire:target="closeCetakRiskRegister">
                                <span class="spinner-border spinner-border-sm" role="status"
                                    aria-hidden="true"></span>
                            </span>
                        </button>
                        <button type="button" class="btn btn-primary" wire:click="printRiskRegister"
                            wire:loading.attr="disabled" wire:target="printRiskRegister">
                            Cetak Profil Risiko
                            <span wire:loading class="ms-2" wire:target="printRiskRegister">
                                <span class="spinner-border spinner-border-sm" role="status"
                                    aria-hidden="true"></span>
                            </span>
                        </button>
                    </div>
                </div>
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <div class="modal-backdrop fade show"></div>
    @endif


    {{-- CETAK RISK CONTROL --}}
    @if ($isOpenCetakRiskControl)
        <div class="modal" tabindex="-1" role="dialog" style="display: block;" aria-modal="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Menunggu Konfirmasi</h5>
                        <button type="button" class="btn-close" aria-label="Close"
                            wire:click="closeXCetakRiskControl"></button>
                    </div>
                    <div class="modal-body">
                        Apakah Anda yakin ingin
                        mencetak Kontrol Risiko
                        ini?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click='closeCetakRiskControl'
                            wire:loading.attr="disabled" wire:target="closeCetakRiskControl">
                            Tutup
                            <span wire:loading class="ms-2" wire:target="closeCetakRiskControl">
                                <span class="spinner-border spinner-border-sm" role="status"
                                    aria-hidden="true"></span>
                            </span>
                        </button>
                        <button type="button" class="btn btn-primary" wire:click="printRiskControl"
                            wire:loading.attr="disabled" wire:target="printRiskControl">
                            Cetak Kontrol Risiko
                            <span wire:loading class="ms-2" wire:target="printRiskControl">
                                <span class="spinner-border spinner-border-sm" role="status"
                                    aria-hidden="true"></span>
                            </span>
                        </button>
                    </div>
                </div>
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <div class="modal-backdrop fade show"></div>
    @endif

</div>
