<div>

    <div class="container-fluid">

        <!-- breadcrumbs component -->
        <nav aria-label="breadcrumb" class="mb-2">
            <ol class="breadcrumb mb-0 p-2">
                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-apps"></i>
                        App</a></li>
                <li class="breadcrumb-item"><a
                        href="{{ route('riskRegisterOw.index', ['role' => request()->query('role')]) }}" wire:navigate>
                        Risk Register</a></li>
                <li class="breadcrumb-item active"><a href="#">KPI Unit
                        {{ $title }}</a>
                </li>
            </ol>
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
                                KPI Management is essential for system, It involves creating, editing, and managing
                                kpis.
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
                                        @foreach ($years as $year)
                                            <option value="{{ $year }}">{{ $year }}</option>
                                        @endforeach
                                    </select>
                                    {{-- @if ($searchPeriod)
                                        <button type="button" wire:click.prevent="clearSearchPeriod"
                                            class="btn btn-primary d-flex align-items-center btn-sm"
                                            title="Cancel Edit">
                                            <i class="ri-close-line">
                                            </i>
                                        </button>
                                    @endif --}}
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
                                        <th wire:click.live="doSort('unit_activeStatus')" style="cursor: pointer;">
                                            Nama KPI
                                            <x-sorting-table :orderAsc="$orderAsc" :orderBy="$orderBy" :columnName="'status'" />
                                        </th>
                                        <th>
                                            Kategori Standar
                                        </th>
                                        <th wire:click.live="doSort('unit_activeStatus')" style="cursor: pointer;">
                                            Tanggal Mulai
                                        </th>
                                        <th wire:click.live="doSort('unit_activeStatus')" style="cursor: pointer;">
                                            Periode
                                        </th>
                                        <th>Aksi</th>
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
                                            <td>
                                                {{ ucwords($kpi->kpi_nama) }}
                                            </td>
                                            <td>
                                                {{ ucwords($kpi->kategoriStandar->kategoriStandar_desc) }}
                                            </td>
                                            <td>
                                                {{ $kpi->kpi_tanggalMulai }}
                                            </td>
                                            <td>
                                                {{ date('Y', strtotime($kpi->kpi_periode)) }}
                                            </td>
                                            <td>
                                                <div class="btn-group gap-2" role="group">
                                                    <button type="button"
                                                        wire:click.prevent="konteksRisiko({{ $kpi->kpi_id }})"
                                                        wire:loading.attr="disabled"
                                                        wire:target="konteksRisiko({{ $kpi->kpi_id }})"
                                                        class="btn btn-primary btn-sm d-flex text-center align-items-center">
                                                        <i class="ri-login-box-line me-1" wire:loading.remove
                                                            wire:target='konteksRisiko({{ $kpi->kpi_id }})'>
                                                        </i>
                                                        Mulai
                                                        <span class="ms-2" wire:loading
                                                            wire:target="konteksRisiko({{ $kpi->kpi_id }})">
                                                            <span class="spinner-border spinner-border-sm"
                                                                role="status" aria-hidden="true"></span>
                                                        </span>
                                                    </button>
                                                    @if ($kpi->kpi_sendUMRStatus)
                                                        <span
                                                            class="badge badge-outline-danger rounded-pill mt-2">Selesaikan!</span>
                                                    @else
                                                        <button type="button"
                                                            wire:click.prevent="openModalConfirm({{ $kpi->kpi_id }})"
                                                            wire:loading.attr="disabled"
                                                            wire:target="openModalConfirm({{ $kpi->kpi_id }})"
                                                            class="btn btn-success btn-sm d-flex text-center align-items-center">
                                                            <i class="ri-send-plane-line me-1" wire:loading.remove
                                                                wire:target='openModalConfirm({{ $kpi->kpi_id }})'>
                                                            </i>
                                                            UMR
                                                            <span wire:loading
                                                                wire:target="openModalConfirm({{ $kpi->kpi_id }})">
                                                                <span class="spinner-border spinner-border-sm"
                                                                    role="status" aria-hidden="true"></span>
                                                            </span>
                                                        </button>
                                                    @endif
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

</div>
