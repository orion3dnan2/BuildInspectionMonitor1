<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'مدير النظام',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('123456'),
            'role' => 'admin',
            'rank' => 'مدير',
            'office' => 'الإدارة العامة',
        ]);

        User::create([
            'name' => 'محمد أحمد',
            'username' => 'inspector1',
            'email' => 'inspector1@example.com',
            'password' => Hash::make('123456'),
            'role' => 'inspector',
            'rank' => 'مفتش',
            'office' => 'مكتب التفتيش الأول',
        ]);

        User::create([
            'name' => 'علي محمود',
            'username' => 'inspector2',
            'email' => 'inspector2@example.com',
            'password' => Hash::make('123456'),
            'role' => 'inspector',
            'rank' => 'مفتش أول',
            'office' => 'مكتب التفتيش الثاني',
        ]);
    }
}
