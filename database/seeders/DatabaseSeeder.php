<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        $user = User::updateOrcreate([
            'email' => 'user@mail.loc'
        ], [
            'name' => 'User',
            'password' => bcrypt('123123')
        ]);

        if (!$user->budget) {
            $user->budget()->create([
                'amount' => 0
            ]);
        }
    }
}
