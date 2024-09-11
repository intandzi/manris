@if ($isOpen)
    <div class="modal" tabindex="-1" role="dialog" style="display: block;" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0">Unit Form</h5>
                    <button type="button" class="btn-close" wire:click='closeXModal' data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label class="form-label" for="formrow-firstname-input">Nama Unit <span
                                    style="color: red">*</span></label>
                            <input type="text" wire:model="unit_name"
                                class="form-control @error('unit_name') is-invalid @enderror {{ $unit_name ? 'is-valid' : '' }}"
                                id="formrow-firstunit_name-input" placeholder="ketik nama unit...">
                            <span class="invalid-feedback">{{ $errors->first('unit_name') }}</span>
                        </div>
                        @if ($isEdit)
                            <div class="mb-3">
                                <label class="form-label" for="formrow-firstname-input">Status Unit <span
                                        style="color: red">*</span></label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="toggle_{{ $unit_id }}"
                                        wire:click="toggleActive({{ $unit_id }})"
                                        @if ($unit_activeStatus) checked @endif>
                                </div>
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
                            <button type="button" wire:click.prevent='storeUnit' wire:loading.attr="disabled"
                                wire:target="storeUnit" class="btn btn-primary w-md waves-effect waves-light">Submit
                                <span wire:loading class="ms-2" wire:target="storeUnit">
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
