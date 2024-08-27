@include('livewire.pages.risk-owner.risk-register.rencana-perlakuan-risiko.modal-rencana-perlakuan')

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
                                <th wire:click.live="doSortRisk('risk_kode')" style="cursor: pointer; width:150px;">
                                    Kode Risiko
                                    <x-sorting-table :orderAsc="$orderAscRisk" :orderBy="$orderByRisk" :columnName="'risk_kode'" />
                                </th>
                                <th wire:click.live="doSort('risk_riskDesc')" style="cursor: pointer; width:500px;">
                                    Risiko
                                </th>
                                <th>
                                    Status <br> Risk That Matter
                                </th>
                                <th>
                                    Perlakuan Risiko
                                </th>
                                <th>Tindak Lanjut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no = 1;
                            @endphp
                            @if ($rencanaPerlakuans && count($rencanaPerlakuans) > 0)
                                @forelse ($rencanaPerlakuans as $index => $item)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>
                                            {{ $item->risk_kode }}
                                        </td>
                                        <td style="word-break: break-word;">
                                            {{ Str::limit($item->risk_riskDesc, 100, '...') }}
                                        </td>
                                        <td>
                                            <span
                                                class="mt-2">{{ $item->controlRisk->first()->controlRisk_RTM }}</span>
                                        </td>

                                        <td>
                                            @if ($item->controlRisk->first()->perlakuanRisiko->isNotEmpty())
                                                {{ ucwords($item->controlRisk->first()->perlakuanRisiko->first()->jenisPerlakuan->jenisPerlakuan_desc) }}
                                            @else
                                                <span class="badge badge-outline-danger rounded-pill mt-2">Belum
                                                    diset!</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group gap-2" role="group">
                                                <button type="button"
                                                    wire:click.prevent="showRencanaPerlakuan({{ $item->risk_id }})"
                                                    wire:loading.attr="disabled"
                                                    wire:target="showRencanaPerlakuan({{ $item->risk_id }})"
                                                    class="btn btn-primary btn-sm d-flex text-center align-items-center">
                                                    <i class="ri-eye-fill" wire:loading.remove
                                                        wire:target='showRencanaPerlakuan({{ $item->risk_id }})'>
                                                    </i>
                                                    <span wire:loading
                                                        wire:target="showRencanaPerlakuan({{ $item->risk_id }})">
                                                        <span class="spinner-border spinner-border-sm" role="status"
                                                            aria-hidden="true"></span>
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
                        {!! $kriterias->links() !!}
                    </div>
                </div>

            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div><!-- end row -->


@if ($isOpenConfirmRencanaPerlakuan)
    <div class="modal" tabindex="-1" role="dialog" style="display: block;" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Menunggu Konfirmasi</h5>
                    <button type="button" class="btn-close" aria-label="Close"
                        wire:click="closeXModalConfirmRencanaPerlakuan"></button>
                </div>
                <div class="modal-body">
                    Data tidak bisa diubah apabila Anda mengunci Rencana Perlakuan Risiko. Apakah Anda yakin ingin
                    mengunci
                    Evaluasi Risiko
                    ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click='closeModalConfirmRencanaPerlakuan'
                        wire:loading.attr="disabled" wire:target="closeModalConfirmRencanaPerlakuan">
                        Tutup
                        <span wire:loading class="ms-2" wire:target="closeModalConfirmRencanaPerlakuan">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        </span>
                    </button>
                    <button type="button" class="btn btn-primary" wire:click="lockRencanaPerlakuan"
                        wire:loading.attr="disabled" wire:target="lockRencanaPerlakuan">
                        Kunci Rencana Perlakuan Risiko
                        <span wire:loading class="ms-2" wire:target="lockRencanaPerlakuan">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        </span>
                    </button>
                </div>
            </div>
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <div class="modal-backdrop fade show"></div>
@endif
