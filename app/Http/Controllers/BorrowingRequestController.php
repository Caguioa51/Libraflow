<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BorrowingRequest;
use App\Models\Borrowing;
use App\Models\Book;
use App\Mail\BookRequestExpired;
use App\Mail\BookRequestApproved;
use Illuminate\Support\Facades\Mail;

class BorrowingRequestController extends Controller
{
    public function index()
    {
        // Auto-expire overdue requests before showing the list
        $this->autoExpireRequests();
        
        $user = auth()->user();
        $borrowingRequests = BorrowingRequest::where('user_id', $user->id)
            ->with(['book', 'book.author', 'book.category'])
            ->latest()
            ->paginate(10);
            
        return view('borrowing-requests.index', compact('borrowingRequests'));
    }

    /**
     * Auto-expire overdue requests and restore book availability
     */
    private function autoExpireRequests()
    {
        $expiredRequests = BorrowingRequest::where('status', 'pending')
            ->where('expires_at', '<', now())
            ->with(['book', 'user'])
            ->get();

        foreach ($expiredRequests as $request) {
            // Update request status to expired
            $request->update(['status' => 'expired']);

            // Restore book availability
            $book = $request->book;
            if ($book) {
                $book->increment('available_quantity');
            }

            // Send expiration email notification
            if ($request->user && $request->user->email) {
                try {
                    Mail::to($request->user->email)->send(new BookRequestExpired($request));
                } catch (\Exception $e) {
                    // Log error but continue processing other requests
                    \Log::error("Failed to send expiration email to {$request->user->email}: {$e->getMessage()}");
                }
            }
        }
    }

    public function approve(Request $request, BorrowingRequest $borrowingRequest)
    {
        // Only admins can approve requests
        if (!auth()->user()->isAdmin()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        // Auto-expire overdue requests first
        $this->autoExpireRequests();

        // Check if request is still pending and not expired
        if ($borrowingRequest->status !== 'pending' || $borrowingRequest->shouldExpire()) {
            return response()->json(['success' => false, 'message' => 'Request is no longer pending or has expired'], 400);
        }

        // Check if book is still available
        $book = $borrowingRequest->book;
        if ($book->available_quantity <= 0) {
            return response()->json(['success' => false, 'message' => 'Book is no longer available'], 400);
        }

        // Check if user has reached their borrowing limit
        $maxBooks = (int) \App\Models\SystemSetting::get('max_books_per_user', 3);
        $currentBorrowings = Borrowing::where('user_id', $borrowingRequest->user_id)
            ->where('status', 'borrowed')
            ->count();

        if ($currentBorrowings >= $maxBooks) {
            return response()->json([
                'success' => false, 
                'message' => "User has reached their maximum borrowing limit of {$maxBooks} books. They currently have {$currentBorrowings} books."
            ], 400);
        }

        // Validate due date
        $request->validate([
            'due_date' => 'required|date|after:today'
        ]);

        // Create borrowing record with custom due date
        $borrowing = Borrowing::create([
            'user_id' => $borrowingRequest->user_id,
            'book_id' => $borrowingRequest->book_id,
            'borrowed_at' => now(),
            'due_date' => $request->due_date,
            'status' => 'borrowed'
        ]);

        // Approve request (book quantity already reduced when request was made)
        $borrowingRequest->approve(auth()->id());

        // Send approval email notification
        try {
            Mail::to($borrowingRequest->user->email)->send(new BookRequestApproved($borrowingRequest, $borrowing, auth()->user()));
        } catch (\Exception $e) {
            // Log error but don't fail the approval process
            \Log::error("Failed to send approval email to {$borrowingRequest->user->email}: {$e->getMessage()}");
        }

        return response()->json([
            'success' => true, 
            'message' => 'Request approved successfully',
            'borrowing_id' => $borrowing->id
        ]);
    }

    public function reject(Request $request, BorrowingRequest $borrowingRequest)
    {
        // Only admins can reject requests
        if (!auth()->user()->isAdmin()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        // Check if request is still pending
        if ($borrowingRequest->status !== 'pending') {
            return response()->json(['success' => false, 'message' => 'Request is no longer pending'], 400);
        }

        $request->validate([
            'notes' => 'nullable|string|max:255'
        ]);

        // Reject the request
        $borrowingRequest->reject(auth()->id(), $request->notes);

        // Restore book availability
        $book = $borrowingRequest->book;
        if ($book) {
            $book->increment('available_quantity');
        }

        return response()->json([
            'success' => true, 
            'message' => 'Request rejected successfully'
        ]);
    }

    /**
     * Cancel a borrowing request (user can cancel their own pending requests)
     */
    public function cancel(Request $request, BorrowingRequest $borrowingRequest)
    {
        $user = auth()->user();
        
        // Check if request belongs to the authenticated user
        if ($borrowingRequest->user_id !== $user->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        // Check if request is still pending
        if ($borrowingRequest->status !== 'pending') {
            return response()->json(['success' => false, 'message' => 'Only pending requests can be cancelled'], 400);
        }

        // Update request status to cancelled
        $borrowingRequest->update(['status' => 'cancelled']);

        // Restore book availability
        $book = $borrowingRequest->book;
        if ($book) {
            $book->increment('available_quantity');
        }

        return response()->json([
            'success' => true, 
            'message' => 'Your borrowing request has been cancelled successfully.'
        ]);
    }

    /**
     * Delete a borrowing request (only for cancelled and rejected requests)
     */
    public function destroy(Request $request, BorrowingRequest $borrowingRequest)
    {
        $user = auth()->user();
        
        // Check if request belongs to the authenticated user
        if ($borrowingRequest->user_id !== $user->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        // Check if request can be deleted (cancelled, rejected, or expired)
        if (!in_array($borrowingRequest->status, ['cancelled', 'rejected', 'expired'])) {
            return response()->json(['success' => false, 'message' => 'Only cancelled, rejected, or expired requests can be deleted'], 400);
        }

        // Get book title for response message
        $bookTitle = $borrowingRequest->book->title ?? 'Unknown book';

        // Delete the request
        $borrowingRequest->delete();

        return response()->json([
            'success' => true, 
            'message' => "Borrowing request for \"{$bookTitle}\" has been deleted successfully."
        ]);
    }
}
