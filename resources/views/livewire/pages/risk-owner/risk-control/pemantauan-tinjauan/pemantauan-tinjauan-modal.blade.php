@if ($isOpenPemantauanTinjauan)
    <div class="modal" tabindex="-1" role="dialog" style="display: block;" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0">Pemantauan dan Tinjauan Form</h5>
                    <button type="button" class="btn-close" wire:click='closeXModalPemantauanTinjauan'
                        data-bs-dismiss="modal" aria-label="Close"></button>
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
                                    <tr>
                                        <td style="width: 200px; vertical-align: top;">
                                            <p class="card-text me-4" style="font-weight: bold;">
                                                Risiko
                                            </p>
                                        </td>
                                        <td style="vertical-align: top;">:</td>
                                        <td style="width: 500px; vertical-align: bottom; word-break: break-word;">
                                            {{ $risk_riskDesc }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 200px; vertical-align: top;">
                                            <p class="card-text me-4" style="font-weight: bold;">
                                                Risiko Penyebab
                                            </p>
                                        </td>
                                        <td style="vertical-align: top;">:</td>
                                        <td style="width: 500px; vertical-align: bottom; word-break: break-word;">
                                            {{ $risk_penyebab }}
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <ul class="list-group mt-3">
                                    <h5>Daftar Rencana Perlakuan Risiko</h5>
                                    @foreach ($plans as $index => $plan)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            {{ $plan['desc'] }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div> <!-- end col -->
                    </div>
                    @if ($isEditPemantauanTinjauan || !$isShowPemantauanTinjauan)
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-firstname-input">Frekuensi pemantauan /tahun
                                        <span style="color: red">*</span></label>
                                    <select wire:model='pemantauanTinjauan_freqPemantauan'
                                        {{ $isShowPemantauanTinjauan ? 'disabled' : '' }}
                                        class="form-control @error('pemantauanTinjauan_freqPemantauan') is-invalid @enderror {{ $pemantauanTinjauan_freqPemantauan ? 'is-valid' : '' }}">
                                        <option value="">-- Pilih Frekuensi --</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="10">10</option>
                                    </select>
                                    <span
                                        class="invalid-feedback">{{ $errors->first('pemantauanTinjauan_freqPemantauan') }}</span>
                                </div>
                            </div> <!-- end col -->
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-password_confirmation-input">Pemantauan
                                        <span style="color: red">*</span></label>
                                    <textarea id="snow-editor"
                                        class="form-control @error('pemantauanTinjauan_pemantauanDesc') is-invalid @enderror {{ $pemantauanTinjauan_pemantauanDesc ? 'is-valid' : '' }}"
                                        wire:model='pemantauanTinjauan_pemantauanDesc' aria-label="With textarea" cols="20" rows="10"
                                        {{ $isShowPemantauanTinjauan ? 'disabled' : '' }}></textarea>
                                    <span
                                        class="invalid-feedback">{{ $errors->first('pemantauanTinjauan_pemantauanDesc') }}</span>
                                </div>
                            </div> <!-- end col -->
                            <div class="col-md-12">
                                @if ($isEditPemantauanTinjauan)
                                    <div class="row">
                                        <!-- Bukti Tinjauan Input -->
                                        <div class="col-md-8">
                                            <div class="mb-3">
                                                <label class="form-label"
                                                    for="formrow-password_confirmation-input">Bukti
                                                    Pemantauan
                                                    <span style="color: red">*</span></label>
                                                <input type="file"
                                                    class="form-control @error('pemantauanTinjauan_buktiPemantauan') is-invalid @enderror {{ $pemantauanTinjauan_buktiPemantauan ? 'is-valid' : '' }}"
                                                    wire:model='pemantauanTinjauan_buktiPemantauan'>
                                                <span
                                                    class="invalid-feedback">{{ $errors->first('pemantauanTinjauan_buktiPemantauan') }}</span>
                                            </div>
                                        </div>

                                        <!-- Lihat Bukti Button -->
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="lihat-bukti">
                                                    Lihat Bukti
                                                </label>
                                                @if ($pemantauanTinjauan_buktiPemantauan || isset($pemantauanTinjauan_buktiPemantauan))
                                                    @php
                                                        $decryptedBuktiPemantauan = $pemantauanTinjauan_buktiPemantauan
                                                            ? Crypt::decrypt($pemantauanTinjauan_buktiPemantauan)
                                                            : null;
                                                    @endphp

                                                    <div>
                                                        <a href="{{ asset('storage/' . $decryptedBuktiPemantauan) }}"
                                                            target="_blank">
                                                            <button class="btn btn-primary">Lihat Bukti</button>
                                                        </a>
                                                    </div>
                                                @else
                                                    <p class="text-muted">No document uploaded</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="mb-3">
                                        <label class="form-label" for="formrow-password_confirmation-input">Bukti
                                            Pemantauan
                                            <span style="color: red">*</span></label>
                                        <input type="file"
                                            class="form-control @error('pemantauanTinjauan_buktiPemantauan') is-invalid @enderror {{ $pemantauanTinjauan_buktiPemantauan ? 'is-valid' : '' }}"
                                            wire:model='pemantauanTinjauan_buktiPemantauan'>
                                        <span
                                            class="invalid-feedback">{{ $errors->first('pemantauanTinjauan_buktiPemantauan') }}</span>
                                    </div>
                                @endif
                            </div> <!-- end col -->
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-firstname-input">Frekuensi laporan /tahun
                                        <span style="color: red">*</span></label>
                                    <select wire:model='pemantauanTinjauan_freqPelaporan'
                                        {{ $isShowPemantauanTinjauan ? 'disabled' : '' }}
                                        class="form-control @error('pemantauanTinjauan_freqPelaporan') is-invalid @enderror {{ $pemantauanTinjauan_freqPelaporan ? 'is-valid' : '' }}">
                                        <option value="">-- Pilih Frekuensi --</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="10">10</option>
                                    </select>
                                    <span
                                        class="invalid-feedback">{{ $errors->first('pemantauanTinjauan_freqPemantauan') }}</span>
                                </div>
                            </div> <!-- end col -->
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-password_confirmation-input">Tinjauan
                                        <span style="color: red">*</span></label>
                                    <textarea id="snow-editor"
                                        class="form-control @error('pemantauanTinjauan_tinjauanDesc') is-invalid @enderror {{ $pemantauanTinjauan_tinjauanDesc ? 'is-valid' : '' }}"
                                        wire:model='pemantauanTinjauan_tinjauanDesc' aria-label="With textarea" cols="20" rows="10"
                                        {{ $isShowPemantauanTinjauan ? 'disabled' : '' }}></textarea>
                                    <span
                                        class="invalid-feedback">{{ $errors->first('pemantauanTinjauan_tinjauanDesc') }}</span>
                                </div>
                            </div> <!-- end col -->
                            <div class="col-md-12">
                                @if ($isEditPemantauanTinjauan)
                                    <div class="row">
                                        <!-- Bukti Tinjauan Input -->
                                        <div class="col-md-8">
                                            <div class="mb-3">
                                                <label class="form-label" for="pemantauanTinjauan_buktiTinjauan">
                                                    Bukti Tinjauan <span style="color: red">*</span>
                                                </label>
                                                <input type="file"
                                                    class="form-control @error('pemantauanTinjauan_buktiTinjauan') is-invalid @enderror {{ $pemantauanTinjauan_buktiTinjauan ? 'is-valid' : '' }}"
                                                    wire:model='pemantauanTinjauan_buktiTinjauan'>
                                                <span
                                                    class="invalid-feedback">{{ $errors->first('pemantauanTinjauan_buktiTinjauan') }}</span>
                                            </div>
                                        </div>

                                        <!-- Lihat Bukti Button -->
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="lihat-bukti">
                                                    Lihat Bukti
                                                </label>
                                                @if ($pemantauanTinjauan_buktiTinjauan || isset($pemantauanTinjauan_buktiTinjauan))
                                                    @php
                                                        $decryptedBuktiTinjauan = $pemantauanTinjauan_buktiTinjauan
                                                            ? Crypt::decrypt($pemantauanTinjauan_buktiTinjauan)
                                                            : null;
                                                    @endphp

                                                    <div>
                                                        <a href="{{ asset('storage/' . $decryptedBuktiTinjauan) }}"
                                                            target="_blank">
                                                            <button type="button" class="btn btn-primary">Lihat
                                                                Bukti</button>
                                                        </a>
                                                    </div>
                                                @else
                                                    <p class="text-muted">No document uploaded</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="mb-3">
                                        <label class="form-label" for="formrow-password_confirmation-input">Bukti
                                            Tinjauan
                                            <span style="color: red">*</span></label>
                                        <input type="file"
                                            class="form-control @error('pemantauanTinjauan_buktiTinjauan') is-invalid @enderror {{ $pemantauanTinjauan_buktiTinjauan ? 'is-valid' : '' }}"
                                            wire:model='pemantauanTinjauan_buktiTinjauan'>
                                        <span
                                            class="invalid-feedback">{{ $errors->first('pemantauanTinjauan_buktiTinjauan') }}</span>
                                    </div>
                                @endif
                            </div> <!-- end col -->
                        </div>
                    @else
                        <dt class="mb-2">Pemantauan dan Tinjauan</dt>
                        <div class="table-responsive mb-2">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width: 500px;">Pemantauan</th>
                                        <th style="width: 500px;">Tinjauan</th>
                                        <th style="width: 100px;">Frequensi Pemantauan</th>
                                        <th style="width: 100px;">Frequensi Pelaporan</th>
                                        <th style="width: 100px;">Bukti Pemantauan</th>
                                        <th style="width: 150px;">Bukti Tinjauan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="word-wrap: break-word;">
                                            {{ ucfirst($pemantauanTinjauan_pemantauanDesc) }}
                                        </td>
                                        <td style="word-wrap: break-word;">
                                            {{ ucfirst($pemantauanTinjauan_tinjauanDesc) }}
                                        </td>
                                        <td style="word-wrap: break-word;">
                                            {{ ucfirst($pemantauanTinjauan_freqPemantauan) }}
                                        </td>
                                        <td style="word-wrap: break-word;">
                                            {{ ucfirst($pemantauanTinjauan_freqPelaporan) }}
                                        </td>
                                        <td style="word-wrap: break-word;">
                                            @if ($pemantauanTinjauan_buktiPemantauan || isset($pemantauanTinjauan_buktiPemantauan))
                                                @php
                                                    $decryptedBuktiPemantauan = $pemantauanTinjauan_buktiPemantauan
                                                        ? Crypt::decrypt($pemantauanTinjauan_buktiPemantauan)
                                                        : null;
                                                @endphp

                                                <div>
                                                    <a href="{{ asset('storage/' . $decryptedBuktiPemantauan) }}"
                                                        target="_blank">
                                                        <button class="btn btn-primary">Lihat Bukti</button>
                                                    </a>
                                                </div>
                                            @else
                                                <p class="text-muted">No document uploaded</p>
                                            @endif
                                        </td>
                                        <td style="word-wrap: break-word;">
                                            @if ($pemantauanTinjauan_buktiTinjauan || isset($pemantauanTinjauan_buktiTinjauan))
                                                @php
                                                    $decryptedBuktiTinjauan = $pemantauanTinjauan_buktiTinjauan
                                                        ? Crypt::decrypt($pemantauanTinjauan_buktiTinjauan)
                                                        : null;
                                                @endphp

                                                <div>
                                                    <a href="{{ asset('storage/' . $decryptedBuktiTinjauan) }}"
                                                        target="_blank">
                                                        <button type="button" class="btn btn-primary">Lihat
                                                            Bukti</button>
                                                    </a>
                                                </div>
                                            @else
                                                <p class="text-muted">No document uploaded</p>
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    @endif

                    <div class="mt-1 border-top mb-3"></div>
                    <div class="d-flex justify-content-between">
                        <div class="">
                            @if ($isEditPemantauanTinjauan)
                                <button type="button" class="btn btn-danger"
                                    wire:click='openModalConfirmDeletePemantauanTinjauan({{ $controlRisk_id }})'
                                    wire:loading.attr="disabled"
                                    wire:target="openModalConfirmDeletePemantauanTinjauan({{ $controlRisk_id }})">
                                    Delete Pemantauan Tinjauan
                                    <span wire:loading class="ms-2"
                                        wire:target="openModalConfirmDeletePemantauanTinjauan({{ $controlRisk_id }})">
                                        <span class="spinner-border spinner-border-sm" role="status"
                                            aria-hidden="true"></span>
                                    </span>
                                </button>
                            @endif
                        </div>
                        <div class="">
                            <button type="button" class="btn btn-secondary"
                                wire:click='closeModalPemantauanTinjauan' wire:loading.attr="disabled"
                                wire:target="closeModalPemantauanTinjauan">
                                Close
                                <span wire:loading class="ms-2" wire:target="closeModalPemantauanTinjauan">
                                    <span class="spinner-border spinner-border-sm" role="status"
                                        aria-hidden="true"></span>
                                </span>
                            </button>
                            @if (!$isShowPemantauanTinjauan || $isEditPemantauanTinjauan)
                                <button type="button" wire:click.prevent='storePemantauanTinjauan'
                                    wire:loading.attr="disabled" wire:target="storePemantauanTinjauan"
                                    class="btn btn-primary w-md waves-effect waves-light">Submit
                                    <span wire:loading class="ms-2" wire:target="storePemantauanTinjauan">
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



@if ($isOpenConfirmDeletePemantauanTinjauan)
    <div class="modal" tabindex="-1" role="dialog" style="display: block;" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Menunggu Konfirmasi</h5>
                    <button type="button" class="btn-close" aria-label="Close"
                        wire:click="closeXModalConfirmDeletePemantauanTinjauan"></button>
                </div>
                <div class="modal-body">
                    Data tidak bisa dikembalikan apabila Anda menghapus Pemantauan dan Tinjauan. Apakah Anda yakin ingin menghapus
                    Pemantauan dan Tinjauan ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click='closeModalConfirmDeletePemantauanTinjauan'
                        wire:loading.attr="disabled" wire:target="closeModalConfirmDeletePemantauanTinjauan">
                        Tutup
                        <span wire:loading class="ms-2" wire:target="closeModalConfirmDeletePemantauanTinjauan">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        </span>
                    </button>
                    <button type="button" class="btn btn-danger" wire:click="deletePemantauanTinjauan"
                        wire:loading.attr="disabled" wire:target="deletePemantauanTinjauan">
                        Hapus Pemantauan dan Tinjauan
                        <span wire:loading class="ms-2" wire:target="deletePemantauanTinjauan">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        </span>
                    </button>
                </div>
            </div>
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <div class="modal-backdrop fade show"></div>
@endif