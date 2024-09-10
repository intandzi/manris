<div>

    <div class="container-fluid">

        <!-- breadcrumbs component -->
        <nav aria-label="breadcrumb" class="mb-2">
            <ol class="breadcrumb mb-0 p-2">
                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-apps"></i>
                        App</a></li>
                <li class="breadcrumb-item active"><a href="#">KPI Unit
                        {{ $title }}</a>
                </li>
            </ol>
        </nav>

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
                                                {{ $kpi->kpi_periode }}
                                            </td>
                                            <td>
                                                <div class="btn-group gap-2" role="group">
                                                    <button type="button"
                                                        wire:click.prevent="konteksRisiko({{ $kpi->kpi_id }})"
                                                        wire:loading.attr="disabled"
                                                        wire:target="konteksRisiko({{ $kpi->kpi_id }})"
                                                        class="btn btn-primary btn-sm d-flex text-center align-items-center">
                                                        <i class="ri-eye-fill me-1" wire:loading.remove
                                                            wire:target='konteksRisiko({{ $kpi->kpi_id }})'>
                                                        </i>
                                                        Lihat
                                                        <span
                                                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                                            style="font-size: 12px;">
                                                            {{ $this->countKontekssWithStatusTrue($kpi) }}
                                                            <span class="visually-hidden">unread messages</span>
                                                        </span>
                                                        <span class="ms-2" wire:loading
                                                            wire:target="konteksRisiko({{ $kpi->kpi_id }})">
                                                            <span class="spinner-border spinner-border-sm"
                                                                role="status" aria-hidden="true"></span>
                                                        </span>
                                                    </button>
                                                    <button type="button"
                                                        wire:click.prevent="openHistoryPengembalian({{ $kpi->kpi_id }})"
                                                        wire:loading.attr="disabled"
                                                        wire:target="openHistoryPengembalian({{ $kpi->kpi_id }})"
                                                        class="btn btn-secondary btn-sm d-flex text-center align-items-center">
                                                        <i class="ri-history-line me-1" wire:loading.remove
                                                            wire:target='openHistoryPengembalian({{ $kpi->kpi_id }})'>
                                                        </i>
                                                        History
                                                        <span class="ms-2" wire:loading
                                                            wire:target="openHistoryPengembalian({{ $kpi->kpi_id }})">
                                                            <span class="spinner-border spinner-border-sm"
                                                                role="status" aria-hidden="true"></span>
                                                        </span>
                                                    </button>
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


    @if ($isOpenHistoryPengembalian)
        <div class="modal" tabindex="-1" role="dialog" style="display: block;" aria-modal="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">History Pengembalian</h5>
                        <button type="button" class="btn-close" aria-label="Close"
                            wire:click="closeXHistoryPengembalian"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive mb-2">
                                    <table class="table table-centered mb-0">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>Tanggal Control</th>
                                                <th style="cursor: pointer; width:500px;">Alasan Pengembalian</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($historyPengembalian && $historyPengembalian->konteks->isNotEmpty())
                                                @php
                                                    $hasHistory = false;
                                                @endphp
                                                @foreach ($historyPengembalian->konteks as $konteks)
                                                    @forelse ($konteks->historyPengembalian as $history)
                                                        @php
                                                            $hasHistory = true;
                                                        @endphp
                                                        <tr>
                                                            <td>
                                                                {{ $history->historyPengembalian_tgl }}
                                                            </td>
                                                            <td style="word-break: break-word;">
                                                                {{ $history->historyPengembalian_alasan }}
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        {{-- Continue to the next konteks if no history found --}}
                                                    @endforelse
                                                @endforeach

                                                @if (!$hasHistory)
                                                    <div class="alert alert-danger mt-2 mb-2">
                                                        No data available.
                                                    </div>
                                                @endif
                                            @else
                                                <div class="alert alert-danger mt-2 mb-2">
                                                    No data available.
                                                </div>
                                            @endif

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click='closeHistoryPengembalian'
                            wire:loading.attr="disabled" wire:target="closeHistoryPengembalian">
                            Tutup
                            <span wire:loading class="ms-2" wire:target="closeHistoryPengembalian">
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
