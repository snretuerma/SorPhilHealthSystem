<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // TODO Clean this
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::create(['name' => 'view budget']);
        Permission::create(['name' => 'add budget']);
        Permission::create(['name' => 'edit budget']);
        Permission::create(['name' => 'delete budget']);

        Permission::create(['name' => 'view record']);
        Permission::create(['name' => 'add record']);
        Permission::create(['name' => 'edit record']);
        Permission::create(['name' => 'delete record']);

        Permission::create(['name' => 'view hospital']);
        Permission::create(['name' => 'add hospital']);
        Permission::create(['name' => 'edit hospital']);
        Permission::create(['name' => 'delete hospital']);

        Permission::create(['name' => 'view user']);
        Permission::create(['name' => 'add user']);
        Permission::create(['name' => 'edit user']);
        Permission::create(['name' => 'delete user']);
        
        Permission::create(['name' => 'view physician']);
        Permission::create(['name' => 'add physician']);
        Permission::create(['name' => 'edit physician']);
        Permission::create(['name' => 'delete physician']);

        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo('view budget');
        $admin->givePermissionTo('add budget');
        $admin->givePermissionTo('edit budget');
        $admin->givePermissionTo('delete budget');

        $admin->givePermissionTo('view record');
        $admin->givePermissionTo('add record');
        $admin->givePermissionTo('edit record');
        $admin->givePermissionTo('delete record');
        
        $admin->givePermissionTo('view hospital');
        $admin->givePermissionTo('add hospital');
        $admin->givePermissionTo('edit hospital');
        $admin->givePermissionTo('delete hospital');

        $admin->givePermissionTo('view physician');
        $admin->givePermissionTo('add physician');
        $admin->givePermissionTo('edit physician');
        $admin->givePermissionTo('delete physician');

        $admin->givePermissionTo('view user');
        $admin->givePermissionTo('add user');
        $admin->givePermissionTo('edit user');
        $admin->givePermissionTo('delete user');

        $user = Role::create(['name' => 'user']);
        $user->givePermissionTo('view budget');
        $user->givePermissionTo('add budget');
        $user->givePermissionTo('edit budget');
        $user->givePermissionTo('delete budget');

        $user->givePermissionTo('view record');
        $user->givePermissionTo('add record');
        $user->givePermissionTo('edit record');
        $user->givePermissionTo('delete record');
        
        $user->givePermissionTo('view hospital');
        $user->givePermissionTo('add hospital');
        $user->givePermissionTo('edit hospital');
        $user->givePermissionTo('delete hospital');

        $user->givePermissionTo('view physician');
        $user->givePermissionTo('add physician');
        $user->givePermissionTo('edit physician');
        $user->givePermissionTo('delete physician');

        // observer permissions
        $observer = Role::create(['name' => 'observer']);
        $observer->givePermissionTo('view budget');
        $observer->givePermissionTo('view record');
        $observer->givePermissionTo('view user');
        $observer->givePermissionTo('view hospital');
        $observer->givePermissionTo('view physician');
    }
}
