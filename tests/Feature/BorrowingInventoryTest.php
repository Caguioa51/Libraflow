<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Book;
use App\Models\Borrowing;
use Illuminate\Support\Facades\Hash;

class BorrowingInventoryTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_borrowing_and_return_updates_available_quantity()
    {
        // Create admin and student
        $admin = User::where('email', 'admin@gmail.com')->first();
        if (!$admin) {
            $admin = User::create([
                'name' => 'Admin Administrator',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('11111111'),
                'role' => 'admin',
            ]);
        }

        $student = User::create([
            'name' => 'Test Student',
            'email' => 'student@example.com',
            'password' => Hash::make('password'),
            'role' => 'student'
        ]);

        // Create minimal required related data (author, category)
        $author = \App\Models\Author::first() ?? \App\Models\Author::factory()->create();
        $category = \App\Models\Category::first() ?? \App\Models\Category::factory()->create();

        // Create a book with 3 copies
        $book = Book::create([
            'title' => 'Inventory Book',
            'category_id' => $category->id,
            'author_id' => $author->id,
            'location' => 'Shelf A',
            'quantity' => 3,
            'available_quantity' => 3,
            'status' => 'available',
        ]);

        // Student borrows 3 times via POST to borrowings.store
        for ($i = 1; $i <= 3; $i++) {
            $response = $this->actingAs($student)->post(route('borrowings.store'), [
                'user_id' => $student->id,
                'book_id' => $book->id,
            ]);
            $response->assertRedirect();
            $book->refresh();
            $this->assertEquals(3 - $i, $book->available_quantity);
        }

        // No copies left
        $this->assertEquals(0, $book->available_quantity);
        $this->assertEquals('borrowed', $book->status);

        // Admin returns one copy by updating the latest borrowing
        $borrowing = Borrowing::where('user_id', $student->id)->where('book_id', $book->id)->where('status', 'borrowed')->latest()->first();
        $this->actingAs($admin)->put(route('borrowings.update', $borrowing));

        $book->refresh();
        $this->assertEquals(1, $book->available_quantity);
        $this->assertEquals('available', $book->status);
    }
}
