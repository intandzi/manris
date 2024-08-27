<div>

    @include('livewire.pages.risk-owner.risk-control.konteks-risiko-control.konteks-risiko-modal')

    <div class="container-fluid">

        <!-- breadcrumbs component -->
        <nav aria-label="breadcrumb" class="mb-2">
            @if ($this->role === 'risk owner')
                <ol class="breadcrumb mb-0 p-2">
                    <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-apps"></i>
                            App</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('riskControlOw.index', ['role' => $encryptedRole]) }}"
                            wire:navigate>
                            Risk Control</a></li>
                    <li class="breadcrumb-item active"><a href="#">Konteks Risiko</a>
                    </li>
                </ol>
            @else
                <ol class="breadcrumb mb-0 p-2">
                    <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-apps"></i>
                            App</a></li>
                    <li class="breadcrumb-item"><a
                            href="{{ route('officerRiskControl.index', ['role' => $encryptedRole]) }}" wire:navigate>
                            Risk Control</a></li>
                    <li class="breadcrumb-item active"><a href="#">Konteks Risiko</a>
                    </li>
                </ol>
            @endif
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
                                                <td style="word-break: break-word;">
                                                    {{ Str::limit($item->konteks_desc, 100, '...') }}
                                                </td>
                                                <td>
                                                    {{ ucwords($item->konteks_kategori) }}
                                                </td>
                                                <td>
                                                    <div class="btn-group gap-2" role="group">
                                                        @if ($item->konteks_lockStatus)
                                                            <span
                                                                class="badge badge-outline-danger rounded-pill mt-2">Locked!</span>
                                                        @else
                                                            <button type="button"
                                                                wire:click.prevent="editKonteks({{ $item->konteks_id }})"
                                                                wire:loading.attr="disabled"
                                                                wire:target="editKonteks({{ $item->konteks_id }})"
                                                                class="btn btn-warning btn-sm d-flex text-center align-items-center">
                                                                <i class="ri-pencil-fill" wire:loading.remove
                                                                    wire:target='editKonteks({{ $item->konteks_id }})'>
                                                                </i>
                                                                <span wire:loading
                                                                    wire:target="editKonteks({{ $item->konteks_id }})">
                                                                    <span class="spinner-border spinner-border-sm"
                                                                        role="status" aria-hidden="true"></span>
                                                                </span>
                                                            </button>
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
                                                            @if ($this->role === 'risk owner')
                                                                <button type="button"
                                                                    wire:click.prevent="openModalConfirm({{ $item->konteks_id }})"
                                                                    wire:loading.attr="disabled"
                                                                    wire:target="openModalConfirm({{ $item->konteks_id }})"
                                                                    class="btn btn-danger btn-sm d-flex text-center align-items-center">
                                                                    <i class="ri-lock-fill" wire:loading.remove
                                                                        wire:target='openModalConfirm({{ $item->konteks_id }})'>
                                                                    </i>
                                                                    <span wire:loading
                                                                        wire:target="openModalConfirm({{ $item->konteks_id }})">
                                                                        <span class="spinner-border spinner-border-sm"
                                                                            role="status" aria-hidden="true"></span>
                                                                    </span>
                                                                </button>
                                                            @else
                                                                <span
                                                                    class="badge badge-outline-danger rounded-pill mt-2">Bukan
                                                                    Hak Akses!</span>
                                                            @endif
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="btn-group gap-2" role="group">
                                                        @if ($item->konteks_lockStatus)
                                                            <button type="button"
                                                                wire:click.prevent="listRiskControl({{ $item->konteks_id }})"
                                                                wire:loading.attr="disabled"
                                                                wire:target="listRiskControl({{ $item->konteks_id }})"
                                                                class="btn btn-primary btn-sm d-flex text-center align-items-center">
                                                                <i class="ri-login-box-line me-1" wire:loading.remove
                                                                    wire:target='listRiskControl({{ $item->konteks_id }})'>
                                                                </i>
                                                                Identifikasi
                                                                <span class="ms-2" wire:loading
                                                                    wire:target="listRiskControl({{ $item->konteks_id }})">
                                                                    <span class="spinner-border spinner-border-sm"
                                                                        role="status" aria-hidden="true"></span>
                                                                </span>
                                                            </button>
                                                        @else
                                                            <span
                                                                class="badge badge-outline-danger rounded-pill mt-2">Selesaikan
                                                                Konteks!</span>
                                                        @endif
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


    @if ($isOpenConfirm)
        <div class="modal" tabindex="-1" role="dialog" style="display: block;" aria-modal="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Menunggu Konfirmasi</h5>
                        <button type="button" class="btn-close" aria-label="Close"
                            wire:click="closeXModalConfirm"></button>
                    </div>
                    <div class="modal-body">
                        Data tidak bisa diubah apabila Anda mengunci Konteks. Apakah Anda yakin ingin mengunci Konteks
                        ini?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click='closeModalConfirm'
                            wire:loading.attr="disabled" wire:target="closeModalConfirm">
                            Tutup
                            <span wire:loading class="ms-2" wire:target="closeModalConfirm">
                                <span class="spinner-border spinner-border-sm" role="status"
                                    aria-hidden="true"></span>
                            </span>
                        </button>
                        <button type="button" class="btn btn-primary" wire:click="lockKonteks"
                            wire:loading.attr="disabled" wire:target="lockKonteks">
                            Kunci Konteks
                            <span wire:loading class="ms-2" wire:target="lockKonteks">
                                <span class="spinner-border spinner-border-sm" role="status"
                                    aria-hidden="true"></span>
                            </span>
                        </button>
                    </div>
                </div>
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <div class="modal-backdrop fade show"></div>
    @endif
</div>
