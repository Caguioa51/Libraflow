<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Book;
use App\Models\User;
use App\Models\Borrowing;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class SmokeBorrowTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'library:smoke-borrow';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run a smoke test for borrowing/return inventory flow';

    public function handle()
    {
        $this->info('Starting borrow/return smoke test');

        $admin = User::where('email', 'admin@gmail.com')->first();
        if (!$admin) {
            $this->warn('Admin user not found.');
        }

        $user = User::where('email', 'teststudent@example.com')->first();
        if (!$user) {
            $user = User::create([
                'name' => 'Test Student',
                'email' => 'teststudent@example.com',
                'password' => Hash::make('password'),
                'role' => 'student',
            ]);
            $this->info('Created test student');
        }

        $book = Book::where('title', 'Tinker Book')->first();
        if (!$book) {
            $book = Book::create([
                'title' => 'Tinker Book',
                'category_id' => 1,
                'author_id' => 1,
                'location' => 'Shelf A',
                'quantity' => 3,
                'available_quantity' => 3,
                'status' => 'available',
                'description' => 'Test',
            ]);
            $this->info('Created test book');
        }

        $this->line('BEFORE: ' . json_encode($book->toArray()));

        // Borrow 3 times (should deplete availability)
        $controller = new \App\Http\Controllers\BorrowingController();
        for ($i = 1; $i <= 3; $i++) {
            $req = new \Illuminate\Http\Request(['user_id' => $user->id, 'book_id' => $book->id]);
            $controller->store($req);
            $book = Book::find($book->id);
            $this->line("AFTER BORROW {$i}: " . json_encode($book->toArray()));
        }

        // Return one
        $bor = Borrowing::where('user_id', $user->id)->where('book_id', $book->id)->where('status', 'borrowed')->latest()->first();
        if ($bor) {
            // Simulate admin performing return
            Auth::loginUsingId($admin->id);
            $controller->update(new \Illuminate\Http\Request(), $bor);
            $book = Book::find($book->id);
            $this->line('AFTER RETURN: ' . json_encode($book->toArray()));
        } else {
            $this->warn('No borrowing record found to return');
        }

        $this->info('Smoke test completed');
        return 0;
    }
}
