<?php

namespace App\Livewire;

use App\Models\Location;
use Livewire\Component;

class Navbar extends Component
{
      public $currentRoute;
    public $activeLocationId;
    public $locale;

    public function mount()
    {
        $this->currentRoute = request()->routeIs() ? request()->route()->getName() : '';
        $this->locale = app()->getLocale();
        $this->activeLocationId = session('active_location_id')
            ?: auth()->user()?->locations()->orderBy('locations.id')->value('locations.id');

        if ($this->activeLocationId) {
            session(['active_location_id' => $this->activeLocationId]);
        }
    }

    public function setActive($route)
    {
        $this->currentRoute = $route;
    }

    public function switchLocation($locationId): void
    {
        abort_unless(auth()->user()->locations()->whereKey($locationId)->exists(), 403);

        session(['active_location_id' => (int) $locationId]);
        $this->activeLocationId = (int) $locationId;
        $this->dispatch('location-switched');
    }

    public function switchLocale($locale): void
    {
        abort_unless(in_array($locale, ['en', 'bn'], true), 400);

        session(['locale' => $locale]);
        $this->locale = $locale;
        app()->setLocale($locale);
        $this->redirect(url()->current(), navigate: true);
    }

    public function render()
    {
        return view('livewire.navbar', [
            'locations' => auth()->check()
                ? auth()->user()->locations()->orderBy('name')->get(['locations.id', 'locations.name'])
                : collect(),
        ]);
    }
}
