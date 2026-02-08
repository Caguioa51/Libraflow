<?php

require_once 'vendor/autoload.php';

use Illuminate\Http\Request;
use App\Models\Book;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Test the books API
echo "Testing Books API...\n";

try {
    $books = Book::where('available_quantity', '>', 0)
        ->with(['author', 'category'])
        ->select('id', 'title', 'author_id', 'category_id', 'available_quantity', 'location')
        ->limit(10)
        ->get();
    
    echo "Found " . $books->count() . " available books:\n";
    
    foreach ($books as $book) {
        $authorName = $book->author ? $book->author->name : 'Unknown Author';
        $categoryName = $book->category ? $book->category->name : 'Unknown Category';
        
        echo "- ID: {$book->id}, Title: {$book->title}, Author: {$authorName}, Available: {$book->available_quantity}\n";
    }
    
    echo "\nAPI Response Format:\n";
    $response = [
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
    
    echo json_encode($response, JSON_PRETTY_PRINT);
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
