<?php

use Illuminate\Support\Facades\DB;
use App\Models\User;

// Fix all approved users to ensure they can login
Route::get('/fix-approved-users', function () {
    try {
        $output = "<h1>🔧 Fixing Approved Users...</h1>";
        
        // Update all users who should be approved but might have wrong values
        $updated = DB::table('users')
            ->where(function($query) {
                $query->where('is_approved', true)
                      ->orWhere('is_approved', 1)
                      ->orWhere('role', 'admin');
            })
            ->update([
                'is_approved' => true,
                'approved_at' => DB::raw('COALESCE(approved_at, NOW())'),
            ]);
        
        $output .= "<p>✅ Updated {$updated} user(s) to ensure proper approval status</p>";
        
        // Show current status of all users
        $users = DB::table('users')
            ->select('id', 'name', 'email', 'role', 'is_approved', 'approved_at')
            ->orderBy('id')
            ->get();
        
        $output .= "<h3>Current User Status:</h3>";
        $output .= "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        $output .= "<tr style='background: #f0f0f0;'><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>is_approved</th><th>approved_at</th><th>Can Login?</th></tr>";
        
        foreach ($users as $user) {
            $canLogin = ($user->role === 'admin') || ($user->is_approved == 1);
            $status = $canLogin ? "✅ YES" : "❌ NO";
            
            $output .= "<tr>";
            $output .= "<td>{$user->id}</td>";
            $output .= "<td>{$user->name}</td>";
            $output .= "<td>{$user->email}</td>";
            $output .= "<td>{$user->role}</td>";
            $output .= "<td>" . ($user->is_approved ? "✅ True" : "❌ False") . "</td>";
            $output .= "<td>{$user->approved_at}</td>";
            $output .= "<td><strong>{$status}</strong></td>";
            $output .= "</tr>";
        }
        $output .= "</table>";
        
        // Test User model methods
        $output .= "<h3>Testing User Model Methods:</h3>";
        foreach ($users as $userData) {
            $user = User::find($userData->id);
            if ($user) {
                $canLogin = $user->isAdmin() || $user->isApproved();
                $methodStatus = $canLogin ? "✅ Can Login" : "❌ Cannot Login";
                $output .= "<p><strong>{$user->email}:</strong> isAdmin() = " . ($user->isAdmin() ? "true" : "false") . ", isApproved() = " . ($user->isApproved() ? "true" : "false") . " → {$methodStatus}</p>";
            }
        }
        
        $output .= "<h3>🎯 Test These Accounts:</h3>";
        $output .= "<ul>";
        $output .= "<li><strong>Admin:</strong> admin@libraflow.com / admin123</li>";
        $output .= "<li><strong>Student:</strong> student@test.com / student123</li>";
        $output .= "<li><strong>Teacher:</strong> teacher@test.com / teacher123</li>";
        $output .= "</ul>";
        
        $output .= "<p><a href='/login' class='btn btn-primary'>Go Test Login</a></p>";
        $output .= "<p><a href='/admin/user-approvals' class='btn btn-success'>Admin Approvals</a></p>";
        
        return $output;
        
    } catch (Exception $e) {
        return "<h1>❌ Error</h1><p>" . $e->getMessage() . "</p>";
    }
})->name('fix-approved-users');
