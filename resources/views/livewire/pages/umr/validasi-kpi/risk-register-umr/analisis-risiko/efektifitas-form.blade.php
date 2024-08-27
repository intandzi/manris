{{-- CHECK IF EDIT --}}
@if (!$isShowEfektifitas || $isEditEfektifitas)
    <div class="row mt-2">
        <div class="col-md-12">
            <div class="mb-3">
                <label class="form-label" for="formrow-firstname-input">Apakah sudah terdapat pengendalian efetifitas
                    risiko? <span style="color: red">*</span></label>
                <select wire:model='efektifitasKontrol_kontrolStatus'
                    class="form-control @error('efektifitasKontrol_kontrolStatus') is-invalid @enderror {{ $efektifitasKontrol_kontrolStatus ? 'is-valid' : '' }}">
                    <option value="">-- Pilih Status --</option>
                    <option value="1">Ada</option>
                    <option value="0">Belum Ada</option>
                </select>
                <span class="invalid-feedback">{{ $errors->first('efektifitasKontrol_kontrolStatus') }}</span>
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
                <span class="invalid-feedback">{{ $errors->first('efektifitasKontrol_kontrolDesc') }}</span>
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
                            <select wire:model.live='penilaianEfektifitas_skor.{{ $index }}'
                                class="form-control @error('penilaianEfektifitas_skor.' . $index) is-invalid @enderror {{ isset($penilaianEfektifitas_skor[$index]) ? 'is-valid' : '' }}">
                                <option value="">-- Pilih Jawaban --</option>
                                <option value="{{ $item->penilaianEfektifitas_ya }}">Ya, Skor :
                                    {{ $item->penilaianEfektifitas_ya }}</option>
                                <option value="{{ $item->penilaianEfektifitas_sebagian }}">Sebagian, Skor :
                                    {{ $item->penilaianEfektifitas_sebagian }}</option>
                                <option value="{{ $item->penilaianEfektifitas_tidak }}">Tidak, Skor :
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
@else
    <div class="table-responsive mb-2">
        <table>
            <tr>
                <td style="width: 200px; vertical-align: top;">
                    <p class="card-text me-4" style="font-weight: bold">
                        Kriteria Kemungkinan
                    </p>
                </td>
                <td style="vertical-align: top;">:</td>
                <td style="width: calc(100% - 250px); vertical-align: bottom;">
                    <p class="card-text" style="word-wrap: break-word;">
                        {{ ucfirst($lastAnalisis->kemungkinan->kemungkinan_desc) }}
                    </p>
                </td>
            </tr>
            <tr>
                <td style="width: 200px; vertical-align: top;">
                    <p class="card-text me-4" style="font-weight: bold">
                        Kriteria Dampak
                    </p>
                </td>
                <td style="vertical-align: top;">:</td>
                <td style="width: calc(100% - 250px); vertical-align: bottom;">
                    <p class="card-text" style="word-wrap: break-word;">
                        {{ ucfirst($lastAnalisis->dampak->dampak_desc) }}
                    </p>
                </td>
            </tr>
            <tr>
                <td style="width: 200px; vertical-align: top;">
                    <p class="card-text me-4" style="font-weight: bold">
                        Kriteria Deteksi Kegagalan
                    </p>
                </td>
                <td style="vertical-align: top;">:</td>
                <td style="width: calc(100% - 250px); vertical-align: bottom;">
                    <p class="card-text" style="word-wrap: break-word;">
                        {{ ucfirst($lastAnalisis->deteksiKegagalan->deteksiKegagalan_desc) }}
                    </p>
                </td>
            </tr>
        </table>
    </div>
@endif
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
