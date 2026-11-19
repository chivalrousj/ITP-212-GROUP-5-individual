<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('name', 'Admin')->first();

        // firstOrCreate: running this twice will not make a duplicate Admin
        User::firstOrCreate(
            ['email' => 'admin@spamast.edu.ph'],
            [
                'first_name' => 'System',
                'last_name' => 'Administrator',
                'password' => Hash::make('Admin@12345'),
                'role_id' => $adminRole->id,
                'is_active' => true,
                'is_admin' => true,
            ]
        );
    }
}