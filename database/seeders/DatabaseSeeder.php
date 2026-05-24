<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\RoleUser;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $dtdef = [
            ['name' => 'Admin', 'email' => 'admin@app.com'],
        ];

        foreach ($dtdef as $dt) {
            User::create([
                'name' => $dt['name'],
                'email' => $dt['email'],
                'password' => Hash::make('12345678'),
            ]);
        }

        $dtdef = [
            ['role' => 'Admin', 'is_admin' => 1],
            ['role' => 'Pengelola', 'is_admin' => 0],
            ['role' => 'Dosen', 'is_admin' => 0],
            ['role' => 'Mahasiswa', 'is_admin' => 0],
        ];

        foreach ($dtdef as $dt) {
            Role::create([
                'nama' => $dt['role'],
                'is_admin' => $dt['is_admin'],
            ]);
        }

        $dtdef = [
            ['user_id' => '1', 'role_id' => 1],
            ['user_id' => '1', 'role_id' => 2],
        ];

        foreach ($dtdef as $dt) {
            RoleUser::create([
                'user_id' => $dt['user_id'],
                'role_id' => $dt['role_id'],
            ]);
        }

    }
}
