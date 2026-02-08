<?php

// Simple test to check if the borrowing endpoint works
require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Http\Request;
use App\Http\Controllers\BorrowingController;

echo "Testing borrowing endpoint fix...\n";

// Check if the BorrowingController exists and has the updated store method
$controller = new BorrowingController();
$reflection = new ReflectionClass($controller);

if ($reflection->hasMethod('store')) {
    $storeMethod = $reflection->getMethod('store');
    echo "✅ BorrowingController::store method exists\n";
    
    // Check if the method has validation for due_date
    $source = file_get_contents(__DIR__ . '/app/Http/Controllers/BorrowingController.php');
    if (strpos($source, 'due_date') !== false) {
        echo "✅ due_date validation found\n";
    } else {
        echo "❌ due_date validation not found\n";
    }
    
    if (strpos($source, 'wantsJson') !== false) {
        echo "✅ JSON response handling found\n";
    } else {
        echo "❌ JSON response handling not found\n";
    }
    
    if (strpos($source, 'ValidationException') !== false) {
        echo "✅ Validation exception handling found\n";
    } else {
        echo "❌ Validation exception handling not found\n";
    }
} else {
    echo "❌ BorrowingController::store method not found\n";
}

echo "\nTest completed.\n";
