<div>
    @include('livewire.pages.umr.manajemen-kategori-standar.kategori-standar-modal')

    <div class="container-fluid">

        <!-- breadcrumbs component -->
        <nav aria-label="breadcrumb" class="mb-2">
            <ol class="breadcrumb mb-0 p-2">
                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-apps"></i>
                        App</a></li>
                <li class="breadcrumb-item active"><a href="{{ route('manajemenKategoriStandar.index', ['role' => request()->query('role')]) }}"
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
                                Kategori Standart Management is essential for system, It involves creating, editing, and managing
                                Kategori Standart.
                            </p>
                        </div>
                        <div class="section-description-actions">
                            <button type="button" wire:click.prevent="openModal" class="btn btn-primary"
                                wire:loading.attr="disabled" wire:target="openModal"><i class="ri-add-line"></i> Create
                                Kategori
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
                                <label for="" class="form-label">Search Kategori</label>
                                <div class="input-group">
                                    <input wire:model.live.debounce.100ms="search" type="text" class="form-control"
                                        placeholder="Search Kategori...">
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
                                        <th wire:click.live="doSort('kategoriStandar_desc')" style="cursor: pointer;">
                                            Deskripsi Kategori Standar
                                            <x-sorting-table :orderAsc="$orderAsc" :orderBy="$orderBy" :columnName="'kategoriStandar_desc'" />
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
                                    @forelse ($kategoriStandars as $index => $kategoriStandar)
                                        <tr>
                                            <td>
                                                {{ $kategoriStandar->kategoriStandar_id }}
                                            </td>
                                            <td>
                                                {{ ucwords($kategoriStandar->kategoriStandar_desc) }}
                                            </td>
                                            <td>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox"
                                                        id="toggle_{{ $kategoriStandar->kategoriStandar_id }}"
                                                        wire:click="toggleActive({{ $kategoriStandar->kategoriStandar_id }})"
                                                        @if ($kategoriStandar->kategoriStandar_activeStatus === 1) checked @endif>
                                                </div>
                                            </td>
                                            <td>
                                                <button type="button"
                                                    wire:click.prevent="editKategoriStandar({{ $kategoriStandar->kategoriStandar_id }})"
                                                    wire:loading.attr="disabled" wire:target="editKategoriStandar({{ $kategoriStandar->kategoriStandar_id }})"
                                                    class="btn btn-warning btn-sm d-flex text-center align-items-center">
                                                    <i class="ri-pencil-fill" wire:loading.remove wire:target='editKategoriStandar({{ $kategoriStandar->kategoriStandar_id }})'>
                                                    </i>
                                                    <span wire:loading wire:target="editKategoriStandar({{ $kategoriStandar->kategoriStandar_id }})">
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
                                {!! $kategoriStandars->links() !!}
                            </div>
                        </div>

                    </div> <!-- end card body-->
                </div> <!-- end card -->
            </div><!-- end col-->
        </div><!-- end row -->

    </div> <!-- container -->
</div>
