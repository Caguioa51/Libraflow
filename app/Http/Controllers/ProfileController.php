<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request)
    {
        $user = $request->user();
        $recentBorrowings = $user->borrowings()->with('book')->orderByDesc('created_at')->limit(5)->get();
        $totalBorrowed = $user->borrowings()->count();
        $overdue = $user->borrowings()->where('status', 'borrowed')->whereDate('borrowed_at', '<=', now()->subDays(14))->count();
        $finePerBook = 20;
        $totalFine = $overdue * $finePerBook;
        return view('profile.edit', [
            'user' => $user,
            'recentBorrowings' => $recentBorrowings,
            'totalBorrowed' => $totalBorrowed,
            'totalFine' => $totalFine,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $user = $request->user();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'student_id' => 'nullable|string|max:255',
        ]);
        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('settings')->with('success', 'Profile updated successfully!');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request)
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function downloadData(Request $request)
    {
        $user = $request->user();
        $borrowings = $user->borrowings()->with('book')->get();
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="borrowing_history.csv"',
        ];
        $columns = ['Book Title', 'Borrowed At', 'Returned At', 'Status'];
        $callback = function() use ($borrowings, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($borrowings as $b) {
                fputcsv($file, [
                    $b->book->title ?? 'N/A',
                    $b->borrowed_at,
                    $b->returned_at,
                    $b->status,
                ]);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Display the user's settings form.
     */
    public function settings(Request $request)
    {
        $user = $request->user();
        $totalBorrowed = $user->borrowings()->count();
        
        return view('profile.settings', [
            'user' => $user,
            'totalBorrowed' => $totalBorrowed,
        ]);
    }

    /**
     * Update the user's settings.
     */
    public function updateSettings(Request $request)
    {
        $user = $request->user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'lrn_number' => 'nullable|string|max:255|unique:users,lrn_number,' . $user->id,
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.settings')->with('success', 'Settings updated successfully!');
    }

    // QR feature removed: QR generation and view were deleted.
}
