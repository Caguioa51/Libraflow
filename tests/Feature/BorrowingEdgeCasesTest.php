<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Book;
use App\Models\Borrowing;
use Illuminate\Support\Facades\Hash;

class BorrowingEdgeCasesTest extends TestCase
{
    use RefreshDatabase;

    public function test_cannot_borrow_when_no_copies_available()
    {
        // setup users
        $student = User::factory()->create(['role' => 'student']);
        $author = \App\Models\Author::factory()->create();
        $category = \App\Models\Category::factory()->create();

        // create a book with 0 available copies
        $book = Book::create([
            'title' => 'No Copies Book',
            'category_id' => $category->id,
            'author_id' => $author->id,
            'quantity' => 1,
            'available_quantity' => 0,
            'status' => 'borrowed',
        ]);

        $response = $this->actingAs($student)->post(route('borrowings.store'), [
            'user_id' => $student->id,
            'book_id' => $book->id,
        ]);

        $response->assertSessionHas('error');
        $book->refresh();
        $this->assertEquals(0, $book->available_quantity);
    }

    public function test_return_cannot_exceed_quantity()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $student = User::factory()->create(['role' => 'student']);
        $author = \App\Models\Author::factory()->create();
        $category = \App\Models\Category::factory()->create();

        // book with quantity 2 and available 1
        $book = Book::create([
            'title' => 'Return Cap Book',
            'category_id' => $category->id,
            'author_id' => $author->id,
            'quantity' => 2,
            'available_quantity' => 1,
            'status' => 'available',
        ]);

        // create a borrowing record representing a borrowed copy
        $borrowing = Borrowing::create([
            'user_id' => $student->id,
            'book_id' => $book->id,
            'borrowed_at' => now(),
            'status' => 'borrowed',
            'due_date' => now()->addDays(7),
        ]);

        // Admin processes return
        $this->actingAs($admin)->put(route('borrowings.update', $borrowing));

        $book->refresh();
        $this->assertLessThanOrEqual($book->quantity, $book->available_quantity);
        $this->assertEquals(2, $book->available_quantity);
    }
}
