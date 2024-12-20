<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Enums\UserStatusEnum;
use App\Enums\UserTypeEnum;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();
        \App\Models\Country::create([
            'id' => 1,
            'name' => 'India',
            'code' => 'IN',
            'number_code' => 91,

        ]);
        \App\Models\State::create([
            'id' => 1,
            'name' => 'West Bengal',
            'code' => 'WB',
            'country_id' => 1,
            'number_code' => 26,

        ]);
        \App\Models\User::create([
            'name' => 'Admin User',
            'username' => 'ADMIN00001',
            'user_type' => UserTypeEnum::ADMIN,
            'manager_id' => null,
            'parent_id' => null,
            'email' => 'admin@admin.com',
            'email_verified_at' => now(),
            'contact_no' => '8240707689',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ]);
        \App\Models\Product::create([
            'id' => 1,
            'name' => 'Foot Patch',
            'price' => 300,

        ]);
        for ($i = 0; $i < 2; $i++) {
            $user = \App\Models\User::create([
                'name' => 'Manager '.$i,
                'parent_id' => 1,
                'manager_id' => null,
                'status' => UserStatusEnum::ACTIVE,
                'user_type' => UserTypeEnum::MANAGER,
                'email' => 'manager'.$i.'@admin.com',
                'email_verified_at' => now(),
                'contact_no' => str_repeat((string) $i, 10),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'remember_token' => Str::random(10),
            ]);
            for ($j = 0; $j < 3; $j++) {
                \App\Models\User::create([
                    'name' => 'MEMBER '.$i.$j,
                    'parent_id' => $user->id,
                    'manager_id' => $user->id,
                    'status' => UserStatusEnum::INACTIVE,
                    'user_type' => UserTypeEnum::MEMBER,
                    'email' => 'member'.$i.$j.'@admin.com',
                    'email_verified_at' => now(),
                    'contact_no' => str_pad($user->id.str_repeat((string) $i.$j, 4), 10, '0', STR_PAD_RIGHT),
                    'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                    'remember_token' => Str::random(10),
                ]);
            }
        }

    }
}
