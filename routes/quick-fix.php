<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

// Quick fix for admin approval status
Route::get('/quick-admin-fix', function () {
    try {
        // First, ensure the approval fields exist in the database
        try {
            DB::statement("ALTER TABLE users ADD COLUMN IF NOT EXISTS is_approved BOOLEAN DEFAULT FALSE");
            DB::statement("ALTER TABLE users ADD COLUMN IF NOT EXISTS approved_at TIMESTAMP NULL");
            DB::statement("ALTER TABLE users ADD COLUMN IF NOT EXISTS approved_by INTEGER NULL REFERENCES users(id)");
        } catch (Exception $e) {
            // Columns might already exist, continue
        }

        // Update all admin users to be approved
        $updated = DB::table('users')
            ->where('role', 'admin')
            ->update([
                'is_approved' => true,
                'approved_at' => now(),
                'approved_by' => null,
            ]);

        // Create a fresh admin if none exist
        $adminExists = DB::table('users')->where('role', 'admin')->exists();
        if (!$adminExists) {
            DB::table('users')->insert([
                'name' => 'System Administrator',
                'email' => 'admin@libraflow.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'email_verified_at' => now(),
                'is_approved' => true,
                'approved_at' => now(),
                'approved_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $message = "Created new admin account";
        } else {
            $message = "Updated {$updated} admin account(s)";
        }

        // Show current admin accounts for verification
        $admins = DB::table('users')
            ->where('role', 'admin')
            ->get(['name', 'email', 'is_approved', 'approved_at']);

        $adminList = "<ul>";
        foreach ($admins as $admin) {
            $status = $admin->is_approved ? "✅ Approved" : "❌ Not Approved";
            $adminList .= "<li><strong>{$admin->email}</strong> - {$status}</li>";
        }
        $adminList .= "</ul>";

        return "<h1>✅ Admin Approval Fixed!</h1>
               <p><strong>{$message}</strong></p>
               <p><strong>Current Admin Accounts:</strong></p>
               {$adminList}
               <p><strong>Login Credentials:</strong></p>
               <ul>
                   <li>Email: admin@libraflow.com</li>
                   <li>Password: admin123</li>
               </ul>
               <p><a href='/login'>Go to Login Page</a></p>";

    } catch (Exception $e) {
        return "<h1>❌ Error</h1>
               <p>Error: " . $e->getMessage() . "</p>";
    }
})->name('quick-admin-fix');
