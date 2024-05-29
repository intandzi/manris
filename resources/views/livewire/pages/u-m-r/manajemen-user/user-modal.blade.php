@if ($isOpen)
    <div class="modal" tabindex="-1" role="dialog" style="display: block;" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0">User Form</h5>
                    <button type="button" class="btn-close" wire:click='closeModal' data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label class="form-label" for="formrow-firstname-input">Nama User <span
                                    style="color: red">*</span></label>
                            <input type="text" wire:model="name"
                                class="form-control @error('name') is-invalid @enderror {{ $name ? 'is-valid' : '' }}"
                                id="formrow-firstname-input" placeholder="ketik nama user...">
                            <span class="invalid-feedback">{{ $errors->first('name') }}</span>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-email-input">Email <span
                                            style="color: red">*</span></label>
                                    <input type="email" wire:model="email"
                                        class="form-control @error('email') is-invalid @enderror {{ $email ? 'is-valid' : '' }}"
                                        id="formrow-email-input" placeholder="ketik email user...">
                                    <span class="invalid-feedback">{{ $errors->first('email') }}</span>
                                </div>
                            </div> <!-- end col -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="user_unit">Unit <span
                                            style="color: red">*</span></label>
                                    <select wire:model.live="unit_id"
                                        class="form-control @error('unit_id') is-invalid @enderror {{ $unit_id ? 'is-valid' : '' }}">
                                        <option value="">-- Pilih Unit User --</option>
                                        @foreach ($units as $unit)
                                            <option value="{{ $unit->unit_id }}">{{ ucwords($unit->unit_name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="invalid-feedback">{{ $errors->first('unit_id') }}</span>
                                </div>
                            </div> <!-- end col -->
                        </div> <!-- end row -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-jabatan-input">Jabatan <span
                                            style="color: red">*</span></label>
                                    <input type="jabatan" wire:model="jabatan"
                                        class="form-control @error('jabatan') is-invalid @enderror {{ $jabatan ? 'is-valid' : '' }}"
                                        id="formrow-jabatan-input" placeholder="ketik jabatan user...">
                                    <span class="invalid-feedback">{{ $errors->first('jabatan') }}</span>
                                </div>
                            </div> <!-- end col -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="user_role">Peran User <span
                                            style="color: red">*</span></label>
                                    <div class="form-check form-switch">
                                        <div class="form-check form-switch">
                                            @foreach ($roles as $role)
                                                <div class="form-check" style="margin-left: -50px;">
                                                    @php
                                                        $isChecked = in_array($role->name, $selectedRoles);
                                                    @endphp
                                                    <input type="checkbox"
                                                        class="form-check-input @error('selectedRoles') is-invalid @enderror {{ $isChecked ? 'is-valid' : '' }}"
                                                        id="role_{{ $role->id }}" wire:model="selectedRoles"
                                                        value="{{ $role->name }}">
                                                    <label class="form-check-label"
                                                        for="role_{{ $role->id }}">{{ ucwords($role->name) }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    @error('selectedRoles')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                    <span class="invalid-feedback">{{ $errors->first('selectedRoles') }}</span>

                                </div>
                            </div> <!-- end col -->
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-password-input">Kata Sandi <span
                                            style="color: red">*</span></label>
                                    <input type="password" wire:model="password"
                                        class="form-control @error('password') is-invalid @enderror {{ $password ? 'is-valid' : '' }}"
                                        id="formrow-password-input" placeholder="ketik password user...">
                                    <span class="invalid-feedback">{{ $errors->first('password') }}</span>
                                </div>
                            </div> <!-- end col -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-password_confirmation-input">Konfirmasi Kata
                                        Sandi <span style="color: red">*</span></label>
                                    <input type="password" wire:model="password_confirmation"
                                        class="form-control @error('password') is-invalid @enderror {{ $password_confirmation ? 'is-valid' : '' }}"
                                        id="formrow-password_confirmation-input" placeholder="ketik password user...">
                                    <span class="invalid-feedback">{{ $errors->first('password') }}</span>
                                </div>
                            </div> <!-- end col -->
                        </div> <!-- end row -->
                        <div class="mt-3 border-top mb-3"></div>
                        <div class="mt-4 text-end">
                            <button type="button" class="btn btn-secondary" wire:click='closeModal'>Close</button>
                            <button type="button" wire:click.prevent='storeUser' wire:loading.attr="disabled"
                                wire:target="storeUser"
                                class="btn btn-primary w-md waves-effect waves-light">Submit
                                <span wire:loading class="ms-2" wire:target="storeUser">
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
