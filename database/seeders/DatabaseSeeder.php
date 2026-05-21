<?php

namespace Database\Seeders;

use App\Models\Hari;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\SlotWaktu;
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
                'password' => Hash::make('Admin@1234'),
            ]);
        }

        $dtdef = [
            ['role' => 'Admin', 'is_admin' => 1],
            ['role' => 'Pengguna', 'is_admin' => 0],
            ['role' => 'Pengelola', 'is_admin' => 0],
            ['role' => 'Dosen', 'is_admin' => 0],
        ];

        foreach ($dtdef as $dt) {
            Role::create([
                'nama' => $dt['role'],
                'is_admin' => $dt['is_admin'],
            ]);
        }

        $dtdef = [
            ['user_id' => '1', 'role_id' => 1],
        ];

        foreach ($dtdef as $dt) {
            RoleUser::create([
                'user_id' => $dt['user_id'],
                'role_id' => $dt['role_id'],
            ]);
        }

        $dtdef = [
            ['hari' => 'Senin'],
            ['hari' => 'Selasa'],
            ['hari' => 'Rabu'],
            ['hari' => 'Kamis'],
            ['hari' => 'Jumat'],
            ['hari' => 'Sabtu'],
            ['hari' => 'Minggu'],
        ];

        foreach ($dtdef as $dt) {
            Hari::create([
                'nama' => $dt['hari'],
            ]);
        }

        $dtdef = [
            ['nama' => 'Jam I',   'jam_mulai' => '07:30:00', 'jam_selesai' => '08:20:00'],
            ['nama' => 'Jam II',  'jam_mulai' => '08:20:00', 'jam_selesai' => '09:10:00'],
            ['nama' => 'Jam III', 'jam_mulai' => '09:20:00', 'jam_selesai' => '10:10:00'],
            ['nama' => 'Jam IV',  'jam_mulai' => '10:10:00', 'jam_selesai' => '11:00:00'],
            ['nama' => 'Jam V',   'jam_mulai' => '11:10:00', 'jam_selesai' => '12:00:00'],
            // Istirahat Dzuhur 12:00 - 13:00
            ['nama' => 'Jam VI',  'jam_mulai' => '13:00:00', 'jam_selesai' => '13:50:00'],
            ['nama' => 'Jam VII', 'jam_mulai' => '13:50:00', 'jam_selesai' => '14:40:00'],
            ['nama' => 'Jam VIII', 'jam_mulai' => '14:50:00', 'jam_selesai' => '15:40:00'],
            // Istirahat Ashar 15:40 - 16:00
            ['nama' => 'Jam IX',  'jam_mulai' => '16:00:00', 'jam_selesai' => '16:50:00'],
        ];
        foreach ($dtdef as $dt) {
            SlotWaktu::create([
                'nama' => $dt['nama'],
                'jam_mulai' => $dt['jam_mulai'],
                'jam_selesai' => $dt['jam_selesai'],
            ]);
        }
    }
}
