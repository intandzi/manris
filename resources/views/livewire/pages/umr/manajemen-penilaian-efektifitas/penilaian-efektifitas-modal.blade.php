@if ($isOpen)
    <div class="modal" tabindex="-1" role="dialog" style="display: block;" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0">Penilaian Efektifitas Form</h5>
                    <button type="button" class="btn-close" wire:click='closeXModal' data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if ($isEdit || !$isShow)
                        <div class="mb-3">
                            <label class="form-label" for="formrow-firstname-input">Pertanyaan Penilaian Efektifitas
                                <span style="color: red">*</span></label>
                            <textarea id="snow-editor"
                                class="form-control @error('penilaianEfektifitas_pertanyaan') is-invalid @enderror {{ $penilaianEfektifitas_pertanyaan ? 'is-valid' : '' }}"
                                wire:model='penilaianEfektifitas_pertanyaan' aria-label="With textarea" rows="5" cols="5"></textarea>
                            <span
                                class="invalid-feedback">{{ $errors->first('penilaianEfektifitas_pertanyaan') }}</span>
                        </div>
                        <dt>Skor Jawaban</dt>
                        <p>Penentuan angka adalah dari rendah ke tinggi untuk jawaban “Ya” ke “Tidak”</p>
                        <div class="mb-3">
                            <label class="form-label" for="user_unit">Ya <span style="color: red">*</span></label>
                            <select wire:model="penilaianEfektifitas_ya"
                                class="form-control @error('penilaianEfektifitas_ya') is-invalid @enderror {{ $penilaianEfektifitas_ya ? 'is-valid' : '' }}">
                                <option value="">-- Pilih Skor --</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                            </select>
                            <span class="invalid-feedback">{{ $errors->first('penilaianEfektifitas_ya') }}</span>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="user_unit">Sebagian <span style="color: red">*</span></label>
                            <select wire:model="penilaianEfektifitas_sebagian"
                                class="form-control @error('penilaianEfektifitas_sebagian') is-invalid @enderror {{ $penilaianEfektifitas_sebagian ? 'is-valid' : '' }}">
                                <option value="">-- Pilih Skor --</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                            </select>
                            <span class="invalid-feedback">{{ $errors->first('penilaianEfektifitas_sebagian') }}</span>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="user_unit">Tidak <span style="color: red">*</span></label>
                            <select wire:model="penilaianEfektifitas_tidak"
                                class="form-control @error('penilaianEfektifitas_tidak') is-invalid @enderror {{ $penilaianEfektifitas_tidak ? 'is-valid' : '' }}">
                                <option value="">-- Pilih Skor --</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                            </select>
                            <span class="invalid-feedback">{{ $errors->first('penilaianEfektifitas_tidak') }}</span>
                        </div>
                    @else
                        <div class="table-responsive mb-2">
                            <table>
                                <tr>
                                    <td style="width: 200px;">
                                        <p class="card-text me-4" style="font-weight: bold; vertical-align: top;">
                                            Penilaian Efektifitas Pertanyaan
                                        </p>
                                    </td>
                                    <td style="vertical-align: top;">:</td>
                                    <td style="width: calc(100% - 250px); vertical-align: bottom;">
                                        <p class="card-text" style="word-wrap: break-word;">
                                            {{ $penilaianEfektifitas_pertanyaan }}
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 300px;">
                                        <p class="card-text me-4" style="font-weight: bold">
                                            Skor Jawaban Ya
                                        </p>
                                    </td>
                                    <td>:</td>
                                    <td style="width: calc(100% - 250px); vertical-align: bottom;">
                                        <p class="card-text" style="word-wrap: break-word;">
                                            {{ $penilaianEfektifitas_ya }}
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 300px;">
                                        <p class="card-text me-4" style="font-weight: bold">
                                            Skor Jawaban Sebagian
                                        </p>
                                    </td>
                                    <td>:</td>
                                    <td style="width: calc(100% - 250px); vertical-align: bottom;">
                                        <p class="card-text" style="word-wrap: break-word;">
                                            {{ $penilaianEfektifitas_sebagian }}
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 300px;">
                                        <p class="card-text me-4" style="font-weight: bold">
                                            Skor Jawaban Tidak
                                        </p>
                                    </td>
                                    <td>:</td>
                                    <td style="width: calc(100% - 250px); vertical-align: bottom;">
                                        <p class="card-text" style="word-wrap: break-word;">
                                            {{ $penilaianEfektifitas_tidak }}
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    @endif


                    <div class="mt-3 border-top mb-3"></div>
                    <div class="mt-4 text-end">
                        <button type="button" class="btn btn-secondary" wire:click='closeModal'
                            wire:loading.attr="disabled" wire:target="closeModal">
                            Close
                            <span wire:loading class="ms-2" wire:target="closeModal">
                                <span class="spinner-border spinner-border-sm" role="status"
                                    aria-hidden="true"></span>
                            </span>
                        </button>
                        @if ($isEdit || !$isShow)
                            <button type="button" wire:click.prevent='storePenilaianEfektifitas'
                                wire:loading.attr="disabled" wire:target="storePenilaianEfektifitas"
                                class="btn btn-primary w-md waves-effect waves-light">Submit
                                <span wire:loading class="ms-2" wire:target="storePenilaianEfektifitas">
                                    <span class="spinner-border spinner-border-sm" role="status"
                                        aria-hidden="true"></span>
                                </span>
                            </button>
                        @endif
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <div class="modal-backdrop fade show"></div>
@endif
