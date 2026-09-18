<div class="container-xl py-4">

    {{-- Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1">Roles</h2>
            <p class="text-muted mb-0">Manage roles and permissions</p>
        </div>

        @can('role-create')
            <button wire:click="create" class="btn btn-primary px-4">
                + New Role
            </button>
        @endcan
    </div>

    {{-- Flash Messages --}}
    @if(session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-3 mb-4"
             role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @elseif(session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm rounded-3 mb-4"
             role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Search & Per Page --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <div class="row g-3 align-items-center">

                <div class="col-12 col-md-5">
                    <input type="text"
                           wire:model.live="search"
                           placeholder="Search Roles..."
                           class="form-control">
                </div>

                <div class="col-12 col-md-auto ms-md-auto">
                    <select wire:model="perPage"
                            class="form-select"
                            style="min-width: 120px;">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="25">25</option>
                    </select>
                </div>

            </div>
        </div>
    </div>

    {{-- Roles Table --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

        <div class="card-header bg-white border-0 px-4 py-3">
            <div>
                <h5 class="fw-bold mb-1">Roles</h5>
                <small class="text-muted">Role and permission list</small>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">
                <tr>
                    <th class="px-4">#</th>
                    <th>Name</th>
                    <th>Permissions</th>
                    <th class="text-end px-4">Actions</th>
                </tr>
                </thead>

                <tbody>

                @forelse($roles as $key => $role)

                    <tr>

                        <td class="px-4 fw-semibold text-muted">
                            {{ $roles->firstItem() + $key }}
                        </td>

                        <td>
                            <span class="fw-semibold">
                                {{ $role->name }}
                            </span>
                        </td>

                        <td>
                            <div class="d-flex flex-wrap gap-1">
                                @foreach($role->permissions as $perm)
                                    <span class="badge bg-info-subtle text-info-emphasis px-2 py-1">
                                        {{ $perm->name }}
                                    </span>
                                @endforeach
                            </div>
                        </td>

                        <td class="text-end px-4">

                            <div class="d-flex justify-content-end gap-2">

                                @can('role-edit')
                                    <button wire:click="edit({{ $role->id }})"
                                            class="btn btn-sm btn-warning px-3">
                                        Edit
                                    </button>
                                @endcan

                                @can('role-delete')
                                    <button wire:click="delete({{ $role->id }})"
                                            onclick="confirm('Are you sure?') || event.stopImmediatePropagation()"
                                            class="btn btn-sm btn-danger px-3">
                                        Delete
                                    </button>
                                @endcan

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="4" class="text-center py-5">
                            <div class="text-muted">
                                <div class="fs-5 fw-semibold mb-1">
                                    No roles found.
                                </div>
                            </div>
                        </td>
                    </tr>

                @endforelse

                </tbody>

            </table>
        </div>

    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $roles->links() }}
    </div>

    {{-- Modal --}}
    @if($isOpen)

        <div class="modal fade show d-block"
             tabindex="-1"
             style="background: rgba(0,0,0,.55);">

            <div class="modal-dialog modal-dialog-centered">

                <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

                    {{-- Modal Header --}}
                    <div class="modal-header bg-light border-0 px-4 py-3">

                        <div>
                            <h5 class="modal-title fw-bold mb-1">
                                {{ $role_id ? 'Edit Role' : 'New Role' }}
                            </h5>

                            <small class="text-muted">
                                Manage role permissions
                            </small>
                        </div>

                        <button type="button"
                                class="btn-close"
                                wire:click="closeModal">
                        </button>

                    </div>

                    {{-- Modal Body --}}
                    <div class="modal-body p-4">

                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Role Name
                            </label>

                            <input type="text"
                                   wire:model="name"
                                   placeholder="Role Name"
                                   class="form-control form-control-lg">

                            @error('name')
                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        {{-- Permissions --}}
                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Permissions
                            </label>

                            <div class="row g-2">

                                @foreach($allPermissions as $perm)

                                    <div class="col-12 col-md-6">

                                        <div class="form-check border rounded-3 p-2 ps-5">

                                            <input type="checkbox"
                                                   wire:model="permissions"
                                                   value="{{ $perm->name }}"
                                                   class="form-check-input">

                                            <label class="form-check-label">
                                                {{ $perm->name }}
                                            </label>

                                        </div>

                                    </div>

                                @endforeach

                            </div>

                            @error('permissions')
                                <div class="text-danger small mt-2">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>

                    {{-- Modal Footer --}}
                    <div class="modal-footer bg-light border-0 px-4 py-3">

                        <button wire:click="closeModal"
                                class="btn btn-light border px-4">
                            Cancel
                        </button>

                        @if($role_id)

                            <button wire:click="update"
                                    class="btn btn-success px-4">
                                Update
                            </button>

                        @else

                            <button wire:click="store"
                                    class="btn btn-primary px-4">
                                Save
                            </button>

                        @endif

                    </div>

                </div>

            </div>

        </div>

    @endif

</div>