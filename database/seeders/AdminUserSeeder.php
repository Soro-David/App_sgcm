<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'id' => 1,
            'name' => 'KKS-TECHNOLOGIE',
            'role' => 'superadmin',
            'email' => 'admin@gmail.com',
            'email_verified_at' => null,
            'password' => Hash::make('Kks-technologies@2026'),
            'remember_token' => null,
            'created_at' => Carbon::parse('2026-02-12 14:54:11'),
            'updated_at' => Carbon::parse('2026-02-12 14:54:11'),
        ]);
    }
}
