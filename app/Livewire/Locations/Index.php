<?php

namespace App\Livewire\Locations;

use App\Models\Location;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind'; // DaisyUI compatible

    public $name;
    public $locationId;
    public $isEdit = false;
    public $perPage = 30;

    protected $rules = [
        'name' => 'required|string|max:255',
    ];

    public function store()
    {
        $this->validate();

        $location = Location::create([
            'name' => $this->name,
        ]);
        $location->users()->attach(Auth::id(), ['is_owner' => true]);

        $this->resetInput();
        $this->resetPage(); // important

        session()->flash('success', 'Location added successfully');
    }

    public function edit($id)
    {
        $location = $this->accessibleLocations()->findOrFail($id);

        $this->locationId = $id;
        $this->name = $location->name;
        $this->isEdit = true;
    }

    public function update()
    {
        $this->validate();

        $this->accessibleLocations()->whereKey($this->locationId)->update([
            'name' => $this->name,
        ]);

        $this->resetInput();

        session()->flash('success', 'Location updated successfully');
    }

    public function delete($id)
    {
        $this->accessibleLocations()->findOrFail($id)->delete();
        $this->resetPage(); // fix pagination bug

        session()->flash('success', 'Location deleted successfully');
    }

    public function resetInput()
    {
        $this->name = '';
        $this->locationId = null;
        $this->isEdit = false;
    }

    public function render()
    {
        return view('livewire.locations.index', [
            'locations' => $this->accessibleLocations()->latest()->paginate($this->perPage),
        ]);
    }

    private function accessibleLocations()
    {
        return Auth::user()->locations();
    }
}
