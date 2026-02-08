<?php

// Simple debug script to test the books API
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== DEBUG BOOKS API ===\n";

try {
    // Test basic database connection
    echo "1. Testing database connection...\n";
    $connection = \DB::connection();
    echo "   Database connected: " . get_class($connection) . "\n";
    
    // Test books table
    echo "2. Testing books table...\n";
    $bookCount = \DB::table('books')->count();
    echo "   Total books in database: " . $bookCount . "\n";
    
    // Test available books
    echo "3. Testing available books query...\n";
    $availableBooks = \DB::table('books')
        ->where('available_quantity', '>', 0)
        ->count();
    echo "   Available books: " . $availableBooks . "\n";
    
    // Test with Eloquent model
    echo "4. Testing Eloquent model...\n";
    $books = \App\Models\Book::where('available_quantity', '>', 0)
        ->with(['author', 'category'])
        ->select('id', 'title', 'author_id', 'category_id', 'available_quantity', 'location')
        ->limit(3)
        ->get();
    
    echo "   Eloquent query result count: " . $books->count() . "\n";
    
    foreach ($books as $book) {
        echo "   - Book ID: {$book->id}, Title: {$book->title}\n";
        echo "     Author ID: {$book->author_id}, Category ID: {$book->category_id}\n";
        echo "     Available: {$book->available_quantity}, Location: {$book->location}\n";
        
        if ($book->author) {
            echo "     Author Name: " . $book->author->name . "\n";
        } else {
            echo "     Author: Not found\n";
        }
        
        if ($book->category) {
            echo "     Category Name: " . $book->category->name . "\n";
        } else {
            echo "     Category: Not found\n";
        }
        echo "\n";
    }
    
    // Test API response format
    echo "5. Testing API response format...\n";
    $apiResponse = [
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
    ];
    
    echo "   API Response:\n";
    echo json_encode($apiResponse, JSON_PRETTY_PRINT) . "\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n=== END DEBUG ===\n";
