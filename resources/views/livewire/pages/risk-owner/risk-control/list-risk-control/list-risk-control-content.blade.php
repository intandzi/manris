<div>

    @include('livewire.pages.risk-owner.risk-register.identifikasi-risiko.identifikasi-risiko-modal')

    <div class="container-fluid">

        <!-- breadcrumbs component -->
        <nav aria-label="breadcrumb" class="mb-2">
            @if ($this->role === 'risk owner')
                <ol class="breadcrumb mb-0 p-2">
                    <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-apps"></i>
                            App</a></li>

                    <li class="breadcrumb-item"><a href="{{ route('riskControlOw.index', ['role' => $encryptedRole]) }}"
                            wire:navigate>
                            Risk Register</a></li>
                    <li class="breadcrumb-item active"><a
                            href="{{ route('controlKonteksRisikoOw.index', ['role' => $encryptedRole, 'kpi' => $encryptedKPI]) }}"
                            wire:navigate>Konteks Risiko</a></li>
                    <li class="breadcrumb-item active"><a href="#">Daftar Risiko</a></li>
                </ol>
            @else
                <ol class="breadcrumb mb-0 p-2">
                    <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-apps"></i>
                            App</a></li>

                    <li class="breadcrumb-item"><a href="{{ route('officerRiskControl.index', ['role' => $encryptedRole]) }}"
                            wire:navigate>
                            Risk Register</a></li>
                    <li class="breadcrumb-item active"><a
                            href="{{ route('officerControlKonteksRisiko.index', ['role' => $encryptedRole, 'kpi' => $encryptedKPI]) }}"
                            wire:navigate>Konteks Risiko</a></li>
                    <li class="breadcrumb-item active"><a href="#">Daftar Risiko</a></li>
                </ol>
            @endif
        </nav>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="table-responsive mb-2">
                            <table>
                                <tr>
                                    <td style="width: 200px;">
                                        <p class="card-text me-4" style="font-weight: bold">Unit Pemilik Risiko
                                        </p>
                                    </td>
                                    <td>:</td>
                                    <td style="width: calc(100% - 250px); vertical-align: bottom;">
                                        <p class="card-text" style="word-wrap: break-word;">{{ $unit_nama }}
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 200px; vertical-align: top;">
                                        <p class="card-text" style="font-weight: bold">Deskripsi Konteks</p>
                                    </td>
                                    <td style="vertical-align: top;">:</td>
                                    <td style="vertical-align: bottom;">
                                        <p class="card-text" style="word-wrap: break-word;">{{ $konteks_desc }}
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div> <!-- end card-->
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
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
                            <div class="col-3 ms-auto">
                                <label for="" class="form-label">Search</label>
                                <div class="input-group">
                                    <input wire:model.live.debounce.100ms="searchRisk" type="text"
                                        class="form-control" placeholder="Search...">
                                    @if ($searchRisk)
                                        <button type="button" wire:click.prevent="clearSearchRisk"
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
                                        <th wire:click.live="doSortRisk('risk_kode')"
                                            style="cursor: pointer; width:50px;">
                                            Kode Risiko
                                            <x-sorting-table :orderAsc="$orderAscRisk" :orderBy="$orderByRisk" :columnName="'risk_kode'" />
                                        </th>
                                        <th wire:click.live="doSort('risk_riskDesc')"
                                            style="cursor: pointer; width:700px;">
                                            Risiko
                                        </th>
                                        <th style="cursor: pointer; width:150px;">
                                            Status <br> Risk That Matter
                                        </th>
                                        <th style="cursor: pointer; width:300px;">
                                            Perlakuan Risiko
                                        </th>
                                        <th>
                                            Aksi
                                        </th>
                                        <th>Tindak Lanjut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @if ($risks && count($risks) > 0)
                                        @forelse ($risks as $index => $item)
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td>
                                                    {{ $item->risk_kode }}
                                                </td>
                                                <td style="word-break: break-word;">
                                                    {{ Str::limit($item->risk_riskDesc, 100, '...') }}
                                                </td>
                                                <td>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox"
                                                            id="toggle_{{ $item->controlRisk->first()->controlRisk_id }}"
                                                            wire:click="toggleActiveRTM({{ $item->controlRisk->first()->controlRisk_id }})"
                                                            @if ($item->controlRisk->first()->controlRisk_RTM === 'RTM') checked @endif>
                                                    </div>
                                                </td>
                                                <td style="word-break: break-word;">
                                                    <button class="btn btn-light">
                                                        {{ ucwords($item->controlRisk->first()->perlakuanRisiko->first()->jenisPerlakuan->jenisPerlakuan_desc) }}
                                                    </button>
                                                </td>
                                                <td>
                                                    <div class="btn-group gap-2" role="group">
                                                        <button type="button"
                                                            wire:click.prevent="showRisk({{ $item->risk_id }})"
                                                            wire:loading.attr="disabled"
                                                            wire:target="showRisk({{ $item->risk_id }})"
                                                            class="btn btn-primary btn-sm d-flex text-center align-items-center">
                                                            <i class="ri-eye-fill" wire:loading.remove
                                                                wire:target='showRisk({{ $item->risk_id }})'>
                                                            </i>
                                                            <span wire:loading
                                                                wire:target="showRisk({{ $item->risk_id }})">
                                                                <span class="spinner-border spinner-border-sm"
                                                                    role="status" aria-hidden="true"></span>
                                                            </span>
                                                        </button>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="btn-group gap-2" role="group">
                                                        <button type="button"
                                                            wire:click.prevent="riskControl({{ $item->risk_id }})"
                                                            wire:loading.attr="disabled"
                                                            wire:target="riskControl({{ $item->risk_id }})"
                                                            class="btn btn-success btn-sm d-flex text-center align-items-center">
                                                            <i class="ri-arrow-right-line" wire:loading.remove
                                                                wire:target='riskControl({{ $item->risk_id }})'>
                                                            </i>
                                                            <span wire:loading
                                                                wire:target="riskControl({{ $item->risk_id }})">
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
                                    @else
                                        <div class="alert alert-danger mt-2 mb-2">
                                            No data available.
                                        </div>
                                    @endif
                                </tbody>
                            </table>
                        </div> <!-- end table-responsive-->
                        <div class="row mt-2">
                            <div class="col-md-12 text-end">
                                {!! $risks->links() !!}
                            </div>
                        </div>

                    </div> <!-- end card body-->
                </div> <!-- end card -->
            </div><!-- end col-->
        </div><!-- end row -->


        @if ($isOpenConfirmRisk)
            <div class="modal" tabindex="-1" role="dialog" style="display: block;" aria-modal="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Menunggu Konfirmasi</h5>
                            <button type="button" class="btn-close" aria-label="Close"
                                wire:click="closeXModalConfirm"></button>
                        </div>
                        <div class="modal-body">
                            Data tidak bisa diubah apabila Anda mengunci Risiko. Apakah Anda yakin ingin mengunci Risiko
                            ini?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" wire:click='closeModalConfirmRisk'
                                wire:loading.attr="disabled" wire:target="closeModalConfirmRisk">
                                Tutup
                                <span wire:loading class="ms-2" wire:target="closeModalConfirmRisk">
                                    <span class="spinner-border spinner-border-sm" role="status"
                                        aria-hidden="true"></span>
                                </span>
                            </button>
                            <button type="button" class="btn btn-primary" wire:click="lockRisk"
                                wire:loading.attr="disabled" wire:target="lockRisk">
                                Kunci Risiko
                                <span wire:loading class="ms-2" wire:target="lockRisk">
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

    </div> <!-- container -->

</div>
