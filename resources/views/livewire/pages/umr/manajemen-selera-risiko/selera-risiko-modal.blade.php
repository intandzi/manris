@if ($isOpen)
    <div class="modal" tabindex="-1" role="dialog" style="display: block;" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-full-width">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0">Selera Risiko Form</h5>
                    <button type="button" class="btn-close" wire:click='closeXModal' data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if ($isEdit || !$isShow)
                        <div class="mb-3">
                            <label class="form-label" for="formrow-firstname-input">Selera Risiko Deskripsi
                                <span style="color: red">*</span></label>
                            <textarea id="snow-editor"
                                class="form-control @error('seleraRisiko_desc') is-invalid @enderror {{ $seleraRisiko_desc ? 'is-valid' : '' }}"
                                wire:model='seleraRisiko_desc' aria-label="With textarea" rows="5" cols="5"></textarea>
                            <span class="invalid-feedback">{{ $errors->first('seleraRisiko_desc') }}</span>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="formrow-firstname-input">Selera Risiko Tindak Lanjut
                                <span style="color: red">*</span></label>
                            <textarea id="snow-editor"
                                class="form-control @error('seleraRisiko_tindakLanjut') is-invalid @enderror {{ $seleraRisiko_tindakLanjut ? 'is-valid' : '' }}"
                                wire:model='seleraRisiko_tindakLanjut' aria-label="With textarea" rows="5" cols="5"></textarea>
                            <span class="invalid-feedback">{{ $errors->first('seleraRisiko_tindakLanjut') }}</span>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="user_unit">Derajat Risiko <span
                                    style="color: red">*</span></label>
                            <select wire:model="derajatRisiko_id"
                                class="form-control @error('derajatRisiko_id') is-invalid @enderror {{ $derajatRisiko_id ? 'is-valid' : '' }}">
                                <option value="">-- Pilih Derajat Risiko --</option>
                                @foreach ($derajatRisikos as $item)
                                    <option value="{{ $item->derajatRisiko_id }}">
                                        {{ ucwords($item->derajatRisiko_desc) }}
                                        {{ $item->derajatRisiko_nilaiTingkatMin }}-{{ $item->derajatRisiko_nilaiTingkatMax }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="invalid-feedback">{{ $errors->first('derajatRisiko_id') }}</span>
                        </div>
                    @else
                        <div class="table-responsive mb-2">
                            <table>
                                <tr>
                                    <td style="width: 100px;">
                                        <p class="card-text me-4" style="font-weight: bold">Tingkat Risiko
                                        </p>
                                    </td>
                                    <td>:</td>
                                    <td style="width: calc(100% - 250px); vertical-align: bottom;">
                                        <p class="card-text" style="word-wrap: break-word;">
                                            {{ $derajatRisk->derajatRisiko_nilaiTingkatMin }}-{{ $derajatRisk->derajatRisiko_nilaiTingkatMax }}
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 200px;">
                                        <p class="card-text me-4" style="font-weight: bold">Derajat Risiko
                                        </p>
                                    </td>
                                    <td>:</td>
                                    <td style="width: calc(100% - 250px); vertical-align: bottom;">
                                        <p class="card-text" style="word-wrap: break-word;">
                                            {{ ucfirst($derajatRisk->derajatRisiko_desc) }}
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 200px; vertical-align: top;">
                                        <p class="card-text" style="font-weight: bold">Deskripsi Selera Risiko</p>
                                    </td>
                                    <td style="vertical-align: top;">:</td>
                                    <td style="vertical-align: bottom;">
                                        <p class="card-text" style="word-wrap: break-word;">
                                            {{ $seleraRisiko_desc }}
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 200px; vertical-align: top;">
                                        <p class="card-text" style="font-weight: bold">Tindak Lanjut Risiko</p>
                                    </td>
                                    <td style="vertical-align: top;">:</td>
                                    <td style="vertical-align: bottom;">
                                        <p class="card-text" style="word-wrap: break-word;">
                                            {{ $seleraRisiko_tindakLanjut }}
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    @endif

                    <h4>Riwayat Selera Risiko</h4>
                    @if ($riwayatSelera && count($riwayatSelera) > 0)
                        <div class="table-responsive mt-2">
                            <table class="table table-centered mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th style="width:100px;">Created At</th>
                                        <th wire:click.live="doSort('derajatRisiko_id')" style="">
                                            Tingkat Risiko
                                        </th>
                                        <th wire:click.live="doSort('seleraRisiko_desc')" style="">
                                            Deskripsi Selera Risiko
                                        </th>
                                        <th style="">
                                            Tindak Lanjut Yg Diperlukan
                                        </th>
                                        @if ($isShow)
                                            <th>
                                                Status
                                            </th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($riwayatSelera as $index => $data)
                                        <tr>
                                            <td>
                                                {{ $data->created_at }}
                                            </td>
                                            <td>
                                                {{ $data->derajatRisiko->derajatRisiko_nilaiTingkatMin }}-{{ $data->derajatRisiko->derajatRisiko_nilaiTingkatMax }}
                                            </td>
                                            <td>
                                                {{ ucwords($data->seleraRisiko_desc) }}
                                            </td>
                                            <td>
                                                {{ ucwords($data->seleraRisiko_tindakLanjut) }}
                                            </td>
                                            @if ($isShow)
                                                <td>
                                                    @if (count($riwayatSelera) > 1)
                                                        @if ($data->seleraRisiko_activeStatus)
                                                            <button type="button"
                                                                wire:click.prevent="toggleActive({{ $data->seleraRisiko_id }}, {{ $data->derajatRisiko_id }})"
                                                                wire:loading.attr="disabled"
                                                                wire:target="toggleActive({{ $data->seleraRisiko_id }}, {{ $data->derajatRisiko_id }})"
                                                                class="btn btn-success btn-sm d-flex text-center align-items-center me-2">
                                                                <i class="ri-check-line" wire:loading.remove
                                                                    wire:target='toggleActive({{ $data->seleraRisiko_id }}, {{ $data->derajatRisiko_id }})'>
                                                                </i>
                                                                <span wire:loading
                                                                    wire:target="toggleActive({{ $data->seleraRisiko_id }}, {{ $data->derajatRisiko_id }})">
                                                                    <span class="spinner-border spinner-border-sm"
                                                                        role="status" aria-hidden="true"></span>
                                                                </span>
                                                            </button>
                                                        @else
                                                            <button type="button"
                                                                wire:click.prevent="toggleActive({{ $data->seleraRisiko_id }}, {{ $data->derajatRisiko_id }})"
                                                                wire:loading.attr="disabled"
                                                                wire:target="toggleActive({{ $data->seleraRisiko_id }}, {{ $data->derajatRisiko_id }})"
                                                                class="btn btn-danger btn-sm d-flex text-center align-items-center me-2">
                                                                <i class="ri-close-line" wire:loading.remove
                                                                    wire:target='toggleActive({{ $data->seleraRisiko_id }}, {{ $data->derajatRisiko_id }})'>
                                                                </i>
                                                                <span wire:loading
                                                                    wire:target="toggleActive({{ $data->seleraRisiko_id }}, {{ $data->derajatRisiko_id }})">
                                                                    <span class="spinner-border spinner-border-sm"
                                                                        role="status" aria-hidden="true"></span>
                                                                </span>
                                                            </button>
                                                        @endif
                                                    @else
                                                        <span
                                                            class="badge badge-outline-danger rounded-pill mt-2">Tambahkan <br> Terlebih <br> dahulu!</span>
                                                    @endif
                                                    @error('alreadyActive')
                                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                                    @enderror
                                                </td>
                                            @endif
                                        </tr>
                                    @empty
                                        <div class="alert alert-danger mt-2 mb-2">
                                            No data available.
                                        </div>
                                    @endforelse
                                </tbody>
                            </table>
                        </div> <!-- end table-responsive-->
                    @else
                        <div class="alert alert-danger mt-2 mb-2">
                            No data available.
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
                            <button type="button" wire:click.prevent='storeSeleraRisiko'
                                wire:loading.attr="disabled" wire:target="storeSeleraRisiko"
                                class="btn btn-primary w-md waves-effect waves-light">Submit
                                <span wire:loading class="ms-2" wire:target="storeSeleraRisiko">
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
