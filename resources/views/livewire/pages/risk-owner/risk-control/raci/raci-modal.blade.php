@if ($isOpenRACI)
    <div class="modal" tabindex="-1" role="dialog" style="display: block;" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0">RACI Form</h5>
                    <button type="button" class="btn-close" wire:click='closeXModalRACI' data-bs-dismiss="modal"
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
                    @if ($isEditRACI || !$isShowRACI)
                        <dt>Pengelolaan RACI</dt>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-firstname-input">Responsible
                                        <span style="color: red">*</span></label>
                                    <div class="relative">
                                        <div class="input-group">
                                            <input type="text" wire:model.live="searchR"
                                                placeholder="Search Responsible..." wire:focus.live="activateSearchR"
                                                wire:blur.live="deactivateSearchR"
                                                class="form-control @error('RACI_R') is-invalid @enderror">
                                            <span class="invalid-feedback">{{ $errors->first('RACI_R') }}</span>
                                        </div>
                                        @if ($searchActiveR)
                                            <!-- Display results only if search is active -->
                                            <div
                                                style="position:absolute; z-index:100; background-color:white; width:96%; max-height:200px; overflow-y:auto;">
                                                <ul class="list-group">
                                                    @forelse ($searchResultsR as $item)
                                                        <li wire:click='selectRaci_R({{ $item->stakeholder_id }})'
                                                            class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                                                            style="cursor: pointer;">
                                                            {{ ucwords($item->stakeholder_jabatan) }} -
                                                            {{ ucwords($item->stakeholder_singkatan) }}
                                                            <button type="button"
                                                                wire:click="selectRaci_R({{ $item->stakeholder_id }})"
                                                                class="btn btn-primary btn-sm">Add</button>
                                                        </li>
                                                    @empty
                                                        <li class="list-group-item">Found nothing...</li>
                                                    @endforelse
                                                </ul>
                                            </div>
                                        @endif

                                        <p><small>Ketikkan singkatan/jabatan pemangku kepentingan.</small></p>

                                        @if (count($RACI_R) > 0)
                                            <ul class="list-group mt-3">
                                                <h5>Pemangku Kepentingan Responsible</h5>
                                                @foreach ($RACI_R as $index => $R)
                                                    <li
                                                        class="list-group-item d-flex justify-content-between align-items-center">
                                                        {{ $R['stakeholder_jabatan'] }} -
                                                        {{ $R['stakeholder_singkatan'] }}
                                                        <button type="button"
                                                            wire:click.prevent='removeRaci_R({{ $index }})'
                                                            wire:loading.attr="disabled"
                                                            wire:target="removeRaci_R({{ $index }})"
                                                            class="btn btn-danger btn-sm w-md waves-effect waves-light">Hapus
                                                            <span wire:loading class="ms-2"
                                                                wire:target="removeRaci_R({{ $index }})">
                                                                <span class="spinner-border spinner-border-sm"
                                                                    role="status" aria-hidden="true"></span>
                                                            </span>
                                                        </button>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                        <!-- Duplicate stakeholder error -->
                                        @if (session()->has('error_R'))
                                            <div class="alert alert-danger">
                                                {{ session('error_R') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div> <!-- end col -->
                            <hr>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-firstname-input">Accountable
                                        <span style="color: red">*</span></label>
                                    <div class="relative">
                                        <div class="input-group">
                                            <input type="text" wire:model.live="searchA"
                                                placeholder="Search Accountable..." wire:focus.live="activateSearchA"
                                                wire:blur.live="deactivateSearchA"
                                                class="form-control @error('RACI_A') is-invalid @enderror">
                                            <span class="invalid-feedback">{{ $errors->first('RACI_A') }}</span>
                                            @if ($searchA)
                                                <button type="button" wire:click.prevent="clearSearchA"
                                                    class="btn btn-primary d-flex align-items-center btn-sm"
                                                    title="Cancel Edit">
                                                    <span class="material-icons-outlined">close</span>
                                                </button>
                                            @endif
                                        </div>

                                        @if ($searchActiveA)
                                            <!-- Display results only if search is active -->
                                            <div
                                                style="position:absolute; z-index:100; background-color:white; width:96%; max-height:200px; overflow-y:auto;">
                                                <ul class="list-group">
                                                    @forelse ($searchResultsA as $item)
                                                        <li wire:click='selectRaci_A({{ $item->stakeholder_id }})'
                                                            class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                                                            style="cursor: pointer;">
                                                            {{ ucwords($item->stakeholder_jabatan) }} -
                                                            {{ ucwords($item->stakeholder_singkatan) }}
                                                            <button type="button"
                                                                wire:click="selectRaci_A({{ $item->stakeholder_id }})"
                                                                class="btn btn-primary btn-sm">Add</button>
                                                        </li>
                                                    @empty
                                                        <li class="list-group-item">Found nothing...</li>
                                                    @endforelse
                                                </ul>
                                            </div>
                                        @endif

                                        <p><small>Ketikkan singkatan/jabatan pemangku kepentingan.</small></p>

                                        @if (count($RACI_A) > 0)
                                            <ul class="list-group mt-3">
                                                <h5>Pemangku Kepentingan Accountable</h5>
                                                @foreach ($RACI_A as $index => $R)
                                                    <li
                                                        class="list-group-item d-flex justify-content-between align-items-center">
                                                        {{ $R['stakeholder_jabatan'] }} -
                                                        {{ $R['stakeholder_singkatan'] }}
                                                        <button type="button"
                                                            wire:click.prevent='removeRaci_A({{ $index }})'
                                                            wire:loading.attr="disabled"
                                                            wire:target="removeRaci_A({{ $index }})"
                                                            class="btn btn-danger btn-sm w-md waves-effect waves-light">Hapus
                                                            <span wire:loading class="ms-2"
                                                                wire:target="removeRaci_A({{ $index }})">
                                                                <span class="spinner-border spinner-border-sm"
                                                                    role="status" aria-hidden="true"></span>
                                                            </span>
                                                        </button>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                        <!-- Duplicate stakeholder error -->
                                        @if (session()->has('error_A'))
                                            <div class="alert alert-danger">
                                                {{ session('error_A') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div> <!-- end col -->
                            <hr>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-firstname-input">Consulted
                                        <span style="color: red">*</span></label>
                                    <div class="relative">
                                        <div class="input-group">
                                            <input type="text" wire:model.live="searchC"
                                                placeholder="Search Consulted..." wire:focus.live="activateSearchC"
                                                wire:blur.live="deactivateSearchC"
                                                class="form-control @error('RACI_C') is-invalid @enderror">
                                            <span class="invalid-feedback">{{ $errors->first('RACI_C') }}</span>

                                            @if ($searchC)
                                                <button type="button" wire:click.prevent="clearSearchC"
                                                    class="btn btn-primary d-flex align-items-center btn-sm"
                                                    title="Cancel Edit">
                                                    <span class="material-icons-outlined">close</span>
                                                </button>
                                            @endif
                                        </div>

                                        @if ($searchActiveC)
                                            <!-- Display results only if search is active -->
                                            <div
                                                style="position:absolute; z-index:100; background-color:white; width:96%; max-height:200px; overflow-y:auto;">
                                                <ul class="list-group">
                                                    @forelse ($searchResultsC as $item)
                                                        <li wire:click='selectRaci_C({{ $item->stakeholder_id }})'
                                                            class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                                                            style="cursor: pointer;">
                                                            {{ ucwords($item->stakeholder_jabatan) }} -
                                                            {{ ucwords($item->stakeholder_singkatan) }}
                                                            <button type="button"
                                                                wire:click="selectRaci_C({{ $item->stakeholder_id }})"
                                                                class="btn btn-primary btn-sm">Add</button>
                                                        </li>
                                                    @empty
                                                        <li class="list-group-item">Found nothing...</li>
                                                    @endforelse
                                                </ul>
                                            </div>
                                        @endif

                                        <p><small>Ketikkan singkatan/jabatan pemangku kepentingan.</small></p>

                                        @if (count($RACI_C) > 0)
                                            <ul class="list-group mt-3">
                                                <h5>Pemangku Kepentingan Consulted</h5>
                                                @foreach ($RACI_C as $index => $R)
                                                    <li
                                                        class="list-group-item d-flex justify-content-between align-items-center">
                                                        {{ $R['stakeholder_jabatan'] }} -
                                                        {{ $R['stakeholder_singkatan'] }}
                                                        <button type="button"
                                                            wire:click.prevent='removeRaci_C({{ $index }})'
                                                            wire:loading.attr="disabled"
                                                            wire:target="removeRaci_C({{ $index }})"
                                                            class="btn btn-danger btn-sm w-md waves-effect waves-light">Hapus
                                                            <span wire:loading class="ms-2"
                                                                wire:target="removeRaci_C({{ $index }})">
                                                                <span class="spinner-border spinner-border-sm"
                                                                    role="status" aria-hidden="true"></span>
                                                            </span>
                                                        </button>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                        <!-- Duplicate stakeholder error -->
                                        @if (session()->has('error_C'))
                                            <div class="alert alert-danger">
                                                {{ session('error_C') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div> <!-- end col -->
                            <hr>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-firstname-input">Informed
                                        <span style="color: red">*</span></label>
                                    <div class="relative">
                                        <div class="input-group">
                                            <input type="text" wire:model.live="searchI"
                                                placeholder="Search Informed..." wire:focus.live="activateSearchI"
                                                wire:blur.live="deactivateSearchI"
                                                class="form-control @error('RACI_I') is-invalid @enderror">
                                            <span class="invalid-feedback">{{ $errors->first('RACI_I') }}</span>

                                            @if ($searchI)
                                                <button type="button" wire:click.prevent="clearSearchI"
                                                    class="btn btn-primary d-flex align-items-center btn-sm"
                                                    title="Cancel Edit">
                                                    <span class="material-icons-outlined">close</span>
                                                </button>
                                            @endif
                                        </div>

                                        @if ($searchActiveI)
                                            <!-- Display results only if search is active -->
                                            <div
                                                style="position:absolute; z-index:100; background-color:white; width:96%; max-height:200px; overflow-y:auto;">
                                                <ul class="list-group">
                                                    @forelse ($searchResultsI as $item)
                                                        <li wire:click='selectRaci_I({{ $item->stakeholder_id }})'
                                                            class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                                                            style="cursor: pointer;">
                                                            {{ ucwords($item->stakeholder_jabatan) }} -
                                                            {{ ucwords($item->stakeholder_singkatan) }}
                                                            <button type="button"
                                                                wire:click="selectRaci_I({{ $item->stakeholder_id }})"
                                                                class="btn btn-primary btn-sm">Add</button>
                                                        </li>
                                                    @empty
                                                        <li class="list-group-item">Found nothing...</li>
                                                    @endforelse
                                                </ul>
                                            </div>
                                        @endif

                                        <p><small>Ketikkan singkatan/jabatan pemangku kepentingan.</small></p>

                                        @if (count($RACI_I) > 0)
                                            <ul class="list-group mt-3">
                                                <h5>Pemangku Kepentingan Informed</h5>
                                                @foreach ($RACI_I as $index => $R)
                                                    <li
                                                        class="list-group-item d-flex justify-content-between align-items-center">
                                                        {{ $R['stakeholder_jabatan'] }} -
                                                        {{ $R['stakeholder_singkatan'] }}
                                                        <button type="button"
                                                            wire:click.prevent='removeRaci_I({{ $index }})'
                                                            wire:loading.attr="disabled"
                                                            wire:target="removeRaci_I({{ $index }})"
                                                            class="btn btn-danger btn-sm w-md waves-effect waves-light">Hapus
                                                            <span wire:loading class="ms-2"
                                                                wire:target="removeRaci_I({{ $index }})">
                                                                <span class="spinner-border spinner-border-sm"
                                                                    role="status" aria-hidden="true"></span>
                                                            </span>
                                                        </button>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                        <!-- Duplicate stakeholder error -->
                                        @if (session()->has('error_I'))
                                            <div class="alert alert-danger">
                                                {{ session('error_I') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div> <!-- end col -->
                        </div>
                    @else
                        <dt class="mb-2">Pengelolaan Raci</dt>
                        <div class="table-responsive mb-2">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width: 300px;">Responsible</th>
                                        <th style="width: 300px;">Accountable</th>
                                        <th style="width: 300px;">Consulted</th>
                                        <th style="width: 300px;">Informed</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="word-wrap: break-word;">
                                            @foreach ($RACI_R as $R)
                                                {{ $R['stakeholder_jabatan'] }} ({{ $R['stakeholder_singkatan'] }}),
                                            @endforeach
                                        </td>
                                        <td style="word-wrap: break-word;">
                                            @foreach ($RACI_A as $A)
                                                {{ $A['stakeholder_jabatan'] }} ({{ $A['stakeholder_singkatan'] }}),
                                            @endforeach
                                        </td>
                                        <td style="word-wrap: break-word;">
                                            @foreach ($RACI_C as $C)
                                                {{ $C['stakeholder_jabatan'] }} ({{ $C['stakeholder_singkatan'] }}),
                                            @endforeach
                                        </td>
                                        <td style="word-wrap: break-word;">
                                            @foreach ($RACI_I as $I)
                                                {{ $I['stakeholder_jabatan'] }} ({{ $I['stakeholder_singkatan'] }}),
                                            @endforeach
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    @endif

                    <div class="mt-1 border-top mb-3"></div>
                    <div class="d-flex justify-content-between">
                        <div class="">
                            @if ($isEditRACI)
                                <button type="button" class="btn btn-danger"
                                    wire:click='openModalConfirmDeleteRACI({{ $controlRisk_id }})'
                                    wire:loading.attr="disabled"
                                    wire:target="openModalConfirmDeleteRACI({{ $controlRisk_id }})">
                                    Delete RACI
                                    <span wire:loading class="ms-2"
                                        wire:target="openModalConfirmDeleteRACI({{ $controlRisk_id }})">
                                        <span class="spinner-border spinner-border-sm" role="status"
                                            aria-hidden="true"></span>
                                    </span>
                                </button>
                            @endif
                        </div>
                        <div class="">
                            <button type="button" class="btn btn-secondary" wire:click='closeModalRACI'
                                wire:loading.attr="disabled" wire:target="closeModalRACI">
                                Close
                                <span wire:loading class="ms-2" wire:target="closeModalRACI">
                                    <span class="spinner-border spinner-border-sm" role="status"
                                        aria-hidden="true"></span>
                                </span>
                            </button>
                            @if (!$isShowRACI || $isEditRACI)
                                <button type="button" wire:click.prevent='storeRACI' wire:loading.attr="disabled"
                                    wire:target="storeRACI"
                                    class="btn btn-primary w-md waves-effect waves-light">Submit
                                    <span wire:loading class="ms-2" wire:target="storeRACI">
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



@if ($isOpenConfirmDeleteRACI)
    <div class="modal" tabindex="-1" role="dialog" style="display: block;" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Menunggu Konfirmasi</h5>
                    <button type="button" class="btn-close" aria-label="Close"
                        wire:click="closeXModalConfirmDeleteRACI"></button>
                </div>
                <div class="modal-body">
                    Data tidak bisa dikembalikan apabila Anda menghapus RACI. Apakah Anda yakin ingin menghapus
                    RACI ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click='closeModalConfirmDeleteRACI'
                        wire:loading.attr="disabled" wire:target="closeModalConfirmDeleteRACI">
                        Tutup
                        <span wire:loading class="ms-2" wire:target="closeModalConfirmDeleteRACI">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        </span>
                    </button>
                    <button type="button" class="btn btn-danger" wire:click="deleteRACI"
                        wire:loading.attr="disabled" wire:target="deleteRACI">
                        Hapus RACI
                        <span wire:loading class="ms-2" wire:target="deleteRACI">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        </span>
                    </button>
                </div>
            </div>
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <div class="modal-backdrop fade show"></div>
@endif
