<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\UserApproved;
use App\Mail\UserRejected;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class UserApprovalController extends Controller
{
    public function index()
    {
        $pendingUsers = User::where('is_approved', false)
            ->where('role', '!=', 'admin') // Exclude admin accounts from pending
            ->withCount('borrowings')
            ->orderBy('created_at', 'desc')
            ->get();
        
        $recentlyApproved = User::where('is_approved', true)
            ->whereNotNull('approved_at')
            ->with('approvedBy')
            ->orderBy('approved_at', 'desc')
            ->limit(10)
            ->get();
        
        return view('admin.user-approvals', compact('pendingUsers', 'recentlyApproved'));
    }

    public function show(User $user)
    {
        if ($user->is_approved) {
            return redirect()->route('admin.users.approvals')
                ->with('error', 'This user is already approved.');
        }
        
        return view('admin.user-approval-detail', compact('user'));
    }

    public function approve(User $user)
    {
        if ($user->is_approved) {
            return redirect()->back()->with('error', 'User is already approved.');
        }

        $user->update([
            'is_approved' => true,
            'approved_at' => now(),
            'approved_by' => Auth::id(),
        ]);

        // Send approval email notification
        $approvedBy = Auth::user();
        Mail::to($user->email)->send(new UserApproved($user, $approvedBy));

        return redirect()->route('admin.users.approvals')
            ->with('success', "User {$user->name} has been approved successfully!");
    }

    public function reject(Request $request, User $user)
    {
        if ($user->is_approved) {
            return redirect()->back()->with('error', 'User is already approved.');
        }

        $reason = $request->input('reason', 'Registration rejected by administrator');
        
        // Store user info before deletion
        $userEmail = $user->email;
        $userName = $user->name;
        
        // Send rejection email notification before deletion
        Mail::to($userEmail)->send(new UserRejected($userName, $userEmail, $reason));
        
        $user->delete();

        return redirect()->route('admin.users.approvals')
            ->with('success', "User {$userName} has been rejected and removed.");
    }

    public function bulkApprove(Request $request)
    {
        $userIds = $request->input('user_ids', []);
        
        // Convert string to array if it's not already an array
        if (!is_array($userIds)) {
            $userIds = [$userIds];
        }
        
        // Filter out any empty values
        $userIds = array_filter($userIds);
        
        if (empty($userIds)) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['error' => 'No users selected.'], 422);
            }
            return redirect()->back()->with('error', 'No users selected.');
        }

        // Get users before updating for email notifications
        $users = User::whereIn('id', $userIds)
            ->where('is_approved', false)
            ->get();

        $updated = User::whereIn('id', $userIds)
            ->where('is_approved', false)
            ->update([
                'is_approved' => true,
                'approved_at' => now(),
                'approved_by' => Auth::id(),
            ]);

        // Send approval email notifications
        $approvedBy = Auth::user();
        foreach ($users as $user) {
            Mail::to($user->email)->send(new UserApproved($user, $approvedBy));
        }

        $message = "Successfully approved {$updated} user(s).";
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => $message,
                'updated' => $updated
            ]);
        }
        
        return redirect()->back()->with('success', $message);
    }

    public function bulkReject(Request $request)
    {
        $userIds = $request->input('user_ids', []);
        
        // Convert string to array if it's not already an array
        if (!is_array($userIds)) {
            $userIds = [$userIds];
        }
        
        // Filter out any empty values
        $userIds = array_filter($userIds);
        
        if (empty($userIds)) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['error' => 'No users selected.'], 422);
            }
            return redirect()->back()->with('error', 'No users selected.');
        }

        // Get users before deletion for email notifications
        $users = User::whereIn('id', $userIds)
            ->where('is_approved', false)
            ->get();

        // Send rejection email notifications before deletion
        foreach ($users as $user) {
            Mail::to($user->email)->send(new UserRejected($user->name, $user->email));
        }

        $deleted = User::whereIn('id', $userIds)
            ->where('is_approved', false)
            ->delete();

        $message = "Successfully rejected and removed {$deleted} user(s).";
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => $message,
                'deleted' => $deleted
            ]);
        }
        
        return redirect()->back()
            ->with('success', $message);
    }

    public function stats()
    {
        $pendingCount = User::where('is_approved', false)
            ->where('role', '!=', 'admin') // Exclude admin accounts
            ->count();
        $approvedCount = User::where('is_approved', true)->count();
        $totalUsers = User::count();
        
        return response()->json([
            'pending_count' => $pendingCount,
            'approved_count' => $approvedCount,
            'total_users' => $totalUsers,
            'approval_rate' => $totalUsers > 0 ? round(($approvedCount / $totalUsers) * 100, 2) : 0,
        ]);
    }
}
