<?php

Route::get('/test-borrowing-endpoint', function() {
    return response()->json([
        'success' => true,
        'message' => 'Borrowing endpoint is accessible',
        'auth_check' => auth()->check(),
        'user_id' => auth()->id()
    ]);
});
