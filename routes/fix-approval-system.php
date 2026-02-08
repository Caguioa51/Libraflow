<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

// Comprehensive fix for approval system
Route::get('/fix-approval-system', function () {
    try {
        $output = "<h1>🔧 Fixing Approval System...</h1>";
        
        // Step 1: Ensure approval columns exist
        $output .= "<h3>Step 1: Adding Approval Columns</h3>";
        try {
            DB::statement("ALTER TABLE users ADD COLUMN IF NOT EXISTS is_approved BOOLEAN DEFAULT FALSE");
            $output .= "<p>✅ Added is_approved column</p>";
        } catch (Exception $e) {
            $output .= "<p>⚠️ is_approved column already exists or error: " . $e->getMessage() . "</p>";
        }
        
        try {
            DB::statement("ALTER TABLE users ADD COLUMN IF NOT EXISTS approved_at TIMESTAMP NULL");
            $output .= "<p>✅ Added approved_at column</p>";
        } catch (Exception $e) {
            $output .= "<p>⚠️ approved_at column already exists or error: " . $e->getMessage() . "</p>";
        }
        
        try {
            DB::statement("ALTER TABLE users ADD COLUMN IF NOT EXISTS approved_by INTEGER NULL");
            $output .= "<p>✅ Added approved_by column</p>";
        } catch (Exception $e) {
            $output .= "<p>⚠️ approved_by column already exists or error: " . $e->getMessage() . "</p>";
        }
        
        // Step 2: Update all admin accounts to be approved
        $output .= "<h3>Step 2: Approving Admin Accounts</h3>";
        $adminUpdated = DB::table('users')
            ->where('role', 'admin')
            ->update([
                'is_approved' => true,
                'approved_at' => now(),
                'approved_by' => null,
            ]);
        $output .= "<p>✅ Updated {$adminUpdated} admin account(s)</p>";
        
        // Step 3: Create test users if none exist
        $output .= "<h3>Step 3: Creating Test Users</h3>";
        
        // Create a test student account
        $studentExists = DB::table('users')->where('email', 'student@test.com')->exists();
        if (!$studentExists) {
            DB::table('users')->insert([
                'name' => 'Test Student',
                'email' => 'student@test.com',
                'password' => Hash::make('student123'),
                'role' => 'student',
                'email_verified_at' => now(),
                'is_approved' => false, // Pending approval
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $output .= "<p>✅ Created test student account (pending approval)</p>";
        } else {
            $output .= "<p>⚠️ Test student already exists</p>";
        }
        
        // Create a test teacher account  
        $teacherExists = DB::table('users')->where('email', 'teacher@test.com')->exists();
        if (!$teacherExists) {
            DB::table('users')->insert([
                'name' => 'Test Teacher',
                'email' => 'teacher@test.com',
                'password' => Hash::make('teacher123'),
                'role' => 'teacher',
                'email_verified_at' => now(),
                'is_approved' => false, // Pending approval
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $output .= "<p>✅ Created test teacher account (pending approval)</p>";
        } else {
            $output .= "<p>⚠️ Test teacher already exists</p>";
        }
        
        // Step 4: Show current status
        $output .= "<h3>Step 4: Current User Status</h3>";
        $users = DB::table('users')->select('id', 'name', 'email', 'role', 'is_approved', 'approved_at', 'approved_by')->get();
        
        $output .= "<table border='1' style='border-collapse: collapse; width: 100%; margin: 20px 0;'>";
        $output .= "<tr style='background: #f0f0f0;'><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>is_approved</th><th>approved_at</th><th>approved_by</th><th>Login Status</th></tr>";
        
        foreach ($users as $user) {
            $canLogin = ($user->role === 'admin') || ($user->is_approved);
            $status = $canLogin ? "✅ Can Login" : "❌ Needs Approval";
            
            $output .= "<tr>";
            $output .= "<td>{$user->id}</td>";
            $output .= "<td>{$user->name}</td>";
            $output .= "<td>{$user->email}</td>";
            $output .= "<td>{$user->role}</td>";
            $output .= "<td>" . ($user->is_approved ? "✅ True" : "❌ False") . "</td>";
            $output .= "<td>{$user->approved_at}</td>";
            $output .= "<td>{$user->approved_by}</td>";
            $output .= "<td><strong>{$status}</strong></td>";
            $output .= "</tr>";
        }
        $output .= "</table>";
        
        // Step 5: Test approval functionality
        $output .= "<h3>Step 5: Testing Approval Function</h3>";
        $testStudent = DB::table('users')->where('email', 'student@test.com')->first();
        if ($testStudent && !$testStudent->is_approved) {
            // Simulate approval
            DB::table('users')
                ->where('email', 'student@test.com')
                ->update([
                    'is_approved' => true,
                    'approved_at' => now(),
                    'approved_by' => 1, // Assuming admin ID 1
                ]);
            $output .= "<p>✅ Approved test student account</p>";
        }
        
        $output .= "<h3>🎯 Test Login Credentials:</h3>";
        $output .= "<ul>";
        $output .= "<li><strong>Admin:</strong> admin@libraflow.com / admin123</li>";
        $output .= "<li><strong>Student:</strong> student@test.com / student123</li>";
        $output .= "<li><strong>Teacher:</strong> teacher@test.com / teacher123</li>";
        $output .= "</ul>";
        
        $output .= "<p><a href='/login' class='btn btn-primary'>Go to Login</a></p>";
        $output .= "<p><a href='/admin/user-approvals' class='btn btn-success'>Go to Admin Approvals</a></p>";
        
        return $output;
        
    } catch (Exception $e) {
        return "<h1>❌ Fix Error</h1><p>Error: " . $e->getMessage() . "</p>";
    }
})->name('fix-approval-system');
