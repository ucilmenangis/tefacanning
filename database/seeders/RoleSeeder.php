<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Product permissions
            'view_any_product',
            'view_product',
            'create_product',
            'update_product',
            'delete_product',
            'restore_product',
            'force_delete_product',

            // Order permissions
            'view_any_order',
            'view_order',
            'create_order',
            'update_order',
            'delete_order',
            'restore_order',
            'force_delete_order',

            // Customer permissions
            'view_any_customer',
            'view_customer',
            'create_customer',
            'update_customer',
            'delete_customer',
            'restore_customer',
            'force_delete_customer',

            // Batch permissions
            'view_any_batch',
            'view_batch',
            'create_batch',
            'update_batch',
            'delete_batch',
            'restore_batch',
            'force_delete_batch',

            // Financial permissions
            'view_financial_data',
            'update_product_price',

            // User management
            'view_any_user',
            'view_user',
            'create_user',
            'update_user',
            'delete_user',

            // Activity log
            'view_any_activity',
            'view_activity',

            // Shield (role management)
            'view_any_role',
            'view_role',
            'create_role',
            'update_role',
            'delete_role',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create Superadmin role with all permissions
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin']);
        $superAdmin->givePermissionTo(Permission::all());

        // Create Teknisi role with limited permissions
        $teknisi = Role::firstOrCreate(['name' => 'teknisi']);
        $teknisi->givePermissionTo([
            'view_any_product',
            'view_product',

            'view_any_order',
            'view_order',
            'create_order',
            'update_order',

            'view_any_customer',
            'view_customer',
            'create_customer',
            'update_customer',

            'view_any_batch',
            'view_batch',
            'update_batch',
        ]);
    }
}
