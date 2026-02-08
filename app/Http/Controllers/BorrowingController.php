<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\Book;
use App\Models\User;
use App\Models\SystemSetting;
use App\Services\DueDateCalculator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BorrowingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized.');
        }
        $borrowings = Borrowing::with(['book', 'user'])->orderByDesc('created_at')->paginate(10);
        return view('borrowings.index', compact('borrowings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $books = Book::where('status', 'available')->get();
        $users = User::all();
        return view('borrowings.create', compact('books', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Debug logging for QR scanner requests
        if ($request->header('Content-Type') === 'application/json') {
            \Log::info('QR Scanner borrowing request:', [
                'request_data' => $request->all(),
                'headers' => $request->headers->all(),
                'user_authenticated' => auth()->check(),
                'user_id' => auth()->id()
            ]);
        }
        
        // Check if user is authenticated
        if (!auth()->check()) {
            if ($request->wantsJson() || $request->header('Content-Type') === 'application/json') {
                return response()->json(['success' => false, 'message' => 'User not authenticated'], 401);
            }
            return redirect()->route('login');
        }
        
        try {
            $validated = $request->validate([
                'user_id' => 'required|numeric|exists:users,id',
                'book_id' => 'required|numeric|exists:books,id',
                'due_date' => 'nullable|date|after:today',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->wantsJson() || $request->header('Content-Type') === 'application/json') {
                return response()->json([
                    'success' => false, 
                    'message' => 'Validation failed: ' . implode(', ', $e->errors())
                ], 422);
            }
            throw $e;
        }

        // Check if self-service is enabled and user is borrowing for themselves
        $isSelfService = SystemSetting::get('self_service_enabled', true) &&
                        $validated['user_id'] == auth()->id() &&
                        !auth()->user()->isAdmin();

        // Check if user can borrow more books
        $maxBooks = (int) SystemSetting::get('max_books_per_user', 3);
        $currentBorrowings = Borrowing::where('user_id', $validated['user_id'])
            ->where('status', 'borrowed')
            ->count();

        if ($currentBorrowings >= $maxBooks) {
            if ($request->wantsJson() || $request->header('Content-Type') === 'application/json') {
                return response()->json(['success' => false, 'message' => "You can only borrow up to {$maxBooks} books at a time."], 422);
            }
            return redirect()->back()->with('error', "You can only borrow up to {$maxBooks} books at a time.");
        }

        // Check if book is available and has copies
        $book = Book::find($validated['book_id']);
        $available = is_null($book->available_quantity) ? ($book->quantity ?? 1) : $book->available_quantity;
        if ($book->status !== 'available' || $available <= 0) {
            if ($request->wantsJson() || $request->header('Content-Type') === 'application/json') {
                return response()->json(['success' => false, 'message' => 'This book is not available for borrowing.'], 422);
            }
            return redirect()->back()->with('error', 'This book is not available for borrowing.');
        }

        $borrowingDuration = SystemSetting::get('borrowing_duration_days', 14);
        
        // Use provided due_date or calculate using DueDateCalculator
        $dueDate = $validated['due_date'] ?? DueDateCalculator::calculateDueDate();

        // Use transaction to ensure borrow and inventory update happen together
        \DB::beginTransaction();
        try {
            $borrowing = Borrowing::create([
                'user_id' => $validated['user_id'],
                'book_id' => $validated['book_id'],
                'borrowed_at' => now(),
                'status' => 'borrowed',
                'due_date' => $dueDate,
            ]);

            // Decrement available_quantity safely
            $newAvailable = max(0, ($available - 1));
            $book->available_quantity = $newAvailable;
            // If no copies remain, mark as borrowed; otherwise remain available
            $book->status = $newAvailable <= 0 ? 'borrowed' : 'available';
            $book->save();

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            if ($request->wantsJson() || $request->header('Content-Type') === 'application/json') {
                return response()->json(['success' => false, 'message' => 'Failed to borrow book. Please try again.'], 500);
            }
            return redirect()->back()->with('error', 'Failed to borrow book. Please try again.');
        }

        if ($request->wantsJson() || $request->header('Content-Type') === 'application/json') {
            return response()->json(['success' => true, 'message' => 'Borrowing processed successfully!']);
        }
        
        if ($isSelfService) {
            $redirectRoute = 'borrowings.my_history'; // Redirect to user's borrowing history
            return redirect()->route($redirectRoute, $validated['user_id']);
        } else {
            // Admin borrowing for another user
            $userName = $validated['user_id'] == auth()->id() ? 'yourself' : User::find($validated['user_id'])->name;
            $message = "Book borrowed successfully for {$userName}! You can view their borrowing history or borrow more books.";
            $redirectRoute = 'admin.users.view_history';
            return redirect()->route($redirectRoute, $validated['user_id'])->with('success', $message);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Borrowing $borrowing)
    {
        $borrowing->load(['book', 'user']);
        return view('borrowings.show', compact('borrowing'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Borrowing $borrowing)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized.');
        }
        return redirect()->route('borrowings.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Borrowing $borrowing)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized.');
        }
        // Use transaction to mark returned and adjust inventory
        \DB::beginTransaction();
        try {
            $borrowing->update([
                'returned_at' => now(),
                'status' => 'returned',
            ]);

            $book = $borrowing->book;
            $quantity = $book->quantity ?? 1;
            $available = is_null($book->available_quantity) ? 0 : $book->available_quantity;
            $newAvailable = min($quantity, $available + 1);
            $book->available_quantity = $newAvailable;
            // If at least one copy is available, mark status available
            $book->status = $newAvailable > 0 ? 'available' : $book->status;
            $book->save();

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            return redirect()->back()->with('error', 'Failed to process return. Please try again.');
        }

        return redirect()->route('borrowings.index')->with('success', 'Book returned successfully.');
    }

    /**
     * Renew a borrowing (extend due date)
     */
    public function renew(Borrowing $borrowing)
    {
        // Check if user owns this borrowing or is admin
        if ($borrowing->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized.');
        }

        if ($borrowing->status !== 'borrowed') {
            return redirect()->back()->with('error', 'This book cannot be renewed because it is not currently borrowed.');
        }

        // Check if borrowing can be renewed
        if (!$borrowing->canRenew()) {
            $maxRenewals = SystemSetting::get('max_renewals', 2);
            return redirect()->back()->with('error', "This book has reached the maximum number of renewals ({$maxRenewals}).");
        }

        // Check if book is overdue
        if ($borrowing->due_date < now()) {
            return redirect()->back()->with('error', 'Overdue books cannot be renewed. Please return the book first.');
        }

        try {
            $borrowing->update([
                'due_date' => DueDateCalculator::calculateDueDate($borrowing->due_date),
                'renewals_count' => ($borrowing->renewals_count ?? 0) + 1,
            ]);

            $message = "Book renewed successfully! New due date: " . $borrowing->due_date->format('M d, Y');

            if (auth()->user()->isAdmin()) {
                return redirect()->back()->with('success', $message);
            } else {
                return redirect()->route('borrowings.my_history')->with('success', $message);
            }

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to renew book. Please try again.');
        }
    }

    /**
     * Mark borrowing as returned (dedicated method for "Mark as Returned" functionality)
     */
    public function markAsReturned(Borrowing $borrowing)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized.');
        }

        if ($borrowing->status === 'returned') {
            return redirect()->back()->with('error', 'This book has already been returned.');
        }

        // Use transaction to mark returned and adjust inventory
        \DB::beginTransaction();
        try {
            $borrowing->update([
                'returned_at' => now(),
                'status' => 'returned',
            ]);

            $book = $borrowing->book;
            $quantity = $book->quantity ?? 1;
            $available = is_null($book->available_quantity) ? 0 : $book->available_quantity;
            $newAvailable = min($quantity, $available + 1);
            $book->available_quantity = $newAvailable;
            // If at least one copy is available, mark status available
            $book->status = $newAvailable > 0 ? 'available' : $book->status;
            $book->save();

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            return redirect()->back()->with('error', 'Failed to process return. Please try again.');
        }

        return redirect()->back()->with('success', 'Book marked as returned successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Borrowing $borrowing)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized.');
        }

        $borrowing->delete();

        // Check if request came from reports page and redirect back there
        $referer = request()->headers->get('referer');
        if ($referer && str_contains($referer, route('borrowings.report', [], false))) {
            return redirect()->route('borrowings.report')->with('success', 'Borrowing record deleted successfully.');
        }

        return redirect()->route('borrowings.index')->with('success', 'Borrowing record deleted successfully.');
    }

    public function myHistory()
    {
        if (auth()->user()->isAdmin()) {
            // For admins, redirect to admin borrow page instead of dashboard
            return redirect()->route('borrowings.admin_borrow');
        }
        $borrowings = Borrowing::with('book')
            ->where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->paginate(10);
        return view('borrowings.my_history', compact('borrowings'));
    }

    /**
     * Display all currently borrowed books with user information
     */
    public function borrowed(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized.');
        }

        // Build query for currently borrowed books
        $borrowingsQuery = Borrowing::with(['book', 'user'])
            ->where('status', 'borrowed')
            ->orderByDesc('borrowed_at');

        // Apply search filter (book title or borrower name)
        if ($request->filled('search')) {
            $search = $request->search;
            $borrowingsQuery->where(function($query) use ($search) {
                $query->whereHas('book', function($q) use ($search) {
                    $q->where('title', 'like', '%' . $search . '%');
                })->orWhereHas('user', function($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                });
            });
        }

        // Apply overdue filter
        if ($request->filled('filter')) {
            switch ($request->filter) {
                case 'overdue':
                    $borrowingsQuery->where('due_date', '<', now());
                    break;
                case 'due_today':
                    $borrowingsQuery->whereDate('due_date', today());
                    break;
                case 'due_this_week':
                    $borrowingsQuery->whereBetween('due_date', [now(), now()->addDays(7)]);
                    break;
            }
        }

        // Get borrowed books with pagination
        $borrowings = $borrowingsQuery->paginate(20)->withQueryString();

        // Get statistics
        $totalBorrowed = Borrowing::where('status', 'borrowed')->count();
        $overdueCount = Borrowing::where('status', 'borrowed')
            ->where('due_date', '<', now())
            ->count();
        $dueTodayCount = Borrowing::where('status', 'borrowed')
            ->whereDate('due_date', today())
            ->count();

        return view('borrowings.borrowed', compact(
            'borrowings',
            'totalBorrowed',
            'overdueCount',
            'dueTodayCount'
        ));
    }

    public function report(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized.');
        }

        // Basic stats
        $totalBooks = \App\Models\Book::count();
        $borrowedBooks = \App\Models\Book::where('status', 'borrowed')->count();
        $availableBooks = \App\Models\Book::where('status', 'available')->count();

        // Calculate currently borrowed books (books that are actually borrowed, not just status)
        $currentlyBorrowed = Borrowing::where('status', 'borrowed')->count();

        // Get overdue books count
        $overdueBooks = Borrowing::where('status', 'borrowed')
            ->where('due_date', '<', now())
            ->count();

        // Real-time data
        $realTimeData = [
            'today_borrowings' => Borrowing::whereDate('borrowed_at', today())->count(),
            'today_returns' => Borrowing::whereDate('returned_at', today())->count(),
            'active_users_today' => Borrowing::whereDate('borrowed_at', today())
                ->distinct('user_id')
                ->count('user_id'),
            'available_books' => $availableBooks,
            'overdue_books' => $overdueBooks,
        ];

        // Build query for borrowing records with search and filters
        $borrowingsQuery = Borrowing::with(['book', 'user']);

        // Apply search filter (book title or borrower name)
        if ($request->filled('search')) {
            $search = $request->search;
            $borrowingsQuery->where(function($query) use ($search) {
                $query->whereHas('book', function($q) use ($search) {
                    $q->where('title', 'like', '%' . $search . '%');
                })->orWhereHas('user', function($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                });
            });
        }

        // Apply status filter
        if ($request->filled('status')) {
            $borrowingsQuery->where('status', $request->status);
        }

        // Apply date range filter
        if ($request->filled('from_date')) {
            $borrowingsQuery->whereDate('borrowed_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $borrowingsQuery->whereDate('borrowed_at', '<=', $request->to_date);
        }

        // Get filtered borrowings with pagination
        $borrowings = $borrowingsQuery->orderByDesc('created_at')->paginate(20)->withQueryString();

        return view('borrowings.report', compact(
            'totalBooks',
            'borrowedBooks',
            'availableBooks',
            'currentlyBorrowed',
            'overdueBooks',
            'realTimeData',
            'borrowings'
        ));
    }

    public function adminBorrow(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $books = Book::where('status', 'available')
            ->where('available_quantity', '>', 0)
            ->with(['author', 'category'])
            ->orderBy('title')
            ->get();

        $selectedUser = null;
        $userId = $request->query('user_id');

        // Handle POST request for student search
        if ($request->isMethod('post')) {
            $barcode = $request->input('barcode');
            $searchQuery = $request->input('search_query');

            if ($barcode) {
                // Search by barcode
                $foundUser = User::where('barcode', $barcode)->first();
                if ($foundUser) {
                    return redirect()->route('borrowings.admin_borrow', ['user_id' => $foundUser->id])
                        ->with('success', 'Student found via barcode.');
                } else {
                    return redirect()->route('borrowings.admin_borrow')
                        ->with('error', 'No student found with this barcode.')
                        ->withInput();
                }
            } elseif ($searchQuery) {
                // Search by name, email, or student ID
                $foundUser = User::where('student_id', $searchQuery)
                    ->orWhere('email', $searchQuery)
                    ->orWhere('name', 'like', '%' . $searchQuery . '%')
                    ->first();

                if ($foundUser) {
                    return redirect()->route('borrowings.admin_borrow', ['user_id' => $foundUser->id])
                        ->with('success', 'Student found via search.');
                } else {
                    return redirect()->route('borrowings.admin_borrow')
                        ->with('error', 'No student found matching your search.')
                        ->withInput();
                }
            }
        }

        if ($userId) {
            $selectedUser = User::find($userId);
            if (!$selectedUser) {
                return redirect()->route('borrowings.admin_borrow')
                    ->with('error', 'Student not found.');
            }
        }

        return view('borrowings.admin_borrow', compact('books', 'selectedUser'));
    }

    public function adminBarcodeLookup(Request $request)
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
                'message' => 'No student found with this barcode.'
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

    public function adminUserSearch(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'q' => 'required|string|min:2'
        ]);

        $query = $request->q;

        // Search for users by student_id, email, or name
        $users = User::where('student_id', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->orWhere('name', 'like', "%{$query}%")
            ->select('id', 'name', 'email', 'student_id', 'role')
            ->limit(10)
            ->get();

        if ($users->count() === 1) {
            // If only one user found, return it directly
            $user = $users->first();
            return response()->json([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'student_id' => $user->student_id,
                'role' => $user->role,
                'profile_photo' => $user->profile_photo_url,
            ]);
        } elseif ($users->count() > 1) {
            // If multiple users found, return list for selection
            return response()->json([
                'multiple' => true,
                'users' => $users->map(function($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'student_id' => $user->student_id,
                        'role' => $user->role,
                        'profile_photo' => $user->profile_photo_url,
                    ];
                })
            ]);
        } else {
            return response()->json([
                'error' => 'No students found matching your search.'
            ], 404);
        }
    }

    // Local-only testing endpoint to allow concurrent HTTP borrow attempts without CSRF.
    // This should only be enabled in local environment for testing.
    public function testingBorrow(Request $request)
    {
        if (!app()->environment('local')) {
            return response()->json(['error' => 'Not available'], 403);
        }
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
        ]);

        // Reuse core borrow logic similar to store(), but return JSON and avoid redirects
        $userId = $validated['user_id'];
        $book = Book::find($validated['book_id']);
        $available = is_null($book->available_quantity) ? ($book->quantity ?? 1) : $book->available_quantity;
        if ($book->status !== 'available' || $available <= 0) {
            return response()->json(['success' => false, 'message' => 'Not available'], 409);
        }

        \DB::beginTransaction();
        try {
            $borrowing = Borrowing::create([
                'user_id' => $userId,
                'book_id' => $book->id,
                'borrowed_at' => now(),
                'status' => 'borrowed',
                'due_date' => now()->addDays(SystemSetting::get('borrowing_duration_days', 14)),
            ]);
            $newAvailable = max(0, ($available - 1));
            $book->available_quantity = $newAvailable;
            $book->status = $newAvailable <= 0 ? 'borrowed' : 'available';
            $book->save();
            \DB::commit();
            return response()->json(['success' => true, 'borrowing_id' => $borrowing->id], 201);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Failed'], 500);
        }
    }

    // Update due date for borrowing
    public function updateDueDate(Request $request, Borrowing $borrowing)
    {
        if (!auth()->user()->isAdmin()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        $request->validate([
            'due_date' => 'required|date|after:today'
        ]);

        try {
            $borrowing->update([
                'due_date' => $request->due_date
            ]);

            return response()->json(['success' => true, 'message' => 'Due date updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update due date. Please try again.']);
        }
    }

    // Change book in borrowing
    public function changeBook(Request $request, Borrowing $borrowing)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized.');
        }

        $request->validate([
            'new_book_id' => 'required|exists:books,id'
        ]);

        $newBookId = $request->new_book_id;
        $oldBookId = $borrowing->book_id;

        // Don't allow changing to the same book
        if ($newBookId == $oldBookId) {
            return redirect()->back()->with('error', 'Cannot change to the same book.');
        }

        // Check if new book is available
        $newBook = Book::find($newBookId);
        if ($newBook->status !== 'available' || ($newBook->available_quantity ?? $newBook->quantity ?? 1) <= 0) {
            return redirect()->back()->with('error', 'The selected book is not available.');
        }

        $borrowingDuration = SystemSetting::get('borrowing_duration_days', 14);

        \DB::beginTransaction();
        try {
            // Update old book inventory (return it)
            $oldBook = $borrowing->book;
            $oldAvailable = is_null($oldBook->available_quantity) ? ($oldBook->quantity ?? 1) : $oldBook->available_quantity;
            $oldBook->available_quantity = min($oldBook->quantity ?? 1, $oldAvailable + 1);
            $oldBook->status = $oldBook->available_quantity > 0 ? 'available' : 'borrowed';
            $oldBook->save();

            // Update new book inventory (borrow it)
            $newAvailable = is_null($newBook->available_quantity) ? ($newBook->quantity ?? 1) : $newBook->available_quantity;
            $newBook->available_quantity = max(0, $newAvailable - 1);
            $newBook->status = $newBook->available_quantity <= 0 ? 'borrowed' : 'available';
            $newBook->save();

            // Update borrowing record
            $borrowing->update([
                'book_id' => $newBookId,
                'due_date' => now()->addDays($borrowingDuration),
                'renewals_count' => 0, // Reset renewals when changing book
            ]);

            \DB::commit();

            return redirect()->back()->with('success', 'Book changed successfully! Due date has been reset.');
        } catch (\Exception $e) {
            \DB::rollBack();
            return redirect()->back()->with('error', 'Failed to change book. Please try again.');
        }
    }

    // Local-only: allow header-based token auth and bypass CSRF/session for stress testing.
    public function testingBorrowNoCsrf(Request $request)
    {
        if (!app()->environment('local')) {
            return response()->json(['error' => 'Not available'], 403);
        }
        $secret = env('STRESS_TEST_SECRET') ?: 'local-secret-123';
        $provided = $request->header('X-TEST-SECRET');
        if (!$provided || !hash_equals($secret, $provided)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
        ]);

        $userId = $validated['user_id'];
        $book = Book::find($validated['book_id']);
        $available = is_null($book->available_quantity) ? ($book->quantity ?? 1) : $book->available_quantity;
        if ($book->status !== 'available' || $available <= 0) {
            return response()->json(['success' => false, 'message' => 'Not available'], 409);
        }

        \DB::beginTransaction();
        try {
            $borrowing = Borrowing::create([
                'user_id' => $userId,
                'book_id' => $book->id,
                'borrowed_at' => now(),
                'status' => 'borrowed',
                'due_date' => now()->addDays(SystemSetting::get('borrowing_duration_days', 14)),
            ]);
            $newAvailable = max(0, ($available - 1));
            $book->available_quantity = $newAvailable;
            $book->status = $newAvailable <= 0 ? 'borrowed' : 'available';
            $book->save();
            \DB::commit();
            return response()->json(['success' => true, 'borrowing_id' => $borrowing->id], 201);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Failed'], 500);
        }
    }
}
