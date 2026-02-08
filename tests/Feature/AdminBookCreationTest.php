<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Book;
use App\Models\User;
use App\Models\Author;
use App\Models\Category;

class AdminBookCreationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_book_with_quantity_and_location()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $author = Author::factory()->create();
        $category = Category::factory()->create();

        $this->actingAs($admin)->post(route('books.store'), [
            'title' => 'Test Book',
            'author_id' => $author->id,
            'category_id' => $category->id,
            'genre' => 'Fiction',
            'description' => 'A test book',
            'location' => 'Shelf A3',
            'quantity' => 5,
        ]);

        $book = Book::where('title', 'Test Book')->first();
        $this->assertNotNull($book);
        $this->assertEquals(5, $book->quantity);
        $this->assertEquals(5, $book->available_quantity);
        $this->assertEquals('Shelf A3', $book->location);
    }
}
