
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        DB::table('permissions')->updateOrInsert(
            ['name' => 'audit_logs.view'],
            [
                'description' => 'View system audit logs.',
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        $role = DB::table('roles')
            ->where('name', 'Administrator')
            ->first();

        $permission = DB::table('permissions')
            ->where('name', 'audit_logs.view')
            ->first();

        if ($role && $permission) {
            DB::table('permission_role')->updateOrInsert(
                [
                    'role_id' => $role->id,
                    'permission_id' => $permission->id,
                ],
                [
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }

    public function down(): void
    {
        $permission = DB::table('permissions')
            ->where('name', 'audit_logs.view')
            ->first();

        if ($permission) {
            DB::table('permission_role')
                ->where('permission_id', $permission->id)
                ->delete();

            DB::table('permissions')
                ->where('id', $permission->id)
                ->delete();
        }
    }
};
