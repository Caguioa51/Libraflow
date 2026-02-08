<?php

use Illuminate\Support\Facades\DB;
use App\Models\User;

// Debug route to check approval status
Route::get('/debug-approval', function () {
    try {
        $output = "<h1>🔍 Approval Debug Information</h1>";
        
        // Check if columns exist
        $columns = DB::select("DESCRIBE users");
        $hasIsApproved = false;
        $hasApprovedAt = false;
        $hasApprovedBy = false;
        
        foreach ($columns as $column) {
            if ($column->Field === 'is_approved') $hasIsApproved = true;
            if ($column->Field === 'approved_at') $hasApprovedAt = true;
            if ($column->Field === 'approved_by') $hasApprovedBy = true;
        }
        
        $output .= "<h3>Database Columns:</h3>";
        $output .= "<ul>";
        $output .= "<li>is_approved: " . ($hasIsApproved ? "✅ Exists" : "❌ Missing") . "</li>";
        $output .= "<li>approved_at: " . ($hasApprovedAt ? "✅ Exists" : "❌ Missing") . "</li>";
        $output .= "<li>approved_by: " . ($hasApprovedBy ? "✅ Exists" : "❌ Missing") . "</li>";
        $output .= "</ul>";
        
        // Show all users with their approval status
        $users = DB::table('users')->select('id', 'name', 'email', 'role', 'is_approved', 'approved_at', 'approved_by')->get();
        
        $output .= "<h3>All Users:</h3>";
        $output .= "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        $output .= "<tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>is_approved</th><th>approved_at</th><th>approved_by</th></tr>";
        
        foreach ($users as $user) {
            $output .= "<tr>";
            $output .= "<td>{$user->id}</td>";
            $output .= "<td>{$user->name}</td>";
            $output .= "<td>{$user->email}</td>";
            $output .= "<td>{$user->role}</td>";
            $output .= "<td>" . ($user->is_approved ? "✅ True" : "❌ False") . "</td>";
            $output .= "<td>{$user->approved_at}</td>";
            $output .= "<td>{$user->approved_by}</td>";
            $output .= "</tr>";
        }
        $output .= "</table>";
        
        // Test User model methods
        $output .= "<h3>User Model Method Tests:</h3>";
        foreach ($users as $userData) {
            $user = User::find($userData->id);
            if ($user) {
                $output .= "<p><strong>{$user->email}:</strong> isApproved() = " . ($user->isApproved() ? "✅ True" : "❌ False") . "</p>";
            }
        }
        
        return $output;
        
    } catch (Exception $e) {
        return "<h1>❌ Debug Error</h1><p>" . $e->getMessage() . "</p>";
    }
})->name('debug-approval');
