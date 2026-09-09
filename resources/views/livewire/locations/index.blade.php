<div class="max-w-3xl mx-auto p-6">

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">{{ __('ui.locations_title') }}</h2>
        <select class="select select-bordered w-auto" wire:model.live="perPage">
            <option value="20">20</option>
            <option value="50">50</option>
            <option value="100">100</option>
            <option value="150">150</option>
        </select>
    </div>

    @if(session()->has('success'))
        <div class="alert alert-success mb-4">
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Form Card -->
    <div class="card bg-base-100 shadow p-4 mb-6">
        <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}">
            <div class="form-control mb-3">
                <label for="location-name" class="label">
                    <span class="label-text">{{ __('ui.location_name') }}</span>
                </label>
                <input id="location-name" type="text" wire:model="name"
                       class="input input-bordered w-full"
                       placeholder="{{ __('ui.location_name') }}">
                @error('name') <span class="text-error text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="flex gap-2">
                <button class="btn btn-primary">
                    {{ $isEdit ? __('ui.update') : __('ui.save') }}
                </button>
                @if($isEdit)
                    <button type="button" wire:click="resetInput" class="btn btn-ghost">
                        {{ __('ui.cancel') }}
                    </button>
                @endif
            </div>
        </form>
    </div>
    <!-- Table Card -->
    <div class="card bg-base-100 shadow">
        <div class="overflow-x-auto">
            <table class="table table-zebra w-full">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th class="text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($locations as $key => $location)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $location->name }}</td>
                            <td class="text-right flex justify-end gap-2">
                                <button wire:click="edit({{ $location->id }})"
                                        class="btn btn-sm btn-warning">{{ __('ui.edit') }}</button>

                                <button wire:click="delete({{ $location->id }})"
                                        onclick="confirm('Are you sure to delete this location?') || event.stopImmediatePropagation()"
                                        class="btn btn-sm btn-error">{{ __('ui.delete') }}</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-gray-400">{{ __('ui.no_locations') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $locations->links() }}
    </div>

</div>
