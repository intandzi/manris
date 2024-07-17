@include('livewire.pages.risk-owner.risk-register.kriteria-risiko.components.kemungkinan-modal')
@include('livewire.pages.risk-owner.risk-register.kriteria-risiko.components.dampak-modal')
@include('livewire.pages.risk-owner.risk-register.kriteria-risiko.components.deteksi-modal')

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
                                <th wire:click.live="doSortRisk('risk_kode')" style="cursor: pointer; width:120px;">
                                    Kode Risiko
                                    <x-sorting-table :orderAsc="$orderAscRisk" :orderBy="$orderByRisk" :columnName="'risk_kode'" />
                                </th>
                                <th wire:click.live="doSort('risk_riskDesc')" style="cursor: pointer; width:500px;">
                                    Risiko
                                </th>
                                <th>
                                    Kemungkinan
                                </th>
                                <th>
                                    Dampak
                                </th>
                                <th>
                                    Deteksi
                                </th>
                                <th>Tindak Lanjut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no = 1;
                            @endphp
                            @if ($kriterias && count($kriterias) > 0)
                                @forelse ($kriterias as $index => $item)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>
                                            {{ $item->risk_kode }}
                                        </td>
                                        <td>
                                            {{ Str::limit($item->risk_riskDesc, 100, '...') }}
                                        </td>
                                        <td>
                                            @if ($item->kemungkinan->isNotEmpty())
                                                @if ($item->kemungkinan->first()->kemungkinan_lockStatus)
                                                    <span
                                                        class="badge badge-outline-danger rounded-pill mt-2">Locked!</span>
                                                @else
                                                    <div class="d-inline d-flex">
                                                        <button type="button"
                                                            wire:click.prevent="editKemungkinan({{ $item->risk_id }})"
                                                            wire:loading.attr="disabled"
                                                            wire:target="editKemungkinan({{ $item->risk_id }})"
                                                            class="btn btn-warning btn-sm d-flex text-center align-items-center">
                                                            <i class="ri-pencil-fill" wire:loading.remove
                                                                wire:target='editKemungkinan({{ $item->risk_id }})'>
                                                            </i>
                                                            <span wire:loading
                                                                wire:target="editKemungkinan({{ $item->risk_id }})">
                                                                <span class="spinner-border spinner-border-sm"
                                                                    role="status" aria-hidden="true"></span>
                                                            </span>
                                                        </button>
                                                        <button type="button"
                                                            wire:click.prevent="showKemungkinan({{ $item->risk_id }})"
                                                            wire:loading.attr="disabled"
                                                            wire:target="showKemungkinan({{ $item->risk_id }})"
                                                            class="btn btn-primary btn-sm d-flex text-center align-items-center ms-1">
                                                            <i class="ri-eye-fill" wire:loading.remove
                                                                wire:target='showKemungkinan({{ $item->risk_id }})'>
                                                            </i>
                                                            <span wire:loading
                                                                wire:target="showKemungkinan({{ $item->risk_id }})">
                                                                <span class="spinner-border spinner-border-sm"
                                                                    role="status" aria-hidden="true"></span>
                                                            </span>
                                                        </button>
                                                    </div>
                                                @endif
                                            @else
                                                <button type="button"
                                                    wire:click.prevent="openModalKemungkinan({{ $item->risk_id }})"
                                                    wire:loading.attr="disabled"
                                                    wire:target="openModalKemungkinan({{ $item->risk_id }})"
                                                    class="btn btn-success btn-sm d-flex text-center align-items-center">
                                                    <i class="ri-add-line" wire:loading.remove
                                                        wire:target='openModalKemungkinan({{ $item->risk_id }})'>
                                                    </i>
                                                    Kemungkinan
                                                    <span class="ms-2" wire:loading
                                                        wire:target="openModalKemungkinan({{ $item->risk_id }})">
                                                        <span class="spinner-border spinner-border-sm" role="status"
                                                            aria-hidden="true"></span>
                                                    </span>
                                                </button>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($item->dampak->isNotEmpty())
                                                @if ($item->dampak->first()->dampak_lockStatus)
                                                    <span
                                                        class="badge badge-outline-danger rounded-pill mt-2">Locked!</span>
                                                @else
                                                    <div class="d-inline d-flex">
                                                        <button type="button"
                                                            wire:click.prevent="editDampak({{ $item->risk_id }})"
                                                            wire:loading.attr="disabled"
                                                            wire:target="editDampak({{ $item->risk_id }})"
                                                            class="btn btn-warning btn-sm d-flex text-center align-items-center">
                                                            <i class="ri-pencil-fill" wire:loading.remove
                                                                wire:target='editDampak({{ $item->risk_id }})'>
                                                            </i>
                                                            <span wire:loading
                                                                wire:target="editDampak({{ $item->risk_id }})">
                                                                <span class="spinner-border spinner-border-sm"
                                                                    role="status" aria-hidden="true"></span>
                                                            </span>
                                                        </button>
                                                        <button type="button"
                                                            wire:click.prevent="showDampak({{ $item->risk_id }})"
                                                            wire:loading.attr="disabled"
                                                            wire:target="showDampak({{ $item->risk_id }})"
                                                            class="btn btn-primary btn-sm d-flex text-center align-items-center ms-1">
                                                            <i class="ri-eye-fill" wire:loading.remove
                                                                wire:target='showDampak({{ $item->risk_id }})'>
                                                            </i>
                                                            <span wire:loading
                                                                wire:target="showDampak({{ $item->risk_id }})">
                                                                <span class="spinner-border spinner-border-sm"
                                                                    role="status" aria-hidden="true"></span>
                                                            </span>
                                                        </button>
                                                    </div>
                                                @endif
                                            @else
                                                <button type="button"
                                                    wire:click.prevent="openModalDampak({{ $item->risk_id }})"
                                                    wire:loading.attr="disabled"
                                                    wire:target="openModalDampak({{ $item->risk_id }})"
                                                    class="btn btn-success btn-sm d-flex text-center align-items-center">
                                                    <i class="ri-add-line me-1" wire:loading.remove
                                                        wire:target='openModalDampak({{ $item->risk_id }})'>
                                                    </i>
                                                    Dampak
                                                    <span class="ms-2" wire:loading
                                                        wire:target="openModalDampak({{ $item->risk_id }})">
                                                        <span class="spinner-border spinner-border-sm" role="status"
                                                            aria-hidden="true"></span>
                                                    </span>
                                                </button>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($item->deteksiKegagalan->isNotEmpty())
                                                @if ($item->deteksiKegagalan->first()->deteksiKegagalan_lockStatus)
                                                    <span
                                                        class="badge badge-outline-danger rounded-pill mt-2">Locked!</span>
                                                @else
                                                    <div class="d-inline d-flex">
                                                        <button type="button"
                                                            wire:click.prevent="editDeteksi({{ $item->risk_id }})"
                                                            wire:loading.attr="disabled"
                                                            wire:target="editDeteksi({{ $item->risk_id }})"
                                                            class="btn btn-warning btn-sm text-center align-items-center">
                                                            <i class="ri-pencil-fill" wire:loading.remove
                                                                wire:target='editDeteksi({{ $item->risk_id }})'></i>
                                                            <span wire:loading
                                                                wire:target="editDeteksi({{ $item->risk_id }})">
                                                                <span class="spinner-border spinner-border-sm"
                                                                    role="status" aria-hidden="true"></span>
                                                            </span>
                                                        </button>
                                                        <button type="button"
                                                            wire:click.prevent="showDeteksi({{ $item->risk_id }})"
                                                            wire:loading.attr="disabled"
                                                            wire:target="showDeteksi({{ $item->risk_id }})"
                                                            class="btn btn-primary btn-sm text-center align-items-center ms-1">
                                                            <i class="ri-eye-fill" wire:loading.remove
                                                                wire:target='showDeteksi({{ $item->risk_id }})'></i>
                                                            <span wire:loading
                                                                wire:target="showDeteksi({{ $item->risk_id }})">
                                                                <span class="spinner-border spinner-border-sm"
                                                                    role="status" aria-hidden="true"></span>
                                                            </span>
                                                        </button>
                                                    </div>
                                                @endif
                                            @else
                                                <button type="button"
                                                    wire:click.prevent="openModalDeteksi({{ $item->risk_id }})"
                                                    wire:loading.attr="disabled"
                                                    wire:target="openModalDeteksi({{ $item->risk_id }})"
                                                    class="btn btn-success btn-sm d-flex text-center align-items-center">
                                                    <i class="ri-add-line me-1" wire:loading.remove
                                                        wire:target='openModalDeteksi({{ $item->risk_id }})'>
                                                    </i>
                                                    Deteksi
                                                    <span class="ms-2" wire:loading
                                                        wire:target="openModalDeteksi({{ $item->risk_id }})">
                                                        <span class="spinner-border spinner-border-sm" role="status"
                                                            aria-hidden="true"></span>
                                                    </span>
                                                </button>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group gap-2" role="group">
                                                @if ($item->dampak->isNotEmpty() && $item->deteksiKegagalan->isNotEmpty() && $item->kemungkinan->isNotEmpty())
                                                    @if (
                                                        $item->kemungkinan->first()->kemungkinan_lockStatus &&
                                                            $item->dampak->first()->dampak_lockStatus &&
                                                            $item->deteksiKegagalan->first()->deteksiKegagalan_lockStatus)
                                                        <span
                                                            class="badge badge-outline-danger rounded-pill mt-2">Locked!</span>
                                                    @else
                                                        <button type="button"
                                                            wire:click.prevent="openModalConfirmKriteria({{ $item->risk_id }})"
                                                            wire:loading.attr="disabled"
                                                            wire:target="openModalConfirm({{ $item->risk_id }})"
                                                            class="btn btn-danger btn-sm d-flex text-center align-items-center">
                                                            <i class="ri-lock-fill" wire:loading.remove
                                                                wire:target='openModalConfirmKriteria({{ $item->risk_id }})'>
                                                            </i>
                                                            <span wire:loading
                                                                wire:target="openModalConfirmKriteria({{ $item->risk_id }})">
                                                                <span class="spinner-border spinner-border-sm"
                                                                    role="status" aria-hidden="true"></span>
                                                            </span>
                                                        </button>
                                                    @endif
                                                @else
                                                    <span class="badge badge-outline-danger rounded-pill mt-2">Lengkapi
                                                        Kriteria!</span>
                                                @endif
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
                        {!! $kriterias->links() !!}
                    </div>
                </div>

            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div><!-- end row -->


@if ($isOpenConfirmKriteria)
    <div class="modal" tabindex="-1" role="dialog" style="display: block;" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Menunggu Konfirmasi</h5>
                    <button type="button" class="btn-close" aria-label="Close"
                        wire:click="closeXModalConfirm"></button>
                </div>
                <div class="modal-body">
                    Data tidak bisa diubah apabila Anda mengunci Kriteria Risiko. Apakah Anda yakin ingin mengunci
                    Kriteria Risiko
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
                    <button type="button" class="btn btn-primary" wire:click="lockKriteria"
                        wire:loading.attr="disabled" wire:target="lockKriteria">
                        Kunci Kriteria Risiko
                        <span wire:loading class="ms-2" wire:target="lockKriteria">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        </span>
                    </button>
                </div>
            </div>
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <div class="modal-backdrop fade show"></div>
@endif
