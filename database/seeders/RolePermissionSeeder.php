<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Permissions
        |--------------------------------------------------------------------------
        */

        $permissions = [

            // Users
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',

            // Products
            'products.view',
            'products.create',
            'products.edit',
            'products.delete',

            // Stock
            'stock.view',
            'stock.adjust',

            // Sales
            'sales.view',
            'sales.create',
            'sales.edit',
            'sales.delete',

            // Purchases
            'purchases.view',
            'purchases.create',
            'purchases.edit',
            'purchases.delete',

            // Customers
            'customers.view',
            'customers.create',
            'customers.edit',
            'customers.delete',

            // Suppliers
            'suppliers.view',
            'suppliers.create',
            'suppliers.edit',
            'suppliers.delete',

            // Accounts
            'accounts.view',
            'accounts.create',
            'accounts.edit',

            // Expenses
            'expenses.view',
            'expenses.create',
            'expenses.edit',
            'expenses.delete',

            // Reports
            'reports.view',
        ];

        /*
        |--------------------------------------------------------------------------
        | Create Permissions
        |--------------------------------------------------------------------------
        */

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission],
                ['description' => ucfirst(str_replace('.', ' ', $permission))]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Roles
        |--------------------------------------------------------------------------
        */

        $administrator = Role::firstOrCreate(
            ['name' => 'Administrator'],
            [
                'description' => 'Full access to the entire system.',
                'status' => true,
            ]
        );

        $manager = Role::firstOrCreate(
            ['name' => 'Manager'],
            [
                'description' => 'Management access to business operations.',
                'status' => true,
            ]
        );

        $accountant = Role::firstOrCreate(
            ['name' => 'Accountant'],
            [
                'description' => 'Access to accounts, expenses and financial reports.',
                'status' => true,
            ]
        );

        $salesStaff = Role::firstOrCreate(
            ['name' => 'Sales Staff'],
            [
                'description' => 'Access to sales and customers.',
                'status' => true,
            ]
        );

        $stockStaff = Role::firstOrCreate(
            ['name' => 'Stock Staff'],
            [
                'description' => 'Access to products and stock management.',
                'status' => true,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Administrator gets ALL permissions
        |--------------------------------------------------------------------------
        */

        $administrator->permissions()->sync(
            Permission::pluck('id')
        );

        /*
        |--------------------------------------------------------------------------
        | Manager permissions
        |--------------------------------------------------------------------------
        */

        $manager->permissions()->sync(
            Permission::whereIn('name', [
                'products.view',
                'products.create',
                'products.edit',

                'stock.view',
                'stock.adjust',

                'sales.view',
                'sales.create',
                'sales.edit',

                'purchases.view',
                'purchases.create',
                'purchases.edit',

                'customers.view',
                'customers.create',
                'customers.edit',

                'suppliers.view',
                'suppliers.create',
                'suppliers.edit',

                'expenses.view',
                'expenses.create',

                'accounts.view',

                'reports.view',
            ])->pluck('id')
        );

        /*
        |--------------------------------------------------------------------------
        | Accountant permissions
        |--------------------------------------------------------------------------
        */

        $accountant->permissions()->sync(
            Permission::whereIn('name', [
                'accounts.view',
                'accounts.create',
                'accounts.edit',

                'expenses.view',
                'expenses.create',
                'expenses.edit',
                'expenses.delete',

                'sales.view',
                'purchases.view',

                'customers.view',
                'suppliers.view',

                'reports.view',
            ])->pluck('id')
        );

        /*
        |--------------------------------------------------------------------------
        | Sales Staff permissions
        |--------------------------------------------------------------------------
        */

        $salesStaff->permissions()->sync(
            Permission::whereIn('name', [
                'products.view',

                'customers.view',
                'customers.create',
                'customers.edit',

                'sales.view',
                'sales.create',
            ])->pluck('id')
        );

        /*
        |--------------------------------------------------------------------------
        | Stock Staff permissions
        |--------------------------------------------------------------------------
        */

        $stockStaff->permissions()->sync(
            Permission::whereIn('name', [
                'products.view',
                'products.create',
                'products.edit',

                'stock.view',
                'stock.adjust',

                'suppliers.view',
            ])->pluck('id')
        );
    }
}