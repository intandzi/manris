{{-- CHECK IF EDIT --}}
@if (!$isShowAnalisis || $isEditAnalisis)
    <div class="row mt-2">
        <label for="" class="form-label">Silahkan Memilih Kriteria Untuk Analisis
            Risiko!</label>
        <div class="col-md-12">
            <div class="mb-3">
                <div class="accordion" id="accordionExample">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
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
                                                    <input type="radio" wire:model='kemungkinan_id'
                                                        id="kemungkinanRadio{{ $index }}" name="kemungkinanRadio"
                                                        class="form-check-input @error('kemungkinan_id') is-invalid @enderror {{ $kemungkinan_id ? 'is-valid' : '' }}"
                                                        value="{{ $item->kemungkinan_id }}">
                                                    <label class="form-check-label" style="font-weight: 400"
                                                        for="kemungkinanRadio{{ $index }}">{{ $index + 1 }}.
                                                        {{ $item->kemungkinan_desc }}</label>
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
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
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
                                                        id="dampakRadio{{ $index }}" name="dampakRadio"
                                                        class="form-check-input @error('dampak_id') is-invalid @enderror {{ $dampak_id ? 'is-valid' : '' }}"
                                                        value="{{ $item->dampak_id }}">
                                                    <label class="form-check-label" style="font-weight: 400"
                                                        for="dampakRadio{{ $index }}">{{ $index + 1 }}.
                                                        {{ $item->dampak_desc }}</label>
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
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
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
                                                    <input type="radio" wire:model='deteksiKegagalan_id'
                                                        id="deteksiRadio{{ $index }}" name="deteksiRadio"
                                                        class="form-check-input @error('deteksiKegagalan_id') is-invalid @enderror {{ $deteksiKegagalan_id ? 'is-valid' : '' }}"
                                                        value="{{ $item->deteksiKegagalan_id }}">
                                                    <label class="form-check-label" style="font-weight: 400"
                                                        for="deteksiRadio{{ $index }}">{{ $index + 1 }}.
                                                        {{ $item->deteksiKegagalan_desc }}</label>
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
@else
    <dt class="mb-2">Kriteria Risiko</dt>
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
@endif
