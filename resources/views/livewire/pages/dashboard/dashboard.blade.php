<div>

    {{-- @push('style-alt')
        <style>
            #dash {
                border-collapse: collapse;
                width: 100%;
                text-align: center;
                font-family: Arial, sans-serif;
            }

            #dash th,
            #dash td {
                border: 1px solid #000;
                padding: 10px;
                width: 40px;
                height: 40px;
            }

            .low {
                background-color: #00ff00;
            }

            .medium-low {
                background-color: #adff2f;
            }

            .medium {
                background-color: #ffff00;
            }

            .medium-high {
                background-color: #ff9900;
            }

            .high {
                background-color: #ff0000;
            }

            .header-vertical {
                writing-mode: vertical-rl;
                transform: rotate(180deg);
            }
        </style>
    @endpush --}}

    <div class="container-fluid">

        <!-- breadcrumbs component -->
        <nav aria-label="breadcrumb" class="mb-2">
            <ol class="breadcrumb mb-0 p-2">
                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i>
                        Main</a></li>
                <li class="breadcrumb-item active"><a href="{{ route('lembaga.dashboard') }}">Dashboard</a></li>
            </ol>
        </nav>

        {{-- <div class="row">
            <div class="col-lg-12">
                <div class="card py-2">
                    <div class="">
                        <div class="table-responsive mx-lg-5">
                            <table class="table-centered mb-2" id="dash">
                                <thead>
                                    <tr class="border-0">
                                        <th colspan="12" class="border-0">RISK MATRIKS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="border-0" style="width: 50px;">
                                        <th rowspan="11" class="header-vertical border-0" style="width: 50px;">Tingkat Keparahan Peristiwa</th>
                                    </tr>
                                    <tr>
                                        <th>10</th>
                                        <td class="medium-low">-</td>
                                        <td class="medium-low">-</td>
                                        <td class="medium-low">-</td>
                                        <td class="medium">-</td>
                                        <td class="medium">-</td>
                                        <td class="medium-high">-</td>
                                        <td class="medium-high">-</td>
                                        <td class="high">-</td>
                                        <td class="high">1</td>
                                        <td class="high">-</td>
                                    </tr>
                                    <tr>
                                        <th>9</th>
                                        <td class="medium-low">-</td>
                                        <td class="medium-low">-</td>
                                        <td class="medium-low">-</td>
                                        <td class="medium">-</td>
                                        <td class="medium">-</td>
                                        <td class="medium-high">-</td>
                                        <td class="medium-high">-</td>
                                        <td class="high">-</td>
                                        <td class="high">-</td>
                                        <td class="high">-</td>
                                    </tr>
                                    <tr>
                                        <th>8</th>
                                        <td class="medium-low">-</td>
                                        <td class="medium-low">-</td>
                                        <td class="medium-low">-</td>
                                        <td class="medium">-</td>
                                        <td class="medium">-</td>
                                        <td class="medium-high">-</td>
                                        <td class="medium-high">-</td>
                                        <td class="high">-</td>
                                        <td class="high">-</td>
                                        <td class="high">-</td>
                                    </tr>
                                    <tr>
                                        <th>7</th>
                                        <td class="medium-low">-</td>
                                        <td class="medium-low">-</td>
                                        <td class="medium-low">-</td>
                                        <td class="medium">-</td>
                                        <td class="medium">-</td>
                                        <td class="medium">-</td>
                                        <td class="medium-high">-</td>
                                        <td class="medium-high">-</td>
                                        <td class="high">-</td>
                                        <td class="high">-</td>
                                    </tr>
                                    <tr>
                                        <th>6</th>
                                        <td class="low">-</td>
                                        <td class="medium-low">-</td>
                                        <td class="medium-low">-</td>
                                        <td class="medium-low">-</td>
                                        <td class="medium">-</td>
                                        <td class="medium">-</td>
                                        <td class="medium">-</td>
                                        <td class="medium-high">-</td>
                                        <td class="high">-</td>
                                        <td class="high">-</td>
                                    </tr>
                                    <tr>
                                        <th>5</th>
                                        <td class="low">-</td>
                                        <td class="low">-</td>
                                        <td class="medium-low">-</td>
                                        <td class="medium-low">-</td>
                                        <td class="medium">-</td>
                                        <td class="medium">-</td>
                                        <td class="medium">-</td>
                                        <td class="medium-high">-</td>
                                        <td class="high">-</td>
                                        <td class="high">-</td>
                                    </tr>
                                    <tr>
                                        <th>4</th>
                                        <td class="low">-</td>
                                        <td class="low">-</td>
                                        <td class="medium-low">-</td>
                                        <td class="medium-low">-</td>
                                        <td class="medium">-</td>
                                        <td class="medium">-</td>
                                        <td class="medium-high">-</td>
                                        <td class="medium-high">-</td>
                                        <td class="medium-high">-</td>
                                        <td class="high">-</td>
                                    </tr>
                                    <tr>
                                        <th>3</th>
                                        <td class="low">-</td>
                                        <td class="low">1</td>
                                        <td class="medium-low">-</td>
                                        <td class="medium-low">-</td>
                                        <td class="medium">-</td>
                                        <td class="medium">-</td>
                                        <td class="medium-high">-</td>
                                        <td class="medium-high">-</td>
                                        <td class="high">-</td>
                                        <td class="high">-</td>
                                    </tr>
                                    <tr>
                                        <th>2</th>
                                        <td class="low">-</td>
                                        <td class="low">-</td>
                                        <td class="low">-</td>
                                        <td class="medium-low">-</td>
                                        <td class="medium-low">-</td>
                                        <td class="medium">-</td>
                                        <td class="medium">-</td>
                                        <td class="medium">-</td>
                                        <td class="medium-high">-</td>
                                        <td class="high">-</td>
                                    </tr>
                                    <tr>
                                        <th>1</th>
                                        <td class="low">1</td>
                                        <td class="low">-</td>
                                        <td class="low">-</td>
                                        <td class="medium-low">-</td>
                                        <td class="medium-low">-</td>
                                        <td class="medium-low">-</td>
                                        <td class="medium">-</td>
                                        <td class="medium">-</td>
                                        <td class="medium-high">-</td>
                                        <td class="medium-high">-</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr class="border-0">
                                        <th class="border-0"></th>
                                        <th></th>
                                        <th>1</th>
                                        <th>2</th>
                                        <th>3</th>
                                        <th>4</th>
                                        <th>5</th>
                                        <th>6</th>
                                        <th>7</th>
                                        <th>8</th>
                                        <th>9</th>
                                        <th>10</th>
                                    </tr>
                                    <tr class="border-0">
                                        <th colspan="13" class="border-0">Kemungkinan Terjadinya dari Penyebab</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col-->
        </div><!-- end row --> --}}

        {{-- ONLY DISPLAY IF ROLE EQUAL TO RISK OWNER || RISK OFFICER --}}
        @if ($this->role == 'risk owner' || $this->role == 'risk officer')
            <div class="row">
                <div class="col-xl-4">
                    <div class="card overflow-hidden border-top-0">
                        <div class="progress progress-sm rounded-0 bg-light" role="progressbar" aria-valuenow="100"
                            aria-valuemin="0" aria-valuemax="100">
                            <div class="progress-bar bg-primary" style="width: 100%"></div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="">
                                    <p class="text-muted fw-semibold fs-16 mb-1">Total KPIs</p>
                                    <p class="text-muted mb-4">
                                        {{ ucwords($unit->unit_name) }}
                                    </p>
                                </div>
                                <div class="avatar-sm mb-4">
                                    <div class="avatar-title bg-primary-subtle text-primary fs-24 rounded">
                                        <i class="ri-file-list-2-line"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex flex-wrap flex-lg-nowrap justify-content-between align-items-end">
                                <h3 class="mb-0 d-flex">{{ $totalKPI }}</h3>
                                <div class="d-flex align-items-end h-100">
                                    <div id="daily-orders" data-colors="#007aff"></div>
                                </div>
                            </div>
                        </div><!-- end card-body -->
                    </div><!-- end card -->
                </div><!-- end col -->

                <div class="col-xl-4">
                    <div class="card overflow-hidden border-top-0">
                        <div class="progress progress-sm rounded-0 bg-light" role="progressbar" aria-valuenow="88"
                            aria-valuemin="0" aria-valuemax="100">
                            <div class="progress-bar bg-dark" style="width: 100%"></div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="">
                                    <p class="text-muted fw-semibold fs-16 mb-1">Total Risks</p>
                                    <p class="text-muted mb-4"> {{ ucwords($unit->unit_name) }}
                                    </p>
                                </div>
                                <div class="avatar-sm mb-4">
                                    <div class="avatar-title bg-dark-subtle text-dark fs-24 rounded">
                                        <i class="ri-book-2-line"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex flex-wrap flex-lg-nowrap justify-content-between align-items-end">
                                <h3 class="mb-0 d-flex"> {{ $totalRisk }} </h3>
                                <div class="d-flex align-items-end h-100">
                                    <div id="new-leads-chart" data-colors="#404040"></div>
                                </div>
                            </div>
                        </div><!-- end card-body -->
                    </div><!-- end card -->
                </div><!-- end col -->

                <div class="col-xl-4">
                    <div class="card overflow-hidden border-top-0">
                        <div class="progress progress-sm rounded-0 bg-light" role="progressbar" aria-valuenow="88"
                            aria-valuemin="0" aria-valuemax="100">
                            <div class="progress-bar bg-danger" style="width: 100%"></div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="">
                                    <p class="text-muted fw-semibold fs-16 mb-1">Total Controls</p>
                                    <p class="text-muted mb-4">
                                        {{ ucwords($unit->unit_name) }}
                                    </p>
                                </div>
                                <div class="avatar-sm mb-4">
                                    <div class="avatar-title bg-danger-subtle text-danger fs-24 rounded">
                                        <i class="ri-calendar-todo-line"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex flex-wrap flex-lg-nowrap justify-content-between align-items-end">
                                <h3 class="mb-0 d-flex">{{ $totalControl }} </h3>
                                <div class="d-flex align-items-end h-100">
                                    <div id="booked-revenue-chart" data-colors="#bb3939"></div>
                                </div>
                            </div>
                        </div><!-- end card-body -->
                    </div><!-- end card -->
                </div><!-- end col -->
            </div><!-- end row -->
        @endif

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div class="">
                            <h4 class="card-title">Daftar Ranking Resiko</h4>
                            <p class="text-muted fw-semibold mb-0">Daftar Risiko Tertinggi</p>
                        </div><!-- end card-header -->
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
                                    <input wire:model.live.debounce.100ms="search" type="text"
                                        class="form-control" placeholder="Search...">
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
                            @php
                                $hasData = false;
                                $risksToShow = [];
                            @endphp

                            @forelse ($kpis as $index => $kpi)
                                @foreach ($kpi->konteks as $konteks)
                                    @foreach ($konteks->risk as $risk)
                                        @php
                                            $lastControlRisk = $risk->controlRisk->last();
                                        @endphp

                                        @if ($lastControlRisk && $lastControlRisk->controlRisk_RPN >= 501 && $lastControlRisk->controlRisk_RPN <= 1000)
                                            @php
                                                $hasData = true;
                                                $risksToShow[] = [
                                                    'index' => $index + 1,
                                                    'risk_id' => $risk->risk_id,
                                                    'kode' => $risk->risk_kode,
                                                    'desc' => $risk->risk_riskDesc,
                                                    'rpn' => $lastControlRisk->controlRisk_RPN,
                                                    'unit' => $kpi->unit->unit_name,
                                                    'degree' => $lastControlRisk->derajatRisiko->derajatRisiko_desc,
                                                    'periode' => $kpi->kpi_periode,
                                                    'kpi_id' => $kpi->kpi_id,
                                                ];
                                            @endphp
                                        @endif
                                    @endforeach
                                @endforeach
                            @empty
                            @endforelse

                            @if ($hasData)
                                @php
                                    // Sort risks by RPN in descending order (high to low)
                                    usort($risksToShow, function ($a, $b) {
                                        return $b['rpn'] - $a['rpn'];
                                    });
                                @endphp

                                <table class="table table-centered mb-0">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>No</th>
                                            <th style="width: 100px;">Kode</th>
                                            <th>Deskripsi Risiko</th>
                                            <th>RPN</th>
                                            <th>Unit</th>
                                            <th>Derajat Tingkat Risiko</th>
                                            <th>Periode</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($risksToShow as $risk)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $risk['kode'] }}</td>
                                                <td style="word-break: break-word;">
                                                    {{ ucwords($risk['desc']) }}
                                                </td>
                                                <td>
                                                    <button class="btn btn-danger">
                                                        {{ $risk['rpn'] }}
                                                    </button>
                                                </td>
                                                <td>{{ ucwords($risk['unit']) }}</td>
                                                <td>{{ ucfirst($risk['degree']) ?? 'N/A' }}</td>
                                                <td>{{ $risk['periode'] }}</td>
                                                <td>
                                                    <div
                                                        class="d-flex justify-content-center align-items-center gap-2 flex-column">
                                                        <div class="btn-group gap-2 d-flex flex-column"
                                                            role="group">
                                                            <button type="button"
                                                                wire:click.prevent="openCetakRiskControl({{ $risk['kpi_id'] }}, {{ $risk['risk_id'] }})"
                                                                wire:loading.attr="disabled"
                                                                wire:target="openCetakRiskControl({{ $risk['kpi_id'] }}, {{ $risk['risk_id'] }})"
                                                                class="btn btn-dark btn-sm">
                                                                <i class="ri-printer-line me-1" wire:loading.remove
                                                                    wire:target='openCetakRiskControl({{ $risk['kpi_id'] }}, {{ $risk['risk_id'] }})'>
                                                                </i>
                                                                Cetak
                                                                <span class="ms-2" wire:loading
                                                                    wire:target="openCetakRiskControl({{ $risk['kpi_id'] }}, {{ $risk['risk_id'] }})">
                                                                    <span class="spinner-border spinner-border-sm"
                                                                        role="status" aria-hidden="true"></span>
                                                                </span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="alert alert-danger mt-2 mb-2">
                                    No Data Available.
                                </div>
                            @endif
                        </div> <!-- end table-responsive -->


                        <div class="row mt-2">
                            <div class="col-md-12 text-end">
                                {!! $kpis->links() !!}
                            </div>
                        </div>

                    </div> <!-- end card body-->
                </div> <!-- end card -->
            </div><!-- end col-->
        </div><!-- end row -->
    </div>


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
