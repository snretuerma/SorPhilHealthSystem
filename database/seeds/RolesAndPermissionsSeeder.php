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

        // Create all permissions
        Permission::create(['name' => 'view budget']);
        Permission::create(['name' => 'add budget']);
        Permission::create(['name' => 'edit budget']);
        Permission::create(['name' => 'delete budget']);

        Permission::create(['name' => 'view complaint']);
        Permission::create(['name' => 'add complaint']);
        Permission::create(['name' => 'edit complaint']);
        Permission::create(['name' => 'delete complaint']);

        Permission::create(['name' => 'view contribution']);
        Permission::create(['name' => 'add contribution']);
        Permission::create(['name' => 'edit contribution']);
        Permission::create(['name' => 'delete contribution']);

        Permission::create(['name' => 'view hospital']);
        Permission::create(['name' => 'add hospital']);
        Permission::create(['name' => 'edit hospital']);
        Permission::create(['name' => 'delete hospital']);

        Permission::create(['name' => 'view medical_record']);
        Permission::create(['name' => 'add medical_record']);
        Permission::create(['name' => 'edit medical_record']);
        Permission::create(['name' => 'delete medical_record']);

        Permission::create(['name' => 'view patient']);
        Permission::create(['name' => 'add patient']);
        Permission::create(['name' => 'edit patient']);
        Permission::create(['name' => 'delete patient']);

        Permission::create(['name' => 'view personnel']);
        Permission::create(['name' => 'add personnel']);
        Permission::create(['name' => 'edit personnel']);
        Permission::create(['name' => 'delete personnel']);

        Permission::create(['name' => 'view user']);
        Permission::create(['name' => 'add user']);
        Permission::create(['name' => 'edit user']);
        Permission::create(['name' => 'delete user']);

        // Create role and assign permissions to role
        $admin = Role::create(['name' => 'admin']);
        // $admin->givePermissionTo('view budget');
        // $admin->givePermissionTo('add budget');
        // $admin->givePermissionTo('edit budget');
        // $admin->givePermissionTo('delete budget');

        $admin->givePermissionTo('view hospital');
        $admin->givePermissionTo('add hospital');
        $admin->givePermissionTo('edit hospital');
        $admin->givePermissionTo('delete hospital');

        // $admin->givePermissionTo('view medical_record');
        // $admin->givePermissionTo('add medical_record');
        // $admin->givePermissionTo('edit medical_record');
        // $admin->givePermissionTo('delete medical_record');

        // $admin->givePermissionTo('view physician');
        // $admin->givePermissionTo('add physician');
        // $admin->givePermissionTo('edit physician');
        // $admin->givePermissionTo('delete physician');

        $admin->givePermissionTo('view user');
        $admin->givePermissionTo('add user');
        $admin->givePermissionTo('edit user');
        $admin->givePermissionTo('delete user');

        $user = Role::create(['name' => 'user']);
        $user->givePermissionTo('view budget');
        $user->givePermissionTo('add budget');
        $user->givePermissionTo('edit budget');
        $user->givePermissionTo('delete budget');

        $user->givePermissionTo('view contribution');
        $user->givePermissionTo('add contribution');
        $user->givePermissionTo('edit contribution');
        $user->givePermissionTo('delete contribution');

        $user->givePermissionTo('view hospital');
        $user->givePermissionTo('add hospital');
        $user->givePermissionTo('edit hospital');
        $user->givePermissionTo('delete hospital');

        $user->givePermissionTo('view medical_record');
        $user->givePermissionTo('add medical_record');
        $user->givePermissionTo('edit medical_record');
        $user->givePermissionTo('delete medical_record');

        $user->givePermissionTo('view patient');
        $user->givePermissionTo('add patient');
        $user->givePermissionTo('edit patient');
        $user->givePermissionTo('delete patient');

        $user->givePermissionTo('view personnel');
        $user->givePermissionTo('add personnel');
        $user->givePermissionTo('edit personnel');
        $user->givePermissionTo('delete personnel');

        // observer permissions
        $observer = Role::create(['name' => 'observer']);
        $observer->givePermissionTo('view budget');
        $observer->givePermissionTo('view complaint');
        $observer->givePermissionTo('view contribution');
        $observer->givePermissionTo('view hospital');
        $observer->givePermissionTo('view medical_record');
        $observer->givePermissionTo('view patient');
        $observer->givePermissionTo('view personnel');
        $observer->givePermissionTo('view user');
    }
}
