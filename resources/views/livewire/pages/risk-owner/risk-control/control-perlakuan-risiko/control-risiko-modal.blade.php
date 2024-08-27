@if ($isOpenControlRisk)
    <div class="modal" tabindex="-1" role="dialog" style="display: block;" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0">Control Risiko Form</h5>
                    <button type="button" class="btn-close" wire:click='closeXModalControlRisk' data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive mb-2">
                                <table>
                                    <tr>
                                        <td style="width: 200px; vertical-align: top;">
                                            <p class="card-text me-4" style="font-weight: bold">Unit Pemilik Risiko
                                            </p>
                                        </td>
                                        <td style="vertical-align: top;">:</td>
                                        <td style="width: calc(100% - 250px); vertical-align: bottom;">
                                            <p class="card-text" style="word-wrap: break-word;">{{ $unit_nama }}
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 200px; vertical-align: top;">
                                            <p class="card-text" style="font-weight: bold">Pemilik Risiko</p>
                                        </td>
                                        <td style="vertical-align: top;">:</td>
                                        <td style="vertical-align: bottom;">
                                            <p class="card-text">{{ ucwords($user_pemilik) }}</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 200px; vertical-align: top;">
                                            <p class="card-text" style="font-weight: bold">Deskripsi Konteks</p>
                                        </td>
                                        <td style="vertical-align: top;">:</td>
                                        <td style="vertical-align: bottom;">
                                            <p class="card-text" style="word-wrap: break-word;">{{ $konteks_desc }}
                                            </p>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    @if ($isEditControlRisk || !$isShowControlRisk)
                        <hr>
                        <dt class="mb-2">Kriteria Risiko</dt>
                        <div class="row mt-2">
                            <label for="" class="form-label">Silahkan Memilih Kriteria Untuk Analisis
                                Risiko!</label>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <div class="accordion" id="accordionExample">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapseOne"
                                                    aria-expanded="false" aria-controls="collapseOne">
                                                    Kriteria Kemungkinan
                                                </button>
                                            </h2>
                                            <div id="collapseOne"
                                                class="accordion-collapse collapse @error('kemungkinan_id') show @enderror {{ $kemungkinan_id ? 'show' : '' }}"
                                                data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <label for="" class="form-label">Pilih Kriteria
                                                                Kemungkinan!</label>
                                                            @foreach ($dataKemungkinan as $index => $item)
                                                                <div class="mt-3">
                                                                    <div class="form-check">
                                                                        <input type="radio"
                                                                            wire:model='kemungkinan_id'
                                                                            id="kemungkinanRadio{{ $index }}"
                                                                            name="kemungkinanRadio"
                                                                            class="form-check-input @error('kemungkinan_id') is-invalid @enderror {{ $kemungkinan_id ? 'is-valid' : '' }}"
                                                                            value="{{ $item->kemungkinan_id }}">
                                                                        <label class="form-check-label"
                                                                            style="font-weight: 400"
                                                                            for="kemungkinanRadio{{ $index }}">{{ $item->kemungkinan_desc }}</label>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                            @error('kemungkinan_id')
                                                                <div class="invalid-feedback" style="display: block;">
                                                                    {{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapseTwo"
                                                    aria-expanded="false" aria-controls="collapseTwo">
                                                    Kriteria Dampak
                                                </button>
                                            </h2>
                                            <div id="collapseTwo"
                                                class="accordion-collapse collapse @error('dampak_id') show @enderror {{ $dampak_id ? 'show' : '' }}"
                                                data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <label for="" class="form-label">Pilih Kriteria
                                                                Dampak!</label>
                                                            @foreach ($dataDampak as $index => $item)
                                                                <div class="mt-3">
                                                                    <div class="form-check">
                                                                        <input type="radio" wire:model='dampak_id'
                                                                            id="dampakRadio{{ $index }}"
                                                                            name="dampakRadio"
                                                                            class="form-check-input @error('dampak_id') is-invalid @enderror {{ $dampak_id ? 'is-valid' : '' }}"
                                                                            value="{{ $item->dampak_id }}">
                                                                        <label class="form-check-label"
                                                                            style="font-weight: 400"
                                                                            for="dampakRadio{{ $index }}">{{ $item->dampak_desc }}</label>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                            @error('dampak_id')
                                                                <div class="invalid-feedback" style="display: block;">
                                                                    {{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapseThree"
                                                    aria-expanded="false" aria-controls="collapseThree">
                                                    Kriteria Deteksi Kegagalan
                                                </button>
                                            </h2>
                                            <div id="collapseThree"
                                                class="accordion-collapse collapse @error('deteksiKegagalan_id') show @enderror {{ $deteksiKegagalan_id ? 'show' : '' }}"
                                                data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <label for="" class="form-label">Pilih
                                                                Kriteria
                                                                Deteksi!</label>
                                                            @foreach ($dataDeteksi as $index => $item)
                                                                <div class="mt-3">
                                                                    <div class="form-check">
                                                                        <input type="radio"
                                                                            wire:model='deteksiKegagalan_id'
                                                                            id="deteksiRadio{{ $index }}"
                                                                            name="deteksiRadio"
                                                                            class="form-check-input @error('deteksiKegagalan_id') is-invalid @enderror {{ $deteksiKegagalan_id ? 'is-valid' : '' }}"
                                                                            value="{{ $item->deteksiKegagalan_id }}">
                                                                        <label class="form-check-label"
                                                                            style="font-weight: 400"
                                                                            for="deteksiRadio{{ $index }}">{{ $item->deteksiKegagalan_desc }}</label>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                            @error('deteksiKegagalan_id')
                                                                <div class="invalid-feedback" style="display: block;">
                                                                    {{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div> <!-- end col -->
                        </div> <!-- end row -->
                        <hr>
                        <dt class="mb-2">Efektifitas Kontrol</dt>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-firstname-input">Apakah sudah terdapat
                                        pengendalian efetifitas
                                        risiko? <span style="color: red">*</span></label>
                                    <select wire:model='efektifitasKontrol_kontrolStatus'
                                        class="form-control @error('efektifitasKontrol_kontrolStatus') is-invalid @enderror {{ $efektifitasKontrol_kontrolStatus ? 'is-valid' : '' }}">
                                        <option value="">-- Pilih Status --</option>
                                        <option value="1">Ada</option>
                                        <option value="0">Belum Ada</option>
                                    </select>
                                    <span
                                        class="invalid-feedback">{{ $errors->first('efektifitasKontrol_kontrolStatus') }}</span>
                                </div>
                            </div> <!-- end col -->
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-password_confirmation-input">
                                        Uraian Pengendalian Efektifitas Risiko
                                        <span style="color: red">*</span></label>
                                    <textarea id="snow-editor"
                                        class="form-control @error('efektifitasKontrol_kontrolDesc') is-invalid @enderror {{ $efektifitasKontrol_kontrolDesc ? 'is-valid' : '' }}"
                                        wire:model='efektifitasKontrol_kontrolDesc' aria-label="With textarea" cols="20" rows="10"></textarea>
                                    <span
                                        class="invalid-feedback">{{ $errors->first('efektifitasKontrol_kontrolDesc') }}</span>
                                </div>
                            </div> <!-- end col -->
                            <label for="" class="form-label">Penilaian Efektifitas Pengendalian Risiko</label>
                            @if ($penilaianEfektifitas && count($penilaianEfektifitas) > 0)
                                @forelse ($penilaianEfektifitas as $index => $item)
                                    <div class="col-md-12 mb-2">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <strong>Pertanyaan {{ $index + 1 }}</strong>
                                                <input type="hidden" wire:model='penilaianEfektifitas_id'
                                                    value="{{ $item->penilaianEfektifitas_id }}">
                                            </div>
                                            <div class="col-md-9">
                                                {{ $item->penilaianEfektifitas_pertanyaan }}
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <strong>Jawaban {{ $index + 1 }}</strong>
                                            </div>
                                            <div class="col-md-9">
                                                <select
                                                    wire:model.live='penilaianEfektifitas_skor.{{ $index }}'
                                                    class="form-control @error('penilaianEfektifitas_skor.' . $index) is-invalid @enderror {{ isset($penilaianEfektifitas_skor[$index]) ? 'is-valid' : '' }}">
                                                    <option value="">-- Pilih Jawaban --</option>
                                                    <option value="{{ $item->penilaianEfektifitas_ya }}">Ya, Skor :
                                                        {{ $item->penilaianEfektifitas_ya }}</option>
                                                    <option value="{{ $item->penilaianEfektifitas_sebagian }}">
                                                        Sebagian, Skor :
                                                        {{ $item->penilaianEfektifitas_sebagian }}</option>
                                                    <option value="{{ $item->penilaianEfektifitas_tidak }}">Tidak,
                                                        Skor
                                                        :
                                                        {{ $item->penilaianEfektifitas_tidak }}</option>
                                                </select>
                                                <span class="invalid-feedback">
                                                    @error('penilaianEfektifitas_skor.' . $index)
                                                        {{ $message }}
                                                    @enderror
                                                </span>
                                            </div>
                                        </div>
                                    </div> <!-- end col -->
                                @empty
                                    <div class="alert alert-danger mt-2 mb-2">
                                        No questions data available.
                                    </div>
                                @endforelse
                                <div class="col-md-12 mb-2">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <strong>Total Nilai Efektifitas</strong>
                                        </div>
                                        <div class="col-md-9">
                                            <dt>{{ $efektifitasKontrol_totalEfektifitas }}</dt>
                                            <input type="hidden" wire:model='efektifitasKontrol_totalEfektifitas'
                                                value="{{ $efektifitasKontrol_totalEfektifitas }}">
                                        </div>
                                    </div>
                                </div> <!-- end col -->
                            @else
                                <div class="alert alert-danger mt-2 mb-2">
                                    No questions data available.
                                </div>
                            @endif
                        </div> <!-- end row -->
                        <dt>Keterangan</dt>
                        <div class="table-responsive mb-2">
                            <table>
                                <tr>
                                    <td style="width: 200px; vertical-align: top;">
                                        <p class="card-text me-4">
                                            Efektif
                                        </p>
                                    </td>
                                    <td style="vertical-align: top;">:</td>
                                    <td style="width: calc(100% - 250px); vertical-align: bottom;">
                                        <p class="card-text" style="word-wrap: break-word;">
                                            3
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 200px; vertical-align: top;">
                                        <p class="card-text me-4">
                                            Sebagian Efektif
                                        </p>
                                    </td>
                                    <td style="vertical-align: top;">:</td>
                                    <td style="width: calc(100% - 250px); vertical-align: bottom;">
                                        <p class="card-text" style="word-wrap: break-word;">
                                            4-7
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 200px; vertical-align: top;">
                                        <p class="card-text me-4">
                                            Kurang Efektif
                                        </p>
                                    </td>
                                    <td style="vertical-align: top;">:</td>
                                    <td style="width: calc(100% - 250px); vertical-align: bottom;">
                                        <p class="card-text" style="word-wrap: break-word;">
                                            8-9
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 200px; vertical-align: top;">
                                        <p class="card-text me-4">
                                            Tidak Efektif
                                        </p>
                                    </td>
                                    <td style="vertical-align: top;">:</td>
                                    <td style="width: calc(100% - 250px); vertical-align: bottom;">
                                        <p class="card-text" style="word-wrap: break-word;">
                                            10-12
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <hr>
                        <dt class="mb-2">Rencana Perlakuan Risiko</dt>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-firstname-input">Jenis Perlakuan <span
                                            style="color: red">*</span></label>
                                    <select wire:model='jenisPerlakuan_id'
                                        class="form-control @error('jenisPerlakuan_id') is-invalid @enderror {{ $jenisPerlakuan_id ? 'is-valid' : '' }}">
                                        <option value="">-- Pilih Jenis Perlakuan --</option>
                                        @foreach ($jenisPerlakuans as $item)
                                            <option value="{{ $item->jenisPerlakuan_id }}">
                                                {{ ucwords($item->jenisPerlakuan_desc) }}</option>
                                        @endforeach
                                    </select>
                                    <span class="invalid-feedback">{{ $errors->first('jenisPerlakuan_id') }}</span>
                                </div>
                            </div> <!-- end col -->
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-password_confirmation-input">Keterangan
                                        <span style="color: red">*</span></label>
                                    <textarea id="snow-editor"
                                        class="form-control @error('rencanaPerlakuan_desc') is-invalid @enderror {{ $rencanaPerlakuan_desc ? 'is-valid' : '' }}"
                                        wire:model='rencanaPerlakuan_desc' aria-label="With textarea" cols="20" rows="10"></textarea>
                                    <span
                                        class="invalid-feedback">{{ $errors->first('rencanaPerlakuan_desc') }}</span>
                                </div>
                            </div> <!-- end col -->
                        </div> <!-- end row -->

                        <button type="button" wire:click.prevent='addPlan' wire:loading.attr="disabled"
                            wire:target="addPlan" class="btn btn-primary w-md waves-effect waves-light">Tambah
                            rencana...
                            <span wire:loading class="ms-2" wire:target="addPlan">
                                <span class="spinner-border spinner-border-sm" role="status"
                                    aria-hidden="true"></span>
                            </span>
                        </button>

                        @if (count($plans) > 0)
                            <ul class="list-group mt-3">
                                <h5>Daftar Rencana Perlakuan Risiko</h5>
                                @foreach ($plans as $index => $plan)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        {{ $plan['desc'] }}
                                        <button type="button" wire:click.prevent='removePlan({{ $index }})'
                                            wire:loading.attr="disabled"
                                            wire:target="removePlan({{ $index }})"
                                            class="btn btn-danger btn-sm w-md waves-effect waves-light">Hapus
                                            <span wire:loading class="ms-2"
                                                wire:target="removePlan({{ $index }})">
                                                <span class="spinner-border spinner-border-sm" role="status"
                                                    aria-hidden="true"></span>
                                            </span>
                                        </button>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    @else
                        <div class="table-responsive mb-2">
                            <dt class="mb-2">Identifikasi Risiko</dt>
                            <table>
                                <tr>
                                    <td style="width: 200px; vertical-align: top;">
                                        <p class="card-text me-4" style="font-weight: bold;">
                                            Risiko
                                        </p>
                                    </td>
                                    <td style="vertical-align: top;">:</td>
                                    <td style="width: 500px; vertical-align: bottom; word-break: break-word;">
                                        {{ $risk_riskDesc }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 200px; vertical-align: top;">
                                        <p class="card-text me-4" style="font-weight: bold;">
                                            Risiko Penyebab
                                        </p>
                                    </td>
                                    <td style="vertical-align: top;">:</td>
                                    <td style="width: 500px; vertical-align: bottom; word-break: break-word;">
                                        {{ $risk_penyebab }}
                                    </td>
                                </tr>
                            </table>
                            <hr>
                            <dt>Kriteria Risiko</dt>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width: 100px;">Kriteria Kemungkinan</th>
                                        <th style="width: 100px;">Kriteria Dampak</th>
                                        <th style="width: 100px;">Kriteria Deteksi Kegagalan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <td style="word-wrap: break-word;">
                                        {{ ucfirst($lastAnalisis->kemungkinan->kemungkinan_desc) }}
                                        <p class="card-text">
                                        </p>
                                    </td>
                                    <td style="word-wrap: break-word;">
                                        <p class="card-text" style="word-wrap: break-word;">
                                            {{ ucfirst($lastAnalisis->dampak->dampak_desc) }}
                                        </p>
                                    </td>
                                    <td style="word-wrap: break-word;">
                                        <p class="card-text" style="word-wrap: break-word;">
                                            {{ ucfirst($lastAnalisis->deteksiKegagalan->deteksiKegagalan_desc) }}
                                        </p>
                                    </td>
                                </tbody>
                            </table>
                            <hr>
                            <dt class="mb-2">Rencana Perlakuan Risiko</dt>
                            <table>
                                <tr>
                                    <td style="width: 200px; vertical-align: top;">
                                        <p class="card-text me-4" style="font-weight: bold">
                                            RTM
                                        </p>
                                    </td>
                                    <td style="vertical-align: top;">:</td>
                                    <td style="width: calc(100% - 250px); vertical-align: bottom;">
                                        <p class="card-text" style="word-wrap: break-word;">
                                            {{ ucwords($rtm) }}
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 200px; vertical-align: top;">
                                        <p class="card-text me-4" style="font-weight: bold">
                                            Jenis Perlakuan Risiko
                                        </p>
                                    </td>
                                    <td style="vertical-align: top;">:</td>
                                    <td style="width: calc(100% - 250px); vertical-align: bottom;">
                                        <p class="card-text" style="word-wrap: break-word;">
                                            {{ ucwords($jenisPerlakuan_desc) }}
                                        </p>
                                    </td>
                                </tr>
                            </table>
                            <ul class="list-group mt-3">
                                <h5>Daftar Rencana Perlakuan Risiko</h5>
                                @foreach ($plans as $index => $plan)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        {{ $plan['desc'] }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mt-1 border-top mb-3"></div>
                    <div class="d-flex justify-content-between">
                        <div class="">
                            @if ($isEditControlRisk)
                                <button type="button" class="btn btn-danger"
                                    wire:click='openModalConfirmDeleteRisk({{ $risk_id }})'
                                    wire:loading.attr="disabled"
                                    wire:target="openModalConfirmDeleteRisk({{ $risk_id }})">
                                    Delete Konteks
                                    <span wire:loading class="ms-2"
                                        wire:target="openModalConfirmDeleteRisk({{ $risk_id }})">
                                        <span class="spinner-border spinner-border-sm" role="status"
                                            aria-hidden="true"></span>
                                    </span>
                                </button>
                            @endif
                        </div>
                        <div class="">
                            <button type="button" class="btn btn-secondary" wire:click='closeModalControlRisk'
                                wire:loading.attr="disabled" wire:target="closeModalControlRisk">
                                Close
                                <span wire:loading class="ms-2" wire:target="closeModalControlRisk">
                                    <span class="spinner-border spinner-border-sm" role="status"
                                        aria-hidden="true"></span>
                                </span>
                            </button>
                            @if (!$isShowControlRisk || $isEditControlRisk)
                                <button type="button" wire:click.prevent='storeControlRisk' wire:loading.attr="disabled"
                                    wire:target="storeControlRisk"
                                    class="btn btn-primary w-md waves-effect waves-light">Submit
                                    <span wire:loading class="ms-2" wire:target="storeControlRisk">
                                        <span class="spinner-border spinner-border-sm" role="status"
                                            aria-hidden="true"></span>
                                    </span>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <div class="modal-backdrop fade show"></div>
@endif


@if ($isOpenConfirmDeleteControlRisk)
    <div class="modal" tabindex="-1" role="dialog" style="display: block;" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Menunggu Konfirmasi</h5>
                    <button type="button" class="btn-close" aria-label="Close"
                        wire:click="closeXModalConfirmDeleteControlRisk"></button>
                </div>
                <div class="modal-body">
                    Data tidak bisa dikembalikan apabila Anda menghapus Risiko. Apakah Anda yakin ingin menghapus
                    Risiko ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click='closeModalConfirmDeleteControlRisk'
                        wire:loading.attr="disabled" wire:target="closeModalConfirmDeleteControlRisk">
                        Tutup
                        <span wire:loading class="ms-2" wire:target="closeModalConfirmDeleteControlRisk">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        </span>
                    </button>
                    <button type="button" class="btn btn-danger" wire:click="deleteRisk"
                        wire:loading.attr="disabled" wire:target="deleteRisk">
                        Hapus Risiko
                        <span wire:loading class="ms-2" wire:target="deleteRisk">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        </span>
                    </button>
                </div>
            </div>
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <div class="modal-backdrop fade show"></div>
@endif
