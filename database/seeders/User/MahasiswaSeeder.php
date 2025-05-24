<?php

namespace Database\Seeders\User;

use App\Models\User;
use App\Models\Mahasiswa;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            $user = User::create([
                // 'id_users' => Str::uuid(),
                'username' => "mahasiswa$i",
                'email' => "mahasiswa$i@example.com",
                'password' => Hash::make('p4ssword'),
                'email_verified_at' => now(),
            ]);

            $user->assignRole(User::ROLE_MAHASISWA);

            Mahasiswa::create([
                'id_mahasiswa' => Str::uuid(),
                'id_users' => $user->id,
                'nim' => 'E421' . str_pad(random_int(0, 99999), 5, '0', STR_PAD_LEFT),
                'nama_lengkap' => "Mahasiswa $i",
                'prodi' => 'Teknik Informatika',
            ]);
        }
    }
}