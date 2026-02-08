<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\Book;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Local-only no-CSRF testing endpoint. Requires header X-TEST-SECRET matching env STRESS_TEST_SECRET.
Route::post('/testing/borrow/no-csrf', [\App\Http\Controllers\BorrowingController::class, 'testingBorrowNoCsrf']);

// Get available books for borrowing (no auth required for QR scanner)
Route::get('/books/available', function() {
    try {
        $books = Book::where('available_quantity', '>', 0)
            ->with(['author', 'category'])
            ->select('id', 'title', 'author_id', 'category_id', 'available_quantity', 'location')
            ->limit(50)
            ->get();
            
        return response()->json([
            'success' => true,
            'books' => $books->map(function($book) {
                return [
                    'id' => $book->id,
                    'title' => $book->title,
                    'author' => $book->author ? $book->author->name : 'Unknown Author',
                    'category' => $book->category ? $book->category->name : 'Unknown Category',
                    'available_quantity' => $book->available_quantity,
                    'location' => $book->location
                ];
            })
        ]);
    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error loading books: ' . $e->getMessage()
        ], 500);
    }
});

// API routes for QR scanner functionality
Route::middleware('auth')->group(function () {
    
    // Search user by student ID or email
    Route::get('/users/search', function(Request $request) {
        $query = $request->get('q');
        
        if (!$query) {
            return response()->json(['success' => false, 'message' => 'Query required']);
        }
        
        $user = User::where('student_id', $query)
            ->orWhere('email', $query)
            ->orWhere('name', 'like', "%{$query}%")
            ->first();
            
        if ($user) {
            return response()->json([
                'success' => true,
                'user' => [
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'student_id' => $user->student_id,
                    'barcode' => $user->barcode
                ]
            ]);
        }
        
        return response()->json(['success' => false, 'message' => 'User not found']);
    });
    
    // Get user by ID
    Route::get('/users/{id}', function($id) {
        $user = User::find($id);
        
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found']);
        }
        
        return response()->json([
            'success' => true,
            'user' => [
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'student_id' => $user->student_id,
                'barcode' => $user->barcode
            ]
        ]);
    });
});
