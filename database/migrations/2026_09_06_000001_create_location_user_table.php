<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('location_user', function (Blueprint $table) {
            $table->foreignId('location_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_owner')->default(false);
            $table->timestamps();
            $table->primary(['location_id', 'user_id']);
        });

        $firstUserId = DB::table('users')->orderBy('id')->value('id');
        if ($firstUserId) {
            $now = now();
            $locations = DB::table('locations')->pluck('id')->map(fn ($locationId) => [
                'location_id' => $locationId,
                'user_id' => $firstUserId,
                'is_owner' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ])->all();

            if ($locations !== []) {
                DB::table('location_user')->insert($locations);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('location_user');
    }
};