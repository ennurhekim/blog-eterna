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
        $role_api_admin = Role::updateOrCreate(['name' => 'admin']);
        $role_api_admin = Role::updateOrCreate(['name' => 'admin', 'guard_name' => 'api']);
        $role_api_writer = Role::updateOrCreate(['name' => 'writer']);
        $role_api_writer = Role::updateOrCreate(['name' => 'writer', 'guard_name' => 'api']);
        $role_api_user = Role::updateOrCreate(['name' => 'user']);
        $role_api_user = Role::updateOrCreate(['name' => 'user', 'guard_name' => 'api']);

        // Admin yetkilerini belirliyoruz 
        $permission_create_users = Permission::updateOrCreate(['name' => 'create users', 'guard_name' => 'api']);
        $role_api_admin->givePermissionTo($permission_create_users);
        $permission_delete_users = Permission::updateOrCreate(['name' => 'delete users', 'guard_name' => 'api']);
        $role_api_admin->givePermissionTo($permission_delete_users);
        $permission_list_users = Permission::updateOrCreate(['name' => 'list users', 'guard_name' => 'api']);
        $role_api_admin->givePermissionTo($permission_list_users);

        //Blog yetkileri
        $permission_create_blog = Permission::updateOrCreate(['name' => 'create blog', 'guard_name' => 'api']);
        $role_api_admin->givePermissionTo($permission_create_blog);
        $role_api_writer->givePermissionTo($permission_create_blog); //Yazar yetkileri
        $permission_delete_blog = Permission::updateOrCreate(['name' => 'delete blog', 'guard_name' => 'api']);
        $role_api_admin->givePermissionTo($permission_delete_blog);
        $role_api_writer->givePermissionTo($permission_create_blog); //Yazar yetkileri
        $permission_list_blog = Permission::updateOrCreate(['name' => 'list blog', 'guard_name' => 'api']);
        $role_api_admin->givePermissionTo($permission_list_blog);
        $role_api_writer->givePermissionTo($permission_list_blog); //Yazar yetkileri

        //Yorum yetkileri
        $permission_create_comment = Permission::updateOrCreate(['name' => 'create comment', 'guard_name' => 'api']);
        $role_api_admin->givePermissionTo($permission_create_comment);
        $role_api_writer->givePermissionTo($permission_create_comment); //Yazar yetkileri
        $role_api_user->givePermissionTo($permission_create_comment); //Kullanıcı yetkileri
        $permission_delete_comment = Permission::updateOrCreate(['name' => 'delete comment', 'guard_name' => 'api']);
        $role_api_admin->givePermissionTo($permission_delete_comment);
        $role_api_writer->givePermissionTo($permission_delete_comment); //Yazar yetkileri
        $role_api_user->givePermissionTo($permission_delete_comment); //Kullanıcı yetkileri
        $permission_list_comment = Permission::updateOrCreate(['name' => 'list comment', 'guard_name' => 'api']);
        $role_api_admin->givePermissionTo($permission_list_comment);
        $role_api_writer->givePermissionTo($permission_list_comment); //Yazar yetkileri
        $role_api_user->givePermissionTo($permission_list_comment); //Kullanıcı yetkileri
    }
}
