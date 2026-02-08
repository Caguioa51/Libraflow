<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

// Test route for logout
Route::post('/test-logout', [AuthenticatedSessionController::class, 'destroy'])->name('test.logout');

// Test view route
Route::middleware('auth')->get('/test-logout-view', function () {
    return view('test.logout-test');
})->name('test.logout.view');
