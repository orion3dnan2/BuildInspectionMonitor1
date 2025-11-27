<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Station;
use App\Models\Port;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'مدير النظام',
                'email' => 'admin@example.com',
                'password' => Hash::make('Admin123!'),
                'role' => 'admin',
                'rank' => 'مدير',
                'office' => 'الإدارة العامة',
            ]
        );

        User::firstOrCreate(
            ['username' => 'supervisor'],
            [
                'name' => 'المشرف',
                'email' => 'supervisor@example.com',
                'password' => Hash::make('123456'),
                'role' => 'supervisor',
                'rank' => 'مشرف',
                'office' => 'قسم الإشراف',
            ]
        );

        User::firstOrCreate(
            ['username' => 'user1'],
            [
                'name' => 'مستخدم عادي',
                'email' => 'user1@example.com',
                'password' => Hash::make('123456'),
                'role' => 'user',
                'rank' => 'موظف',
                'office' => 'قسم المتابعة',
            ]
        );

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
    }
}
