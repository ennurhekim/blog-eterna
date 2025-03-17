<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Rolleri oluştur
        $adminRole = Role::create(['name' => 'admin']);
        $writerRole = Role::create(['name' => 'writer']);
        $userRole = Role::create(['name' => 'user']);

        // İzinleri oluştur
        $permissions = [
            'create post',
            'edit post',
            'delete post',
            'publish post',
            'create category',
            'edit category',
            'delete category',
            'approve comment',
            'delete comment'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Admin rolüne tüm izinleri ver
        $adminRole->givePermissionTo(Permission::all());

        // Writer rolüne post işlemleri izni ver
        $writerRole->givePermissionTo(['create post', 'edit post', 'delete post', 'publish post']);

        // User rolüne sadece yorum yapma izni ver
        $userRole->givePermissionTo(['create post']);
    }
}
