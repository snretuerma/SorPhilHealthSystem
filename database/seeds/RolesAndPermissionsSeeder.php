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
        Role::create(['name' => 'admin'])
        ->givePermissionTo(
            [
                'view hospital', 'add hospital', 'edit hospital', 'delete hospital',
                'view user', 'add user', 'edit user', 'delete user'
            ]
        );

        Role::create(['name' => 'user'])
        ->givePermissionTo(
            [
                'view budget', 'add budget', 'edit budget', 'delete budget',
                'view contribution', 'add contribution', 'edit contribution', 'delete contribution',
                'view hospital', 'add hospital', 'edit hospital', 'delete hospital',
                'view medical_record', 'add medical_record', 'edit medical_record', 'delete medical_record',
                'view patient', 'add patient', 'edit patient', 'delete patient',
                'view personnel', 'add personnel', 'edit personnel', 'delete personnel'
            ]
        );

        // observer permissions
        Role::create(['name' => 'observer'])
        ->givePermissionTo(
            [
                'view budget', 'view complaint', 'view contribution', 'view hospital',
                'view medical_record', 'view patient', 'view personnel', 'view user'
            ]
        );
    }
}
