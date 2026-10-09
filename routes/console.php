<?php

use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| roles:admin {email}
|--------------------------------------------------------------------------
| Grants the admin role (all permissions) to an existing user.
| Repairs a database where roles/permissions were never seeded, or where
| the account you log in with was not the one that received the admin role.
*/
Artisan::command('roles:admin {email : Email address of the user who should become admin}', function (string $email) {
    $user = User::where('email', $email)->first();

    if (! $user) {
        $this->error("No user found with email [{$email}]. Register/create that user first.");

        return 1;
    }

    // Ensure permissions + admin role exist (DatabaseSeeder is idempotent).
    // --force because this command is only ever run deliberately by an admin.
    $this->call('db:seed', ['--force' => true]);

    $user->assignRole('admin');
    $user->unsetRelation('roles');

    $this->info("User [{$email}] now has the admin role with all permissions.");
    $this->line('Roles: '.$user->getRoleNames()->implode(', '));
})->purpose('Grant the admin role (all permissions) to a user by email');
