<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Reward;
use Illuminate\Support\Str;
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
        // User::factory(10)->create();
        // Reward::factory(10)->create();

        User::create([
            'fullname' => 'Administrator',
            'email' => 'batanghitam@gmail.com',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'phone' => '000000',
            'user_points' => 99999,
            'is_admin' => true,
            'remember_token' => Str::random(10),
        ]);


        $this->call(BranchSeeder::class);

    }
}
