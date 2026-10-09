<?php

namespace Tests\Feature;

use App\Livewire\Customers\Index;
use App\Models\Customer;
use App\Models\Location;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CustomerDetailAccessTest extends TestCase
{
    use RefreshDatabase;

    private int $customerSequence = 0;

    private function createLocation(string $name): Location
    {
        return Location::create(['name' => $name]);
    }

    private function createCustomer(Location $location, string $name = 'Test Customer'): Customer
    {
        $this->customerSequence++;

        return Customer::create([
            'customer_name'  => $name,
            'customer_id'    => 1000 + $this->customerSequence,
            'customer_phone' => '017' . str_pad((string) random_int(0, 99999999), 8, '0', STR_PAD_LEFT),
            'location_id'    => $location->id,
        ]);
    }

    public function test_profile_pic_modal_opens_for_customer_shown_in_list_to_user_without_locations(): void
    {
        $customer = $this->createCustomer($this->createLocation('Dhaka'), 'Maria Islam');
        $this->actingAs(User::factory()->create());

        // The customer list shows the customer...
        Livewire::test(Index::class)->assertSee('Maria Islam');

        // ...so clicking the profile picture (view modal) must not 404.
        Livewire::test(Index::class)
            ->call('openModal', $customer->id)
            ->assertSet('showModal', true)
            ->assertSee('Maria Islam');
    }

    public function test_emi_installment_page_opens_for_customer_shown_in_list_to_user_without_locations(): void
    {
        $customer = $this->createCustomer($this->createLocation('Dhaka'), 'Maria Islam');
        $this->actingAs(User::factory()->create());

        // The customer list shows the customer...
        Livewire::test(Index::class)->assertSee('Maria Islam');

        // ...so the customer name link (installment / EMI plan page) must open too.
        $this->get(route('customers.emi_plans', $customer->id))->assertOk();
    }

    public function test_customers_outside_user_locations_stay_hidden_and_scoped(): void
    {
        $dhaka = $this->createLocation('Dhaka');
        $chittagong = $this->createLocation('Chittagong');

        $own = $this->createCustomer($dhaka, 'Own Customer');
        $foreign = $this->createCustomer($chittagong, 'Foreign Customer');

        $user = User::factory()->create();
        $user->locations()->attach($dhaka->id, ['is_owner' => true]);
        $this->actingAs($user);

        // The list only shows customers of the user's locations.
        Livewire::test(Index::class)
            ->assertSee('Own Customer')
            ->assertDontSee('Foreign Customer');

        // Own customer details open fine.
        $this->get(route('customers.emi_plans', $own->id))->assertOk();

        // Customers outside the user's locations are still protected with 404.
        $this->get(route('customers.emi_plans', $foreign->id))->assertNotFound();
    }

    public function test_trashed_customer_shown_in_trash_list_can_still_be_opened(): void
    {
        $customer = $this->createCustomer($this->createLocation('Dhaka'), 'Deleted Customer');
        $customer->delete();

        $user = User::factory()->create();
        $user->assignRole(Role::findOrCreate('admin', 'web'));
        $this->actingAs($user);

        // Trash view lists trashed customers.
        Livewire::test(Index::class)
            ->call('toggleTrash')
            ->assertSee('Deleted Customer');

        // Profile picture click and name link must work for trashed rows too.
        Livewire::test(Index::class)
            ->call('toggleTrash')
            ->call('openModal', $customer->id)
            ->assertSet('showModal', true);

        $this->get(route('customers.emi_plans', $customer->id))->assertOk();
    }
}