<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserRole;
use App\Models\RolePermission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find user (example: user ID 1)
        $user = User::find(1);

        if (!$user) {
            $this->command->info('User with ID 1 not found.');
            return;
        }

        // Create role (admin)
        $role = UserRole::create([
            'user_id' => $user->id,
            'role_name' => 'admin',
            'description' => 'Administrator role',
        ]);

        // Assign permissions
        $permissions = ['create', 'read', 'update', 'delete'];

        foreach ($permissions as $perm) {
            RolePermission::create([
                'user_role_id' => $role->id,
                'description' => $perm,
            ]);
        }

        $this->command->info('Admin role and permissions seeded.');
    }
}
