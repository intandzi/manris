@include('livewire.pages.risk-owner.risk-register.analisis-risiko.analisis-modal')

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
                                <th style="width:100px;">
                                    Efektifitas Kontrol
                                </th>
                                <th style="width:100px;">
                                    Kemungkinan
                                </th>
                                <th style="width:100px;">
                                    Dampak
                                </th>
                                <th style="width:100px;">
                                    Deteksi
                                </th>
                                <th style="width:100px;">
                                    RPN
                                </th>
                                <th style="width:100px;">Aksi</th>
                                @if ($this->role === 'risk owner')
                                    <th>Tindak Lanjut</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no = 1;
                            @endphp
                            @if ($dataAnalisis && count($dataAnalisis) > 0)
                                @forelse ($dataAnalisis as $index => $item)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>
                                            {{ $item->risk_kode }}
                                        </td>
                                        <td style="word-break: break-word;">
                                            {{ Str::limit($item->risk_riskDesc, 100, '...') }}
                                        </td>
                                        <td>
                                            @if ($item->controlRisk->isNotEmpty())
                                                @if ($item->efektifitasKontrol->isNotEmpty())
                                                    @if ($item->efektifitasKontrol->first()->efektifitasKontrol_lockStatus)
                                                        @if (
                                                            $item->efektifitasKontrol->first()->efektifitasKontrol_totalEfektifitas == 3 ||
                                                                $item->efektifitasKontrol->first()->efektifitasKontrol_totalEfektifitas <= 3)
                                                            <!-- Display Efektif -->
                                                            Efektif
                                                        @elseif (
                                                            $item->efektifitasKontrol->first()->efektifitasKontrol_totalEfektifitas >= 4 &&
                                                                $item->efektifitasKontrol->first()->efektifitasKontrol_totalEfektifitas <= 7)
                                                            <!-- Display Sebagian Efektif -->
                                                            Sebagian Efektif
                                                        @elseif (
                                                            $item->efektifitasKontrol->first()->efektifitasKontrol_totalEfektifitas >= 8 &&
                                                                $item->efektifitasKontrol->first()->efektifitasKontrol_totalEfektifitas <= 9)
                                                            <!-- Display Kurang Efektif -->
                                                            Kurang Efektif
                                                        @elseif ($item->efektifitasKontrol->first()->efektifitasKontrol_totalEfektifitas >= 10)
                                                            <!-- Display Tidak Efektif -->
                                                            Tidak Efektif
                                                        @else
                                                            <!-- Display message if no conditions are met -->
                                                            Data tidak tersedia
                                                        @endif
                                                    @else
                                                        <button type="button"
                                                            wire:click.prevent="editEfektifitas({{ $item->risk_id }})"
                                                            wire:loading.attr="disabled"
                                                            wire:target="editEfektifitas({{ $item->risk_id }})"
                                                            class="btn btn-warning btn-sm d-flex text-center align-items-center">
                                                            @if (
                                                                $item->efektifitasKontrol->first()->efektifitasKontrol_totalEfektifitas == 3 ||
                                                                    $item->efektifitasKontrol->first()->efektifitasKontrol_totalEfektifitas <= 3)
                                                                <!-- Display Efektif -->
                                                                Efektif
                                                            @elseif (
                                                                $item->efektifitasKontrol->first()->efektifitasKontrol_totalEfektifitas >= 4 &&
                                                                    $item->efektifitasKontrol->first()->efektifitasKontrol_totalEfektifitas <= 7)
                                                                <!-- Display Sebagian Efektif -->
                                                                Sebagian Efektif
                                                            @elseif (
                                                                $item->efektifitasKontrol->first()->efektifitasKontrol_totalEfektifitas >= 8 &&
                                                                    $item->efektifitasKontrol->first()->efektifitasKontrol_totalEfektifitas <= 9)
                                                                <!-- Display Kurang Efektif -->
                                                                Kurang Efektif
                                                            @elseif ($item->efektifitasKontrol->first()->efektifitasKontrol_totalEfektifitas >= 10)
                                                                <!-- Display Tidak Efektif -->
                                                                Tidak Efektif
                                                            @else
                                                                <!-- Display message if no conditions are met -->
                                                                Data tidak tersedia
                                                            @endif

                                                            <span wire:loading
                                                                wire:target="editEfektifitas({{ $item->risk_id }})">
                                                                <span class="spinner-border spinner-border-sm"
                                                                    role="status" aria-hidden="true"></span>
                                                            </span>
                                                        </button>
                                                    @endif
                                                @else
                                                    <button type="button"
                                                        wire:click.prevent="createEfektifitas({{ $item->risk_id }})"
                                                        wire:loading.attr="disabled"
                                                        wire:target="createEfektifitas({{ $item->risk_id }})"
                                                        class="btn btn-primary btn-sm d-flex text-center align-items-center">
                                                        <i class="ri-add-line" wire:loading.remove
                                                            wire:target='createEfektifitas({{ $item->risk_id }})'>
                                                        </i>
                                                        <span wire:loading
                                                            wire:target="createEfektifitas({{ $item->risk_id }})">
                                                            <span class="spinner-border spinner-border-sm"
                                                                role="status" aria-hidden="true"></span>
                                                        </span>
                                                    </button>
                                                @endif
                                            @else
                                                <span class="badge badge-outline-danger rounded-pill mt-2">Lengkapi
                                                    <br> Analisis!</span>
                                            @endif

                                        </td>
                                        <td>
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
                                        </td>
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
                                            <div class="btn-group gap-2" role="group">
                                                @if ($item->controlRisk->isNotEmpty())
                                                    <button type="button"
                                                        wire:click.prevent="showAnalisis({{ $item->risk_id }})"
                                                        wire:loading.attr="disabled"
                                                        wire:target="showAnalisis({{ $item->risk_id }})"
                                                        class="btn btn-primary btn-sm d-flex text-center align-items-center">
                                                        <i class="ri-eye-fill" wire:loading.remove
                                                            wire:target='showAnalisis({{ $item->risk_id }})'>
                                                        </i>
                                                        <span wire:loading
                                                            wire:target="showAnalisis({{ $item->risk_id }})">
                                                            <span class="spinner-border spinner-border-sm"
                                                                role="status" aria-hidden="true"></span>
                                                        </span>
                                                    </button>
                                                    @if (!$item->controlRisk->first()->controlRisk_lockStatus)
                                                        <button type="button"
                                                            wire:click.prevent="editAnalisis({{ $item->risk_id }})"
                                                            wire:loading.attr="disabled"
                                                            wire:target="editAnalisis({{ $item->risk_id }})"
                                                            class="btn btn-warning btn-sm d-flex text-center align-items-center">
                                                            <i class="ri-pencil-line" wire:loading.remove
                                                                wire:target='editAnalisis({{ $item->risk_id }})'>
                                                            </i>
                                                            <span wire:loading
                                                                wire:target="editAnalisis({{ $item->risk_id }})">
                                                                <span class="spinner-border spinner-border-sm"
                                                                    role="status" aria-hidden="true"></span>
                                                            </span>
                                                        </button>
                                                    @endif
                                                @else
                                                    <button type="button"
                                                        wire:click.prevent="createAnalisis({{ $item->risk_id }})"
                                                        wire:loading.attr="disabled"
                                                        wire:target="createAnalisis({{ $item->risk_id }})"
                                                        class="btn btn-primary btn-sm d-flex text-center align-items-center">
                                                        <i class="ri-add-line" wire:loading.remove
                                                            wire:target='createAnalisis({{ $item->risk_id }})'>
                                                        </i>
                                                        <span wire:loading
                                                            wire:target="createAnalisis({{ $item->risk_id }})">
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
                                                    @if ($item->controlRisk->isNotEmpty() && $item->efektifitasKontrol->isNotEmpty())
                                                        @if (
                                                            $item->controlRisk->first()->controlRisk_lockStatus &&
                                                                $item->efektifitasKontrol->first()->efektifitasKontrol_lockStatus)
                                                            <span
                                                                class="badge badge-outline-danger rounded-pill mt-2">Locked!</span>
                                                        @else
                                                            <button type="button"
                                                                wire:click.prevent="openModalConfirmAnalisis({{ $item->risk_id }})"
                                                                wire:loading.attr="disabled"
                                                                wire:target="openModalConfirm({{ $item->risk_id }})"
                                                                class="btn btn-danger btn-sm d-flex text-center align-items-center">
                                                                <i class="ri-lock-fill" wire:loading.remove
                                                                    wire:target='openModalConfirmAnalisis({{ $item->risk_id }})'>
                                                                </i>
                                                                <span wire:loading
                                                                    wire:target="openModalConfirmAnalisis({{ $item->risk_id }})">
                                                                    <span class="spinner-border spinner-border-sm"
                                                                        role="status" aria-hidden="true"></span>
                                                                </span>
                                                            </button>
                                                        @endif
                                                    @else
                                                        <span
                                                            class="badge badge-outline-danger rounded-pill mt-2">Selesaikan
                                                            <br> Analisis!</span>
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
                        {!! $dataAnalisis->links() !!}
                    </div>
                </div>

            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->

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
                                        <div class="badge bg-danger">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        </div>
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

    <div class="row mt-2 mb-2">
        <div class="col-md-6 text-start">
            <button type="button" wire:click.prevent="toggleTab('kriteriaContent')" class="btn btn-dark"
                wire:loading.attr="disabled" wire:target="toggleTab('kriteriaContent')">
                Sebelumnya
                <span wire:loading class="ms-2" wire:target="toggleTab('kriteriaContent')">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                </span>
            </button>
        </div>
        <div class="col-md-6 text-end">
            <button type="button" wire:click.prevent="toggleTab('evaluasiContent')" class="btn btn-dark"
                wire:loading.attr="disabled" wire:target="toggleTab('evaluasiContent')">
                Selanjutnya
                <span wire:loading class="ms-2" wire:target="toggleTab('evaluasiContent')">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                </span>
            </button>
        </div>
    </div>
</div><!-- end row -->




@if ($isOpenConfirmAnalisis)
    <div class="modal" tabindex="-1" role="dialog" style="display: block;" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Menunggu Konfirmasi</h5>
                    <button type="button" class="btn-close" aria-label="Close"
                        wire:click="closeXModalConfirmAnalisis"></button>
                </div>
                <div class="modal-body">
                    Data tidak bisa diubah apabila Anda mengunci Analisis Risiko. Apakah Anda yakin ingin mengunci
                    Analisis Risiko
                    ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click='closeModalConfirmAnalisis'
                        wire:loading.attr="disabled" wire:target="closeModalConfirmAnalisis">
                        Tutup
                        <span wire:loading class="ms-2" wire:target="closeModalConfirmAnalisis">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        </span>
                    </button>
                    <button type="button" class="btn btn-primary" wire:click="lockAnalisisRisiko"
                        wire:loading.attr="disabled" wire:target="lockAnalisisRisiko">
                        Kunci Analisis Risiko
                        <span wire:loading class="ms-2" wire:target="lockAnalisisRisiko">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        </span>
                    </button>
                </div>
            </div>
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <div class="modal-backdrop fade show"></div>
@endif
