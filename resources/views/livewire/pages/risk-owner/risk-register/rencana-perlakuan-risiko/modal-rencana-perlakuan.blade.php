@if ($isOpenRencanaPerlakuan)
    <div class="modal" tabindex="-1" role="dialog" style="display: block;" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0">Rencana Perlakuan Risiko</h5>
                    <button type="button" class="btn-close" wire:click='closeXModalRencanaPerlakuan'
                        data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive mb-2">
                                <table>
                                    <tr>
                                        <td style="width: 250px; vertical-align: top;">
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
                                    <tr>
                                        <td style="width: 200px; vertical-align: top;">
                                            <p class="card-text me-4" style="font-weight: bold;">
                                                Risiko
                                            </p>
                                        </td>
                                        <td style="vertical-align: top;">:</td>
                                        <td style="width: 800px; vertical-align: bottom; word-break: break-word;">
                                            <p style="word-break: break-word;">
                                                {{ $risk_spesific }}
                                            </p>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    @if ($isEditRencanaPerlakuan || !$isShowRencanaPerlakuan)
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
                                    <span class="invalid-feedback">{{ $errors->first('rencanaPerlakuan_desc') }}</span>
                                </div>
                            </div> <!-- end col -->
                        </div> <!-- end row -->

                        <button type="button" wire:click.prevent='addPlan' wire:loading.attr="disabled"
                            wire:target="addPlan" class="btn btn-primary w-md waves-effect waves-light">Tambah
                            rencana...
                            <span wire:loading class="ms-2" wire:target="addPlan">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            </span>
                        </button>

                        @if (count($plans) > 0)
                            <ul class="list-group mt-3">
                                <h5>Daftar Rencana Perlakuan Risiko</h5>
                                @foreach ($plans as $index => $plan)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        {{ $plan['desc'] }}
                                        <button type="button" wire:click.prevent='removePlan({{ $index }})'
                                            wire:loading.attr="disabled" wire:target="removePlan({{ $index }})"
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
                    <div class="d-flex justify-content-end">
                        <div class="">
                            <button type="button" class="btn btn-secondary" wire:click='closeModalRencanaPerlakuan'
                                wire:loading.attr="disabled" wire:target="closeModalRencanaPerlakuan">
                                Close
                                <span wire:loading class="ms-2" wire:target="closeModalRencanaPerlakuan">
                                    <span class="spinner-border spinner-border-sm" role="status"
                                        aria-hidden="true"></span>
                                </span>
                            </button>

                            @if ((!$isShowRencanaPerlakuan || $isEditRencanaPerlakuan) && count($plans) > 0)
                                <button type="button" wire:click.prevent='storeRencanaPerlakuan'
                                    wire:loading.attr="disabled" wire:target="storeRencanaPerlakuan"
                                    class="btn btn-primary w-md waves-effect waves-light">Submit
                                    <span wire:loading class="ms-2" wire:target="storeRencanaPerlakuan">
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
