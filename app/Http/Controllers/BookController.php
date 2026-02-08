<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Author;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Book::with(['category', 'author'])
            ->whereNotNull('author_id')
            ->whereNotNull('category_id');

        if ($search = request('search')) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                  ->orWhereHas('author', function($q2) use ($search) {
                      $q2->where('name', 'like', "%$search%");
                  })
                  ->orWhere('genre', 'like', "%$search%");
            });
        }

        if ($categoryId = request('category_id')) {
            $query->where('category_id', $categoryId);
        }

        if ($status = request('status')) {
            if ($status === 'available') {
                $query->where('status', 'available')
                      ->where('available_quantity', '>', 0);
            } elseif ($status === 'borrowed') {
                $query->where('status', 'borrowed');
            }
        }

        $books = $query->paginate(12); // Increased per page for better grid layout
        $categories = Category::has('books')->withCount('books')->get();

        return view('books.index', compact('books', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Allow all authenticated users to create books
        $categories = Category::all();
        $authors = Author::all();
        return view('books.create', compact('categories', 'authors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Allow all authenticated users to create books

        // Validate basic fields first
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'genre' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'quantity' => 'required|integer|min:1',
            'author_id' => 'required_without:new_author|nullable|exists:authors,id',
            'new_author' => 'required_without:author_id|nullable|string|max:255',
            'category_id' => 'required_without:new_category|nullable|exists:categories,id',
            'new_category' => 'required_without:category_id|nullable|string|max:255',
        ]);

        // Handle author (existing or new)
        if ($request->filled('new_author')) {
            $author = Author::firstOrCreate(['name' => $request->new_author]);
            $authorId = $author->id;
        } else {
            $authorId = $request->author_id;
        }

        // Handle category (existing or new)
        if ($request->filled('new_category')) {
            $category = Category::firstOrCreate(['name' => $request->new_category]);
            $categoryId = $category->id;
        } else {
            $categoryId = $request->category_id;
        }

        // Create the book with the validated data and resolved IDs
        Book::create([
            'title' => $validated['title'],
            'author_id' => $authorId,
            'category_id' => $categoryId,
            'genre' => $validated['genre'] ?? null,
            'description' => $validated['description'] ?? null,
            'location' => $validated['location'] ?? null,
            'quantity' => $validated['quantity'],
            'available_quantity' => $validated['quantity'], // Set available_quantity equal to quantity
            'status' => 'available', // Set default status
        ]);

        return redirect()->route('books.index')
            ->with('success', 'Book created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        $book->load(['category', 'author']);
        return view('books.show', compact('book'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('books.index')->with('error', 'Unauthorized.');
        }
        $categories = Category::all();
        $authors = Author::all();
        return view('books.edit', compact('book', 'categories', 'authors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('books.index')->with('error', 'Unauthorized.');
        }
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'author_id' => 'required|exists:authors,id',
            'genre' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'quantity' => 'required|integer|min:1',
            'available_quantity' => 'nullable|integer|min:0',
        ]);
        if (isset($validated['quantity']) && !isset($validated['available_quantity'])) {
            // keep existing available quantity but cap at new quantity
            $validated['available_quantity'] = min($book->available_quantity ?? $validated['quantity'], $validated['quantity']);
        }
        if (isset($validated['available_quantity']) && isset($validated['quantity']) && $validated['available_quantity'] > $validated['quantity']) {
            $validated['available_quantity'] = $validated['quantity'];
        }
        $book->update($validated);
        return redirect()->route('books.index')->with('success', 'Book updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('books.index')->with('error', 'Unauthorized.');
        }
        $book->delete();
        return redirect()->route('books.index')->with('book_deleted', 'Book "' . $book->title . '" has been permanently deleted.');
    }

    /**
     * Request to borrow a book (creates borrowing request instead of direct borrowing)
     */
    public function requestBorrow(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
        ]);

        $user = auth()->user();
        $book = Book::findOrFail($request->book_id);

        // Auto-expire overdue requests first
        $this->autoExpireRequests();

        // Check if book is available for requesting
        if ($book->available_quantity <= 0) {
            return redirect()->back()->with('error', 'This book is not available for borrowing.');
        }

        // Check if user already has a pending request for this book
        $existingRequest = \App\Models\BorrowingRequest::where('user_id', $user->id)
            ->where('book_id', $book->id)
            ->where('status', 'pending')
            ->exists();

        if ($existingRequest) {
            return redirect()->back()->with('error', 'You already have a pending request for this book.');
        }

        // Check if user already has this book borrowed
        $existingBorrowing = \App\Models\Borrowing::where('user_id', $user->id)
            ->where('book_id', $book->id)
            ->where('status', 'borrowed')
            ->exists();

        if ($existingBorrowing) {
            return redirect()->back()->with('error', 'You have already borrowed this book.');
        }

        // Check if user has reached their borrowing limit
        $maxBooks = (int) \App\Models\SystemSetting::get('max_books_per_user', 3);
        $currentBorrowings = \App\Models\Borrowing::where('user_id', $user->id)
            ->where('status', 'borrowed')
            ->count();

        if ($currentBorrowings >= $maxBooks) {
            return redirect()->back()->with('error', "You have reached your maximum borrowing limit of {$maxBooks} books. You currently have {$currentBorrowings} books.");
        }

        // Create borrowing request with 2-hour expiration
        \App\Models\BorrowingRequest::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'status' => 'pending',
            'requested_at' => now(),
            'expires_at' => now()->addHours(2),
        ]);

        // Reduce available quantity immediately
        $book->decrement('available_quantity');

        return redirect()->back()->with('success', 'Your borrowing request has been submitted and is pending admin approval. The request will expire in 2 hours if not approved.');
    }

    /**
     * Auto-expire overdue requests and restore book availability
     */
    private function autoExpireRequests()
    {
        $expiredRequests = \App\Models\BorrowingRequest::where('status', 'pending')
            ->where('expires_at', '<', now())
            ->with(['book'])
            ->get();

        foreach ($expiredRequests as $request) {
            // Update request status to expired
            $request->update(['status' => 'expired']);

            // Restore book availability
            $book = $request->book;
            if ($book) {
                $book->increment('available_quantity');
            }
        }
    }

    /**
     * Reserve a book for the authenticated user.
     */
    public function reserve(Book $book)
    {
        if (auth()->user()->isAdmin()) {
            return redirect()->back()->with('error', 'Admins cannot reserve books.');
        }

        // Check if user already has this book reserved
        $existingReservation = \App\Models\BookReservation::where('user_id', auth()->id())
            ->where('book_id', $book->id)
            ->where('status', 'active')
            ->exists();

        if ($existingReservation) {
            return redirect()->back()->with('error', 'You have already reserved this book.');
        }

        // Check if book is available for reservation
        if ($book->available_quantity <= 0) {
            return redirect()->back()->with('error', 'This book is not available for reservation.');
        }

        // Check user's reservation limit
        $userReservations = \App\Models\BookReservation::where('user_id', auth()->id())
            ->where('status', 'active')
            ->count();

        $maxReservations = auth()->user()->isStudent() ? 2 : 3;
        if ($userReservations >= $maxReservations) {
            return redirect()->back()->with('error', "You can only have {$maxReservations} active reservations at a time.");
        }

        // Create reservation
        \App\Models\BookReservation::create([
            'user_id' => auth()->id(),
            'book_id' => $book->id,
            'reserved_at' => now(),
            'status' => 'active',
        ]);

        return redirect()->back()->with('success', 'Book reserved successfully! You will be notified when it becomes available.');
    }

    /**
     * Cancel a book reservation.
     */
    public function cancelReservation(Book $book)
    {
        $reservation = \App\Models\BookReservation::where('user_id', auth()->id())
            ->where('book_id', $book->id)
            ->where('status', 'active')
            ->first();

        if (!$reservation) {
            return redirect()->back()->with('error', 'No active reservation found for this book.');
        }

        $reservation->update(['status' => 'cancelled']);

        return redirect()->back()->with('success', 'Book reservation cancelled successfully.');
    }

    /**
     * Display user's reservations.
     */
    public function myReservations()
    {
        $reservations = \App\Models\BookReservation::with(['book', 'book.category', 'book.author'])
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('books.reservations', compact('reservations'));
    }
}
