<div>

    @include('livewire.pages.umr.validasi-kpi.risk-register-umr.konteks-risiko.konteks-risiko-modal')

    <div class="container-fluid">

        <!-- breadcrumbs component -->
        <nav aria-label="breadcrumb" class="mb-2">
            <ol class="breadcrumb mb-0 p-2">
                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-apps"></i>
                        App</a></li>
                {{-- <li class="breadcrumb-item"><a
                        href="{{ route('listKPIUMR.index', ['role' => $encryptedRole, 'unit' => $encryptedUnit]) }}"
                        wire:navigate>
                        Risk Register</a></li> --}}
                <li class="breadcrumb-item">
                    Risk Register</li>
                <li class="breadcrumb-item active"><a href="#">Konteks Risiko</a>
                </li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">Konteks Risiko</h3>
                        <p class="card-text d-inline" style="font-weight: bold">{{ $kpi_kode }}</p>
                        <span>&nbsp;</span>
                        <p class="card-text d-inline">{{ ucwords($kpi_nama) }}</p>
                    </div> <!-- end card body-->
                    <div class="card-footer bg-transparent border-success">
                        <p class="card-text d-inline"><i class="ri-information-line d-inline"></i></p>
                        <span>&nbsp;</span>
                        <p class="card-text d-inline">Tahap ini Anda diminta melakukan identifikasi konteks risiko
                            dengan memperhatikan target atau
                            Key Performance Indicator yang telah dipilih.</p>
                    </div>
                </div> <!-- end card -->
            </div><!-- end col-->
        </div><!-- end row -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-1 me-2">
                                <label for="" class="form-label">Show Data</label>
                                <select wire:model.live="perPage" class="form-control" id="">
                                    <option selected value="5">--</option>
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                            </div>
                            <div class="col-3 ms-auto">
                                <label for="" class="form-label">Search</label>
                                <div class="input-group">
                                    <input wire:model.live.debounce.100ms="search" type="text" class="form-control"
                                        placeholder="Search...">
                                    @if ($search)
                                        <button type="button" wire:click.prevent="clearSearch"
                                            class="btn btn-primary d-flex align-items-center btn-sm"
                                            title="Cancel Edit">
                                            <i class="ri-close-line">
                                            </i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive mt-2">
                            <table class="table table-centered mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No</th>
                                        <th wire:click.live="doSort('konteksRisiko_kode')" style="cursor: pointer;">
                                            Kode
                                            <x-sorting-table :orderAsc="$orderAsc" :orderBy="$orderBy" :columnName="'konteksRisiko_kode'" />
                                        </th>
                                        <th wire:click.live="doSort('konteksRisiko_desc')"
                                            style="cursor: pointer; width:500px;">
                                            Deskripsi Konteks
                                        </th>
                                        <th>
                                            Kategori Konteks
                                        </th>
                                        <th>
                                            Aksi
                                        </th>
                                        <th>Tindak Lanjut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @if ($konteksRisikos && count($konteksRisikos) > 0)
                                        @forelse ($konteksRisikos as $index => $item)
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td>
                                                    {{ $item->konteks_kode }}
                                                </td>
                                                <td>
                                                    {{ Str::limit($item->konteks_desc, 100, '...') }}
                                                </td>
                                                <td>
                                                    {{ ucwords($item->konteks_kategori) }}
                                                </td>
                                                <td>
                                                    <div class="btn-group gap-2" role="group">
                                                        <button type="button"
                                                            wire:click.prevent="showKonteks({{ $item->konteks_id }})"
                                                            wire:loading.attr="disabled"
                                                            wire:target="showKonteks({{ $item->konteks_id }})"
                                                            class="btn btn-primary btn-sm d-flex text-center align-items-center">
                                                            <i class="ri-eye-fill" wire:loading.remove
                                                                wire:target='showKonteks({{ $item->konteks_id }})'>
                                                            </i>
                                                            <span wire:loading
                                                                wire:target="showKonteks({{ $item->konteks_id }})">
                                                                <span class="spinner-border spinner-border-sm"
                                                                    role="status" aria-hidden="true"></span>
                                                            </span>
                                                        </button>
                                                    </div>
                                                </td>
                                                <td>
                                                    {{-- <div
                                                        class="d-flex justify-content-center align-items-center flex-column gap-2">
                                                        <div class="btn-group gap-2" role="group">
                                                            <button type="button"
                                                                wire:click.prevent="identifikasiRisiko({{ $item->konteks_id }})"
                                                                wire:loading.attr="disabled"
                                                                wire:target="identifikasiRisiko({{ $item->konteks_id }})"
                                                                class="btn btn-primary btn-sm d-flex text-center align-items-center">
                                                                <i class="ri-login-box-line me-1" wire:loading.remove
                                                                    wire:target='identifikasiRisiko({{ $item->konteks_id }})'>
                                                                </i>
                                                                Identifikasi
                                                                <span class="ms-2" wire:loading
                                                                    wire:target="identifikasiRisiko({{ $item->konteks_id }})">
                                                                    <span class="spinner-border spinner-border-sm"
                                                                        role="status" aria-hidden="true"></span>
                                                                </span>
                                                            </button>
                                                            <button type="button"
                                                                wire:click.prevent="openModalConfirm({{ $item->konteks_id }})"
                                                                wire:loading.attr="disabled"
                                                                wire:target="openModalConfirm({{ $item->konteks_id }})"
                                                                class="btn btn-success btn-sm d-flex text-center align-items-center">
                                                                <i class="ri-checkbox-circle-line me-1"
                                                                    wire:loading.remove
                                                                    wire:target='openModalConfirm({{ $item->konteks_id }})'>
                                                                </i>
                                                                Verifikasi
                                                                <span class="ms-2" wire:loading
                                                                    wire:target="openModalConfirm({{ $item->konteks_id }})">
                                                                    <span class="spinner-border spinner-border-sm"
                                                                        role="status" aria-hidden="true"></span>
                                                                </span>
                                                            </button>
                                                        </div>
                                                        <button type="button"
                                                            wire:click.prevent="openModalConfirmDelete({{ $item->konteks_id }})"
                                                            wire:loading.attr="disabled"
                                                            wire:target="openModalConfirmDelete({{ $item->konteks_id }})"
                                                            class="btn btn-danger btn-sm d-flex text-center align-items-center">
                                                            <i class="ri-arrow-go-back-line me-1" wire:loading.remove
                                                                wire:target='openModalConfirmDelete({{ $item->konteks_id }})'>
                                                            </i>
                                                            Kembalikan
                                                            <span class="ms-2" wire:loading
                                                                wire:target="openModalConfirmDelete({{ $item->konteks_id }})">
                                                                <span class="spinner-border spinner-border-sm"
                                                                    role="status" aria-hidden="true"></span>
                                                            </span>
                                                        </button>
                                                    </div> --}}
                                                    <div
                                                        class="d-flex justify-content-center align-items-center gap-2 flex-column">
                                                        <div class="btn-group gap-2 d-flex flex-column" role="group">
                                                            <button type="button"
                                                                wire:click.prevent="identifikasiRisiko({{ $item->konteks_id }})"
                                                                wire:loading.attr="disabled"
                                                                wire:target="identifikasiRisiko({{ $item->konteks_id }})"
                                                                class="btn btn-primary btn-sm d-flex text-center align-items-center">
                                                                <i class="ri-login-box-line me-1" wire:loading.remove
                                                                    wire:target='identifikasiRisiko({{ $item->konteks_id }})'>
                                                                </i>
                                                                Identifikasi
                                                                <span class="ms-2" wire:loading
                                                                    wire:target="identifikasiRisiko({{ $item->konteks_id }})">
                                                                    <span class="spinner-border spinner-border-sm"
                                                                        role="status" aria-hidden="true"></span>
                                                                </span>
                                                            </button>
                                                            <button type="button"
                                                                wire:click.prevent="openModalConfirmDelete({{ $item->konteks_id }})"
                                                                wire:loading.attr="disabled"
                                                                wire:target="openModalConfirmDelete({{ $item->konteks_id }})"
                                                                class="btn btn-danger btn-sm d-flex text-center align-items-center">
                                                                <i class="ri-arrow-go-back-line me-1"
                                                                    wire:loading.remove
                                                                    wire:target='openModalConfirmDelete({{ $item->konteks_id }})'>
                                                                </i>
                                                                Kembalikan
                                                                <span class="ms-2" wire:loading
                                                                    wire:target="openModalConfirmDelete({{ $item->konteks_id }})">
                                                                    <span class="spinner-border spinner-border-sm"
                                                                        role="status" aria-hidden="true"></span>
                                                                </span>
                                                            </button>
                                                            <button type="button"
                                                                wire:click.prevent="openModalConfirm({{ $item->konteks_id }})"
                                                                wire:loading.attr="disabled"
                                                                wire:target="openModalConfirm({{ $item->konteks_id }})"
                                                                class="btn btn-success btn-sm d-flex text-center align-items-center">
                                                                <i class="ri-checkbox-circle-line me-1"
                                                                    wire:loading.remove
                                                                    wire:target='openModalConfirm({{ $item->konteks_id }})'>
                                                                </i>
                                                                Verifikasi
                                                                <span class="ms-2" wire:loading
                                                                    wire:target="openModalConfirm({{ $item->konteks_id }})">
                                                                    <span class="spinner-border spinner-border-sm"
                                                                        role="status" aria-hidden="true"></span>
                                                                </span>
                                                            </button>
                                                        </div>
                                                    </div>

                                                </td>
                                            </tr>
                                        @empty
                                            <div class="alert alert-danger mt-2 mb-2">
                                                No data available.
                                            </div>
                                        @endforelse
                                    @else
                                        <div class="alert alert-danger mt-2 mb-2">
                                            No data available.
                                        </div>
                                    @endif
                                </tbody>
                            </table>
                        </div> <!-- end table-responsive-->
                        <div class="row mt-2">
                            <div class="col-md-12 text-end">
                                {!! $konteksRisikos->links() !!}
                            </div>
                        </div>

                    </div> <!-- end card body-->
                </div> <!-- end card -->
            </div><!-- end col-->
        </div><!-- end row -->

    </div> <!-- container -->
</div>
