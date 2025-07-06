<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Todo;
use Carbon\Carbon;

class TodoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first user or create one if none exists
        $user = User::firstOrCreate(
            ['email' => 'test@example.com'],
            ['name' => 'Test User', 'password' => bcrypt('password')]
        );

        // Clear existing todos for this user to prevent duplicates on re-seeding
        $user->todos()->delete();

        // Create some sample todos for the user
        Todo::factory()->count(20)->create(['user_id' => $user->id]);
    }
}