<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // User::factory()->create(
        //     [
        //         'username' => 'superadmin',
        //         'password' => Hash::make('superadmin'),
        //         'role' => 'Super Admin',
        //         'nip' => '1234567890',
        //         'email_verified_at' => now(),
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],
        // );

        $users = array(
            [
                'username' => 'superadmin',
                'password' => Hash::make('superadmin'),
                'role' => 'Super Admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'admin',
                'password' => Hash::make('admin'),
                'role' => 'Admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'dosen',
                'password' => Hash::make('dosen'),
                'role' => 'Dosen',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'kaprodi',
                'password' => Hash::make('kaprodi'),
                'role' => 'Kaprodi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'kajur',
                'password' => Hash::make('kajur'),
                'role' => 'Kajur',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        );

        array_map(function (array $user) {
            User::query()->updateOrCreate(
                $user
            );
        }, $users);
    }
}
