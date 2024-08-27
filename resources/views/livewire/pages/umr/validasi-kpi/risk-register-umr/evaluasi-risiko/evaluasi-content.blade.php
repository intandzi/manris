@include('livewire.pages.risk-owner.risk-register.evaluasi-risiko.evaluasi-modal')

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
                                <th wire:click.live="doSortRisk('risk_kode')" style="cursor: pointer; width:50px;">
                                    Kode Risiko
                                    <x-sorting-table :orderAsc="$orderAscRisk" :orderBy="$orderByRisk" :columnName="'risk_kode'" />
                                </th>
                                <th wire:click.live="doSort('risk_riskDesc')" style="cursor: pointer; width:400px;">
                                    Risiko
                                </th>
                                <th>
                                    RPN
                                </th>
                                <th>
                                    Derajat Risiko
                                </th>
                                <th style="cursor: pointer; width:400px;">Tindak Lanjut yang Diperlukan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no = 1;
                            @endphp
                            @if ($evaluasis && count($evaluasis) > 0)
                                @forelse ($evaluasis as $index => $item)
                                    {{-- @dump($item->controlRisk->first()) --}}

                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>
                                            {{ $item->risk_kode }}
                                        </td>
                                        <td style="word-break: break-word;">
                                            {{-- {{ Str::limit($item->risk_riskDesc, 100, '...') }} --}}
                                            {{ $item->risk_riskDesc }}
                                        </td>
                                        {{-- <td>
                                            @if ($item->controlRisk->isNotEmpty())
                                                <button class="btn btn-secondary">
                                                    {{ $item->controlRisk->first()->kemungkinan->kemungkinan_scale }}
                                                </button>
                                            @else
                                                <button class="btn btn-secondary">
                                                    -
                                                </button>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($item->controlRisk->isNotEmpty())
                                                <button class="btn btn-secondary">
                                                    {{ $item->controlRisk->first()->dampak->dampak_scale }}
                                                </button>
                                            @else
                                                <button class="btn btn-secondary">
                                                    -
                                                </button>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($item->controlRisk->isNotEmpty())
                                                <button class="btn btn-secondary">
                                                    {{ $item->controlRisk->first()->deteksiKegagalan->deteksiKegagalan_scale }}
                                                </button>
                                            @else
                                                <button class="btn btn-secondary">
                                                    -
                                                </button>
                                            @endif
                                        </td> --}}
                                        <td>
                                            @if ($item->controlRisk->isNotEmpty())
                                                @php
                                                    $controlRiskRPN = $item->controlRisk->first()->controlRisk_RPN;
                                                    $background = '';

                                                    if ($controlRiskRPN <= 250) {
                                                        $background = 'success';
                                                    } elseif ($controlRiskRPN <= 500) {
                                                        $background = 'warning';
                                                    } elseif ($controlRiskRPN <= 1000) {
                                                        $background = 'danger';
                                                    }
                                                @endphp

                                                <button class="btn btn-{{ $background }}">
                                                    {{ $controlRiskRPN }}
                                                </button>
                                            @else
                                                <button class="btn btn-secondary">
                                                    -
                                                </button>
                                            @endif

                                        </td>
                                        <td>
                                            {{ ucwords($item->controlRisk->first()->derajatRisiko->derajatRisiko_desc) }}
                                        </td>
                                        {{-- GET ACTIVE SELERA RISIKO --}}
                                        @php
                                            $tindakLanjut = '';
                                            $controlRisk = $item->controlRisk->first(); // Assuming controlRisk is a relationship that returns a collection or null

                                            if ($controlRisk) {
                                                $derajatRisiko = $controlRisk->derajatRisiko; // Assuming derajatRisiko is a relationship
                                                if ($derajatRisiko) {
                                                    foreach ($derajatRisiko->seleraRisiko as $data) {
                                                        if ($data->seleraRisiko_activeStatus) {
                                                            $tindakLanjut = $data->seleraRisiko_tindakLanjut;
                                                            break; // Exit the loop as we found the first active seleraRisiko
                                                        }
                                                    }
                                                }
                                            }
                                        @endphp

                                        <td style="word-break: break-word;">
                                            {{ ucwords($tindakLanjut) }}
                                        </td>
                                        {{-- <td>
                                            <div class="btn-group gap-2" role="group">
                                                @if ($item->controlRisk->isNotEmpty())
                                                    @if ($item->controlRisk->first()->controlRisk_lockStatus)
                                                        <span
                                                            class="badge badge-outline-danger rounded-pill mt-2">Locked!</span>
                                                    @else
                                                        <button type="button"
                                                            wire:click.prevent="editEvaluasi({{ $item->risk_id }})"
                                                            wire:loading.attr="disabled"
                                                            wire:target="editEvaluasi({{ $item->risk_id }})"
                                                            class="btn btn-warning btn-sm d-flex text-center align-items-center">
                                                            <i class="ri-pencil-line" wire:loading.remove
                                                                wire:target='editEvaluasi({{ $item->risk_id }})'>
                                                            </i>
                                                            <span wire:loading
                                                                wire:target="editEvaluasi({{ $item->risk_id }})">
                                                                <span class="spinner-border spinner-border-sm"
                                                                    role="status" aria-hidden="true"></span>
                                                            </span>
                                                        </button>
                                                        <button type="button"
                                                            wire:click.prevent="openModalConfirmEvaluasi({{ $item->risk_id }})"
                                                            wire:loading.attr="disabled"
                                                            wire:target="openModalConfirm({{ $item->risk_id }})"
                                                            class="btn btn-danger btn-sm d-flex text-center align-items-center">
                                                            <i class="ri-lock-fill" wire:loading.remove
                                                                wire:target='openModalConfirmEvaluasi({{ $item->risk_id }})'>
                                                            </i>
                                                            <span wire:loading
                                                                wire:target="openModalConfirmEvaluasi({{ $item->risk_id }})">
                                                                <span class="spinner-border spinner-border-sm"
                                                                    role="status" aria-hidden="true"></span>
                                                            </span>
                                                        </button>
                                                    @endif
                                                @else
                                                    <button type="button"
                                                        wire:click.prevent="createEvaluasi({{ $item->risk_id }})"
                                                        wire:loading.attr="disabled"
                                                        wire:target="createEvaluasi({{ $item->risk_id }})"
                                                        class="btn btn-primary btn-sm d-flex text-center align-items-center">
                                                        <i class="ri-add-line" wire:loading.remove
                                                            wire:target='createEvaluasi({{ $item->risk_id }})'>
                                                        </i>
                                                        <span wire:loading
                                                            wire:target="createEvaluasi({{ $item->risk_id }})">
                                                            <span class="spinner-border spinner-border-sm"
                                                                role="status" aria-hidden="true"></span>
                                                        </span>
                                                    </button>
                                                @endif
                                            </div>
                                        </td> --}}

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

<div class="row mt-1 mb-3">
    <div class="col-12">
        <div class="">
            <div class="">
                <div class="row">
                    <div class="col-12">
                        <label for="" class="form-label">Kategori Kekritisan</label>
                        <div class="row">
                            <div class="col-md-12">
                                <span>
                                    <div class="badge bg-danger">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
                                    Tinggi 501 - 1000
                                </span>
                                <span> <br>
                                    <div class="badge bg-warning">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    </div>
                                    Sedang 251 - 500
                                </span> <br>
                                <span>
                                    <div class="badge bg-primary">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    </div>
                                    Rendah 1 - 250
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div><!-- end row -->


{{-- @if ($isOpenConfirmEvaluasi)
    <div class="modal" tabindex="-1" role="dialog" style="display: block;" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Menunggu Konfirmasi</h5>
                    <button type="button" class="btn-close" aria-label="Close"
                        wire:click="closeXModalConfirmEvaluasi"></button>
                </div>
                <div class="modal-body">
                    Data tidak bisa diubah apabila Anda mengunci Evaluasi Risiko. Apakah Anda yakin ingin mengunci
                    Evaluasi Risiko
                    ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click='closeModalConfirmEvaluasi'
                        wire:loading.attr="disabled" wire:target="closeModalConfirmEvaluasi">
                        Tutup
                        <span wire:loading class="ms-2" wire:target="closeModalConfirmEvaluasi">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        </span>
                    </button>
                    <button type="button" class="btn btn-primary" wire:click="lockEvaluasiRisiko"
                        wire:loading.attr="disabled" wire:target="lockEvaluasiRisiko">
                        Kunci Evaluasi Risiko
                        <span wire:loading class="ms-2" wire:target="lockEvaluasiRisiko">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        </span>
                    </button>
                </div>
            </div>
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <div class="modal-backdrop fade show"></div>
@endif --}}
