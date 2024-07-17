@if ($isOpenDeteksi)
    <div class="modal" tabindex="-1" role="dialog" style="display: block;" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0">Kriteria Deteksi Form</h5>
                    <button type="button" class="btn-close" wire:click='closeXModalDeteksi' data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>

                        @include('livewire.pages.risk-owner.risk-register.kriteria-risiko.components.header-modal')

                        @php
                            $data = range(0, 9); // Assuming you want to repeat the section 10 times
                        @endphp

                        @if ($isShowDeteksi)
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">Deteksi Risiko</h4>
                                            <div class="table-responsive mb-2">
                                                <table>
                                                    @foreach ($deteksi as $index => $item)
                                                        <tr>
                                                            <td style="width: 200px; vertical-align: top;">
                                                                <p class="card-text me-4" style="font-weight: bold">
                                                                    Skala {{ $index + 1 }}
                                                                </p>
                                                            </td>
                                                            <td style="vertical-align: top;">:</td>
                                                            <td style="width: calc(100% - 250px); vertical-align: top;">
                                                                <p class="card-text" style="word-wrap: break-word;">
                                                                    {{ $deteksi[$index]['deteksi_desc'] }}
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
                            @foreach ($deteksi as $index => $item)
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

                                    {{-- deteksi ID --}}
                                    <input type="hidden" id="deteksi.{{ $index }}.deteksi_id"
                                        wire:model="deteksi.{{ $index }}.deteksi_id">

                                    {{-- RISK ID --}}
                                    <input type="hidden" id="deteksi.{{ $index }}.risk_id"
                                        wire:model="deteksi.{{ $index }}.risk_id">

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label" for="formrow-password_confirmation-input">
                                                Keterangan
                                                <span style="color: red">*</span>
                                            </label>
                                            <textarea id="snow-editor.{{ $index }}"
                                                class="form-control @error('deteksi.' . $index . '.deteksi_desc') is-invalid @enderror {{ isset($deteksi[$index]['deteksi_desc']) && $deteksi[$index]['deteksi_desc'] ? 'is-valid' : '' }}"
                                                wire:model="deteksi.{{ $index }}.deteksi_desc" aria-label="With textarea" cols="20" rows="10"
                                                {{ $isShowDeteksi ? 'disabled' : '' }}></textarea>
                                            <span class="invalid-feedback">
                                                @error('deteksi.' . $index . '.deteksi_desc')
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
                                @if ($isEditDeteksi)
                                    <button type="button" class="btn btn-danger"
                                        wire:click='openModalConfirmDeleteDeteksi({{ $risk_id }})'
                                        wire:loading.attr="disabled"
                                        wire:target="openModalConfirmDeleteDeteksi({{ $risk_id }})">
                                        Delete Kriteria Deteksi
                                        <span wire:loading class="ms-2"
                                            wire:target="openModalConfirmDeleteDeteksi({{ $risk_id }})">
                                            <span class="spinner-border spinner-border-sm" role="status"
                                                aria-hidden="true"></span>
                                        </span>
                                    </button>
                                @endif
                            </div>
                            <div class="">
                                <button type="button" class="btn btn-secondary" wire:click='closeModalDeteksi'
                                    wire:loading.attr="disabled" wire:target="closeModalDeteksi">
                                    Close
                                    <span wire:loading class="ms-2" wire:target="closeModalDeteksi">
                                        <span class="spinner-border spinner-border-sm" role="status"
                                            aria-hidden="true"></span>
                                    </span>
                                </button>
                                @if (!$isShowDeteksi)
                                    <button type="button" wire:click.prevent='storeDeteksi'
                                        wire:loading.attr="disabled" wire:target="storeDeteksi"
                                        class="btn btn-primary w-md waves-effect waves-light">Submit
                                        <span wire:loading class="ms-2" wire:target="storeDeteksi">
                                            <span class="spinner-border spinner-border-sm" role="status"
                                                aria-hidden="true"></span>
                                        </span>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <div class="modal-backdrop fade show"></div>
@endif


@if ($isOpenConfirmDeleteDeteksi)
    <div class="modal" tabindex="-1" role="dialog" style="display: block;" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Menunggu Konfirmasi</h5>
                    <button type="button" class="btn-close" aria-label="Close"
                        wire:click="closeXModalConfirmDeleteDeteksi"></button>
                </div>
                <div class="modal-body">
                    Data tidak bisa dikembalikan apabila Anda menghapus Kriteria Deteksi. Apakah Anda yakin ingin
                    menghapus
                    Kriteria Deteksi ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click='closeModalConfirmDeleteDeteksi'
                        wire:loading.attr="disabled" wire:target="closeModalConfirmDeleteDeteksi">
                        Tutup
                        <span wire:loading class="ms-2" wire:target="closeModalConfirmDeleteDeteksi">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        </span>
                    </button>
                    <button type="button" class="btn btn-danger" wire:click="deleteDeteksi"
                        wire:loading.attr="disabled" wire:target="deleteDeteksi">
                        Hapus Kriteria Deteksi
                        <span wire:loading class="ms-2" wire:target="deleteDeteksi">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        </span>
                    </button>
                </div>
            </div>
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <div class="modal-backdrop fade show"></div>
@endif
