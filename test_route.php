<?php

// Test if the route is registered
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== ROUTE TEST ===\n";

try {
    // Test the books query directly
    $books = \App\Models\Book::where('available_quantity', '>', 0)
        ->with(['author', 'category'])
        ->select('id', 'title', 'author_id', 'category_id', 'available_quantity', 'location')
        ->limit(3)
        ->get();
    
    echo "Books query executed successfully!\n";
    echo "Found " . $books->count() . " available books\n";
    
    foreach ($books as $book) {
        echo "- {$book->title} by " . ($book->author ? $book->author->name : 'Unknown') . "\n";
    }
    
    // Test the API response format
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
    
    echo "\nAPI Response:\n";
    echo json_encode($response, JSON_PRETTY_PRINT) . "\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

echo "\n=== END TEST ===\n";
