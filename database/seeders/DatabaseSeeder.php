<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Client;
use App\Models\Deal;
use App\Models\Task;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory(3)
            ->has(
                Client::factory(5)
                    ->has(
                        Deal::factory(3)
                            ->has(Task::factory(4))
                    )
            )
            ->create();
    }
}
