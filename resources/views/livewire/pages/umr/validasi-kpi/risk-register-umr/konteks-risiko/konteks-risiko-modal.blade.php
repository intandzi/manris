
{{-- DETAIL KONTEKS --}}
@if ($isOpen)
    <div class="modal" tabindex="-1" role="dialog" style="display: block;" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0">Konteks Risiko Form</h5>
                    <button type="button" class="btn-close" wire:click='closeXModal' data-bs-dismiss="modal"
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
                                </table>
                            </div>
                        </div>
                    </div>
                    @if ($isEdit || !$isShow)
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-firstname-input">Kategori Konteks <span
                                            style="color: red">*</span></label>
                                    <select wire:model='konteks_kategori' {{ $isShow ? 'disabled' : '' }}
                                        class="form-control @error('konteks_kategori') is-invalid @enderror {{ $konteks_kategori ? 'is-valid' : '' }}">
                                        <option value="">-- Pilih Kategori --</option>
                                        <option value="internal">Internal</option>
                                        <option value="external">External</option>
                                    </select>
                                    <span class="invalid-feedback">{{ $errors->first('konteks_kategori') }}</span>
                                </div>
                            </div> <!-- end col -->
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-password_confirmation-input">Deskripsi
                                        Konteks
                                        <span style="color: red">*</span></label>
                                    <textarea id="snow-editor"
                                        class="form-control @error('konteks_desc') is-invalid @enderror {{ $konteks_desc ? 'is-valid' : '' }}"
                                        wire:model='konteks_desc' aria-label="With textarea" cols="20" rows="10" {{ $isShow ? 'disabled' : '' }}></textarea>
                                    <span class="invalid-feedback">{{ $errors->first('konteks_desc') }}</span>
                                </div>
                            </div> <!-- end col -->
                        </div> <!-- end row -->
                    @else
                        <div class="table-responsive mb-2">
                            <table>
                                <tr>
                                    <td style="width: 200px; vertical-align: top;">
                                        <p class="card-text me-4" style="font-weight: bold">
                                            Kategori Konteks
                                        </p>
                                    </td>
                                    <td style="vertical-align: top;">:</td>
                                    <td style="width: calc(100% - 250px); vertical-align: bottom;">
                                        <p class="card-text" style="word-wrap: break-word;">
                                            {{ ucfirst($konteks_kategori) }}
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 200px; vertical-align: top;">
                                        <p class="card-text me-4" style="font-weight: bold">
                                            Deskripsi Konteks
                                        </p>
                                    </td>
                                    <td style="vertical-align: top;">:</td>
                                    <td style="width: calc(100% - 250px); vertical-align: bottom;">
                                        <p class="card-text" style="word-wrap: break-word;">
                                            {{ ucfirst($konteks_desc) }}
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    @endif

                    <div class="mt-1 border-top mb-3"></div>
                    <div class="d-flex justify-content-between">
                        <div class="">
                            @if ($isEdit)
                                <button type="button" class="btn btn-danger"
                                    wire:click='openModalConfirmDelete({{ $konteks_id }})'
                                    wire:loading.attr="disabled"
                                    wire:target="openModalConfirmDelete({{ $konteks_id }})">
                                    Delete Konteks
                                    <span wire:loading class="ms-2"
                                        wire:target="openModalConfirmDelete({{ $konteks_id }})">
                                        <span class="spinner-border spinner-border-sm" role="status"
                                            aria-hidden="true"></span>
                                    </span>
                                </button>
                            @endif
                        </div>
                        <div class="">
                            <button type="button" class="btn btn-secondary" wire:click='closeModal'
                                wire:loading.attr="disabled" wire:target="closeModal">
                                Close
                                <span wire:loading class="ms-2" wire:target="closeModal">
                                    <span class="spinner-border spinner-border-sm" role="status"
                                        aria-hidden="true"></span>
                                </span>
                            </button>
                            @if (!$isShow || $isEdit)
                                <button type="button" wire:click.prevent='storeKonteks' wire:loading.attr="disabled"
                                    wire:target="storeKonteks"
                                    class="btn btn-primary w-md waves-effect waves-light">Submit
                                    <span wire:loading class="ms-2" wire:target="storeKonteks">
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


{{-- KEMBALIKAN KONTEKS --}}
@if ($isOpenConfirmDelete)
    <div class="modal" tabindex="-1" role="dialog" style="display: block;" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Menunggu Konfirmasi</h5>
                    <button type="button" class="btn-close" aria-label="Close"
                        wire:click="closeXModalConfirmDelete"></button>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                Data akan dikembalikan. Apakah Anda yakin ingin <strong>mengembalikan data
                                Konteks</strong> ini?
                            </div>
                        </div> <!-- end col -->
                    </div> <!-- end row -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="formrow-password_confirmation-input">Alasan
                                    Pengembalian
                                    <span style="color: red">*</span></label>
                                <textarea id="snow-editor"
                                    class="form-control @error('historyPengembalian_alasan') is-invalid @enderror {{ $historyPengembalian_alasan ? 'is-valid' : '' }}"
                                    wire:model='historyPengembalian_alasan' aria-label="With textarea" cols="20" rows="10"
                                    {{ $isShow ? 'disabled' : '' }}></textarea>
                                <span
                                    class="invalid-feedback">{{ $errors->first('historyPengembalian_alasan') }}</span>
                            </div>
                        </div> <!-- end col -->
                    </div> <!-- end row -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click='closeModalConfirmDelete'
                        wire:loading.attr="disabled" wire:target="closeModalConfirmDelete">
                        Tutup
                        <span wire:loading class="ms-2" wire:target="closeModalConfirmDelete">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        </span>
                    </button>
                    <button type="button" class="btn btn-danger" wire:click="kembalikanKonteks"
                        wire:loading.attr="disabled" wire:target="kembalikanKonteks">
                        Kembalikan Konteks
                        <span wire:loading class="ms-2" wire:target="kembalikanKonteks">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        </span>
                    </button>
                </div>
            </div>
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <div class="modal-backdrop fade show"></div>
@endif


{{-- VERIFIKASI KONTEKS --}}
@if ($isOpenConfirm)
    <div class="modal" tabindex="-1" role="dialog" style="display: block;" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Menunggu Konfirmasi</h5>
                    <button type="button" class="btn-close" aria-label="Close"
                        wire:click="closeXModalConfirm"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                Data akan disimpan dan tidak akan bisa diubah lagi. Apakah Anda yakin ingin <strong>melanjutkan proses</strong> ini?
                            </div>
                        </div> <!-- end col -->
                    </div> <!-- end row -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click='closeModalConfirm'
                        wire:loading.attr="disabled" wire:target="closeModalConfirm">
                        Tutup
                        <span wire:loading class="ms-2" wire:target="closeModalConfirm">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        </span>
                    </button>
                    <button type="button" class="btn btn-danger" wire:click="verifikasiKonteks"
                        wire:loading.attr="disabled" wire:target="verifikasiKonteks">
                        Verifikasi Konteks
                        <span wire:loading class="ms-2" wire:target="verifikasiKonteks">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        </span>
                    </button>
                </div>
            </div>
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <div class="modal-backdrop fade show"></div>
@endif
