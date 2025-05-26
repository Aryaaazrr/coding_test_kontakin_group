<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        Role::truncate();
        Permission::truncate();
        DB::table('model_has_roles')->truncate();
        DB::table('model_has_permissions')->truncate();
        DB::table('role_has_permissions')->truncate();

        $roles = ['administrator', 'mahasiswa'];

        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }

        $mahasiswaRole = Role::where(['name' => 'mahasiswa'])->first();
        $mahasiswaPermissions = [
            'role-create',
            'role-read',
            'role-update',
            'role-delete',
            'article-create',
            'article-read',
            'article-update',
        ];

        foreach ($mahasiswaPermissions as $permissionName) {
            $permission = Permission::firstOrCreate(['name' => $permissionName]);
            $mahasiswaRole->givePermissionTo($permission);
        }

        $adminPermissions = [
            'article-create',
            'article-read',
            'article-update',
            'article-delete',
            'role-create',
            'role-read',
            'role-update',
            'role-delete',
            'dosen-create',
            'dosen-read',
            'dosen-update',
            'dosen-delete',
            'user-account-create',
            'user-account-read',
            'user-account-update',
            'user-account-delete',
        ];

        $adminRole = Role::where(['name' => 'administrator'])->first();
        foreach ($adminPermissions as $permissionName) {
            $permission = Permission::firstOrCreate(['name' => $permissionName]);
            $adminRole->givePermissionTo($permission);
        }

        Schema::enableForeignKeyConstraints();
    }
}
