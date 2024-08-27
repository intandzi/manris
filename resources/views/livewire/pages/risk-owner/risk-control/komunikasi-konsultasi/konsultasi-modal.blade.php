@if ($isOpenKonsultasi)
    <div class="modal" tabindex="-1" role="dialog" style="display: block;" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0">Konsultasi Form</h5>
                    <button type="button" class="btn-close" wire:click='closeXModalKonsultasi' data-bs-dismiss="modal"
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
                    </div>
                    @if ($isEditKonsultasi || !$isShowKonsultasi)
                        <dt>Pengelolaan Konsultasi</dt>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-firstname-input">Pemangku Kepentingan
                                        <span style="color: red">*</span></label>
                                    <div class="relative">
                                        <div class="input-group">
                                            <input type="text" wire:model.live="searchStakeholderKonsultasi"
                                                placeholder="Search Pemangku Kepentingan..."
                                                wire:focus.live="activateSearchStakeholderKonsultasi"
                                                wire:blur.live="deactivateSearchStakeholderKonsultasi"
                                                class="form-control @error('konsultasi_stakeholder') is-invalid @enderror">
                                            @error('konsultasi_stakeholder')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        @if ($searchActiveStakeholderKonsultasi)
                                            <!-- Display results only if search is active -->
                                            <div
                                                style="position:absolute; z-index:100; background-color:white; width:96%; max-height:200px; overflow-y:auto;">
                                                <ul class="list-group">
                                                    @forelse ($searchResultsStakeholderKonsultasi as $item)
                                                        <li wire:click="select_stakeholderKonsultasi({{ $item->stakeholder_id }})"
                                                            class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                                                            style="cursor:pointer;">
                                                            {{ ucwords($item->stakeholder_jabatan) }} -
                                                            {{ ucwords($item->stakeholder_singkatan) }}
                                                            <button type="button"
                                                                wire:click.stop="select_stakeholderKonsultasi({{ $item->stakeholder_id }})"
                                                                class="btn btn-primary btn-sm">
                                                                Add
                                                            </button>
                                                        </li>
                                                    @empty
                                                        <li class="list-group-item">Found nothing...</li>
                                                    @endforelse
                                                </ul>
                                            </div>
                                        @endif

                                        <p><small>Ketikkan singkatan/jabatan pemangku kepentingan.</small></p>

                                        @if (count($konsultasi_stakeholder) > 0)
                                            <ul class="list-group mt-3">
                                                <h5>Pemangku Kepentingan</h5>
                                                @foreach ($konsultasi_stakeholder as $index => $R)
                                                    <li
                                                        class="list-group-item d-flex justify-content-between align-items-center">
                                                        {{ $R['stakeholder_jabatan'] }} -
                                                        {{ $R['stakeholder_singkatan'] }}
                                                        <button type="button"
                                                            wire:click.prevent='remove_stakeholderKonsultasi({{ $index }})'
                                                            wire:loading.attr="disabled"
                                                            wire:target="remove_stakeholderKonsultasi({{ $index }})"
                                                            class="btn btn-danger btn-sm w-md waves-effect waves-light">Hapus
                                                            <span wire:loading class="ms-2"
                                                                wire:target="remove_stakeholderKonsultasi({{ $index }})">
                                                                <span class="spinner-border spinner-border-sm"
                                                                    role="status" aria-hidden="true"></span>
                                                            </span>
                                                        </button>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                        <!-- Duplicate stakeholder error -->
                                        @if (session()->has('konsultasi_stakeholder_duplicate'))
                                            <div class="alert alert-danger">
                                                {{ session('konsultasi_stakeholder_duplicate') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div> <!-- end col -->
                            <hr>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-firstname-input">Disiapkan Oleh
                                        <span style="color: red">*</span></label>
                                    <div class="relative">
                                        <div class="input-group">
                                            <input type="text" wire:model.live="searchFasilKonsultasi"
                                                placeholder="Search Disiapkan Oleh..."
                                                wire:focus.live="activateSearchFasilKonsultasi"
                                                wire:blur.live="deactivateSearchFasilKonsultasi"
                                                class="form-control @error('konsultasi_fasil') is-invalid @enderror">
                                            <span
                                                class="invalid-feedback">{{ $errors->first('konsultasi_fasil') }}</span>
                                        </div>
                                        @if ($searchActiveFasilKonsultasi)
                                            <!-- Display results only if search is active -->
                                            <div
                                                style="position:absolute; z-index:100; background-color:white; width:96%; max-height:200px; overflow-y:auto;">
                                                <ul class="list-group">
                                                    @forelse ($searchResultsFasilKonsultasi as $item)
                                                        <li wire:click='select_fasilKonsultasi({{ $item->stakeholder_id }})'
                                                            class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                                                            style="cursor: pointer;">
                                                            {{ ucwords($item->stakeholder_jabatan) }} -
                                                            {{ ucwords($item->stakeholder_singkatan) }}
                                                            <button type="button"
                                                                wire:click="select_fasilKonsultasi({{ $item->stakeholder_id }})"
                                                                class="btn btn-primary btn-sm">Add</button>
                                                        </li>
                                                    @empty
                                                        <li class="list-group-item">Found nothing...</li>
                                                    @endforelse
                                                </ul>
                                            </div>
                                        @endif

                                        <p><small>Ketikkan singkatan/jabatan pemangku kepentingan.</small></p>

                                        @if (count($konsultasi_fasil) > 0)
                                            <ul class="list-group mt-3">
                                                <h5>Disiapkan Oleh</h5>
                                                @foreach ($konsultasi_fasil as $index => $R)
                                                    <li
                                                        class="list-group-item d-flex justify-content-between align-items-center">
                                                        {{ $R['stakeholder_jabatan'] }} -
                                                        {{ $R['stakeholder_singkatan'] }}
                                                        <button type="button"
                                                            wire:click.prevent='remove_fasilKonsultasi({{ $index }})'
                                                            wire:loading.attr="disabled"
                                                            wire:target="remove_fasilKonsultasi({{ $index }})"
                                                            class="btn btn-danger btn-sm w-md waves-effect waves-light">Hapus
                                                            <span wire:loading class="ms-2"
                                                                wire:target="remove_fasilKonsultasi({{ $index }})">
                                                                <span class="spinner-border spinner-border-sm"
                                                                    role="status" aria-hidden="true"></span>
                                                            </span>
                                                        </button>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                        <!-- Duplicate stakeholder error -->
                                        @if (session()->has('konsultasi_fasil_duplicate'))
                                            <div class="alert alert-danger">
                                                {{ session('konsultasi_fasil_duplicate') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div> <!-- end col -->
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-firstname-input">Tujuan
                                        <span style="color: red">*</span></label>
                                    <textarea id="snow-editor"
                                        class="form-control @error('konsultasi_tujuan') is-invalid @enderror {{ $konsultasi_tujuan ? 'is-valid' : '' }}"
                                        wire:model='konsultasi_tujuan' aria-label="With textarea" cols="20" rows="10"></textarea>
                                    <span class="invalid-feedback">{{ $errors->first('konsultasi_tujuan') }}</span>
                                </div>
                            </div> <!-- end col -->
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-firstname-input">Konten
                                        <span style="color: red">*</span></label>
                                    <textarea id="snow-editor"
                                        class="form-control @error('konsultasi_konten') is-invalid @enderror {{ $konsultasi_konten ? 'is-valid' : '' }}"
                                        wire:model='konsultasi_konten' aria-label="With textarea" cols="20" rows="10"></textarea>
                                    <span class="invalid-feedback">{{ $errors->first('konsultasi_konten') }}</span>
                                </div>
                            </div> <!-- end col -->
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-firstname-input">Media
                                        <span style="color: red">*</span></label>
                                    <textarea id="snow-editor"
                                        class="form-control @error('konsultasi_media') is-invalid @enderror {{ $konsultasi_media ? 'is-valid' : '' }}"
                                        wire:model='konsultasi_media' aria-label="With textarea" cols="20" rows="10"></textarea>
                                    <span class="invalid-feedback">{{ $errors->first('konsultasi_media') }}</span>
                                </div>
                            </div> <!-- end col -->
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-firstname-input">Metode
                                        <span style="color: red">*</span></label>
                                    <textarea id="snow-editor"
                                        class="form-control @error('konsultasi_metode') is-invalid @enderror {{ $konsultasi_metode ? 'is-valid' : '' }}"
                                        wire:model='konsultasi_metode' aria-label="With textarea" cols="20" rows="10"></textarea>
                                    <span class="invalid-feedback">{{ $errors->first('konsultasi_metode') }}</span>
                                </div>
                            </div> <!-- end col -->
                        </div>
                    @else
                        <dt class="mb-2">Pengelolaan Konsultasi</dt>
                        <div class="table-responsive mb-2">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width: 400px;">Pemangku Kepentingan</th>
                                        <th style="width: 400px;">Disiapkan Oleh</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="word-wrap: break-word;">
                                            @foreach ($konsultasi_stakeholder as $stakeholder)
                                                {{ $stakeholder['stakeholder_jabatan'] }}
                                                ({{ $stakeholder['stakeholder_singkatan'] }}),
                                            @endforeach
                                        </td>
                                        <td style="word-wrap: break-word;">
                                            @foreach ($konsultasi_fasil as $fasil)
                                                {{ $fasil['stakeholder_jabatan'] }}
                                                ({{ $fasil['stakeholder_singkatan'] }}),
                                            @endforeach
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <table>
                                <tr>
                                    <td style="width: 200px; vertical-align: top;">
                                        <p class="card-text me-4" style="font-weight: bold">Tujuan
                                        </p>
                                    </td>
                                    <td style="vertical-align: top;">:</td>
                                    <td style="width: calc(100% - 250px); vertical-align: bottom;">
                                        <p class="card-text" style="word-wrap: break-word;">{{ $konsultasi_tujuan }}
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 200px; vertical-align: top;">
                                        <p class="card-text me-4" style="font-weight: bold">Konten
                                        </p>
                                    </td>
                                    <td style="vertical-align: top;">:</td>
                                    <td style="width: calc(100% - 250px); vertical-align: bottom;">
                                        <p class="card-text" style="word-wrap: break-word;">{{ $konsultasi_konten }}
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 200px; vertical-align: top;">
                                        <p class="card-text me-4" style="font-weight: bold">Media
                                        </p>
                                    </td>
                                    <td style="vertical-align: top;">:</td>
                                    <td style="width: calc(100% - 250px); vertical-align: bottom;">
                                        <p class="card-text" style="word-wrap: break-word;">{{ $konsultasi_media }}
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 200px; vertical-align: top;">
                                        <p class="card-text me-4" style="font-weight: bold">Metode
                                        </p>
                                    </td>
                                    <td style="vertical-align: top;">:</td>
                                    <td style="width: calc(100% - 250px); vertical-align: bottom;">
                                        <p class="card-text" style="word-wrap: break-word;">{{ $konsultasi_metode }}
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    @endif

                    <div class="mt-1 border-top mb-3"></div>
                    <div class="d-flex justify-content-between">
                        <div class="">
                            @if ($isEditKonsultasi)
                                <button type="button" class="btn btn-danger"
                                    wire:click='openModalConfirmDeleteKonsultasi({{ $konsultasi_id }})'
                                    wire:loading.attr="disabled"
                                    wire:target="openModalConfirmDeleteKonsultasi({{ $konsultasi_id }})">
                                    Delete Konsultasi
                                    <span wire:loading class="ms-2"
                                        wire:target="openModalConfirmDeleteKonsultasi({{ $konsultasi_id }})">
                                        <span class="spinner-border spinner-border-sm" role="status"
                                            aria-hidden="true"></span>
                                    </span>
                                </button>
                            @endif
                        </div>
                        <div class="">
                            <button type="button" class="btn btn-secondary" wire:click='closeModalKonsultasi'
                                wire:loading.attr="disabled" wire:target="closeModalKonsultasi">
                                Close
                                <span wire:loading class="ms-2" wire:target="closeModalKonsultasi">
                                    <span class="spinner-border spinner-border-sm" role="status"
                                        aria-hidden="true"></span>
                                </span>
                            </button>
                            @if (!$isShowKonsultasi || $isEditKonsultasi)
                                <button type="button" wire:click.prevent='storeKonsultasi'
                                    wire:loading.attr="disabled" wire:target="storeKonsultasi"
                                    class="btn btn-primary w-md waves-effect waves-light">Submit
                                    <span wire:loading class="ms-2" wire:target="storeKonsultasi">
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



@if ($isOpenConfirmDeleteKonsultasi)
    <div class="modal" tabindex="-1" role="dialog" style="display: block;" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Menunggu Konfirmasi</h5>
                    <button type="button" class="btn-close" aria-label="Close"
                        wire:click="closeXModalConfirmDeleteKonsultasi"></button>
                </div>
                <div class="modal-body">
                    Data tidak bisa dikembalikan apabila Anda menghapus Konsultasi. Apakah Anda yakin ingin menghapus
                    Konsultasi ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click='closeModalConfirmDeleteKonsultasi'
                        wire:loading.attr="disabled" wire:target="closeModalConfirmDeleteKonsultasi">
                        Tutup
                        <span wire:loading class="ms-2" wire:target="closeModalConfirmDeleteKonsultasi">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        </span>
                    </button>
                    <button type="button" class="btn btn-danger" wire:click="deleteKonsultasi"
                        wire:loading.attr="disabled" wire:target="deleteKonsultasi">
                        Hapus Konsultasi
                        <span wire:loading class="ms-2" wire:target="deleteKonsultasi">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        </span>
                    </button>
                </div>
            </div>
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <div class="modal-backdrop fade show"></div>
@endif
