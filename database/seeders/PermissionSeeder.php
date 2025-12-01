<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        Permission::syncPermissions();
        
        Role::syncDefaultRoles();
        
        Role::assignDefaultPermissions();
        
        $users = User::all();
        foreach ($users as $user) {
            $role = Role::where('slug', $user->role)->first();
            if ($role) {
                $user->roles()->syncWithoutDetaching([$role->id]);
            }
        }

        $this->command->info('Permissions and roles synced successfully!');
    }
}
