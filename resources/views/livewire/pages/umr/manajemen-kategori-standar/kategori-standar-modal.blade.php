@if ($isOpen)
    <div class="modal" tabindex="-1" role="dialog" style="display: block;" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0">Kategori Standar Form</h5>
                    <button type="button" class="btn-close" wire:click='closeXModal' data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label" for="formrow-firstname-input">Kategori Standar Deskripsi <span
                                style="color: red">*</span></label>
                        <input type="text" wire:model="kategoriStandar_desc"
                            class="form-control @error('kategoriStandar_desc') is-invalid @enderror {{ $kategoriStandar_desc ? 'is-valid' : '' }}"
                            id="formrow-firstkategoriStandar_desc-input" placeholder="ketik nama kategori standar...">
                        <span class="invalid-feedback">{{ $errors->first('kategoriStandar_desc') }}</span>
                    </div>
                    <div class="mt-3 border-top mb-3"></div>
                    <div class="mt-4 text-end">
                        <button type="button" class="btn btn-secondary" wire:click='closeModal' wire:loading.attr="disabled"
                            wire:target="closeModal">
                            Close
                            <span wire:loading class="ms-2" wire:target="closeModal">
                                <span class="spinner-border spinner-border-sm" role="status"
                                    aria-hidden="true"></span>
                            </span>
                        </button>
                        <button type="button" wire:click.prevent='storeKategoriStandar' wire:loading.attr="disabled"
                                wire:target="storeKategoriStandar"
                                class="btn btn-primary w-md waves-effect waves-light">Submit
                                <span wire:loading class="ms-2" wire:target="storeKategoriStandar">
                                    <span class="spinner-border spinner-border-sm" role="status"
                                        aria-hidden="true"></span>
                                </span>
                            </button>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <div class="modal-backdrop fade show"></div>
@endif
