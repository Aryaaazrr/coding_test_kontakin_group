<?php

namespace Database\Seeders\User;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('users')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $admin = User::firstOrCreate([
            // 'id_users' => Str::uuid(),
            'email' => 'admin@gmail.com',
        ], [
            'username' => 'administrator',
            'password' => Hash::make('p4ssword'),
            'email_verified_at' => now(),
        ]);

        $admin->assignRole(User::ROLE_ADMIN);
    }
}
