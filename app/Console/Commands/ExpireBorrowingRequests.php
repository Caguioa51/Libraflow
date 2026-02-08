<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BorrowingRequest;
use App\Models\Book;
use App\Mail\BookRequestExpired;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ExpireBorrowingRequests extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'borrowing-requests:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expire pending borrowing requests after 2 hours and restore book availability';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for expired borrowing requests...');

        // Find pending requests that have expired
        $expiredRequests = BorrowingRequest::where('status', 'pending')
            ->where('expires_at', '<', now())
            ->with(['book', 'user'])
            ->get();

        if ($expiredRequests->isEmpty()) {
            $this->info('No expired requests found.');
            return 0;
        }

        $this->info("Found {$expiredRequests->count()} expired requests.");

        DB::transaction(function () use ($expiredRequests) {
            foreach ($expiredRequests as $request) {
                // Update request status to expired
                $request->update(['status' => 'expired']);

                // Restore book availability
                $book = $request->book;
                if ($book) {
                    $book->increment('available_quantity');
                    $this->line("Restored availability for book: {$book->title}");
                }

                // Send expiration email notification
                if ($request->user && $request->user->email) {
                    try {
                        Mail::to($request->user->email)->send(new BookRequestExpired($request));
                        $this->line("Sent expiration email to user: {$request->user->email}");
                    } catch (\Exception $e) {
                        $this->error("Failed to send email to {$request->user->email}: {$e->getMessage()}");
                    }
                }

                $this->line("Expired request #{$request->id} for user #{$request->user_id}");
            }
        });

        $this->info('Successfully processed expired requests.');
        return 0;
    }
}
