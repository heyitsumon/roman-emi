<div class="container-xl py-4">

    {{-- Header --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
                <h2 class="fw-bold mb-0">User Management</h2>

                @can('user-create')
                    <button class="btn btn-primary px-4" wire:click="create">
                        + New User
                    </button>
                @endcan
            </div>
        </div>
    </div>

    {{-- Success Message --}}
    @if(session()->has('success'))
        <div class="alert alert-success shadow-sm rounded-3 mb-4">
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- Users Table --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">
                    <tr>
                        <th class="px-4">#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Roles</th>
                        <th class="text-end px-4">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($users as $user)
                        <tr>

                            <th class="px-4">
                                <span class="text-muted small">
                                    {{ $loop->iteration }}
                                </span>
                            </th>

                            <td>
                                <span class="fw-semibold">
                                    {{ $user->name }}
                                </span>
                            </td>

                            <td>
                                <span class="text-muted">
                                    {{ $user->email }}
                                </span>
                            </td>

                            <td>
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach($user->roles as $role)
                                        <span class="badge bg-info-subtle text-info-emphasis rounded-pill px-3 py-2">
                                            {{ $role->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>

                            <td class="text-end px-4">
                                <div class="d-flex justify-content-end gap-2">

                                    @can('user-edit')
                                        <button class="btn btn-sm btn-outline-warning"
                                                wire:click="edit({{ $user->id }})">
                                            Edit
                                        </button>
                                    @endcan

                                    @can('user-delete')
                                        <button class="btn btn-sm btn-outline-danger"
                                                onclick="confirm('Are you sure to delete this user?') || event.stopImmediatePropagation()"
                                                wire:click="delete({{ $user->id }})">
                                            Delete
                                        </button>
                                    @endcan

                                </div>
                            </td>

                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $users->links() }}
    </div>

    {{-- Modal --}}
    @if($isOpen)
        <div class="modal fade show d-block"
             tabindex="-1"
             style="background: rgba(0,0,0,.5);"
             wire:click.self="closeModal">

            <div class="modal-dialog modal-dialog-centered modal-md">
                <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

                    {{-- Modal Header --}}
                    <div class="modal-header bg-light border-0 p-4">
                        <h5 class="modal-title fw-bold mb-0">
                            {{ $user_id ? 'Edit User' : 'Create User' }}
                        </h5>

                        <button type="button"
                                class="btn-close"
                                wire:click="closeModal">
                        </button>
                    </div>

                    {{-- Modal Body --}}
                    <div class="modal-body p-4">

                        {{-- Name --}}
                        <div class="mb-3">
                            <input type="text"
                                   placeholder="Name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   wire:model="name">

                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="mb-3">
                            <input type="email"
                                   placeholder="Email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   wire:model="email">

                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div class="mb-3">
                            <input type="password"
                                   placeholder="Password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   wire:model="password">

                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Roles --}}
                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Roles
                            </label>

                            <div class="border rounded-3 p-3 bg-light">
                                <div class="row g-2">

                                    @foreach($allRoles as $role)
                                        <div class="col-6 col-md-4">
                                            <div class="form-check">
                                                <input type="checkbox"
                                                       class="form-check-input"
                                                       wire:model="roles"
                                                       value="{{ $role->name }}"
                                                       id="role_{{ $role->id }}">

                                                <label class="form-check-label"
                                                       for="role_{{ $role->id }}">
                                                    {{ $role->name }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach

                                </div>
                            </div>

                            @error('roles')
                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>

                    {{-- Modal Footer --}}
                    <div class="modal-footer bg-light border-0 p-4">

                        @if($user_id)
                            <button class="btn btn-success px-4"
                                    wire:click="update">
                                Update
                            </button>
                        @else
                            <button class="btn btn-primary px-4"
                                    wire:click="store">
                                Save
                            </button>
                        @endif

                        <button class="btn btn-light border"
                                wire:click="closeModal">
                            Cancel
                        </button>

                    </div>

                </div>
            </div>

        </div>
    @endif

</div>