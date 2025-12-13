<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\CompanySeeder;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\JobSeeder;
use Database\Seeders\AdminUserSeeder; // ← عدل الاستيراد هنا

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CompanySeeder::class,
            RolePermissionSeeder::class,
            JobSeeder::class,
            AdminUserSeeder::class,
        ]);
    }
}
