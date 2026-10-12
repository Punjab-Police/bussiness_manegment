<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        $permissions = [
            [
                'name' => 'roles.view',
                'description' => 'View roles and their permissions.',
            ],
            [
                'name' => 'roles.create',
                'description' => 'Create roles and assign permissions.',
            ],
            [
                'name' => 'roles.edit',
                'description' => 'Edit roles and update permissions.',
            ],
        ];

        foreach ($permissions as $permission) {
            DB::table('permissions')->updateOrInsert(
                ['name' => $permission['name']],
                [
                    'description' => $permission['description'],
                    'updated_at' => $now,
                    'created_at' => $now,
                ]
            );
        }

        $administrator = DB::table('roles')
            ->where('name', 'Administrator')
            ->first();

        if ($administrator) {
            $permissionIds = DB::table('permissions')
                ->whereIn('name', ['roles.view', 'roles.create', 'roles.edit'])
                ->pluck('id');

            foreach ($permissionIds as $permissionId) {
                DB::table('permission_role')->updateOrInsert(
                    [
                        'role_id' => $administrator->id,
                        'permission_id' => $permissionId,
                    ],
                    [
                        'updated_at' => $now,
                        'created_at' => $now,
                    ]
                );
            }
        }
    }

    public function down(): void
    {
        $permissionIds = DB::table('permissions')
            ->whereIn('name', ['roles.view', 'roles.create', 'roles.edit'])
            ->pluck('id');

        DB::table('permission_role')
            ->whereIn('permission_id', $permissionIds)
            ->delete();

        DB::table('permissions')
            ->whereIn('name', ['roles.view', 'roles.create', 'roles.edit'])
            ->delete();
    }
};
