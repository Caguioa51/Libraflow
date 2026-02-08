<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class BarcodeController extends Controller
{
    public function scan()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        return view('admin.barcode.scan');
    }

    public function lookup(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'barcode' => 'required|string'
        ]);

        $user = User::where('barcode', $request->barcode)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'No user found with this barcode.'
            ]);
        }

        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'student_id' => $user->student_id,
                'role' => $user->role,
                'profile_photo' => $user->profile_photo_url,
            ]
        ]);
    }

    public function assign(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'barcode' => 'required|string|unique:users,barcode'
        ]);

        $user = User::findOrFail($request->user_id);
        $user->update(['barcode' => $request->barcode]);

        return redirect()->back()->with('success', 'Barcode assigned successfully to ' . $user->name);
    }
}
