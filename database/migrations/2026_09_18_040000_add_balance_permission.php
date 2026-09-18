<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $groupId = DB::table('permission_groups')->where('name', 'Tài chính')->value('id');
        if (!$groupId) {
            $groupId = DB::table('permission_groups')->insertGetId([
                'name' => 'Tài chính',
                'description' => 'Quản lý số dư và giao dịch của nhà trường',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if (!DB::table('permissions')->where('resource', 'balance')->exists()) {
            DB::table('permissions')->insert([
                'name' => 'Số dư',
                'resource' => 'balance',
                'permission_group_id' => $groupId,
                'show' => 1,
                'enable_by_default' => 1,
                'description' => 'Xem số dư và lịch sử giao dịch của nhà trường',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $permissionId = DB::table('permissions')->where('resource', 'balance')->value('id');
        if ($permissionId) {
            DB::table('users_permissions')->where('permission_id', $permissionId)->delete();
            DB::table('permissions')->where('id', $permissionId)->delete();
        }

        $groupId = DB::table('permission_groups')->where('name', 'Tài chính')->value('id');
        if ($groupId && !DB::table('permissions')->where('permission_group_id', $groupId)->exists()) {
            DB::table('permission_groups')->where('id', $groupId)->delete();
        }
    }
};
