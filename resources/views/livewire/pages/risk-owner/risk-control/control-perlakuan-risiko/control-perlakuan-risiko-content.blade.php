<div class="row">
    <div class="col-12">
        <div class="card">

            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="section-description-actions">
                    <label for="" class="form-label">Show Data</label>
                    <select wire:model.live="perPage" class="form-control" id="">
                        <option selected value="5">--</option>
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
                <div class="section-description-actions">
                    <button type="button" wire:click.prevent="createControlRisk" class="btn btn-primary"
                        wire:loading.attr="disabled" wire:target="createControlRisk"><i class="ri-add-line"></i>
                        Create
                        Control Risiko
                        <span wire:loading class="ms-2" wire:target="createControlRisk">
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
                                <th wire:click.live="doSort('risk_riskDesc')" style="cursor: pointer; width:300px;">
                                    Efektifitas Kontrol
                                </th>
                                <th style="cursor: pointer; width:100px;">
                                    Dampak
                                </th>
                                <th style="cursor: pointer; width:100px;">
                                    Kemungkinan
                                </th>
                                <th style="cursor: pointer; width:100px;">
                                    Deteksi
                                </th>
                                <th style="cursor: pointer; width:100px;">
                                    RPN
                                </th>
                                <th style="cursor: pointer; width:300px;">
                                    Perlakuan Risiko
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
                                                ];
                                            }
                                        }
                                    @endphp

                                    {{-- Example of how to access the data --}}
                                    @foreach ($data as $index => $item)
                                        <tr>
                                            <td>{{ date('d-m-Y', strtotime($item['tglControl'])) }}</td>
                                            <td>
                                                <button type="button"
                                                    class="btn btn-sm d-flex text-center align-items-center">
                                                    @if (implode(', ', $item['efektifitasKontrol']) == 3 || $item['efektifitasKontrol'] <= 3)
                                                        <!-- Display Efektif -->
                                                        Efektif
                                                    @elseif (implode(', ', $item['efektifitasKontrol']) >= 4 && implode(', ', $item['efektifitasKontrol']) <= 7)
                                                        <!-- Display Sebagian Efektif -->
                                                        Sebagian Efektif
                                                    @elseif (implode(', ', $item['efektifitasKontrol']) >= 8 && implode(', ', $item['efektifitasKontrol']) <= 9)
                                                        <!-- Display Kurang Efektif -->
                                                        Kurang Efektif
                                                    @elseif (implode(', ', $item['efektifitasKontrol']) >= 10)
                                                        <!-- Display Tidak Efektif -->
                                                        Tidak Efektif
                                                    @else
                                                        <!-- Display message if no conditions are met -->
                                                        Data tidak tersedia
                                                    @endif
                                                </button>
                                            </td>
                                            <td>{{ $item['dampak'] }}</td>
                                            <td>{{ $item['kemungkinan'] }}</td>
                                            <td>{{ $item['deteksi'] }}</td>
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
                                            <td>
                                                <button class="btn btn-light">
                                                    {{ ucwords($item['perlakuanRisiko']) }}
                                                </button>

                                            </td>
                                            <td>
                                                <!-- Your action buttons -->
                                                <div class="btn-group gap-2" role="group">
                                                    <button type="button"
                                                        wire:click.prevent="showControlRisk({{ $risk->risk_id }}, {{ $item['controlRisk_id'] }})"
                                                        wire:loading.attr="disabled"
                                                        wire:target="showControlRisk({{ $risk->risk_id }}, {{ $item['controlRisk_id'] }})"
                                                        class="btn btn-primary btn-sm d-flex text-center align-items-center">
                                                        <i class="ri-eye-fill" wire:loading.remove
                                                            wire:target='showControlRisk({{ $risk->risk_id }}, {{ $item['controlRisk_id'] }})'></i>
                                                        <span wire:loading
                                                            wire:target="showControlRisk({{ $risk->risk_id }}, {{ $item['controlRisk_id'] }})">
                                                            <span class="spinner-border spinner-border-sm"
                                                                role="status" aria-hidden="true"></span>
                                                        </span>
                                                    </button>
                                                    @if (!$item['controlRisk_lockStatus'])
                                                        <button type="button"
                                                            wire:click.prevent="editControlRisk({{ $risk->risk_id }}, {{ $item['controlRisk_id'] }})"
                                                            wire:loading.attr="disabled"
                                                            wire:target="editControlRisk({{ $risk->risk_id }}, {{ $item['controlRisk_id'] }})"
                                                            class="btn btn-warning btn-sm d-flex text-center align-items-center">
                                                            <i class="ri-pencil-fill" wire:loading.remove
                                                                wire:target='editControlRisk({{ $risk->risk_id }}, {{ $item['controlRisk_id'] }})'></i>
                                                            <span wire:loading
                                                                wire:target="editControlRisk({{ $risk->risk_id }}, {{ $item['controlRisk_id'] }})">
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
                                                        @if ($item['controlRisk_lockStatus'])
                                                            <span
                                                                class="badge badge-outline-danger rounded-pill mt-2">Locked!</span>
                                                        @else
                                                            <button type="button"
                                                                wire:click.prevent="openModalConfirmControlRisk({{ $risk->risk_id }}, {{ $item['controlRisk_id'] }})"
                                                                wire:loading.attr="disabled"
                                                                wire:target="openModalConfirm({{ $risk->risk_id }}, {{ $item['controlRisk_id'] }})"
                                                                class="btn btn-danger btn-sm d-flex text-center align-items-center">
                                                                <i class="ri-lock-fill" wire:loading.remove
                                                                    wire:target='openModalConfirmControlRisk({{ $risk->risk_id }}, {{ $item['controlRisk_id'] }})'>
                                                                </i>
                                                                <span wire:loading
                                                                    wire:target="openModalConfirmControlRisk({{ $risk->risk_id }}, {{ $item['controlRisk_id'] }})">
                                                                    <span class="spinner-border spinner-border-sm"
                                                                        role="status" aria-hidden="true"></span>
                                                                </span>
                                                            </button>
                                                        @endif
                                                    </div>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
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
                        {!! $controlRisks->links() !!}
                    </div>
                </div>

            </div> <!-- end card body-->
        </div> <!-- end card -->
        <div class="row mt-2 mb-2">
            <div class="col-md-6 text-start">
                {{-- <button type="button" wire:click.prevent="toggleTab('identifikasiContent')" class="btn btn-dark"
                    wire:loading.attr="disabled" wire:target="toggleTab('identifikasiContent')">
                    Sebelumnya
                    <span wire:loading class="ms-2" wire:target="toggleTab('identifikasiContent')">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    </span>
                </button> --}}
            </div>
            <div class="col-md-6 text-end">
                <button type="button" wire:click.prevent="toggleTab('evaluasiKontrolRisikoContent')"
                    class="btn btn-dark" wire:loading.attr="disabled"
                    wire:target="toggleTab('evaluasiKontrolRisikoContent')">
                    Selanjutnya
                    <span wire:loading class="ms-2" wire:target="toggleTab('evaluasiKontrolRisikoContent')">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    </span>
                </button>
            </div>
        </div>
    </div><!-- end col-->
</div><!-- end row -->


@if ($isOpenConfirmControlRisk)
    <div class="modal" tabindex="-1" role="dialog" style="display: block;" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Menunggu Konfirmasi</h5>
                    <button type="button" class="btn-close" aria-label="Close"
                        wire:click="closeXModalConfirmControlRisk"></button>
                </div>
                <div class="modal-body">
                    Data tidak bisa diubah apabila Anda mengunci Control Risiko. Apakah Anda yakin ingin mengunci
                    Control Risiko
                    ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click='closeModalConfirmControlRisk'
                        wire:loading.attr="disabled" wire:target="closeModalConfirmControlRisk">
                        Tutup
                        <span wire:loading class="ms-2" wire:target="closeModalConfirmControlRisk">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        </span>
                    </button>
                    <button type="button" class="btn btn-primary" wire:click="lockControlRisk"
                        wire:loading.attr="disabled" wire:target="lockControlRisk">
                        Kunci Control Risiko
                        <span wire:loading class="ms-2" wire:target="lockControlRisk">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        </span>
                    </button>
                </div>
            </div>
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <div class="modal-backdrop fade show"></div>
@endif
