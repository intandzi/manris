<div>
    @include('livewire.pages.umr.manajemen-unit.unit-modal')

    <div class="container-fluid">

        <!-- breadcrumbs component -->
        <nav aria-label="breadcrumb" class="mb-2">
            <ol class="breadcrumb mb-0 p-2">
                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-apps"></i>
                        App</a></li>
                <li class="breadcrumb-item active"><a
                        href="{{ route('manajemenUnit.index', ['role' => request()->query('role')]) }}"
                        wire:navigate>{{ $title }}</a>
                </li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title">{{ $title }}</h4>
                            <p class="text-muted mb-0">
                                Unit Management is essential for system, It involves creating, editing, and managing
                                units.
                            </p>
                        </div>
                        <div class="section-description-actions">
                            <button type="button" wire:click.prevent="openModal" class="btn btn-primary"
                                wire:loading.attr="disabled" wire:target="openModal"><i class="ri-add-line"></i> Create
                                Unit
                                <span wire:loading class="ms-2" wire:target="openModal">
                                    <span class="spinner-border spinner-border-sm" role="status"
                                        aria-hidden="true"></span>
                                </span>
                            </button>
                        </div>
                    </div>

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
                                <label for="" class="form-label">Search Unit</label>
                                <div class="input-group">
                                    <input wire:model.live.debounce.100ms="search" type="text" class="form-control"
                                        placeholder="Search Unit...">
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
                                        <th wire:click.live="doSort('unit_name')" style="cursor: pointer;">
                                            Unit
                                            <x-sorting-table :orderAsc="$orderAsc" :orderBy="$orderBy" :columnName="'unit_name'" />
                                        </th>
                                        <th wire:click.live="doSort('unit_activeStatus')" style="cursor: pointer;">
                                            Status
                                        </th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @forelse ($units as $index => $unit)
                                        <tr>
                                            <td>
                                                {{ $unit->unit_id }}
                                            </td>
                                            <td>
                                                {{ ucwords($unit->unit_name) }}
                                            </td>
                                            <td>
                                                {{-- <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox"
                                                        id="toggle_{{ $unit->unit_id }}"
                                                        wire:click="toggleActive({{ $unit->unit_id }})"
                                                        @if ($unit->unit_activeStatus === 1) checked @endif>
                                                </div> --}}
                                                @if ($unit->unit_activeStatus)
                                                    <button type="button" class="btn btn-success" readonly>
                                                        Aktif
                                                    </button>
                                                @else
                                                    <button type="button" class="btn btn-danger" readonly>
                                                        Non-aktif
                                                    </button>
                                                @endif
                                            </td>
                                            <td>
                                                <button type="button"
                                                    wire:click.prevent="editUnit({{ $unit->unit_id }})"
                                                    wire:loading.attr="disabled"
                                                    wire:target="editUnit({{ $unit->unit_id }})"
                                                    class="btn btn-warning btn-sm d-flex text-center align-items-center">
                                                    <i class="ri-pencil-fill" wire:loading.remove
                                                        wire:target='editUnit({{ $unit->unit_id }})'>
                                                    </i>
                                                    <span wire:loading wire:target="editUnit({{ $unit->unit_id }})">
                                                        <span class="spinner-border spinner-border-sm" role="status"
                                                            aria-hidden="true"></span>
                                                    </span>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <div class="alert alert-danger mt-2 mb-2">
                                            No data available.
                                        </div>
                                    @endforelse
                                </tbody>
                            </table>
                        </div> <!-- end table-responsive-->
                        <div class="row mt-2">
                            <div class="col-md-12 text-end">
                                {!! $units->links() !!}
                            </div>
                        </div>

                    </div> <!-- end card body-->
                </div> <!-- end card -->
            </div><!-- end col-->
        </div><!-- end row -->

        @if ($isOpenConfirmNonaktif)
            <div class="modal" tabindex="-1" role="dialog" style="display: block;" aria-modal="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Menunggu Konfirmasi</h5>
                            <button type="button" class="btn-close" aria-label="Close"
                                wire:click="closeXModalConfirmNonaktif"></button>
                        </div>
                        <div class="modal-body">
                            Apakah Anda yakin ingin menonaktifkan unit ini? Perlu diperhatikan bahwa pengguna yang
                            terkait dengan unit ini juga akan dinonaktifkan.
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" wire:click='closeModalConfirmNonaktif'
                                wire:loading.attr="disabled" wire:target="closeModalConfirmNonaktif">
                                Tutup
                                <span wire:loading class="ms-2" wire:target="closeModalConfirmNonaktif">
                                    <span class="spinner-border spinner-border-sm" role="status"
                                        aria-hidden="true"></span>
                                </span>
                            </button>
                            <button type="button" class="btn btn-primary" wire:click="confirmNonaktif"
                                wire:loading.attr="disabled" wire:target="confirmNonaktif">
                                Nonaktifkan Unit
                                <span wire:loading class="ms-2" wire:target="confirmNonaktif">
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


    </div> <!-- container -->
</div>
