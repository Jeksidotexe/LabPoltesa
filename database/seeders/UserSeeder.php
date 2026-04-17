<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

use function Symfony\Component\Clock\now;

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
                'nip'      => '197502012005011001',
                'nama'     => 'Super Admin',
                'gelar_belakang'    => 'S.P.',
                'tanggal_lahir'    => '1999-05-19',
                'jenis_kelamin'     => 'L',
                'jabatan'           => 'Super Admin',
                'email'             => 'super@lab.ac.id',
                'telepon'           => '1234567890',
                'tanggal_bergabung' => now(),
                'status'            => 'Aktif',
                'email_verified_at' => now(),
                'role' => 'Super Admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'admin',
                'password' => Hash::make('admin'),
                'nip'      => '197502012005011002',
                'nama'     => 'Admin',
                'gelar_belakang'    => 'S.P.',
                'tanggal_lahir'    => '1999-05-19',
                'jenis_kelamin'     => 'L',
                'jabatan'           => 'Admin',
                'email'             => 'admin@lab.ac.id',
                'telepon'           => '1234567890',
                'tanggal_bergabung' => now(),
                'status'            => 'Aktif',
                'email_verified_at' => now(),
                'role' => 'Admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'dosen',
                'password' => Hash::make('dosen'),
                'nip'      => '197502012005011003',
                'nama'     => 'Dosen',
                'gelar_belakang'    => 'S.P.',
                'tanggal_lahir'    => '1999-05-19',
                'jenis_kelamin'     => 'L',
                'jabatan'           => 'Dosen',
                'email'             => 'dosen@lab.ac.id',
                'telepon'           => '1234567890',
                'tanggal_bergabung' => now(),
                'status'            => 'Aktif',
                'email_verified_at' => now(),
                'role' => 'Dosen',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'kaprodi',
                'password' => Hash::make('kaprodi'),
                'nip'      => '197502012005011004',
                'nama'     => 'Kaprodi',
                'gelar_belakang'    => 'S.P.',
                'tanggal_lahir'    => '1999-05-19',
                'jenis_kelamin'     => 'L',
                'jabatan'           => 'Kaprodi',
                'email'             => 'kaprodi@lab.ac.id',
                'telepon'           => '1234567890',
                'tanggal_bergabung' => now(),
                'status'            => 'Aktif',
                'email_verified_at' => now(),
                'role' => 'Kaprodi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'kajur',
                'password' => Hash::make('kajur'),
                'nip'      => '197502012005011005',
                'nama'     => 'Kajur',
                'gelar_belakang'    => 'S.P.',
                'tanggal_lahir'    => '1999-05-19',
                'jenis_kelamin'     => 'L',
                'jabatan'           => 'Kajur',
                'email'             => 'kajur@lab.ac.id',
                'telepon'           => '1234567890',
                'tanggal_bergabung' => now(),
                'status'            => 'Aktif',
                'email_verified_at' => now(),
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
