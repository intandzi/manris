@if ($isOpen)
    <div class="modal" tabindex="-1" role="dialog" style="display: block;" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0 me-2">Visi Misi Form</h5> |
                    <p>&nbsp;</p> <!-- This will add some space -->
                    @if ($isShow)
                        <div>
                            {{ $created_at }}
                        </div>
                    @endif
                    <button type="button" class="btn-close" wire:click='closeXModal' data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        @if ($isShow)
                            <div class="table-responsive mb-2">
                                <table>
                                    <tr>
                                        <td style="width: 200px;">
                                            <p class="card-text me-4" style="font-weight: bold">Nama Unit
                                            </p>
                                        </td>
                                        <td>:</td>
                                        <td style="width: calc(100% - 250px); vertical-align: bottom;">
                                            <p class="card-text" style="word-wrap: break-word;">
                                                {{ ucwords($unit_name) }}
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 200px; vertical-align: top;">
                                            <p class="card-text" style="font-weight: bold">Visi</p>
                                        </td>
                                        <td style="vertical-align: top;">:</td>
                                        <td style="vertical-align: bottom;">
                                            <p class="card-text" style="word-wrap: break-word;">{!! $visimisi_visi !!}
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 200px; vertical-align: top;">
                                            <p class="card-text" style="font-weight: bold">Misi</p>
                                        </td>
                                        <td style="vertical-align: top;">:</td>
                                        <td style="vertical-align: bottom;">
                                            <p class="card-text" style="word-wrap: break-word;">{!! $visimisi_misi !!}
                                            </p>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        @else
                            <div class="mb-3">
                                <label class="form-label" for="formrow-firstname-input">Nama Unit <span
                                        style="color: red">*</span></label>
                                <input type="text" wire:model="unit_name"
                                    class="form-control @error('unit_name') is-invalid @enderror {{ $unit_name ? 'is-valid' : '' }}"
                                    id="formrow-firstunit_name-input" placeholder="ketik nama unit..." disabled>
                                <span class="invalid-feedback">{{ $errors->first('unit_name') }}</span>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label" for="formrow-password-input">Visi Unit
                                            <span style="color: red">*</span>
                                        </label>
                                        <livewire:quill-text-editor wire:model.live="visimisi_visi" theme="bubble" />
                                        @error('visimisi_visi')
                                            <!-- Display validation error for 'visimisi_visi' -->
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                        <span class="invalid-feedback">{{ $errors->first('visimisi_visi') }}</span>
                                    </div>
                                </div> <!-- end col -->
                            </div> <!-- end row -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label" for="formrow-password_confirmation-input">Misi Unit
                                            <span style="color: red">*</span>
                                        </label>
                                        <livewire:quill-text-editor wire:model.live="visimisi_misi" theme="bubble" />
                                        @error('visimisi_misi')
                                            <!-- Display validation error for 'visimisi_misi' -->
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                        <span class="invalid-feedback">{{ $errors->first('visimisi_misi') }}</span>
                                    </div>
                                </div> <!-- end col -->
                            </div> <!-- end row -->
                        @endif
                        <div class="border-top mb-3"></div>
                        <div class="mt-2 text-end">
                            <button type="button" class="btn btn-secondary" wire:click='closeModal'
                                wire:loading.attr="disabled" wire:target="closeModal">
                                Close
                                <span wire:loading class="ms-2" wire:target="closeModal">
                                    <span class="spinner-border spinner-border-sm" role="status"
                                        aria-hidden="true"></span>
                                </span>
                            </button>
                            @if (!$isShow)
                                <button type="button" wire:click.prevent='storeVisiMisi' wire:loading.attr="disabled"
                                    wire:target="storeVisiMisi"
                                    class="btn btn-primary w-md waves-effect waves-light">Submit
                                    <span wire:loading class="ms-2" wire:target="storeVisiMisi">
                                        <span class="spinner-border spinner-border-sm" role="status"
                                            aria-hidden="true"></span>
                                    </span>
                                </button>
                            @endif
                        </div>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <div class="modal-backdrop fade show"></div>
@endif
