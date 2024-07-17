<style>
    .custom-modal {
        z-index: 1040;
        /* Bootstrap default is 1050 */
    }

    .custom-backdrop {
        z-index: 1030;
        /* Bootstrap default is 1040 */
    }

    .flasher-wrapper {
        z-index: 1060;
        /* Higher than modal */
    }
</style>


@if ($isOpenDampak)
    <div class="modal custom-modal" tabindex="1" role="dialog" style="display: block;" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0">Kriteria Dampak Form</h5>
                    <button type="button" class="btn-close" wire:click='closeXModalDampak' data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>

                        @include('livewire.pages.risk-owner.risk-register.kriteria-risiko.components.header-modal')

                        @php
                            $data = range(0, 9); // Assuming you want to repeat the section 10 times
                        @endphp

                        @if ($isShowDampak)
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">Dampak Risiko</h4>
                                            <div class="table-responsive mb-2">
                                                <table>
                                                    @foreach ($dampak as $index => $item)
                                                        <tr>
                                                            <td style="width: 200px; vertical-align: top;">
                                                                <p class="card-text me-4" style="font-weight: bold">
                                                                    Skala {{ $index + 1 }}
                                                                </p>
                                                            </td>
                                                            <td style="vertical-align: top;">:</td>
                                                            <td style="width: calc(100% - 250px); vertical-align: top;">
                                                                <p class="card-text" style="word-wrap: break-word;">
                                                                    {{ $dampak[$index]['dampak_desc'] }}
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
                            @foreach ($dampak as $index => $item)
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

                                    {{-- dampak ID --}}
                                    <input type="hidden" id="dampak.{{ $index }}.dampak_id"
                                        wire:model="dampak.{{ $index }}.dampak_id">

                                    {{-- RISK ID --}}
                                    <input type="hidden" id="dampak.{{ $index }}.risk_id"
                                        wire:model="dampak.{{ $index }}.risk_id">

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label" for="formrow-password_confirmation-input">
                                                Keterangan
                                                <span style="color: red">*</span>
                                            </label>
                                            <textarea id="snow-editor.{{ $index }}"
                                                class="form-control @error('dampak.' . $index . '.dampak_desc') is-invalid @enderror {{ isset($dampak[$index]['dampak_desc']) && $dampak[$index]['dampak_desc'] ? 'is-valid' : '' }}"
                                                wire:model="dampak.{{ $index }}.dampak_desc" aria-label="With textarea" cols="20" rows="10"
                                                {{ $isShowDampak ? 'disabled' : '' }}></textarea>
                                            <span class="invalid-feedback">
                                                @error('dampak.' . $index . '.dampak_desc')
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
                                @if ($isEditDampak)
                                    <button type="button" class="btn btn-danger"
                                        wire:click='openModalConfirmDeleteDampak({{ $risk_id }})'
                                        wire:loading.attr="disabled"
                                        wire:target="openModalConfirmDeleteDampak({{ $risk_id }})">
                                        Delete Kriteria Dampak
                                        <span wire:loading class="ms-2"
                                            wire:target="openModalConfirmDeleteDampak({{ $risk_id }})">
                                            <span class="spinner-border spinner-border-sm" role="status"
                                                aria-hidden="true"></span>
                                        </span>
                                    </button>
                                @endif
                            </div>
                            <div class="">
                                <button type="button" class="btn btn-secondary" wire:click='closeModalDampak'
                                    wire:loading.attr="disabled" wire:target="closeModalDampak">
                                    Close
                                    <span wire:loading class="ms-2" wire:target="closeModalDampak">
                                        <span class="spinner-border spinner-border-sm" role="status"
                                            aria-hidden="true"></span>
                                    </span>
                                </button>
                                @if (!$isShowDampak)
                                    <button type="button" wire:click.prevent='storeDampak' wire:loading.attr="disabled"
                                        wire:target="storeDampak"
                                        class="btn btn-primary w-md waves-effect waves-light">Submit
                                        <span wire:loading class="ms-2" wire:target="storeDampak">
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
    <div class="modal-backdrop custom-backdrop fade show"></div>
@endif


@if ($isOpenConfirmDeleteDampak)
    <div class="modal" tabindex="-1" role="dialog" style="display: block;" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Menunggu Konfirmasi</h5>
                    <button type="button" class="btn-close" aria-label="Close"
                        wire:click="closeXModalConfirmDeleteDampak"></button>
                </div>
                <div class="modal-body">
                    Data tidak bisa dikembalikan apabila Anda menghapus Kriteria Dampak. Apakah Anda yakin ingin
                    menghapus
                    Kriteria Dampak ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click='closeModalConfirmDeleteDampak'
                        wire:loading.attr="disabled" wire:target="closeModalConfirmDeleteDampak">
                        Tutup
                        <span wire:loading class="ms-2" wire:target="closeModalConfirmDeleteDampak">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        </span>
                    </button>
                    <button type="button" class="btn btn-danger" wire:click="deleteDampak"
                        wire:loading.attr="disabled" wire:target="deleteDampak">
                        Hapus Kriteria Dampak
                        <span wire:loading class="ms-2" wire:target="deleteDampak">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        </span>
                    </button>
                </div>
            </div>
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <div class="modal-backdrop fade show"></div>
@endif
