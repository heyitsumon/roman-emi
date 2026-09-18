<?php

namespace App\Livewire\Locations;

use App\Models\Location;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $name;
    public $locationId;

    public $isEdit = false;
    public $isOpen = false;

    public $perPage = 30;

    protected $rules = [
        'name' => 'required|string|max:255',
    ];

    public function create()
    {
        abort_unless(auth()->user()->can('location-create'), 403);

        $this->resetInput();

        $this->isEdit = false;
        $this->isOpen = true;
    }

    public function store()
    {
        abort_unless(auth()->user()->can('location-create'), 403);

        $this->validate();

        $location = Location::create([
            'name' => $this->name,
        ]);

        $location->users()->attach(Auth::id(), [
            'is_owner' => true
        ]);

        $this->resetInput();
        $this->resetPage();

        session()->flash('success', 'Location added successfully');
    }

    public function edit($id)
    {
        abort_unless(auth()->user()->can('location-edit'), 403);

        $location = $this->accessibleLocations()->findOrFail($id);

        $this->locationId = $id;
        $this->name = $location->name;

        $this->isEdit = true;
        $this->isOpen = true;
    }

    public function update()
    {
        abort_unless(auth()->user()->can('location-edit'), 403);

        $this->validate();

        $this->accessibleLocations()
            ->whereKey($this->locationId)
            ->update([
                'name' => $this->name,
            ]);

        $this->resetInput();

        session()->flash('success', 'Location updated successfully');
    }

    public function delete($id)
    {
        abort_unless(auth()->user()->can('location-delete'), 403);

        $this->accessibleLocations()
            ->findOrFail($id)
            ->delete();

        $this->resetPage();

        session()->flash('success', 'Location deleted successfully');
    }

    public function resetInput()
    {
        $this->name = '';
        $this->locationId = null;
        $this->isEdit = false;
        $this->isOpen = false;

        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.locations.index', [
            'locations' => $this->accessibleLocations()
                ->latest()
                ->paginate($this->perPage),
        ]);
    }

    private function accessibleLocations()
    {
        return Auth::user()->locations();
    }
}