<div>
    <div class="container-fluid">
        
        <!-- breadcrumbs component -->
        <nav aria-label="breadcrumb" class="mb-2">
            <ol class="breadcrumb mb-0 p-2">
                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-apps"></i>
                        App</a></li>
                <li class="breadcrumb-item active"><a href="{{ route('laporan-mrisk.index', ['role' => request()->query('role')]) }}"
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
                                            <x-sorting-table :orderAsc="$orderAsc" :orderBy="$orderBy" :columnName="'unit_nama'" />
                                        </th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($units as $index => $unit)
                                        <tr>
                                            <td>
                                                {{ $unit->unit_id }}
                                            </td>
                                            <td>
                                                {{ ucwords($unit->unit_name) }}
                                            </td>
                                            <td>
                                                <div class="d-inline-flex gap-2">
                                                    <button type="button"
                                                        wire:click.prevent="listKPILaporan({{ $unit->unit_id }})"
                                                        wire:loading.attr="disabled"
                                                        wire:target="listKPILaporan({{ $unit->unit_id }})"
                                                        class="btn btn-primary btn-sm d-flex text-center align-items-center">
                                                        <i class="ri-eye-fill me-1"></i>
                                                        Detail
                                                        <span wire:loading
                                                            wire:target="listKPILaporan({{ $unit->unit_id }})">
                                                            <span class="spinner-border spinner-border-sm ms-2"
                                                                role="status" aria-hidden="true"></span>
                                                        </span>
                                                    </button>
                                                </div>

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

    </div> <!-- container -->{{-- If you look to others for fulfillment, you will never truly be fulfilled. --}}
</div>
