@if ($isOpen)
    <div class="modal" tabindex="-1" role="dialog" style="display: block;" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0">KPI Form</h5>
                    <button type="button" class="btn-close" wire:click='closeXModal' data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if ($isShow)
                        <div class="table-responsive mb-2">
                            <table>
                                <tr>
                                    <td style="width: 200px;">
                                        <p class="card-text me-4" style="font-weight: bold">Deskripsi KPI
                                        </p>
                                    </td>
                                    <td>:</td>
                                    <td style="width: calc(100% - 250px); vertical-align: bottom;">
                                        <p class="card-text" style="word-wrap: break-word;">
                                            {{ ucwords($kpi_nama) }}
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 200px; vertical-align: top;">
                                        <p class="card-text" style="font-weight: bold">Kategori Standar</p>
                                    </td>
                                    <td style="vertical-align: top;">:</td>
                                    <td style="vertical-align: bottom;">
                                        <p class="card-text" style="word-wrap: break-word;">
                                            {{ ucwords($kategoriStandar_id) }}
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 200px; vertical-align: top;">
                                        <p class="card-text" style="font-weight: bold">Tanggal Mulai</p>
                                    </td>
                                    <td style="vertical-align: top;">:</td>
                                    <td style="vertical-align: bottom;">
                                        <p class="card-text" style="word-wrap: break-word;">
                                            {{ date('d M Y', strtotime($kpi_tanggalMulai)) }}
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 200px; vertical-align: top;">
                                        <p class="card-text" style="font-weight: bold">Tanggal Akhir</p>
                                    </td>
                                    <td style="vertical-align: top;">:</td>
                                    <td style="vertical-align: bottom;">
                                        <p class="card-text" style="word-wrap: break-word;">
                                            {{ date('d M Y', strtotime($kpi_tanggalAkhir)) }}
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 200px; vertical-align: top;">
                                        <p class="card-text" style="font-weight: bold">Periode KPI</p>
                                    </td>
                                    <td style="vertical-align: top;">:</td>
                                    <td style="vertical-align: bottom;">
                                        <p class="card-text" style="word-wrap: break-word;">{{ $kpi_periode }}
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 200px; vertical-align: top;">
                                        <p class="card-text" style="font-weight: bold">Kategori Kinerja</p>
                                    </td>
                                    <td style="vertical-align: top;">:</td>
                                    <td style="vertical-align: bottom;">
                                        <p class="card-text" style="word-wrap: break-word;">{{ $kpi_kategoriKinerja }}
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 200px; vertical-align: top;">
                                        <p class="card-text" style="font-weight: bold">Indikator Kinerja</p>
                                    </td>
                                    <td style="vertical-align: top;">:</td>
                                    <td style="vertical-align: bottom;">
                                        <p class="card-text" style="word-wrap: break-word;">{{ $kpi_indikatorKinerja }}
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    @else
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-jabatan-input">Deskripsi KPI <span
                                            style="color: red">*</span></label>
                                    <input type="jabatan" wire:model="kpi_nama"
                                        class="form-control @error('kpi_nama') is-invalid @enderror {{ $kpi_nama ? 'is-valid' : '' }}"
                                        id="formrow-kpi_nama-input" placeholder="ketik nama kpi..."
                                        {{ $isShow ? 'disabled' : '' }}>
                                    <span class="invalid-feedback">{{ $errors->first('kpi_nama') }}</span>
                                </div>
                            </div> <!-- end col -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-firstname-input">Kategori Standar <span
                                            style="color: red">*</span></label>
                                    <select wire:model='kategoriStandar_id' {{ $isShow ? 'disabled' : '' }}
                                        class="form-control @error('kategoriStandar_id') is-invalid @enderror {{ $kategoriStandar_id ? 'is-valid' : '' }}">
                                        <option value="">-- Pilih Kategori Standar --</option>
                                        @foreach ($kategoriStandar as $item)
                                            <option value="{{ $item->kategoriStandar_id }}">
                                                {{ ucwords($item->kategoriStandar_desc) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="invalid-feedback">{{ $errors->first('kategoriStandar_id') }}</span>
                                </div>
                            </div> <!-- end col -->
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-jabatan-input">Tanggal Mulai <span
                                            style="color: red">*</span></label>
                                    <input type="date" wire:model="kpi_tanggalMulai"
                                        class="form-control @error('kpi_tanggalMulai') is-invalid @enderror {{ $kpi_tanggalMulai ? 'is-valid' : '' }}"
                                        id="formrow-kpi_tanggalMulai-input" placeholder="ketik nama kpi..."
                                        {{ $isShow ? 'disabled' : '' }}>
                                    <span class="invalid-feedback">{{ $errors->first('kpi_tanggalMulai') }}</span>
                                </div>
                            </div> <!-- end col -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-jabatan-input">Tanggal Akhir <span
                                            style="color: red">*</span></label>
                                    <input type="date" wire:model="kpi_tanggalAkhir"
                                        class="form-control @error('kpi_tanggalAkhir') is-invalid @enderror {{ $kpi_tanggalAkhir ? 'is-valid' : '' }}"
                                        id="formrow-kpi_tanggalAkhir-input" placeholder="ketik nama kpi..."
                                        {{ $isShow ? 'disabled' : '' }}>
                                    <span class="invalid-feedback">{{ $errors->first('kpi_tanggalAkhir') }}</span>
                                </div>
                            </div> <!-- end col -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-jabatan-input">Periode KPI <span
                                            style="color: red">*</span></label>
                                    <select
                                        class="form-control @error('kpi_periode') is-invalid @enderror {{ $kpi_periode ? 'is-valid' : '' }}"
                                        wire:model.debounce.100ms="kpi_periode" {{ $isShow ? 'disabled' : '' }}>
                                        <option selected value="">--</option>
                                        @foreach ($periodYears as $year)
                                            <option value="{{ $year }}">{{ $year }}</option>
                                        @endforeach
                                    </select>
                                    <span class="invalid-feedback">{{ $errors->first('kpi_periode') }}</span>
                                </div>
                            </div> <!-- end col -->
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-password_confirmation-input">Kategori
                                        Kinerja
                                        <span style="color: red">*</span></label>
                                    <textarea id="snow-editor"
                                        class="form-control @error('kpi_kategoriKinerja') is-invalid @enderror {{ $kpi_kategoriKinerja ? 'is-valid' : '' }}"
                                        wire:model='kpi_kategoriKinerja' aria-label="With textarea" cols="20" rows="10"
                                        {{ $isShow ? 'disabled' : '' }}></textarea>
                                    <span class="invalid-feedback">{{ $errors->first('kpi_kategoriKinerja') }}</span>
                                </div>
                            </div> <!-- end col -->
                        </div> <!-- end row -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-password_confirmation-input">Indikator
                                        Kinerja
                                        <span style="color: red">*</span></label>
                                    <textarea id="snow-editor"
                                        class="form-control @error('kpi_indikatorKinerja') is-invalid @enderror {{ $kpi_indikatorKinerja ? 'is-valid' : '' }}"
                                        wire:model='kpi_indikatorKinerja' aria-label="With textarea" cols="20" rows="10"
                                        {{ $isShow ? 'disabled' : '' }}></textarea>
                                    <span class="invalid-feedback">{{ $errors->first('kpi_indikatorKinerja') }}</span>
                                </div>
                            </div> <!-- end col -->
                        </div> <!-- end row -->
                    @endif


                    {{-- IF EDIT OR NOT SHOW DISPLAY INPUT FILE DOKUMEN PENDUKUNG --}}
                    @if ($isEdit || !$isShow)
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="kpi_dokumenPendukung" class="form-label">Dokumen Pendukung (PDF,
                                        DOC,
                                        XLSX, IMG Max
                                        5MB)</label>
                                    <input type="file"
                                        class="form-control @error('kpi_dokumenPendukung') is-invalid @enderror"
                                        wire:model="kpi_dokumenPendukung">
                                    <span class="invalid-feedback">{{ $errors->first('kpi_dokumenPendukung') }}</span>
                                </div>
                            </div> <!-- end col -->
                        </div> <!-- end row -->
                    @endif

                    {{-- IF EDIT OR SHOW DISPLAY DOKUMEN PENDUKUNG --}}
                    @if ($isEdit || $isShow)

                        {{-- IF DOKUMEN NOT EXIST JUST SHOW ALERT, ON THE OTHER HAND DISPLAY BUTTON --}}
                        @if ($dokumen !== '-')
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="kpi_dokumenPendukung" class="form-label">Dokumen
                                            Pendukung</label><br>

                                        <a href="{{ asset('storage/' . $dokumen) }}" class="btn btn-primary"
                                            target="_blank">
                                            <i class="ri-file-line"></i> Show Dokumen
                                        </a>
                                    </div>
                                </div>
                            </div><!-- end row -->
                        @else
                            <div class="alert alert-danger mt-2 mb-2">
                                No supporting document data available.
                            </div>
                        @endif
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
                        @if (!$isShow)
                            <button type="button" wire:click.prevent='storeKPI' wire:loading.attr="disabled"
                                wire:target="storeKPI" class="btn btn-primary w-md waves-effect waves-light">Submit
                                <span wire:loading class="ms-2" wire:target="storeKPI">
                                    <span class="spinner-border spinner-border-sm" role="status"
                                        aria-hidden="true"></span>
                                </span>
                            </button>
                        @endif
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <div class="modal-backdrop fade show"></div>
@endif
