<div>

    @include('livewire.pages.u-m-r.manajemen-user.user-modal')

    <div class="container-fluid">

        <!-- breadcrumbs component -->
        <nav aria-label="breadcrumb" class="mb-2">
            <ol class="breadcrumb mb-0 p-2">
                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-apps"></i>
                        Main</a></li>
                <li class="breadcrumb-item active"><a href="{{ route('manajemenUser.index') }}"
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
                                User Management is essential for system, It involves creating, editing, and managing user accounts and their roles.
                            </p>
                        </div>
                        <div class="section-description-actions">
                            <button type="button" wire:click.prevent="openModal" class="btn btn-primary"
                                wire:loading.attr="disabled" wire:target="openModal"><i class="ri-add-line"></i> Create
                                User
                                <span wire:loading class="ms-2" wire:target="openModal">
                                    <span class="spinner-border spinner-border-sm" role="status"
                                        aria-hidden="true"></span>
                                </span>
                            </button>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-centered mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th wire:click.live="doSort('email')" style="cursor: pointer;">
                                            Email
                                            <x-sorting-table :orderAsc="$orderAsc" :orderBy="$orderBy" :columnName="'email'" />
                                        </th>
                                        <th wire:click.live="doSort('name')" style="cursor: pointer;">
                                            Nama
                                            <x-sorting-table :orderAsc="$orderAsc" :orderBy="$orderBy" :columnName="'name'" />
                                        </th>
                                        <th>Jabatan</th>
                                        <th>Peran</th>
                                        <th wire:click.live="doSort('name')" style="cursor: pointer;">
                                            Unit
                                        </th>
                                        <th wire:click.live="doSort('status')" style="cursor: pointer;">
                                            Status
                                            <x-sorting-table :orderAsc="$orderAsc" :orderBy="$orderBy" :columnName="'status'" />
                                        </th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($users as $index => $user)
                                        <tr>
                                            <td>
                                                {{ $user->email }}
                                            </td>
                                            <td>
                                                {{ ucwords($user->name) }}
                                            </td>
                                            <td>
                                                {{ ucwords($user->jabatan) }}
                                            </td>
                                            <td>
                                                @foreach (json_decode($user->role) as $item)
                                                    <span
                                                        class="badge badge-outline-secondary rounded-pill">{{ $item }}</span>
                                                @endforeach
                                            </td>
                                            <td>
                                                {{ ucwords($user->unit->unit_name) }}
                                            </td>
                                            <td>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox"
                                                        id="toggle_{{ $user->user_id }}"
                                                        wire:click="toggleActive({{ $user->user_id }})"
                                                        @if ($user->status === 1) checked @endif>
                                                </div>
                                            </td>
                                            <td>
                                                <button type="button"
                                                    wire:click.prevent="editUser({{ $user->user_id }})"
                                                    class="btn btn-warning btn-sm d-flex text-center align-items-center">
                                                    <i class="ri-pencil-line">
                                                    </i>
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
                                {!! $users->links() !!}
                            </div>
                        </div>

                    </div> <!-- end card body-->
                </div> <!-- end card -->
            </div><!-- end col-->
        </div><!-- end row -->

    </div> <!-- container -->
</div>
