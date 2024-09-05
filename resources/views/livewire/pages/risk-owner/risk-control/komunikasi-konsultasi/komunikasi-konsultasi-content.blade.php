@include('livewire.pages.risk-owner.risk-control.komunikasi-konsultasi.komunikasi-modal')
@include('livewire.pages.risk-owner.risk-control.komunikasi-konsultasi.konsultasi-modal')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h4>Komunikasi</h4>
                    <p>Komunikasi ke pemangku kepentingan terkait.</p>
                </div>
                <div>
                    <button type="button" wire:click.prevent="createKomunikasi" class="btn btn-primary"
                        wire:loading.attr="disabled" wire:target="createKomunikasi">
                        <i class="ri-add-line"></i> Create Komunikasi
                        <span wire:loading class="ms-2" wire:target="createKomunikasi">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        </span>
                    </button>
                    <button type="button" wire:click.prevent="openCetakKomunikasi({{ $kpi_id }})" class="btn btn-dark"
                        wire:loading.attr="disabled" wire:target="openCetakKomunikasi({{ $kpi_id }})">
                        <i class="ri-printer-line"></i> Cetak Komunikasi
                        <span wire:loading class="ms-2" wire:target="openCetakKomunikasi({{ $kpi_id }})">
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
                            <input wire:model.live.debounce.100ms="searchControlRisk" type="text"
                                class="form-control" placeholder="Search...">
                            @if ($searchControlRisk)
                                <button type="button" wire:click.prevent="clearSearchControlRisk"
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
                                <th wire:click.live="doSortRisk('risk_kode')" style="cursor: pointer; width:400px;">
                                    Pemangku Kepentingan
                                    <x-sorting-table :orderAsc="$orderAscControlRisk" :orderBy="$orderByControlRisk" :columnName="'risk_kode'" />
                                </th>
                                <th style="cursor: pointer; width:400px;">
                                    Perantara
                                </th>
                                <th style="cursor: pointer; width:400px;">
                                    Disiapkan Oleh
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
                            @if ($komunikasis && count($komunikasis) > 0)
                                @foreach ($komunikasis as $item)
                                    <tr>
                                        <td style="word-break: break-word;">
                                            @forelse ($item->komunikasiStakeholder as $data)
                                                @if ($data->komunikasiStakeholder_ket == 'stakeholder')
                                                    {{ $data->stakeholder->stakeholder_jabatan }}
                                                    ({{ $data->stakeholder->stakeholder_singkatan }})
                                                    ,
                                                @endif
                                            @empty
                                                -
                                            @endforelse
                                        </td>
                                        <td style="word-break: break-word;">
                                            @forelse ($item->komunikasiStakeholder as $data)
                                                @if ($data->komunikasiStakeholder_ket == 'perantara')
                                                    {{ $data->stakeholder->stakeholder_jabatan }}
                                                    ({{ $data->stakeholder->stakeholder_singkatan }})
                                                    ,
                                                @endif
                                            @empty
                                                -
                                            @endforelse
                                        </td>
                                        <td style="word-break: break-word;">
                                            @forelse ($item->komunikasiStakeholder as $data)
                                                @if ($data->komunikasiStakeholder_ket == 'fasil')
                                                    {{ $data->stakeholder->stakeholder_jabatan }}
                                                    ({{ $data->stakeholder->stakeholder_singkatan }})
                                                    ,
                                                @endif
                                            @empty
                                                -
                                            @endforelse
                                        </td>
                                        <td>
                                            <div class="btn-group gap-2" role="group">
                                                <button type="button"
                                                    wire:click.prevent="showKomunikasi({{ $item->komunikasi_id }})"
                                                    wire:loading.attr="disabled"
                                                    wire:target="showKomunikasi({{ $item->komunikasi_id }})"
                                                    class="btn btn-primary btn-sm d-flex text-center align-items-center">
                                                    <i class="ri-eye-fill" wire:loading.remove
                                                        wire:target='showKomunikasi({{ $item->komunikasi_id }})'></i>
                                                    <span wire:loading
                                                        wire:target="showKomunikasi({{ $item->komunikasi_id }})">
                                                        <span class="spinner-border spinner-border-sm" role="status"
                                                            aria-hidden="true"></span>
                                                    </span>
                                                </button>
                                                @if (!$item->komunikasi_lockStatus)
                                                    <button type="button"
                                                        wire:click.prevent="editKomunikasi({{ $item->komunikasi_id }})"
                                                        wire:loading.attr="disabled"
                                                        wire:target="editKomunikasi({{ $item->komunikasi_id }})"
                                                        class="btn btn-warning btn-sm d-flex text-center align-items-center">
                                                        <i class="ri-pencil-fill" wire:loading.remove
                                                            wire:target='editKomunikasi({{ $item->komunikasi_id }})'></i>
                                                        <span wire:loading
                                                            wire:target="editKomunikasi({{ $item->komunikasi_id }})">
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
                                                    @if ($item->komunikasiStakeholder->isNotEmpty())
                                                        @if ($item->komunikasi_lockStatus)
                                                            <span
                                                                class="badge badge-outline-danger rounded-pill mt-2">Locked!</span>
                                                        @else
                                                            <button type="button"
                                                                wire:click.prevent="openModalConfirmKomunikasi({{ $item->komunikasi_id }})"
                                                                wire:loading.attr="disabled"
                                                                wire:target="openModalConfirmKomunikasi({{ $item->komunikasi_id }})"
                                                                class="btn btn-danger btn-sm d-flex text-center align-items-center">
                                                                <i class="ri-lock-fill" wire:loading.remove
                                                                    wire:target='openModalConfirmKomunikasi({{ $item->komunikasi_id }})'></i>
                                                                <span wire:loading
                                                                    wire:target="openModalConfirmKomunikasi({{ $item->komunikasi_id }})">
                                                                    <span class="spinner-border spinner-border-sm"
                                                                        role="status" aria-hidden="true"></span>
                                                                </span>
                                                            </button>
                                                        @endif
                                                    @else
                                                        <span
                                                            class="badge badge-outline-danger rounded-pill mt-2">Selesaikan!</span>
                                                    @endif
                                                </div>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
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
                        {!! $komunikasis->links() !!}
                    </div>
                </div>

            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h4>Konsultasi</h4>
                    <p>Konsultasi ke pemangku kepentingan terkait.</p>
                </div>
                <div>
                    <button type="button" wire:click.prevent="createKonsultasi" class="btn btn-primary"
                        wire:loading.attr="disabled" wire:target="createKonsultasi">
                        <i class="ri-add-line"></i> Create Konsultasi
                        <span wire:loading class="ms-2" wire:target="createKonsultasi">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        </span>
                    </button>
                    <button type="button" wire:click.prevent="openCetakKonsultasi({{ $kpi_id }})" class="btn btn-dark"
                        wire:loading.attr="disabled" wire:target="openCetakKonsultasi({{ $kpi_id }})">
                        <i class="ri-printer-line"></i> Cetak Konsultasi
                        <span wire:loading class="ms-2" wire:target="openCetakKonsultasi({{ $kpi_id }})">
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
                            <input wire:model.live.debounce.100ms="searchControlRisk" type="text"
                                class="form-control" placeholder="Search...">
                            @if ($searchControlRisk)
                                <button type="button" wire:click.prevent="clearSearchControlRisk"
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
                                <th wire:click.live="doSortRisk('risk_kode')" style="cursor: pointer; width:350px;">
                                    Pemangku Kepentingan
                                    <x-sorting-table :orderAsc="$orderAscControlRisk" :orderBy="$orderByControlRisk" :columnName="'risk_kode'" />
                                </th>
                                <th style="cursor: pointer; width:350px;">
                                    Fasilitator
                                </th>
                                <th style="cursor: pointer; width:400px;">
                                    Tujuan
                                </th>
                                <th style="cursor: pointer; width:400px;">
                                    Metode
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
                            @if ($konsultasis && count($konsultasis) > 0)
                                @foreach ($konsultasis as $item)
                                    <tr>
                                        <td style="word-break: break-word;">
                                            @forelse ($item->konsultasiStakeholder as $data)
                                                @if ($data->konsultasiStakeholder_ket == 'stakeholder')
                                                    {{ $data->stakeholder->stakeholder_jabatan }}
                                                    ({{ $data->stakeholder->stakeholder_singkatan }})
                                                    ,
                                                @endif
                                            @empty
                                                -
                                            @endforelse
                                        </td>
                                        <td style="word-break: break-word;">
                                            @forelse ($item->konsultasiStakeholder as $data)
                                                @if ($data->konsultasiStakeholder_ket == 'fasil')
                                                    {{ $data->stakeholder->stakeholder_jabatan }}
                                                    ({{ $data->stakeholder->stakeholder_singkatan }})
                                                    ,
                                                @endif
                                            @empty
                                                -
                                            @endforelse
                                        </td>
                                        <td style="word-break: break-word;">
                                            {{ $item->konsultasi_tujuan }}
                                        </td>
                                        <td style="word-break: break-word;">
                                            {{ $item->konsultasi_metode }}
                                        </td>
                                        <td>
                                            <div class="btn-group gap-2" role="group">
                                                <button type="button"
                                                    wire:click.prevent="showKonsultasi({{ $item->konsultasi_id }})"
                                                    wire:loading.attr="disabled"
                                                    wire:target="showKonsultasi({{ $item->konsultasi_id }})"
                                                    class="btn btn-primary btn-sm d-flex text-center align-items-center">
                                                    <i class="ri-eye-fill" wire:loading.remove
                                                        wire:target='showKonsultasi({{ $item->konsultasi_id }})'></i>
                                                    <span wire:loading
                                                        wire:target="showKonsultasi({{ $item->konsultasi_id }})">
                                                        <span class="spinner-border spinner-border-sm" role="status"
                                                            aria-hidden="true"></span>
                                                    </span>
                                                </button>
                                                @if (!$item->konsultasi_lockStatus)
                                                    <button type="button"
                                                        wire:click.prevent="editKonsultasi({{ $item->konsultasi_id }})"
                                                        wire:loading.attr="disabled"
                                                        wire:target="editKonsultasi({{ $item->konsultasi_id }})"
                                                        class="btn btn-warning btn-sm d-flex text-center align-items-center">
                                                        <i class="ri-pencil-fill" wire:loading.remove
                                                            wire:target='editKonsultasi({{ $item->konsultasi_id }})'></i>
                                                        <span wire:loading
                                                            wire:target="editKonsultasi({{ $item->konsultasi_id }})">
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
                                                    @if ($item->konsultasiStakeholder->isNotEmpty())
                                                        @if ($item->konsultasi_lockStatus)
                                                            <span
                                                                class="badge badge-outline-danger rounded-pill mt-2">Locked!</span>
                                                        @else
                                                            <button type="button"
                                                                wire:click.prevent="openModalConfirmKonsultasi({{ $item->konsultasi_id }})"
                                                                wire:loading.attr="disabled"
                                                                wire:target="openModalConfirmKonsultasi({{ $item->konsultasi_id }})"
                                                                class="btn btn-danger btn-sm d-flex text-center align-items-center">
                                                                <i class="ri-lock-fill" wire:loading.remove
                                                                    wire:target='openModalConfirmKonsultasi({{ $item->konsultasi_id }})'></i>
                                                                <span wire:loading
                                                                    wire:target="openModalConfirmKonsultasi({{ $item->konsultasi_id }})">
                                                                    <span class="spinner-border spinner-border-sm"
                                                                        role="status" aria-hidden="true"></span>
                                                                </span>
                                                            </button>
                                                        @endif
                                                    @else
                                                        <span
                                                            class="badge badge-outline-danger rounded-pill mt-2">Selesaikan!</span>
                                                    @endif
                                                </div>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
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
                        {!! $konsultasis->links() !!}
                    </div>
                </div>

            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div><!-- end row -->


{{-- CONFIRM KOMUNIKASI --}}
@if ($isOpenConfirmKomunikasi)
    <div class="modal" tabindex="-1" role="dialog" style="display: block;" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Menunggu Konfirmasi</h5>
                    <button type="button" class="btn-close" aria-label="Close"
                        wire:click="closeXModalConfirmKomunikasi"></button>
                </div>
                <div class="modal-body">
                    Data tidak bisa diubah apabila Anda mengunci Komunikasi. Apakah Anda yakin ingin
                    mengunci Komunikasi
                    ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click='closeModalConfirmKomunikasi'
                        wire:loading.attr="disabled" wire:target="closeModalConfirmKomunikasi">
                        Tutup
                        <span wire:loading class="ms-2" wire:target="closeModalConfirmKomunikasi">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        </span>
                    </button>
                    <button type="button" class="btn btn-primary" wire:click="lockKomunikasi"
                        wire:loading.attr="disabled" wire:target="lockKomunikasi">
                        Kunci Komunikasi
                        <span wire:loading class="ms-2" wire:target="lockKomunikasi">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        </span>
                    </button>
                </div>
            </div>
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <div class="modal-backdrop fade show"></div>
@endif

{{-- CONFIRM KONSULTASI --}}
@if ($isOpenConfirmKonsultasi)
    <div class="modal" tabindex="-1" role="dialog" style="display: block;" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Menunggu Konfirmasi</h5>
                    <button type="button" class="btn-close" aria-label="Close"
                        wire:click="closeXModalConfirmKonsultasi"></button>
                </div>
                <div class="modal-body">
                    Data tidak bisa diubah apabila Anda mengunci Konsultasi. Apakah Anda yakin ingin
                    mengunci Konsultasi
                    ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click='closeModalConfirmKonsultasi'
                        wire:loading.attr="disabled" wire:target="closeModalConfirmKonsultasi">
                        Tutup
                        <span wire:loading class="ms-2" wire:target="closeModalConfirmKonsultasi">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        </span>
                    </button>
                    <button type="button" class="btn btn-primary" wire:click="lockKonsultasi"
                        wire:loading.attr="disabled" wire:target="lockKonsultasi">
                        Kunci Konsultasi
                        <span wire:loading class="ms-2" wire:target="lockKonsultasi">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        </span>
                    </button>
                </div>
            </div>
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <div class="modal-backdrop fade show"></div>
@endif


{{-- CETAK KOMUNIKASI --}}
@if ($isOpenCetakKomunikasi)
    <div class="modal" tabindex="-1" role="dialog" style="display: block;" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Menunggu Konfirmasi</h5>
                    <button type="button" class="btn-close" aria-label="Close"
                        wire:click="closeXCetakKomunikasi"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin mencetak Komunikasi? (Hanya data yang sudah terkunci akan dicetak.)
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click='closeCetakKomunikasi'
                        wire:loading.attr="disabled" wire:target="closeCetakKomunikasi">
                        Tutup
                        <span wire:loading class="ms-2" wire:target="closeCetakKomunikasi">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        </span>
                    </button>
                    <button type="button" class="btn btn-primary" wire:click="printKomunikasi"
                        wire:loading.attr="disabled" wire:target="printKomunikasi">
                        Cetak Komunikasi
                        <span wire:loading class="ms-2" wire:target="printKomunikasi">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        </span>
                    </button>
                </div>
            </div>
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <div class="modal-backdrop fade show"></div>
@endif


{{-- CETAK KONSULTASI --}}
@if ($isOpenCetakKonsultasi)
    <div class="modal" tabindex="-1" role="dialog" style="display: block;" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Menunggu Konfirmasi</h5>
                    <button type="button" class="btn-close" aria-label="Close"
                        wire:click="closeXCetakKonsultasi"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin mencetak Konsultasi? (Hanya data yang sudah terkunci akan dicetak.)
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click='closeCetakKonsultasi'
                        wire:loading.attr="disabled" wire:target="closeCetakKonsultasi">
                        Tutup
                        <span wire:loading class="ms-2" wire:target="closeCetakKonsultasi">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        </span>
                    </button>
                    <button type="button" class="btn btn-primary" wire:click="printKonsultasi"
                        wire:loading.attr="disabled" wire:target="printKonsultasi">
                        Cetak Konsultasi
                        <span wire:loading class="ms-2" wire:target="printKonsultasi">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        </span>
                    </button>
                </div>
            </div>
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <div class="modal-backdrop fade show"></div>
@endif