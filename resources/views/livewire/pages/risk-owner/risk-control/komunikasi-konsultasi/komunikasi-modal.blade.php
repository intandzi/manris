@if ($isOpenKomunikasi)
    <div class="modal" tabindex="-1" role="dialog" style="display: block;" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0">Komunikasi Form</h5>
                    <button type="button" class="btn-close" wire:click='closeXModalKomunikasi' data-bs-dismiss="modal"
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
                    @if ($isEditKomunikasi || !$isShowKomunikasi)
                        <dt>Pengelolaan Komunikasi</dt>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-firstname-input">Pemangku Kepentingan
                                        <span style="color: red">*</span></label>
                                    <div class="relative">
                                        <div class="input-group">
                                            <input type="text" wire:model.live="searchStakeholder"
                                                placeholder="Search Pemangku Kepentingan..."
                                                wire:focus.live="activateSearchStakeholder"
                                                wire:blur.live="deactivateSearchStakeholder"
                                                class="form-control @error('komunikasi_stakeholder') is-invalid @enderror">
                                            @error('komunikasi_stakeholder')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        @if ($searchActiveStakeholder)
                                            <!-- Display results only if search is active -->
                                            <div
                                                style="position:absolute; z-index:100; background-color:white; width:96%; max-height:200px; overflow-y:auto;">
                                                <ul class="list-group">
                                                    @forelse ($searchResultsStakeholder as $item)
                                                        <li wire:click="select_stakeholder({{ $item->stakeholder_id }})"
                                                            class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                                                            style="cursor:pointer;">
                                                            {{ ucwords($item->stakeholder_jabatan) }} -
                                                            {{ ucwords($item->stakeholder_singkatan) }}
                                                            <button type="button"
                                                                wire:click.stop="select_stakeholder({{ $item->stakeholder_id }})"
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

                                        @if (count($komunikasi_stakeholder) > 0)
                                            <ul class="list-group mt-3">
                                                <h5>Pemangku Kepentingan</h5>
                                                @foreach ($komunikasi_stakeholder as $index => $R)
                                                    <li
                                                        class="list-group-item d-flex justify-content-between align-items-center">
                                                        {{ $R['stakeholder_jabatan'] }} -
                                                        {{ $R['stakeholder_singkatan'] }}
                                                        <button type="button"
                                                            wire:click.prevent='remove_stakeholder({{ $index }})'
                                                            wire:loading.attr="disabled"
                                                            wire:target="remove_stakeholder({{ $index }})"
                                                            class="btn btn-danger btn-sm w-md waves-effect waves-light">Hapus
                                                            <span wire:loading class="ms-2"
                                                                wire:target="remove_stakeholder({{ $index }})">
                                                                <span class="spinner-border spinner-border-sm"
                                                                    role="status" aria-hidden="true"></span>
                                                            </span>
                                                        </button>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                        <!-- Duplicate stakeholder error -->
                                        @if (session()->has('komunikasi_stakeholder_duplicate'))
                                            <div class="alert alert-danger">
                                                {{ session('komunikasi_stakeholder_duplicate') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div> <!-- end col -->
                            <hr>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-firstname-input">Perantara
                                        <span style="color: red">*</span></label>
                                    <div class="relative">
                                        <div class="input-group">
                                            <input type="text" wire:model.live="searchPerantara"
                                                placeholder="Search Perantara..."
                                                wire:focus.live="activateSearchPerantara"
                                                wire:blur.live="deactivateSearchPerantara"
                                                class="form-control @error('komunikasi_perantara') is-invalid @enderror">
                                            <span
                                                class="invalid-feedback">{{ $errors->first('komunikasi_perantara') }}</span>
                                            <!-- Main komunikasi_perantara validation error -->
                                            {{-- @error('komunikasi_perantara')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror --}}
                                        </div>
                                        @if ($searchActivePerantara)
                                            <!-- Display results only if search is active -->
                                            <div
                                                style="position:absolute; z-index:100; background-color:white; width:96%; max-height:200px; overflow-y:auto;">
                                                <ul class="list-group">
                                                    @forelse ($searchResultsPerantara as $item)
                                                        <li wire:click='select_perantara({{ $item->stakeholder_id }})'
                                                            class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                                                            style="cursor: pointer;">
                                                            {{ ucwords($item->stakeholder_jabatan) }} -
                                                            {{ ucwords($item->stakeholder_singkatan) }}
                                                            <button type="button"
                                                                wire:click="select_perantara({{ $item->stakeholder_id }})"
                                                                class="btn btn-primary btn-sm">Add</button>
                                                        </li>
                                                    @empty
                                                        <li class="list-group-item">Found nothing...</li>
                                                    @endforelse
                                                </ul>
                                            </div>
                                        @endif

                                        <p><small>Ketikkan singkatan/jabatan pemangku kepentingan.</small></p>

                                        @if (count($komunikasi_perantara) > 0)
                                            <ul class="list-group mt-3">
                                                <h5>Perantara</h5>
                                                @foreach ($komunikasi_perantara as $index => $R)
                                                    <li
                                                        class="list-group-item d-flex justify-content-between align-items-center">
                                                        {{ $R['stakeholder_jabatan'] }} -
                                                        {{ $R['stakeholder_singkatan'] }}
                                                        <button type="button"
                                                            wire:click.prevent='remove_perantara({{ $index }})'
                                                            wire:loading.attr="disabled"
                                                            wire:target="remove_perantara({{ $index }})"
                                                            class="btn btn-danger btn-sm w-md waves-effect waves-light">Hapus
                                                            <span wire:loading class="ms-2"
                                                                wire:target="remove_perantara({{ $index }})">
                                                                <span class="spinner-border spinner-border-sm"
                                                                    role="status" aria-hidden="true"></span>
                                                            </span>
                                                        </button>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                        <!-- Duplicate stakeholder error -->
                                        @if (session()->has('komunikasi_perantara_duplicate'))
                                            <div class="alert alert-danger">
                                                {{ session('komunikasi_perantara_duplicate') }}
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
                                            <input type="text" wire:model.live="searchFasil"
                                                placeholder="Search Disiapkan Oleh..."
                                                wire:focus.live="activateSearchFasil"
                                                wire:blur.live="deactivateSearchFasil"
                                                class="form-control @error('komunikasi_fasil') is-invalid @enderror">
                                            <span
                                                class="invalid-feedback">{{ $errors->first('komunikasi_fasil') }}</span>
                                            <!-- Main komunikasi_fasil validation error -->
                                            {{-- @error('komunikasi_fasil')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror --}}
                                        </div>
                                        @if ($searchActiveFasil)
                                            <!-- Display results only if search is active -->
                                            <div
                                                style="position:absolute; z-index:100; background-color:white; width:96%; max-height:200px; overflow-y:auto;">
                                                <ul class="list-group">
                                                    @forelse ($searchResultsFasil as $item)
                                                        <li wire:click='select_fasil({{ $item->stakeholder_id }})'
                                                            class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                                                            style="cursor: pointer;">
                                                            {{ ucwords($item->stakeholder_jabatan) }} -
                                                            {{ ucwords($item->stakeholder_singkatan) }}
                                                            <button type="button"
                                                                wire:click="select_fasil({{ $item->stakeholder_id }})"
                                                                class="btn btn-primary btn-sm">Add</button>
                                                        </li>
                                                    @empty
                                                        <li class="list-group-item">Found nothing...</li>
                                                    @endforelse
                                                </ul>
                                            </div>
                                        @endif

                                        <p><small>Ketikkan singkatan/jabatan pemangku kepentingan.</small></p>

                                        @if (count($komunikasi_fasil) > 0)
                                            <ul class="list-group mt-3">
                                                <h5>Disiapkan Oleh</h5>
                                                @foreach ($komunikasi_fasil as $index => $R)
                                                    <li
                                                        class="list-group-item d-flex justify-content-between align-items-center">
                                                        {{ $R['stakeholder_jabatan'] }} -
                                                        {{ $R['stakeholder_singkatan'] }}
                                                        <button type="button"
                                                            wire:click.prevent='remove_fasil({{ $index }})'
                                                            wire:loading.attr="disabled"
                                                            wire:target="remove_fasil({{ $index }})"
                                                            class="btn btn-danger btn-sm w-md waves-effect waves-light">Hapus
                                                            <span wire:loading class="ms-2"
                                                                wire:target="remove_fasil({{ $index }})">
                                                                <span class="spinner-border spinner-border-sm"
                                                                    role="status" aria-hidden="true"></span>
                                                            </span>
                                                        </button>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                        <!-- Duplicate stakeholder error -->
                                        @if (session()->has('komunikasi_fasil_duplicate'))
                                            <div class="alert alert-danger">
                                                {{ session('komunikasi_fasil_duplicate') }}
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
                                        class="form-control @error('komunikasi_tujuan') is-invalid @enderror {{ $komunikasi_tujuan ? 'is-valid' : '' }}"
                                        wire:model='komunikasi_tujuan' aria-label="With textarea" cols="20" rows="10"></textarea>
                                    <span class="invalid-feedback">{{ $errors->first('komunikasi_tujuan') }}</span>
                                </div>
                            </div> <!-- end col -->
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-firstname-input">Konten
                                        <span style="color: red">*</span></label>
                                    <textarea id="snow-editor"
                                        class="form-control @error('komunikasi_konten') is-invalid @enderror {{ $komunikasi_konten ? 'is-valid' : '' }}"
                                        wire:model='komunikasi_konten' aria-label="With textarea" cols="20" rows="10"></textarea>
                                    <span class="invalid-feedback">{{ $errors->first('komunikasi_konten') }}</span>
                                </div>
                            </div> <!-- end col -->
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-firstname-input">Media
                                        <span style="color: red">*</span></label>
                                    <textarea id="snow-editor"
                                        class="form-control @error('komunikasi_media') is-invalid @enderror {{ $komunikasi_media ? 'is-valid' : '' }}"
                                        wire:model='komunikasi_media' aria-label="With textarea" cols="20" rows="10"></textarea>
                                    <span class="invalid-feedback">{{ $errors->first('komunikasi_media') }}</span>
                                </div>
                            </div> <!-- end col -->
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-firstname-input">Metode
                                        <span style="color: red">*</span></label>
                                    <textarea id="snow-editor"
                                        class="form-control @error('komunikasi_metode') is-invalid @enderror {{ $komunikasi_metode ? 'is-valid' : '' }}"
                                        wire:model='komunikasi_metode' aria-label="With textarea" cols="20" rows="10"></textarea>
                                    <span class="invalid-feedback">{{ $errors->first('komunikasi_metode') }}</span>
                                </div>
                            </div> <!-- end col -->
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-firstname-input">Pemilihan Waktu
                                        <span style="color: red">*</span></label>
                                    <textarea id="snow-editor"
                                        class="form-control @error('komunikasi_pemilihanWaktu') is-invalid @enderror {{ $komunikasi_pemilihanWaktu ? 'is-valid' : '' }}"
                                        wire:model='komunikasi_pemilihanWaktu' aria-label="With textarea" cols="20" rows="10"></textarea>
                                    <span
                                        class="invalid-feedback">{{ $errors->first('komunikasi_pemilihanWaktu') }}</span>
                                </div>
                            </div> <!-- end col -->
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-firstname-input">Frekuensi
                                        <span style="color: red">*</span></label>
                                    <textarea id="snow-editor"
                                        class="form-control @error('komunikasi_frekuensi') is-invalid @enderror {{ $komunikasi_frekuensi ? 'is-valid' : '' }}"
                                        wire:model='komunikasi_frekuensi' aria-label="With textarea" cols="20" rows="10"></textarea>
                                    <span class="invalid-feedback">{{ $errors->first('komunikasi_frekuensi') }}</span>
                                </div>
                            </div> <!-- end col -->
                        </div>
                    @else
                        <dt class="mb-2">Pengelolaan Komunikasi</dt>
                        <div class="table-responsive mb-2">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width: 400px;">Pemangku Kepentingan</th>
                                        <th style="width: 400px;">Perantara</th>
                                        <th style="width: 400px;">Disiapkan Oleh</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="word-wrap: break-word;">
                                            @foreach ($komunikasi_stakeholder as $stakeholder)
                                                {{ $stakeholder['stakeholder_jabatan'] }}
                                                ({{ $stakeholder['stakeholder_singkatan'] }}),
                                            @endforeach
                                        </td>
                                        <td style="word-wrap: break-word;">
                                            @foreach ($komunikasi_perantara as $perantara)
                                                {{ $perantara['stakeholder_jabatan'] }}
                                                ({{ $perantara['stakeholder_singkatan'] }}),
                                            @endforeach
                                        </td>
                                        <td style="word-wrap: break-word;">
                                            @foreach ($komunikasi_fasil as $fasil)
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
                                        <p class="card-text" style="word-wrap: break-word;">{{ $komunikasi_tujuan }}
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
                                        <p class="card-text" style="word-wrap: break-word;">{{ $komunikasi_konten }}
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
                                        <p class="card-text" style="word-wrap: break-word;">{{ $komunikasi_media }}
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
                                        <p class="card-text" style="word-wrap: break-word;">{{ $komunikasi_metode }}
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 200px; vertical-align: top;">
                                        <p class="card-text me-4" style="font-weight: bold">Pemilihan Waktu
                                        </p>
                                    </td>
                                    <td style="vertical-align: top;">:</td>
                                    <td style="width: calc(100% - 250px); vertical-align: bottom;">
                                        <p class="card-text" style="word-wrap: break-word;">{{ $komunikasi_pemilihanWaktu }}
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 200px; vertical-align: top;">
                                        <p class="card-text me-4" style="font-weight: bold">Frekuensi
                                        </p>
                                    </td>
                                    <td style="vertical-align: top;">:</td>
                                    <td style="width: calc(100% - 250px); vertical-align: bottom;">
                                        <p class="card-text" style="word-wrap: break-word;">{{ $komunikasi_frekuensi }}
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    @endif

                    <div class="mt-1 border-top mb-3"></div>
                    <div class="d-flex justify-content-between">
                        <div class="">
                            @if ($isEditKomunikasi)
                                <button type="button" class="btn btn-danger"
                                    wire:click='openModalConfirmDeleteKomunikasi({{ $komunikasi_id }})'
                                    wire:loading.attr="disabled"
                                    wire:target="openModalConfirmDeleteKomunikasi({{ $komunikasi_id }})">
                                    Delete Komunikasi
                                    <span wire:loading class="ms-2"
                                        wire:target="openModalConfirmDeleteKomunikasi({{ $komunikasi_id }})">
                                        <span class="spinner-border spinner-border-sm" role="status"
                                            aria-hidden="true"></span>
                                    </span>
                                </button>
                            @endif
                        </div>
                        <div class="">
                            <button type="button" class="btn btn-secondary" wire:click='closeModalKomunikasi'
                                wire:loading.attr="disabled" wire:target="closeModalKomunikasi">
                                Close
                                <span wire:loading class="ms-2" wire:target="closeModalKomunikasi">
                                    <span class="spinner-border spinner-border-sm" role="status"
                                        aria-hidden="true"></span>
                                </span>
                            </button>
                            @if (!$isShowKomunikasi || $isEditKomunikasi)
                                <button type="button" wire:click.prevent='storeKomunikasi'
                                    wire:loading.attr="disabled" wire:target="storeKomunikasi"
                                    class="btn btn-primary w-md waves-effect waves-light">Submit
                                    <span wire:loading class="ms-2" wire:target="storeKomunikasi">
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



@if ($isOpenConfirmDeleteKomunikasi)
    <div class="modal" tabindex="-1" role="dialog" style="display: block;" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Menunggu Konfirmasi</h5>
                    <button type="button" class="btn-close" aria-label="Close"
                        wire:click="closeXModalConfirmDeleteKomunikasi"></button>
                </div>
                <div class="modal-body">
                    Data tidak bisa dikembalikan apabila Anda menghapus Komunikasi. Apakah Anda yakin ingin menghapus
                    Komunikasi ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click='closeModalConfirmDeleteKomunikasi'
                        wire:loading.attr="disabled" wire:target="closeModalConfirmDeleteKomunikasi">
                        Tutup
                        <span wire:loading class="ms-2" wire:target="closeModalConfirmDeleteKomunikasi">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        </span>
                    </button>
                    <button type="button" class="btn btn-danger" wire:click="deleteKomunikasi"
                        wire:loading.attr="disabled" wire:target="deleteKomunikasi">
                        Hapus Komunikasi
                        <span wire:loading class="ms-2" wire:target="deleteKomunikasi">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        </span>
                    </button>
                </div>
            </div>
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <div class="modal-backdrop fade show"></div>
@endif
