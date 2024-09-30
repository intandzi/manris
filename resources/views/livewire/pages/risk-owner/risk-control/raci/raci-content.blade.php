@include('livewire.pages.risk-owner.risk-control.raci.raci-modal')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <label for="" class="form-label">Show Data</label>
                    <select wire:model.live="perPage" class="form-control" id="">
                        <option selected value="5">--</option>
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
                <div>
                    <button type="button" wire:click.prevent="openCetakRaci({{ $kpi_id }})" class="btn btn-dark"
                        wire:loading.attr="disabled" wire:target="openCetakRaci({{ $kpi_id }})">
                        <i class="ri-printer-line"></i> Cetak RACI
                        <span wire:loading class="ms-2" wire:target="openCetakRaci({{ $kpi_id }})">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        </span>
                    </button>
                </div>
            </div>
            <div class="card-body">
                {{-- <div class="row">
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
                </div> --}}

                <div class="table-responsive mt-2">
                    <table class="table table-centered mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th wire:click.live="doSortRisk('risk_kode')" style="cursor: pointer; width:200px;">
                                    Tanggal Control
                                    <x-sorting-table :orderAsc="$orderAscControlRisk" :orderBy="$orderByControlRisk" :columnName="'risk_kode'" />
                                </th>
                                <th style="cursor: pointer; width:300px;">
                                    Perlakuan Risiko
                                </th>
                                <th style="cursor: pointer; width:300px;">
                                    RPN
                                </th>
                                {{-- <th style="cursor: pointer; width:500px;">
                                    Perlakuan Risiko
                                </th> --}}
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
                            @if ($racis && count($racis) > 0)
                                @foreach ($racis as $index => $risk)
                                    @php
                                        $data = [];

                                        foreach ($racis as $risk) {
                                            foreach ($risk->controlRisk as $control) {
                                                // CHECK APAKAH CONTROL PEMANTAUAN KAJIAN SUDAH TERKUNCI
                                                if ($control->perlakuanRisiko->first()->pemantauanKajian_lockStatus) {
                                                    $data[] = [
                                                        'controlRisk_id' => $control->controlRisk_id,
                                                        'controlRisk_lockStatus' => $control->controlRisk_lockStatus,
                                                        'tglControl' => $control->created_at, // Assuming created_at is the Tanggal Control
                                                        'efektifitasKontrol' => $risk->efektifitasKontrol
                                                            ->pluck('efektifitasKontrol_totalEfektifitas')
                                                            ->toArray(),
                                                        'dampak' => $control->dampak->dampak_scale ?? 'No Dampak',
                                                        'kemungkinan' =>
                                                            $control->kemungkinan->kemungkinan_scale ??
                                                            'No Kemungkinan',
                                                        'deteksi' =>
                                                            $control->deteksiKegagalan->deteksiKegagalan_scale ??
                                                            'No Deteksi',
                                                        'rpn' => $control->controlRisk_RPN,
                                                        'perlakuanRisiko' =>
                                                            $control->perlakuanRisiko->first()->jenisPerlakuan
                                                                ->jenisPerlakuan_desc ?? 'No Perlakuan Risiko',
                                                        'rencanaPerlakuan' =>
                                                            $control->perlakuanRisiko->first()->rencanaPerlakuan ??
                                                            'No Rencana Perlakuan Risiko',
                                                        'pemantauanKajian' => $control->perlakuanRisiko
                                                            ->first()
                                                            ->pemantauanTinjauan->isNotEmpty()
                                                            ? true
                                                            : false,
                                                        'pemantauanKajian_lockStatus' => $control->perlakuanRisiko->first()
                                                            ->pemantauanKajian_lockStatus,
                                                        'controlRisk_raci' => $control->raci->isNotEmpty(),
                                                        'raci_lockStatus' =>
                                                            $control->raci->first()->raci_lockStatus ?? false,
                                                    ];
                                                }
                                            }
                                        }

                                        // Sort the array by 'rpn' in descending order
                                        usort($data, function ($a, $b) {
                                            return $b['rpn'] <=> $a['rpn'];
                                        });
                                    @endphp

                                    {{-- Example of how to access the data --}}
                                    @forelse ($data as $index => $item)
                                        <tr>
                                            <td>{{ date('d-m-Y', strtotime($item['tglControl'])) }}</td>
                                            <td>
                                                <button class="btn btn-light">
                                                    {{ ucwords($item['perlakuanRisiko']) }}
                                                </button>

                                            </td>
                                            <td>
                                                @php
                                                    $controlRiskRPN = $item['rpn'];
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
                                                    {{ $item['rpn'] }}
                                                </button>
                                            </td>
                                            {{-- <td style="word-break: break-word;">
                                                @foreach ($item['rencanaPerlakuan'] as $index => $data)
                                                <ul>
                                                    <li>{{ $data->rencanaPerlakuan_desc }}</li>
                                                </ul>
                                                @endforeach
                                            </td> --}}
                                            <td>
                                                <!-- Your action buttons -->
                                                <div class="btn-group gap-2" role="group">
                                                    @if ($item['controlRisk_raci'])
                                                        <button type="button"
                                                            wire:click.prevent="showRACI({{ $item['controlRisk_id'] }})"
                                                            wire:loading.attr="disabled"
                                                            wire:target="showRACI({{ $item['controlRisk_id'] }})"
                                                            class="btn btn-primary btn-sm d-flex text-center align-items-center">
                                                            <i class="ri-eye-fill" wire:loading.remove
                                                                wire:target='showRACI({{ $item['controlRisk_id'] }})'></i>
                                                            <span wire:loading
                                                                wire:target="showRACI({{ $item['controlRisk_id'] }})">
                                                                <span class="spinner-border spinner-border-sm"
                                                                    role="status" aria-hidden="true"></span>
                                                            </span>
                                                        </button>
                                                        @if (!$item['raci_lockStatus'])
                                                            <button type="button"
                                                                wire:click.prevent="editRACI({{ $item['controlRisk_id'] }})"
                                                                wire:loading.attr="disabled"
                                                                wire:target="editRACI({{ $item['controlRisk_id'] }})"
                                                                class="btn btn-warning btn-sm d-flex text-center align-items-center">
                                                                <i class="ri-pencil-fill" wire:loading.remove
                                                                    wire:target='editRACI({{ $item['controlRisk_id'] }})'></i>
                                                                <span wire:loading
                                                                    wire:target="editRACI({{ $item['controlRisk_id'] }})">
                                                                    <span class="spinner-border spinner-border-sm"
                                                                        role="status" aria-hidden="true"></span>
                                                                </span>
                                                            </button>
                                                        @endif
                                                    @else
                                                        <button type="button"
                                                            wire:click.prevent="createRACI({{ $item['controlRisk_id'] }})"
                                                            wire:loading.attr="disabled"
                                                            wire:target="createRACI({{ $item['controlRisk_id'] }})"
                                                            class="btn btn-primary btn-sm d-flex text-center align-items-center">
                                                            Tambah RACI
                                                            <span wire:loading class="ms-2"
                                                                wire:target="createRACI({{ $item['controlRisk_id'] }})">
                                                                <span class="spinner-border spinner-border-sm"
                                                                    role="status" aria-hidden="true"></span>
                                                            </span>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                            @if ($this->role === 'risk owner')
                                                <td>
                                                    <!-- Your action buttons -->
                                                    <div class="btn-group gap-2" role="group">
                                                        @if ($item['controlRisk_raci'])
                                                            @if ($item['raci_lockStatus'])
                                                                <span
                                                                    class="badge badge-outline-danger rounded-pill mt-2">Locked!</span>
                                                            @else
                                                                <button type="button"
                                                                    wire:click.prevent="openModalConfirmRACI({{ $item['controlRisk_id'] }})"
                                                                    wire:loading.attr="disabled"
                                                                    wire:target="openModalConfirm({{ $item['controlRisk_id'] }})"
                                                                    class="btn btn-danger btn-sm d-flex text-center align-items-center">
                                                                    <i class="ri-lock-fill" wire:loading.remove
                                                                        wire:target='openModalConfirmRACI({{ $item['controlRisk_id'] }})'>
                                                                    </i>
                                                                    <span wire:loading
                                                                        wire:target="openModalConfirmRACI({{ $item['controlRisk_id'] }})">
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
                                    @empty
                                        <div class="alert alert-danger mt-2 mb-2">
                                            No data available.
                                        </div>
                                    @endforelse
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
                        {!! $racis->links() !!}
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
<div class="row mt-2 mb-2">
    <div class="col-md-6 text-start">
        <button type="button" wire:click.prevent="toggleTab('pemantauanTinjauanContent')" class="btn btn-dark"
            wire:loading.attr="disabled" wire:target="toggleTab('pemantauanTinjauanContent')">
            Sebelumnya
            <span wire:loading class="ms-2" wire:target="toggleTab('pemantauanTinjauanContent')">
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            </span>
        </button>
    </div>
    <div class="col-md-6 text-end">
        <button type="button" wire:click.prevent="toggleTab('komunikasiKonsultasiContent')" class="btn btn-dark"
            wire:loading.attr="disabled" wire:target="toggleTab('komunikasiKonsultasiContent')">
            Selanjutnya
            <span wire:loading class="ms-2" wire:target="toggleTab('komunikasiKonsultasiContent')">
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            </span>
        </button>
    </div>
</div>


@if ($isOpenConfirmRACI)
    <div class="modal" tabindex="-1" role="dialog" style="display: block;" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Menunggu Konfirmasi</h5>
                    <button type="button" class="btn-close" aria-label="Close"
                        wire:click="closeXModalConfirmRACI"></button>
                </div>
                <div class="modal-body">
                    Data tidak bisa diubah apabila Anda mengunci RACI. Apakah Anda yakin ingin
                    mengunci RACI
                    ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click='closeModalConfirmRACI'
                        wire:loading.attr="disabled" wire:target="closeModalConfirmRACI">
                        Tutup
                        <span wire:loading class="ms-2" wire:target="closeModalConfirmRACI">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        </span>
                    </button>
                    <button type="button" class="btn btn-primary" wire:click="lockRACI"
                        wire:loading.attr="disabled" wire:target="lockRACI">
                        Kunci RACI
                        <span wire:loading class="ms-2" wire:target="lockRACI">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        </span>
                    </button>
                </div>
            </div>
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <div class="modal-backdrop fade show"></div>
@endif


{{-- CETAK RACI --}}
@if ($isOpenCetakRaci)
    <div class="modal" tabindex="-1" role="dialog" style="display: block;" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Menunggu Konfirmasi</h5>
                    <button type="button" class="btn-close" aria-label="Close"
                        wire:click="closeXCetakRaci"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin mencetak RACI? (Hanya data yang sudah terkunci akan dicetak.)
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click='closeCetakRaci'
                        wire:loading.attr="disabled" wire:target="closeCetakRaci">
                        Tutup
                        <span wire:loading class="ms-2" wire:target="closeCetakRaci">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        </span>
                    </button>
                    <button type="button" class="btn btn-primary" wire:click="printRaci"
                        wire:loading.attr="disabled" wire:target="printRaci">
                        Cetak RACI
                        <span wire:loading class="ms-2" wire:target="printRaci">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        </span>
                    </button>
                </div>
            </div>
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <div class="modal-backdrop fade show"></div>
@endif
