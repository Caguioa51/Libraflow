<?php

use App\Http\Controllers\DatabaseSeederController;

// Database Seeder Routes (Admin Only)
Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])->group(function () {
    Route::get('/admin/database-seeder', [DatabaseSeederController::class, 'index'])->name('database-seeder.index');
    Route::post('/admin/database-seeder/run-all', [DatabaseSeederController::class, 'runAllSeeders'])->name('database-seeder.run-all');
    Route::post('/admin/database-seeder/admin', [DatabaseSeederController::class, 'runAdminSeeder'])->name('database-seeder.admin');
    Route::post('/admin/database-seeder/books', [DatabaseSeederController::class, 'runBooksSeeder'])->name('database-seeder.books');
    Route::post('/admin/database-seeder/settings', [DatabaseSeederController::class, 'runSettingsSeeder'])->name('database-seeder.settings');
});
