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
                                <th wire:click.live="doSort('risk_riskDesc')" style="cursor: pointer; width:200px;">
                                    Efektifitas Control
                                </th>
                                <th style="cursor: pointer; width:100px;">
                                    RPN
                                </th>
                                <th style="cursor: pointer; width:100px;">
                                    Derajat Risiko
                                </th>
                                <th style="cursor: pointer; width:500px;">
                                    Tindak Lanjut yang di Perlukan
                                </th>
                                <th>Aksi</th>
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
                                                // Collect all active tindakLanjut from seleraRisiko
                                                $tindakLanjut = [];

                                                if ($control) {
                                                    $derajatRisiko = $control->derajatRisiko; // Assuming derajatRisiko is a relationship
                                                    if ($derajatRisiko) {
                                                        foreach ($derajatRisiko->seleraRisiko as $selera) {
                                                            if ($selera->seleraRisiko_activeStatus) {
                                                                $tindakLanjut[] = $selera->seleraRisiko_tindakLanjut;
                                                            }
                                                        }
                                                    }
                                                }

                                                // If there are multiple active tindakLanjut, pick the first one or combine them as needed
                                                $activeTindakLanjut = !empty($tindakLanjut)
                                                    ? implode(', ', $tindakLanjut)
                                                    : 'No Active Tindak Lanjut';

                                                if($control->controlRisk_lockStatus){ // only control is lock
                                                    $data[] = [
                                                        'controlRisk_id' => $control->controlRisk_id,
                                                        'controlRisk_lockStatus' => $control->controlRisk_lockStatus,
                                                        'tglControl' => $control->created_at, // Assuming created_at is the Tanggal Control
                                                        'efektifitasKontrol' => $risk->efektifitasKontrol
                                                            ->pluck('efektifitasKontrol_totalEfektifitas')
                                                            ->toArray(),
                                                        'rpn' => $control->controlRisk_RPN,
                                                        'perlakuanRisiko' =>
                                                            optional(
                                                                optional($control->perlakuanRisiko->first())
                                                                    ->jenisPerlakuan,
                                                            )->jenisPerlakuan_desc ?? 'No Perlakuan Risiko',
                                                        'rencanaPerlakuan' =>
                                                            optional($control->perlakuanRisiko->first())
                                                                ->rencanaPerlakuan ?? 'No Rencana Perlakuan Risiko',
                                                        'derajatRisiko' =>
                                                            optional($control->derajatRisiko)->derajatRisiko_desc ??
                                                            'No Derajat Risiko',
                                                        'tindakLanjut' => $activeTindakLanjut,
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
                                                <button type="button"
                                                    class="btn btn-sm d-flex text-center align-items-center">
                                                    @if (array_sum($item['efektifitasKontrol']) == 3)
                                                        <!-- Display Efektif -->
                                                        Efektif
                                                    @elseif (array_sum($item['efektifitasKontrol']) >= 4 && array_sum($item['efektifitasKontrol']) <= 7)
                                                        <!-- Display Sebagian Efektif -->
                                                        Sebagian Efektif
                                                    @elseif (array_sum($item['efektifitasKontrol']) >= 8 && array_sum($item['efektifitasKontrol']) <= 9)
                                                        <!-- Display Kurang Efektif -->
                                                        Kurang Efektif
                                                    @elseif (array_sum($item['efektifitasKontrol']) >= 10)
                                                        <!-- Display Tidak Efektif -->
                                                        Tidak Efektif
                                                    @else
                                                        <!-- Display message if no conditions are met -->
                                                        Data tidak tersedia
                                                    @endif
                                                </button>
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
                                            <td>{{ ucfirst($item['derajatRisiko']) }}</td>
                                            <td>{{ $item['tindakLanjut'] }}</td>
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
                        {!! $controlRisks->links() !!}
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
