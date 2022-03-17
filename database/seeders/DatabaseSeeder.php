<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // can be moved to UserSeeder::run method and called as
        // $this->call(UserSeeder::class);
        \DB::table('users')->insert([
            'name' => 'Cristian V',
            'email' => "ss@example.com",
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ]);

        User::factory(2)->create();
        // end

        $this->call(MedicalTestCategorySeeder::class);
        $this->call(MedicalTestSeeder::class);
    }
}
