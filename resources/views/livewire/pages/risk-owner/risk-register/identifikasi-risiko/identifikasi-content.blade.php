<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="section-description-actions">
                    <button type="button" wire:click.prevent="openModalRisk" class="btn btn-primary"
                        wire:loading.attr="disabled" wire:target="openModalRisk"><i class="ri-add-line"></i>
                        Create
                        Identifikasi Risiko
                        <span wire:loading class="ms-2" wire:target="openModalRisk">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        </span>
                    </button>
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
                    <div class="col-3 ms-auto">
                        <label for="" class="form-label">Search</label>
                        <div class="input-group">
                            <input wire:model.live.debounce.100ms="searchRisk" type="text" class="form-control"
                                placeholder="Search...">
                            @if ($searchRisk)
                                <button type="button" wire:click.prevent="clearSearchRisk"
                                    class="btn btn-primary d-flex align-items-center btn-sm" title="Cancel Edit">
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
                                <th wire:click.live="doSortRisk('risk_kode')" style="cursor: pointer; width:50px;">
                                    Kode Risiko
                                    <x-sorting-table :orderAsc="$orderAscRisk" :orderBy="$orderByRisk" :columnName="'risk_kode'" />
                                </th>
                                <th wire:click.live="doSort('risk_riskDesc')" style="cursor: pointer; width:500px;">
                                    Risiko
                                </th>
                                <th style="cursor: pointer; width:50px;">
                                    Kode Penyebab
                                </th>
                                <th style="cursor: pointer; width:500px;">
                                    Penyebab Risiko
                                </th>
                                <th>
                                    Aksi
                                </th>
                                @if ($this->role === 'risk owner')
                                    <th>Tindak Lanjut</th>
                                @endif
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
                                            {{ Str::limit($item->risk_riskDesc, 50, '...') }}
                                        </td>
                                        <td>
                                            {{ ucwords($item->risk_penyebabKode) }}
                                        </td>
                                        <td style="word-break: break-word;">
                                            {{ Str::limit($item->risk_penyebab, 100, '...') }}
                                        </td>
                                        <td>
                                            <div class="btn-group gap-2" role="group">
                                                <button type="button"
                                                    wire:click.prevent="showRisk({{ $item->risk_id }})"
                                                    wire:loading.attr="disabled"
                                                    wire:target="showRisk({{ $item->risk_id }})"
                                                    class="btn btn-primary btn-sm btn-icon">
                                                    <i class="ri-eye-fill" wire:loading.remove
                                                        wire:target='showRisk({{ $item->risk_id }})'>
                                                    </i>
                                                    <span wire:loading wire:target="showRisk({{ $item->risk_id }})">
                                                        <span class="spinner-border spinner-border-sm" role="status"
                                                            aria-hidden="true"></span>
                                                    </span>
                                                </button>
                                                @if (!$item->risk_lockStatus)
                                                    <button type="button"
                                                        wire:click.prevent="editRisk({{ $item->risk_id }})"
                                                        wire:loading.attr="disabled"
                                                        wire:target="editRisk({{ $item->risk_id }})"
                                                        class="btn btn-warning btn-sm btn-icon">
                                                        <i class="ri-pencil-fill" wire:loading.remove
                                                            wire:target='editRisk({{ $item->risk_id }})'>
                                                        </i>
                                                        <span wire:loading
                                                            wire:target="editRisk({{ $item->risk_id }})">
                                                            <span class="spinner-border spinner-border-sm"
                                                                role="status" aria-hidden="true"></span>
                                                        </span>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                        @if ($this->role === 'risk owner')
                                            <td>
                                                <div class="btn-group gap-2" role="group">
                                                    @if ($item->risk_lockStatus)
                                                        <span
                                                            class="badge badge-outline-danger rounded-pill mt-2">Locked!</span>
                                                    @else
                                                        <button type="button"
                                                            wire:click.prevent="openModalConfirmRisk({{ $item->risk_id }})"
                                                            wire:loading.attr="disabled"
                                                            wire:target="openModalConfirm({{ $item->risk_id }})"
                                                            class="btn btn-danger btn-sm btn-icon">
                                                            <i class="ri-lock-fill" wire:loading.remove
                                                                wire:target='openModalConfirmRisk({{ $item->risk_id }})'>
                                                            </i>
                                                            <span wire:loading
                                                                wire:target="openModalConfirmRisk({{ $item->risk_id }})">
                                                                <span class="spinner-border spinner-border-sm"
                                                                    role="status" aria-hidden="true"></span>
                                                            </span>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        @endif

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
        <div class="row mt-2 mb-2">
            <div class="col-md-6 text-start">
                {{-- <button class="btn btn-dark">Sebelumnya</button> --}}
            </div>
            <div class="col-md-6 text-end">
                <button type="button" wire:click.prevent="toggleTab('kriteriaContent')" class="btn btn-dark"
                    wire:loading.attr="disabled" wire:target="toggleTab('kriteriaContent')">
                    Selanjutnya
                    <span wire:loading class="ms-2" wire:target="toggleTab('kriteriaContent')">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    </span>
                </button>
            </div>
        </div>

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
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        </span>
                    </button>
                    <button type="button" class="btn btn-primary" wire:click="lockRisk"
                        wire:loading.attr="disabled" wire:target="lockRisk">
                        Kunci Risiko
                        <span wire:loading class="ms-2" wire:target="lockRisk">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        </span>
                    </button>
                </div>
            </div>
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <div class="modal-backdrop fade show"></div>
@endif
