<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\{
    ProfileController,
    BookController,
    CategoryController,
    AuthorController,
    BorrowingController,
    BorrowingRequestController,
    SettingsController,
    UserController
};

// ======================================================
// BASIC & DEBUG ROUTES
// ======================================================

Route::get('/', fn () => redirect()->to('/welcome', 301))->name('home');

Route::get('/welcome', function () {
    return view('welcome.welcome', [
        'libraryHours' => \App\Models\SystemSetting::get('library_hours'),
        'libraryLocation' => \App\Models\SystemSetting::get('library_location'),
        'featuredBooksText' => \App\Models\SystemSetting::get('featured_books_text'),
    ]);
})->name('welcome');

Route::get('/dashboard', fn () =>
    Auth::check() ? view('dashboard') : redirect()->route('login')
)->name('dashboard');

// ======================================================
// PUBLIC BOOK ROUTES
// ======================================================

Route::get('/books', [BookController::class, 'index'])->name('books.index');
Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');

// ======================================================
// AUTHENTICATED USER ROUTES
// ======================================================

Route::middleware('auth')->group(function () {

    // --------------------
    // PROFILE
    // --------------------
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/settings', [ProfileController::class, 'settings'])
        ->name('profile.settings');

    // --------------------
    // BOOK BORROWING
    // --------------------
    Route::post('/books/request-borrow', [BookController::class, 'requestBorrow'])
        ->name('books.request_borrow');

    // --------------------
    // USER BORROWING REQUESTS
    // --------------------
    Route::get('/borrowing-requests', [BorrowingRequestController::class, 'index'])
        ->name('borrowing-requests.index');

    Route::post('/borrowing-requests/{borrowingRequest}/cancel',
        [BorrowingRequestController::class, 'cancel'])
        ->name('borrowing-requests.cancel');

    Route::delete('/borrowing-requests/{borrowingRequest}',
        [BorrowingRequestController::class, 'destroy'])
        ->name('borrowing-requests.destroy');

    // --------------------
    // BORROWINGS
    // --------------------
    Route::get('/my-borrowings', [BorrowingController::class, 'myHistory'])
        ->name('borrowings.my_history');

    Route::post('/borrowings', [BorrowingController::class, 'store'])
        ->name('borrowings.store');
});

// ======================================================
// ADMIN ROUTES
// ======================================================

Route::middleware(['auth', 'admin'])->group(function () {

    // --------------------
    // BOOK MANAGEMENT
    // --------------------
    Route::resource('books', BookController::class)->except(['index', 'show', 'create']);
    Route::resource('categories', CategoryController::class);
    Route::resource('authors', AuthorController::class);

    // --------------------
    // BORROWINGS
    // --------------------
    Route::resource('borrowings', BorrowingController::class)->except(['store']);
    Route::patch('/borrowings/{borrowing}/return',
        [BorrowingController::class, 'update'])
        ->name('borrowings.return');

    // Borrowed books (dashboard "View All" link)
    Route::get('/borrowings/borrowed', [BorrowingController::class, 'borrowed'])
        ->name('borrowings.borrowed');

    // --------------------
    // BORROWING REQUESTS (ADMIN – NO destroy)
    // --------------------
    Route::resource('borrowing-requests', BorrowingRequestController::class)
        ->except(['index', 'destroy']);
    Route::post('/borrowing-requests/{borrowingRequest}/approve',
        [BorrowingRequestController::class, 'approve'])
        ->name('borrowing-requests.approve');
    Route::post('/borrowing-requests/{borrowingRequest}/reject',
        [BorrowingRequestController::class, 'reject'])
        ->name('borrowing-requests.reject');

    // --------------------
    // SETTINGS
    // --------------------
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');

    // --------------------
    // USER MANAGEMENT
    // --------------------
    Route::resource('admin/users', UserController::class)->names([
        'index' => 'admin.users.index',
        'create' => 'admin.users.create',
        'store' => 'admin.users.store',
        'show' => 'admin.users.show',
        'edit' => 'admin.users.edit',
        'update' => 'admin.users.update',
        'destroy' => 'admin.users.destroy',
    ]);

    Route::get('/admin/users/approvals', [UserController::class, 'approvals'])
        ->name('admin.users.approvals');

    // --------------------
    // Borrowed books
    // --------------------
    Route::get('/borrowings/borrowed', [BorrowingController::class, 'borrowed'])
        ->name('borrowings.borrowed');

    Route::resource('admin/announcements', AnnouncementController::class)->names([
        'index' => 'admin.announcements.index',
        'create' => 'admin.announcements.create',
        'store' => 'admin.announcements.store',
        'show' => 'admin.announcements.show',
        'edit' => 'admin.announcements.edit',
        'update' => 'admin.announcements.update',
        'destroy' => 'admin.announcements.destroy',
    ]);

    Route::get('/admin/qr-scanner', [\App\Http\Controllers\QRScannerController::class, 'index'])
        ->name('admin.qr_scanner');

    Route::get('/admin/settings', [\App\Http\Controllers\SettingsController::class, 'index'])
        ->name('admin.settings');

});

// ======================================================
// AUTH ROUTES
// ======================================================

require __DIR__.'/auth.php';
