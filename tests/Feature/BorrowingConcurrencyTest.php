<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Book;
use App\Models\User;
use App\Models\Borrowing;

class BorrowingConcurrencyTest extends TestCase
{
    use RefreshDatabase;

    public function test_concurrent_like_borrows_do_not_overborrow()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $student = User::factory()->create(['role' => 'student']);
        $author = \App\Models\Author::factory()->create();
        $category = \App\Models\Category::factory()->create();

        // Create a book with quantity 5
        $book = Book::create([
            'title' => 'Concurrent Book',
            'category_id' => $category->id,
            'author_id' => $author->id,
            'quantity' => 5,
            'available_quantity' => 5,
            'status' => 'available',
        ]);

        // Simulate many near-concurrent borrow attempts by firing multiple POSTs in a loop
        $attempts = 12; // more than quantity
        $successes = 0;
        for ($i = 0; $i < $attempts; $i++) {
            $resp = $this->actingAs($student)->post(route('borrowings.store'), [
                'user_id' => $student->id,
                'book_id' => $book->id,
            ]);
            // Consider a borrow successful only if the session has a 'success' flash message
            $session = $resp->baseResponse->getSession();
            if ($session && $session->has('success')) {
                $successes++;
            }
        }

        $book->refresh();
        // At most 'quantity' borrows should have succeeded
        $this->assertLessThanOrEqual(5, $successes);
        $this->assertGreaterThanOrEqual(0, $book->available_quantity);
        $this->assertEquals(max(0, 5 - $successes), $book->available_quantity);
    }
}
