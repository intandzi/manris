{{-- @if ($isOpenEvaluasi)
    <div class="modal" tabindex="-1" role="dialog" style="display: block;" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0">Evaluasi Risiko</h5>
                    <button type="button" class="btn-close" wire:click='closeXModalEvaluasi' data-bs-dismiss="modal"
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
                    <div class="row mt-2">
                        <label for="" class="form-label">Silahkan Memilih Kriteria Untuk Evaluasi
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
                                                        <label for="" class="form-label">Pilih Kriteria
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

                    <div class="mt-1 border-top mb-3"></div>
                    <div class="d-flex justify-content-end">
                        <div class="">
                            <button type="button" class="btn btn-secondary" wire:click='closeModalEvaluasi'
                                wire:loading.attr="disabled" wire:target="closeModalEvaluasi">
                                Close
                                <span wire:loading class="ms-2" wire:target="closeModalEvaluasi">
                                    <span class="spinner-border spinner-border-sm" role="status"
                                        aria-hidden="true"></span>
                                </span>
                            </button>
                            @if (!$isShowRisk)
                                <button type="button" wire:click.prevent='storeEvaluasi'
                                    wire:loading.attr="disabled" wire:target="storeEvaluasi"
                                    class="btn btn-primary w-md waves-effect waves-light">Submit
                                    <span wire:loading class="ms-2" wire:target="storeEvaluasi">
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
@endif --}}
