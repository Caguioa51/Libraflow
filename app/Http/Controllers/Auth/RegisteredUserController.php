<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\UserRegistrationPending;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
// QR code generation removed to simplify install and avoid dependency issues in some environments

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'role' => ['required', 'string', 'in:student,teacher'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ];

        // Add validation based on role
        if ($request->role === 'student') {
            $rules['grade'] = ['required', 'string', 'max:10'];
            $rules['section'] = ['required', 'string', 'max:10'];
        } elseif ($request->role === 'teacher') {
            $rules['department'] = ['required', 'string', 'max:50'];
            $rules['employee_id'] = ['required', 'string', 'max:20'];
        }

        $request->validate($rules);

        // Auto-approve admin roles, require approval for others
        $isApproved = $request->has('role') && $request->role === 'admin';

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_approved' => $isApproved,
            'approved_at' => $isApproved ? now() : null,
            'approved_by' => $isApproved ? null : null, // Self-approved for admins
        ];

        // Add role-specific fields
        if ($request->role === 'student') {
            $userData['grade'] = $request->grade;
            $userData['section'] = $request->section;
        } elseif ($request->role === 'teacher') {
            $userData['department'] = $request->department;
            $userData['employee_id'] = $request->employee_id;
        }

        $user = User::create($userData);

        event(new Registered($user));

        // Send registration pending notification for users requiring approval
        if (!$isApproved) {
            Mail::to($user->email)->send(new UserRegistrationPending($user));
        }

        if ($isApproved) {
            // Auto-login admin users
            Auth::login($user);
            return redirect()->route('dashboard')->with('success', 'Admin account created successfully!');
        } else {
            // Regular users need approval
            return redirect()->route('login')->with('status', 'Registration successful! Your account is pending admin approval. You will be notified when approved.');
        }
    }
}
