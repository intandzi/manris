@if ($isOpen)
    <div class="modal" tabindex="-1" role="dialog" style="display: block;" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0">Pemangku Kepentingan Form</h5>
                    <button type="button" class="btn-close" wire:click='closeXModal' data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-firstname-input">Jabatan Pemangku Kepentingan
                                        <span style="color: red">*</span></label>
                                    <input type="text" wire:model="stakeholder_jabatan"
                                        class="form-control @error('stakeholder_jabatan') is-invalid @enderror {{ $stakeholder_jabatan ? 'is-valid' : '' }}"
                                        id="formrow-firststakeholder_jabatan-input" placeholder="ketik jabatan...">
                                    <span class="invalid-feedback">{{ $errors->first('stakeholder_jabatan') }}</span>
                                </div>
                            </div> <!-- end col -->
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-firstname-input">Singkatan Pemangku
                                        Kepentingan <span style="color: red">*</span></label>
                                    <span style="font-size: 12px;">(maksimal 5
                                        karakter)</span>
                                    <input type="text" wire:model="stakeholder_singkatan"
                                        class="form-control @error('stakeholder_singkatan') is-invalid @enderror {{ $stakeholder_singkatan ? 'is-valid' : '' }}"
                                        id="formrow-firststakeholder_singkatan-input" placeholder="ketik singkatan..."
                                        maxlength="5">
                                    <span class="invalid-feedback">{{ $errors->first('stakeholder_singkatan') }}</span>
                                </div>
                            </div> <!-- end col -->
                        </div>
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
                            <button type="button" wire:click.prevent='storeStakeholder' wire:loading.attr="disabled"
                                wire:target="storeStakeholder"
                                class="btn btn-primary w-md waves-effect waves-light">Submit
                                <span wire:loading class="ms-2" wire:target="storeStakeholder">
                                    <span class="spinner-border spinner-border-sm" role="status"
                                        aria-hidden="true"></span>
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <div class="modal-backdrop fade show"></div>
@endif
