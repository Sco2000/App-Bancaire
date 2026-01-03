<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('role_name', 'ADMIN')->first();
        $clientRole = Role::where('role_name', 'CLIENT')->first();

        $admin1 = User::factory()->create([
            'email' => 'admin1@bank.com'
        ]);

        $admin2 = User::factory()->create([
            'email' => 'admin2@bank.com'
        ]);

        $admin1->roles()->attach([
            $adminRole->id,
            $clientRole->id
        ]);

        $admin2->roles()->attach([
            $adminRole->id
        ]);

        User::factory(5)->create()->each(function ($user) use ($clientRole) {
            $user->roles()->attach($clientRole->id);
        });
    }
}
