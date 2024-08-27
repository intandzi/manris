@if ($isOpenKemungkinan)
    <div class="modal" tabindex="-1" role="dialog" style="display: block;" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0">Kriteria Kemungkinan Form</h5>
                    <button type="button" class="btn-close" wire:click='closeXModalKemungkinan' data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @include('livewire.pages.risk-owner.risk-register.kriteria-risiko.components.header-modal')

                    @php
                        $data = range(0, 9); // Assuming you want to repeat the section 10 times
                    @endphp

                    @if ($isShowKemungkinan)
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Kemungkinan Risiko</h4>
                                        <div class="table-responsive mb-2">
                                            <table>
                                                @foreach ($kemungkinan as $index => $item)
                                                    <tr>
                                                        <td style="width: 200px; vertical-align: top;">
                                                            <p class="card-text me-4" style="font-weight: bold">
                                                                Skala {{ $index + 1 }}
                                                            </p>
                                                        </td>
                                                        <td style="vertical-align: top;">:</td>
                                                        <td
                                                            style="width: calc(100% - 250px); vertical-align: top;">
                                                            <p class="card-text" style="word-wrap: break-word;">
                                                                {{ $kemungkinan[$index]['kemungkinan_desc'] }}
                                                            </p>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </table>
                                        </div>
                                    </div>
                                </div> <!-- end card-->
                            </div>
                        </div>
                    @else
                        @foreach ($kemungkinan as $index => $item)
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-2">
                                        <div class="table-responsive mt-2">
                                            <table>
                                                <tr>
                                                    <td style="width: 200px; vertical-align: top;">
                                                        <p class="card-text me-4" style="font-weight: bold">Skala
                                                        </p>
                                                    </td>
                                                    <td style="vertical-align: top;">:</td>
                                                    <td style="width: calc(100% - 250px); vertical-align: bottom;">
                                                        <p class="card-text" style="word-wrap: break-word;">
                                                            {{ $index + 1 }}
                                                        </p>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div> <!-- end col -->

                                {{-- KEMUNGKINAN ID --}}
                                <input type="hidden" id="kemungkinan.{{ $index }}.kemungkinan_id"
                                    wire:model="kemungkinan.{{ $index }}.kemungkinan_id">

                                {{-- RISK ID --}}
                                <input type="hidden" id="kemungkinan.{{ $index }}.risk_id"
                                    wire:model="kemungkinan.{{ $index }}.risk_id">

                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label" for="formrow-password_confirmation-input">
                                            Keterangan
                                            <span style="color: red">*</span>
                                        </label>
                                        <textarea id="snow-editor.{{ $index }}"
                                            class="form-control @error('kemungkinan.' . $index . '.kemungkinan_desc') is-invalid @enderror {{ isset($kemungkinan[$index]['kemungkinan_desc']) && $kemungkinan[$index]['kemungkinan_desc'] ? 'is-valid' : '' }}"
                                            wire:model="kemungkinan.{{ $index }}.kemungkinan_desc" aria-label="With textarea" cols="20"
                                            rows="10" {{ $isShowKemungkinan ? 'disabled' : '' }}></textarea>
                                        <span class="invalid-feedback">
                                            @error('kemungkinan.' . $index . '.kemungkinan_desc')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>
                                </div> <!-- end col -->
                            </div> <!-- end row -->
                        @endforeach
                    @endif

                    <div class="mt-1 border-top mb-3"></div>
                    <div class="d-flex justify-content-between">
                        <div class="">
                            @if ($isEditKemungkinan)
                                <button type="button" class="btn btn-danger"
                                    wire:click='openModalConfirmDeleteKemungkinan({{ $risk_id }})'
                                    wire:loading.attr="disabled"
                                    wire:target="openModalConfirmDeleteKemungkinan({{ $risk_id }})">
                                    Delete Kriteria Kemungkinan
                                    <span wire:loading class="ms-2"
                                        wire:target="openModalConfirmDeleteKemungkinan({{ $risk_id }})">
                                        <span class="spinner-border spinner-border-sm" role="status"
                                            aria-hidden="true"></span>
                                    </span>
                                </button>
                            @endif
                        </div>
                        <div class="">
                            <button type="button" class="btn btn-secondary" wire:click='closeModalKemungkinan'
                                wire:loading.attr="disabled" wire:target="closeModalKemungkinan">
                                Close
                                <span wire:loading class="ms-2" wire:target="closeModalKemungkinan">
                                    <span class="spinner-border spinner-border-sm" role="status"
                                        aria-hidden="true"></span>
                                </span>
                            </button>
                            @if (!$isShowKemungkinan)
                                <button type="button" wire:click.prevent='storeKemungkinan'
                                    wire:loading.attr="disabled" wire:target="storeKemungkinan"
                                    class="btn btn-primary w-md waves-effect waves-light">Submit
                                    <span wire:loading class="ms-2" wire:target="storeKemungkinan">
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


@if ($isOpenConfirmDeleteKemungkinan)
    <div class="modal" tabindex="-1" role="dialog" style="display: block;" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Menunggu Konfirmasi</h5>
                    <button type="button" class="btn-close" aria-label="Close"
                        wire:click="closeXModalConfirmDeleteKemungkinan"></button>
                </div>
                <div class="modal-body">
                    Data tidak bisa dikembalikan apabila Anda menghapus Kriteria Kemungkinan. Apakah Anda yakin ingin
                    menghapus
                    Kriteria Kemungkinan ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click='closeModalConfirmDeleteKemungkinan'
                        wire:loading.attr="disabled" wire:target="closeModalConfirmDeleteKemungkinan">
                        Tutup
                        <span wire:loading class="ms-2" wire:target="closeModalConfirmDeleteKemungkinan">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        </span>
                    </button>
                    <button type="button" class="btn btn-danger" wire:click="deleteKemungkinan"
                        wire:loading.attr="disabled" wire:target="deleteKemungkinan">
                        Hapus Kriteria Kemungkinan
                        <span wire:loading class="ms-2" wire:target="deleteKemungkinan">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        </span>
                    </button>
                </div>
            </div>
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <div class="modal-backdrop fade show"></div>
@endif
