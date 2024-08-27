@include('livewire.pages.risk-owner.risk-control.pemantauan-tinjauan.pemantauan-tinjauan-modal')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h4>Pemantauan dan Tinjauan</h4>
                    <p>Pemantauan dan Tinjauan untuk kontrol risiko.</p>
                </div>
                <div>
                    <button type="button" wire:click.prevent="createKomunikasi" class="btn btn-dark"
                        wire:loading.attr="disabled" wire:target="createKomunikasi">
                        <i class="ri-printer-line"></i> Cetak Pemantauan dan Tinjauan
                        <span wire:loading class="ms-2" wire:target="createKomunikasi">
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
                                <th>
                                    Tindak Lanjut
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no = 1;
                            @endphp
                            @if ($controlRisks && count($controlRisks) > 0)
                                @foreach ($controlRisks as $index => $risk)
                                    @php
                                        $data = [];

                                        foreach ($controlRisks as $risk) {
                                            foreach ($risk->controlRisk as $control) {
                                                $data[] = [
                                                    'controlRisk_id' => $control->controlRisk_id,
                                                    'controlRisk_lockStatus' => $control->controlRisk_lockStatus,
                                                    'tglControl' => $control->created_at, // Assuming created_at is the Tanggal Control
                                                    'efektifitasKontrol' => $risk->efektifitasKontrol
                                                        ->pluck('efektifitasKontrol_totalEfektifitas')
                                                        ->toArray(),
                                                    'dampak' => $control->dampak->dampak_scale ?? 'No Dampak',
                                                    'kemungkinan' =>
                                                        $control->kemungkinan->kemungkinan_scale ?? 'No Kemungkinan',
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
                                                ];
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
                                                    @if ($item['pemantauanKajian'])
                                                        <button type="button"
                                                            wire:click.prevent="showPemantauanTinjauan({{ $item['controlRisk_id'] }})"
                                                            wire:loading.attr="disabled"
                                                            wire:target="showPemantauanTinjauan({{ $item['controlRisk_id'] }})"
                                                            class="btn btn-primary btn-sm d-flex text-center align-items-center">
                                                            <i class="ri-eye-fill" wire:loading.remove
                                                                wire:target='showPemantauanTinjauan({{ $item['controlRisk_id'] }})'></i>
                                                            <span wire:loading
                                                                wire:target="showPemantauanTinjauan({{ $item['controlRisk_id'] }})">
                                                                <span class="spinner-border spinner-border-sm"
                                                                    role="status" aria-hidden="true"></span>
                                                            </span>
                                                        </button>
                                                        @if ($item['pemantauanKajian_lockStatus'])
                                                            <span
                                                                class="badge badge-outline-danger rounded-pill mt-2">Locked!</span>
                                                        @else
                                                            <button type="button"
                                                                wire:click.prevent="editPemantauanTinjauan({{ $item['controlRisk_id'] }})"
                                                                wire:loading.attr="disabled"
                                                                wire:target="editPemantauanTinjauan({{ $item['controlRisk_id'] }})"
                                                                class="btn btn-warning btn-sm d-flex text-center align-items-center">
                                                                <i class="ri-pencil-fill" wire:loading.remove
                                                                    wire:target='editPemantauanTinjauan({{ $item['controlRisk_id'] }})'></i>
                                                                <span wire:loading
                                                                    wire:target="editPemantauanTinjauan({{ $item['controlRisk_id'] }})">
                                                                    <span class="spinner-border spinner-border-sm"
                                                                        role="status" aria-hidden="true"></span>
                                                                </span>
                                                            </button>
                                                        @endif
                                                    @else
                                                        <button type="button"
                                                            wire:click.prevent="createPemantauanTinjauan({{ $item['controlRisk_id'] }})"
                                                            wire:loading.attr="disabled"
                                                            wire:target="createPemantauanTinjauan({{ $item['controlRisk_id'] }})"
                                                            class="btn btn-primary btn-sm d-flex text-center align-items-center">
                                                            Tambah Pemantauan & Tinjauan
                                                            <span wire:loading
                                                                wire:target="createPemantauanTinjauan({{ $item['controlRisk_id'] }})">
                                                                <span class="spinner-border spinner-border-sm"
                                                                    role="status" aria-hidden="true"></span>
                                                            </span>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <!-- Your action buttons -->
                                                <div class="btn-group gap-2" role="group">
                                                    @if ($item['pemantauanKajian'])
                                                        @if ($item['pemantauanKajian_lockStatus'])
                                                            <span
                                                                class="badge badge-outline-danger rounded-pill mt-2">Locked!</span>
                                                        @else
                                                            @if ($this->role === 'risk owner')
                                                                <button type="button"
                                                                    wire:click.prevent="openModalConfirmPemantauanTinjauan({{ $item['controlRisk_id'] }})"
                                                                    wire:loading.attr="disabled"
                                                                    wire:target="openModalConfirm({{ $item['controlRisk_id'] }})"
                                                                    class="btn btn-danger btn-sm d-flex text-center align-items-center">
                                                                    <i class="ri-lock-fill" wire:loading.remove
                                                                        wire:target='openModalConfirmPemantauanTinjauan({{ $item['controlRisk_id'] }})'>
                                                                    </i>
                                                                    <span wire:loading
                                                                        wire:target="openModalConfirmPemantauanTinjauan({{ $item['controlRisk_id'] }})">
                                                                        <span class="spinner-border spinner-border-sm"
                                                                            role="status" aria-hidden="true"></span>
                                                                    </span>
                                                                </button>
                                                            @else
                                                                <span
                                                                    class="badge badge-outline-danger rounded-pill mt-2">Bukan
                                                                    Hak Akses!</span>
                                                            @endif
                                                        @endif
                                                    @else
                                                        <span
                                                            class="badge badge-outline-danger rounded-pill mt-2">Selesaikan!</span>
                                                    @endif
                                                </div>
                                            </td>
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
                        {!! $evaluasis->links() !!}
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


@if ($isOpenConfirmPemantauanTinjauan)
    <div class="modal" tabindex="-1" role="dialog" style="display: block;" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Menunggu Konfirmasi</h5>
                    <button type="button" class="btn-close" aria-label="Close"
                        wire:click="closeXModalConfirmPemantauanTinjauan"></button>
                </div>
                <div class="modal-body">
                    Data tidak bisa diubah apabila Anda mengunci Pemantauan dan Tinjauan. Apakah Anda yakin ingin
                    mengunci Pemantauan dan Tinjauan
                    ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click='closeModalConfirmPemantauanTinjauan'
                        wire:loading.attr="disabled" wire:target="closeModalConfirmPemantauanTinjauan">
                        Tutup
                        <span wire:loading class="ms-2" wire:target="closeModalConfirmPemantauanTinjauan">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        </span>
                    </button>
                    <button type="button" class="btn btn-primary" wire:click="lockPemantauanTinjauan"
                        wire:loading.attr="disabled" wire:target="lockPemantauanTinjauan">
                        Kunci Pemantauan dan Tinjauan
                        <span wire:loading class="ms-2" wire:target="lockPemantauanTinjauan">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        </span>
                    </button>
                </div>
            </div>
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <div class="modal-backdrop fade show"></div>
@endif
