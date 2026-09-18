<div class="container-xl py-4">

    {{-- Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">

        <div>
            <h2 class="fw-bold mb-1">
                {{ __('ui.locations_title') }}
            </h2>

            <p class="text-muted mb-0">
                Manage your locations
            </p>
        </div>

        <div class="d-flex align-items-center gap-2">

            <div style="width: 110px;">
                <select class="form-select" wire:model.live="perPage">
                    <option value="20">20</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="150">150</option>
                </select>
            </div>

            {{-- Create Location --}}
            @can('location-create')
                <button type="button"
                        class="btn btn-primary px-4"
                        wire:click="create">
                    Create Location
                </button>
            @endcan

        </div>

    </div>


    {{-- Success Message --}}
    @if(session()->has('success'))

        <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-3 mb-4"
             role="alert">

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- Table Card --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

        <div class="card-header bg-white border-0 px-4 py-3">

            <div class="d-flex justify-content-between align-items-center">

                <div>
                    <h5 class="fw-bold mb-1">
                        {{ __('ui.locations_title') }}
                    </h5>

                    <small class="text-muted">
                        Location list
                    </small>
                </div>

                <span class="badge bg-primary-subtle text-primary px-3 py-2">
                    {{ $locations->total() }}
                </span>

            </div>

        </div>


        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">

                    <tr>
                        <th class="px-4">#</th>
                        <th>{{ __('ui.location_name') }}</th>
                        <th class="text-end px-4">Action</th>
                    </tr>

                </thead>


                <tbody>

                    @forelse($locations as $key => $location)

                        <tr>

                            <td class="px-4 fw-semibold text-muted">
                                {{ $key + 1 }}
                            </td>

                            <td>
                                <span class="fw-semibold">
                                    {{ $location->name }}
                                </span>
                            </td>

                            <td class="text-end px-4">

                                <div class="d-flex justify-content-end gap-2">

                                    @can('location-edit')

                                        <button
                                            wire:click="edit({{ $location->id }})"
                                            class="btn btn-sm btn-warning px-3">

                                            {{ __('ui.edit') }}

                                        </button>

                                    @endcan


                                    @can('location-delete')

                                        <button
                                            wire:click="delete({{ $location->id }})"
                                            onclick="confirm('Are you sure to delete this location?') || event.stopImmediatePropagation()"
                                            class="btn btn-sm btn-danger px-3">

                                            {{ __('ui.delete') }}

                                        </button>

                                    @endcan

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="3"
                                class="text-center py-5">

                                <div class="text-muted">

                                    <div class="fs-5 fw-semibold mb-1">
                                        {{ __('ui.no_locations') }}
                                    </div>

                                    <small>
                                        No locations available.
                                    </small>

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
        {{ $locations->links() }}
    </div>


    {{-- Modern Location Modal --}}
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
                                {{ $isEdit ? __('ui.update') : __('ui.save') }}
                                {{ __('ui.location_name') }}
                            </h5>

                            <small class="text-muted">
                                Add or update location information
                            </small>
                        </div>

                        <button type="button"
                                class="btn-close"
                                wire:click="resetInput">
                        </button>

                    </div>


                    {{-- Modal Body --}}
                    <div class="modal-body p-4">

                        <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}">

                            <div class="mb-3">

                                <label for="location-name"
                                       class="form-label fw-semibold">
                                    {{ __('ui.location_name') }}
                                </label>

                                <input
                                    id="location-name"
                                    type="text"
                                    wire:model="name"
                                    class="form-control form-control-lg @error('name') is-invalid @enderror"
                                    placeholder="{{ __('ui.location_name') }}"
                                    autofocus
                                >

                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Modal Buttons --}}
                            <div class="d-flex justify-content-end gap-2 pt-2">

                                <button type="button"
                                        wire:click="resetInput"
                                        class="btn btn-light border px-4">

                                    {{ __('ui.cancel') }}

                                </button>

                                <button type="submit"
                                        class="btn btn-primary px-4">

                                    {{ $isEdit ? __('ui.update') : __('ui.save') }}

                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    @endif

</div>