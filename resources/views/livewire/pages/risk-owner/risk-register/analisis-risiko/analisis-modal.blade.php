@if ($isOpenAnalisis)
    <div class="modal" tabindex="-1" role="dialog" style="display: block;" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0">Analisis Risiko -
                        {{ $isEfektifitas ? 'Efektifitas Kontrol' : 'Kriteria Risiko' }}</h5>
                    <button type="button" class="btn-close" wire:click='closeXModalAnalisis' data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive mb-2">
                                <table>
                                    <tr>
                                        <td style="width: 250px; vertical-align: top;">
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
                                            <p class="card-text" style="font-weight: bold">Deskripsi Konteks</p>
                                        </td>
                                        <td style="vertical-align: top;">:</td>
                                        <td style="vertical-align: bottom;">
                                            <p class="card-text" style="word-wrap: break-word;">{{ $konteks_desc }}
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 200px; vertical-align: top;">
                                            <p class="card-text me-4" style="font-weight: bold;">
                                                Risiko
                                            </p>
                                        </td>
                                        <td style="vertical-align: top;">:</td>
                                        <td style="width: 800px; vertical-align: bottom; word-break: break-word;">
                                            <p style="word-break: break-word;">
                                                {{ $risk_spesific }}
                                            </p>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- CHECK IF EFEKTIFITAS OR KRITERA --}}
                    @if ($isEfektifitas)
                        @include('livewire.pages.risk-owner.risk-register.analisis-risiko.efektifitas-form')
                    @else
                        @include('livewire.pages.risk-owner.risk-register.analisis-risiko.kriteria-form')
                    @endif

                    <div class="mt-1 border-top mb-3"></div>
                    <div class="d-flex justify-content-end">
                        <div class="">
                            <button type="button" class="btn btn-secondary" wire:click='closeModalAnalisis'
                                wire:loading.attr="disabled" wire:target="closeModalAnalisis">
                                Close
                                <span wire:loading class="ms-2" wire:target="closeModalAnalisis">
                                    <span class="spinner-border spinner-border-sm" role="status"
                                        aria-hidden="true"></span>
                                </span>
                            </button>
                            @if ($isEfektifitas)
                                @if (!$isShowEfektifitas || $isEditEfektifitas)
                                    <button type="button" wire:click.prevent='storeEfektifitas'
                                        wire:loading.attr="disabled" wire:target="storeEfektifitas"
                                        class="btn btn-primary w-md waves-effect waves-light">Submit
                                        <span wire:loading class="ms-2" wire:target="storeEfektifitas">
                                            <span class="spinner-border spinner-border-sm" role="status"
                                                aria-hidden="true"></span>
                                        </span>
                                    </button>
                                @endif
                            @else
                                @if (!$isShowAnalisis || $isEditAnalisis)
                                    <button type="button" wire:click.prevent='storeAnalisis'
                                        wire:loading.attr="disabled" wire:target="storeAnalisis"
                                        class="btn btn-primary w-md waves-effect waves-light">Submit
                                        <span wire:loading class="ms-2" wire:target="storeAnalisis">
                                            <span class="spinner-border spinner-border-sm" role="status"
                                                aria-hidden="true"></span>
                                        </span>
                                    </button>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <div class="modal-backdrop fade show"></div>
@endif
