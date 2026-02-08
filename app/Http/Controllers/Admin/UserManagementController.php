<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        $query = User::with(['borrowings' => function($q) {
            $q->where('status', 'borrowed');
        }]);

        // Search functionality
        if ($search = $request->search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('student_id', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($role = $request->role) {
            $query->where('role', $role);
        }

        // Filter by status
        if ($status = $request->status) {
            if ($status === 'active') {
                $query->whereDoesntHave('borrowings', function($q) {
                    $q->where('status', 'borrowed')->where('due_date', '<', now());
                });
            } elseif ($status === 'overdue') {
                $query->whereHas('borrowings', function($q) {
                    $q->where('status', 'borrowed')->where('due_date', '<', now());
                });
            }
        }

        // Sorting
        $sortBy = $request->get('sort', 'latest'); // Default to latest
        switch ($sortBy) {
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'latest':
            default:
                $query->orderBy('updated_at', 'desc');
                break;
        }

        $users = $query->paginate(15)->withQueryString();

        $stats = [
            'total' => User::count(),
            'students' => User::where('role', 'student')->count(),
            'teachers' => User::where('role', 'teacher')->count(),
            'admins' => User::where('role', 'admin')->count(),
            'active_borrowers' => User::whereHas('borrowings', function($q) {
                $q->where('status', 'borrowed');
            })->count(),
            'overdue_borrowers' => User::whereHas('borrowings', function($q) {
                $q->where('status', 'borrowed')->where('due_date', '<', now());
            })->count(),
            'available_books' => \App\Models\Book::where('status', 'available')
                ->where('available_quantity', '>', 0)
                ->count(),
        ];

        return view('admin.users.index', compact('users', 'stats'));
    }

    public function borrowForUser(User $user)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        // Redirect to admin borrow page with user pre-selected
        return redirect()->route('borrowings.admin_borrow', ['user_id' => $user->id]);
    }

    public function viewHistory(User $user)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        $borrowings = \App\Models\Borrowing::with(['book', 'user'])
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->paginate(20);

        // Get total available books in inventory
        $availableBooks = \App\Models\Book::where('status', 'available')
            ->where('available_quantity', '>', 0)
            ->count();

        return view('admin.users.history', compact('borrowings', 'user', 'availableBooks'));
    }

    public function updateStudentId(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'student_id' => 'nullable|string|max:255'
        ]);

        try {
            $user = User::findOrFail($request->user_id);

            // Check if the new student_id is already taken by another user
            if ($request->student_id) {
                $existingUser = User::where('student_id', $request->student_id)
                                   ->where('id', '!=', $user->id)
                                   ->first();

                if ($existingUser) {
                    return response()->json([
                        'success' => false,
                        'message' => 'This Student ID is already assigned to ' . $existingUser->name
                    ]);
                }
            }

            $oldStudentId = $user->student_id;
            $user->update(['student_id' => $request->student_id]);

            // Log the change for audit purposes
            \Log::info('Admin updated student ID', [
                'admin_id' => auth()->id(),
                'admin_name' => auth()->user()->name,
                'user_id' => $user->id,
                'user_name' => $user->name,
                'old_student_id' => $oldStudentId,
                'new_student_id' => $request->student_id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Student ID updated successfully'
            ]);

        } catch (\Exception $e) {
            \Log::error('Failed to update student ID', [
                'error' => $e->getMessage(),
                'user_id' => $request->user_id,
                'admin_id' => auth()->id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update Student ID. Please try again.'
            ]);
        }
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        return view('admin.users.create');
    }

    /**
     * Store a newly created user.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'student_id' => ['required', 'string', 'max:255', 'unique:users,student_id'],
            'role' => ['required', 'in:student,teacher,admin'],
            'rfid_card' => ['nullable', 'string', 'max:255', 'unique:users,barcode'],
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
        ]);

        try {
            // Determine the barcode (RFID) for the user
            $barcode = $request->rfid_card
                ? $request->rfid_card
                : 'STUDENT-' . $request->student_id;

            // Check if the barcode is already taken
            $existingUser = User::where('barcode', $barcode)->first();
            if ($existingUser) {
                return back()->withErrors(['rfid_card' => 'This RFID card number is already assigned to another user.'])->withInput();
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'student_id' => $request->student_id,
                'role' => $request->role,
                'barcode' => $barcode,
                'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            ]);

            // Log the user creation for audit purposes
            \Log::info('Admin created new user', [
                'admin_id' => auth()->id(),
                'admin_name' => auth()->user()->name,
                'new_user_id' => $user->id,
                'new_user_name' => $user->name,
                'new_user_email' => $user->email,
                'new_user_role' => $user->role,
                'barcode' => $barcode
            ]);

            return redirect()->route('admin.users.index')->with('success', 'User created successfully.');

        } catch (\Exception $e) {
            \Log::error('Failed to create user', [
                'error' => $e->getMessage(),
                'admin_id' => auth()->id(),
                'request_data' => $request->except(['password', 'password_confirmation'])
            ]);

            return back()->withErrors(['error' => 'Failed to create user. Please try again.'])->withInput();
        }
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        // Build validation rules based on user role
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'rfid_card' => ['nullable', 'string', 'max:255', 'unique:users,barcode,' . $user->id],
            'password' => ['nullable', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
        ];

        // Add role-specific validation rules
        if ($user->isStudent()) {
            $rules['grade'] = ['required', 'integer', 'min:7', 'max:12'];
            $rules['section'] = ['required', 'string', 'max:10'];
            $rules['lrn_number'] = ['required', 'string', 'max:255', 'unique:users,lrn_number,' . $user->id];
        } elseif ($user->isTeacher()) {
            $rules['department'] = ['required', 'string', 'max:255'];
            $rules['employee_id'] = ['required', 'string', 'max:255', 'unique:users,employee_id,' . $user->id];
        }

        $request->validate($rules);

        try {
            // Start with common fields
            $data = $request->only(['name', 'email']);

            // Add role-specific fields
            if ($user->isStudent()) {
                $data = array_merge($data, $request->only(['grade', 'section', 'lrn_number']));
            } elseif ($user->isTeacher()) {
                $data = array_merge($data, $request->only(['department', 'employee_id']));
            }

            // Handle RFID/barcode update
            if ($request->filled('rfid_card')) {
                $data['barcode'] = $request->rfid_card;
            }

            // Handle password update
            if ($request->filled('password')) {
                $data['password'] = \Illuminate\Support\Facades\Hash::make($request->password);
            }

            // Log changes for audit purposes
            $changes = [];
            foreach ($data as $key => $value) {
                if ($user->$key !== $value) {
                    $changes[$key] = [
                        'old' => $user->$key,
                        'new' => $value
                    ];
                }
            }

            $user->update($data);

            // Log the update for audit purposes
            \Log::info('Admin updated user', [
                'admin_id' => auth()->id(),
                'admin_name' => auth()->user()->name,
                'user_id' => $user->id,
                'user_name' => $user->name,
                'changes' => $changes
            ]);

            return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');

        } catch (\Exception $e) {
            \Log::error('Failed to update user', [
                'error' => $e->getMessage(),
                'admin_id' => auth()->id(),
                'user_id' => $user->id,
                'request_data' => $request->except(['password', 'password_confirmation'])
            ]);

            return back()->withErrors(['error' => 'Failed to update user. Please try again.'])->withInput();
        }
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')->with('error', 'You cannot delete your own account.');
        }

        try {
            // Log the deletion for audit purposes
            \Log::info('Admin deleted user', [
                'admin_id' => auth()->id(),
                'admin_name' => auth()->user()->name,
                'deleted_user_id' => $user->id,
                'deleted_user_name' => $user->name,
                'deleted_user_email' => $user->email
            ]);

            $user->delete();

            return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');

        } catch (\Exception $e) {
            \Log::error('Failed to delete user', [
                'error' => $e->getMessage(),
                'admin_id' => auth()->id(),
                'user_id' => $user->id
            ]);

            return redirect()->route('admin.users.index')->with('error', 'Failed to delete user. Please try again.');
        }
    }
}
