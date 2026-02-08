<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Create multiple admin accounts for different purposes
        $adminAccounts = [
            [
                'name' => 'System Administrator',
                'email' => 'admin@libraflow.com',
                'password' => 'admin123',
                'description' => 'Main system administrator'
            ],
            [
                'name' => 'Library Admin',
                'email' => 'library@libraflow.com',
                'password' => 'library123',
                'description' => 'Library management administrator'
            ],
            [
                'name' => 'Test Admin',
                'email' => 'test@libraflow.com',
                'password' => 'test123',
                'description' => 'Testing administrator account'
            ]
        ];

        foreach ($adminAccounts as $adminData) {
            $this->createAdminAccount($adminData);
        }
    }

    private function createAdminAccount(array $adminData): void
    {
        // Check if admin already exists
        $existingAdmin = DB::table('users')->where('email', $adminData['email'])->first();
        if ($existingAdmin) {
            $this->command->info("Admin user {$adminData['email']} already exists, skipping creation.");
            return;
        }

        // Create admin user with approval fields
        $user = [
            'name' => $adminData['name'],
            'email' => $adminData['email'],
            'password' => Hash::make($adminData['password']),
            'role' => 'admin',
            'email_verified_at' => now(),
            'is_approved' => true, // Auto-approve admin accounts
            'approved_at' => now(),
            'approved_by' => null, // Self-approved
            'created_at' => now(),
            'updated_at' => now(),
        ];

        DB::table('users')->insert($user);
        
        $this->command->info("✅ Admin account created successfully!");
        $this->command->info("📧 Email: {$adminData['email']}");
        $this->command->info("🔑 Password: {$adminData['password']}");
        $this->command->info("👤 Role: {$adminData['description']}");
        $this->command->info("✅ Status: Approved");
        $this->command->info("---");
    }
}
