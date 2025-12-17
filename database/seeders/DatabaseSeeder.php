<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Station;
use App\Models\Port;
use App\Models\Department;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Permission::syncPermissions();
        Role::syncDefaultRoles();
        Role::assignDefaultPermissions();

        $admin = User::firstOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'مدير النظام',
                'email' => 'admin@example.com',
                'password' => Hash::make('Admin123!'),
                'role' => 'admin',
                'rank' => 'مدير',
                'office' => 'الإدارة العامة',
                'system_access' => ['block_system', 'admin_system'],
                'permissions' => [],
            ]
        );

        $supervisor = User::firstOrCreate(
            ['username' => 'supervisor'],
            [
                'name' => 'المشرف',
                'email' => 'supervisor@example.com',
                'password' => Hash::make('123456'),
                'role' => 'supervisor',
                'rank' => 'مشرف',
                'office' => 'قسم الإشراف',
                'system_access' => ['block_system', 'admin_system'],
                'permissions' => ['create_records', 'edit_records', 'import_data'],
            ]
        );

        $user1 = User::firstOrCreate(
            ['username' => 'user1'],
            [
                'name' => 'مستخدم عادي',
                'email' => 'user1@example.com',
                'password' => Hash::make('123456'),
                'role' => 'user',
                'rank' => 'موظف',
                'office' => 'قسم المتابعة',
                'system_access' => ['block_system'],
                'permissions' => [],
            ]
        );

        $adminRole = Role::where('slug', 'admin')->first();
        $supervisorRole = Role::where('slug', 'supervisor')->first();
        $userRole = Role::where('slug', 'user')->first();

        if ($adminRole) $admin->roles()->syncWithoutDetaching([$adminRole->id]);
        if ($supervisorRole) $supervisor->roles()->syncWithoutDetaching([$supervisorRole->id]);
        if ($userRole) $user1->roles()->syncWithoutDetaching([$userRole->id]);

        $stations = [
            ['name' => 'مخفر العاصمة', 'governorate' => 'العاصمة'],
            ['name' => 'مخفر حولي', 'governorate' => 'حولي'],
            ['name' => 'مخفر الفروانية', 'governorate' => 'الفروانية'],
            ['name' => 'مخفر الجهراء', 'governorate' => 'الجهراء'],
            ['name' => 'مخفر الأحمدي', 'governorate' => 'الأحمدي'],
            ['name' => 'مخفر مبارك الكبير', 'governorate' => 'مبارك الكبير'],
        ];

        foreach ($stations as $station) {
            Station::firstOrCreate(['name' => $station['name']], $station);
        }

        $ports = [
            ['name' => 'منفذ العبدلي'],
            ['name' => 'منفذ السالمية'],
            ['name' => 'منفذ النويصيب'],
            ['name' => 'مطار الكويت الدولي'],
            ['name' => 'ميناء الشويخ'],
        ];

        foreach ($ports as $port) {
            Port::firstOrCreate(['name' => $port['name']], $port);
        }

        $departments = [
            ['name' => 'الإدارة العامة', 'code' => 'GM', 'description' => 'الإدارة العليا للنظام', 'manager_id' => $admin->id],
            ['name' => 'الشؤون الإدارية', 'code' => 'ADM', 'description' => 'قسم الشؤون الإدارية'],
            ['name' => 'الموارد البشرية', 'code' => 'HR', 'description' => 'قسم الموارد البشرية'],
            ['name' => 'الشؤون المالية', 'code' => 'FIN', 'description' => 'قسم الشؤون المالية'],
            ['name' => 'تقنية المعلومات', 'code' => 'IT', 'description' => 'قسم تقنية المعلومات'],
        ];

        foreach ($departments as $department) {
            Department::firstOrCreate(['code' => $department['code']], $department);
        }
    }
}
