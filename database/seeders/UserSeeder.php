<?php

namespace Database\Seeders;
use App\Models\Role;
use App\Models\User;   
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRoleId = Role::where('naziv_role', 'Admin')->value('id');

        User::updateOrCreate(
            ['email' => 'admin@restoranpavle.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('admin123'),
                'role_id' => $adminRoleId,
                'email_verified_at' => now(),
            ]
        );
    }
}
