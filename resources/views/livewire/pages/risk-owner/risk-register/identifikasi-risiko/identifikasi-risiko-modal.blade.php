@if ($isOpenRisk)
    <div class="modal" tabindex="-1" role="dialog" style="display: block;" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0">Identifikasi Risiko Form</h5>
                    <button type="button" class="btn-close" wire:click='closeXModalRisk' data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
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
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-password_confirmation-input">
                                        Risiko
                                        <span style="color: red">*</span></label>
                                    <textarea id="snow-editor"
                                        class="form-control @error('risk_riskDesc') is-invalid @enderror {{ $risk_riskDesc ? 'is-valid' : '' }}"
                                        wire:model='risk_riskDesc' aria-label="With textarea" cols="20" rows="10" {{ $isShowRisk ? 'disabled' : '' }}></textarea>
                                    <span class="invalid-feedback">{{ $errors->first('risk_riskDesc') }}</span>
                                </div>
                            </div> <!-- end col -->
                        </div> <!-- end row -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-password_confirmation-input">
                                        Penyebab Risiko
                                        <span style="color: red">*</span></label>
                                    <textarea id="snow-editor"
                                        class="form-control @error('risk_penyebab') is-invalid @enderror {{ $risk_penyebab ? 'is-valid' : '' }}"
                                        wire:model='risk_penyebab' aria-label="With textarea" cols="20" rows="10" {{ $isShowRisk ? 'disabled' : '' }}></textarea>
                                    <span class="invalid-feedback">{{ $errors->first('risk_penyebab') }}</span>
                                </div>
                            </div> <!-- end col -->
                        </div> <!-- end row -->

                        <div class="mt-1 border-top mb-3"></div>
                        <div class="d-flex justify-content-between">
                            <div class="">
                                @if ($isEditRisk)
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
                                <button type="button" class="btn btn-secondary" wire:click='closeModalRisk'
                                    wire:loading.attr="disabled" wire:target="closeModalRisk">
                                    Close
                                    <span wire:loading class="ms-2" wire:target="closeModalRisk">
                                        <span class="spinner-border spinner-border-sm" role="status"
                                            aria-hidden="true"></span>
                                    </span>
                                </button>
                                @if (!$isShowRisk)
                                    <button type="button" wire:click.prevent='storeRisk' wire:loading.attr="disabled"
                                        wire:target="storeRisk"
                                        class="btn btn-primary w-md waves-effect waves-light">Submit
                                        <span wire:loading class="ms-2" wire:target="storeRisk">
                                            <span class="spinner-border spinner-border-sm" role="status"
                                                aria-hidden="true"></span>
                                        </span>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <div class="modal-backdrop fade show"></div>
@endif


@if ($isOpenConfirmDeleteRisk)
    <div class="modal" tabindex="-1" role="dialog" style="display: block;" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Menunggu Konfirmasi</h5>
                    <button type="button" class="btn-close" aria-label="Close"
                        wire:click="closeXModalConfirmDeleteRisk"></button>
                </div>
                <div class="modal-body">
                    Data tidak bisa dikembalikan apabila Anda menghapus Risiko. Apakah Anda yakin ingin menghapus
                    Risiko ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click='closeModalConfirmDeleteRisk'
                        wire:loading.attr="disabled" wire:target="closeModalConfirmDeleteRisk">
                        Tutup
                        <span wire:loading class="ms-2" wire:target="closeModalConfirmDeleteRisk">
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
