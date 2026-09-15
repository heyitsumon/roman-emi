<?php

namespace App\Livewire\Roles;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class Index extends Component
{
   use WithPagination;

    public $role_id;
    public $name;
    public $permissions = [];
    public $allPermissions;

    public $isOpen = false;
    public $search = '';
    public $perPage = 10;

    protected $rules = [
        'name' => 'required|string|max:255|unique:roles,name',
        'permissions' => 'required|array|min:1',
    ];

    public function mount()
    {
        $this->allPermissions = Permission::all();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $roles = Role::where('name','like',"%{$this->search}%")
            ->orderBy('id','desc')
            ->paginate($this->perPage);

        return view('livewire.roles.index', compact('roles'));
    }

    public function create()
    {
        abort_unless(auth()->user()->can('role-create'), 403);

        $this->resetInput();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    public function resetInput()
    {
        $this->role_id = null;
        $this->name = '';
        $this->permissions = [];
        $this->closeModal();
    }

    public function store()
    {
        abort_unless(auth()->user()->can('role-create'), 403);

        $this->validate();

        $role = Role::create(['name' => $this->name]);
        $role->syncPermissions($this->permissions);

        Artisan::call('permission:cache-reset');
        Cache::flush();

        session()->flash('success', 'Role created successfully.');
        $this->resetInput();
    }

    public function edit($id)
    {
        abort_unless(auth()->user()->can('role-edit'), 403);

        $role = Role::findOrFail($id);
        $this->role_id = $id;
        $this->name = $role->name;
        $this->permissions = $role->permissions()->pluck('name')->toArray();
        $this->openModal();
    }

    public function update()
    {
        abort_unless(auth()->user()->can('role-edit'), 403);

        $role = Role::findOrFail($this->role_id);

        $this->validate([
            'name' => 'required|string|max:255|unique:roles,name,'.$role->id,
            'permissions' => 'required|array|min:1',
        ]);

        $role->update(['name' => $this->name]);
        $role->syncPermissions($this->permissions);

        Artisan::call('permission:cache-reset');
        Cache::flush();

        session()->flash('success', 'Role updated successfully.');
        $this->resetInput();
    }

    public function delete($id)
    {
        abort_unless(auth()->user()->can('role-delete'), 403);

        Role::findOrFail($id)->delete();
        session()->flash('error', 'Role deleted successfully.');
    }
}
