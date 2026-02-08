<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\BorrowingRequestController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SystemSettingsController;
use App\Http\Controllers\DatabaseSeederController;

// ULTRA-SIMPLE ADMIN CREATION ROUTE - WORKS EVEN WITHOUT MIGRATIONS
Route::get('/create-admin-simple', function () {
    try {
        // Connect to database
        $pdo = new PDO("pgsql:host=" . env('DB_HOST') . ";port=" . env('DB_PORT') . ";dbname=" . env('DB_DATABASE'), env('DB_USERNAME'), env('DB_PASSWORD'));
        
        // Hash password
        $hashedPassword = password_hash('admin123', PASSWORD_DEFAULT);
        
        // Create admin user with raw SQL
        $sql = "INSERT INTO users (name, email, email_verified_at, password, role, is_approved, approved_at, approved_by, created_at, updated_at) 
                VALUES (:name, :email, NOW(), :password, :role, true, NOW(), NULL, NOW(), NOW())
                ON CONFLICT (email) DO UPDATE SET 
                name = EXCLUDED.name,
                password = EXCLUDED.password,
                role = EXCLUDED.role,
                is_approved = EXCLUDED.is_approved,
                approved_at = EXCLUDED.approved_at,
                updated_at = NOW()";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'name' => 'Administrator',
            'email' => 'admin@libraflow.com',
            'password' => $hashedPassword,
            'role' => 'admin'
        ]);
        
        return "<h1>✅ Admin Created Successfully!</h1>
               <p><strong>Email:</strong> admin@libraflow.com</p>
               <p><strong>Password:</strong> admin123</p>
               <p><a href='/login'>Go to Login Page</a></p>";
               
    } catch (Exception $e) {
        return "<h1>❌ Error Creating Admin</h1>
               <p>Error: " . $e->getMessage() . "</p>
               <p>Please check your database connection and ensure migrations have been run.</p>";
    }
})->name('create-admin-simple');

// FIX ADMIN APPROVAL STATUS - Update existing admin to be approved
Route::get('/fix-admin-approval', function () {
    try {
        // Connect to database
        $pdo = new PDO("pgsql:host=" . env('DB_HOST') . ";port=" . env('DB_PORT') . ";dbname=" . env('DB_DATABASE'), env('DB_USERNAME'), env('DB_PASSWORD'));
        
        // Update existing admin to be approved
        $sql = "UPDATE users SET is_approved = true, approved_at = NOW() WHERE role = 'admin' AND is_approved = false";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        
        $affected = $stmt->rowCount();
        
        return "<h1>✅ Admin Approval Fixed!</h1>
               <p><strong>Updated {$affected} admin accounts</strong></p>
               <p><a href='/login'>Go to Login Page</a></p>";
               
    } catch (Exception $e) {
        return "<h1>❌ Error Fixing Admin Approval</h1>
               <p>Error: " . $e->getMessage() . "</p>";
    }
})->name('fix-admin-approval');

// DEBUG ROUTE - Test basic functionality
Route::get('/debug-test', function () {
    try {
        // Test basic database connection
        $pdo = new PDO("pgsql:host=" . env('DB_HOST') . ";port=" . env('DB_PORT') . ";dbname=" . env('DB_DATABASE'), env('DB_USERNAME'), env('DB_PASSWORD'));
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
        $userCount = $stmt->fetch()['count'];
        
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM books");
        $bookCount = $stmt->fetch()['count'];
        
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM borrowings");
        $borrowingCount = $stmt->fetch()['count'];
        
        return "<h1>✅ Database Connection Test</h1>
               <p><strong>Users:</strong> {$userCount}</p>
               <p><strong>Books:</strong> {$bookCount}</p>
               <p><strong>Borrowings:</strong> {$borrowingCount}</p>
               <p><strong>Status:</strong> Database is working!</p>";
               
    } catch (Exception $e) {
        return "<h1>❌ Database Connection Error</h1>
               <p>Error: " . $e->getMessage() . "</p>
               <p>Please check your database configuration.</p>";
    }
})->name('debug-test');

Route::get('/', function () {
    return redirect()->to('/welcome', 301);
})->name('home');

Route::get('/test-redirect', function () {
    return redirect('/welcome');
})->name('test.redirect');

Route::get('/welcome', function () {
    return view('welcome.welcome', [
        'libraryHours' => \App\Models\SystemSetting::get('library_hours', "Monday - Friday: 8:00 AM - 5:00 PM\nSaturday: 9:00 AM - 12:00 PM\nSunday: Closed"),
        'libraryLocation' => \App\Models\SystemSetting::get('library_location', 'Dagupan City National High School, Dagupan City, Pangasinan'),
        'featuredBooksText' => \App\Models\SystemSetting::get('featured_books_text', 'Discover our most popular and recently added books. From classic literature to modern bestsellers, find your next great read in our carefully curated collection.'),
    ]);
})->name('welcome');

Route::get('/dashboard', function () {
    return Auth::check()
        ? view('dashboard')
        : redirect()->route('login');
})->name('dashboard');

// Public routes


// Book creation route (admin only, but needs to be accessible for route checking)
Route::get('/books/create', [BookController::class, 'create'])->name('books.create')->middleware('auth');

// Public book routes
Route::get('/books', [BookController::class, 'index'])->name('books.index');
Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');

// Test route to verify routing is working
Route::get('/test-route', function() {
    return 'Route is working!';
});

// Protected routes (require authentication)
Route::middleware('auth')->group(function () {
    // User profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/download-data', [ProfileController::class, 'downloadData'])->name('profile.download_data');
    Route::get('/profile/settings', [ProfileController::class, 'settings'])->name('profile.settings');
    Route::patch('/profile/settings', [ProfileController::class, 'updateSettings'])->name('profile.settings.update');
    
    // QR code generation for users
    Route::get('/profile/qr', function() {
        $user = auth()->user();
        $pendingRequests = \App\Models\BorrowingRequest::where('user_id', $user->id)
            ->with(['book', 'book.author', 'book.category'])
            ->where('status', 'pending')
            ->get()
            ->map(function($request) {
                return [
                    'id' => $request->id,
                    'book_title' => $request->book->title,
                    'book_author' => $request->book->author->name ?? 'Unknown',
                    'book_category' => $request->book->category->name ?? 'Unknown',
                    'requested_at' => $request->requested_at->format('M d, Y'),
                    'expires_at' => $request->expires_at ? $request->expires_at->toISOString() : null,
                    'status' => $request->status
                ];
            });
        
        return view('profile.qr', ['user' => $user, 'pendingRequests' => $pendingRequests]);
    })->name('profile.qr');
    
    Route::get('/profile/mobile-qr', function() {
        return view('profile.mobile-qr', ['user' => auth()->user()]);
    })->name('profile.mobile_qr');
    
    Route::get('/users/{user}/qr-code', [\App\Http\Controllers\UserQrCodeController::class, 'show'])->name('users.qr_code');

    // Book borrowing routes
    Route::post('/books/request-borrow', [BookController::class, 'requestBorrow'])->name('books.request_borrow');
    
    // Borrowing routes
    Route::get('/my-borrowings', [BorrowingController::class, 'myHistory'])->name('borrowings.my_history');
    Route::get('/borrowed', [BorrowingController::class, 'borrowed'])->name('borrowings.borrowed');
    Route::get('/my-borrowed', [App\Http\Controllers\MyBorrowedController::class, 'index'])->name('my-borrowed.index');
    
    // Public borrowing creation route (for QR scanner and admin borrowing)
    Route::post('/borrowings', [BorrowingController::class, 'store'])->name('borrowings.store');
    
    // User borrowing requests
    Route::get('/borrowing-requests', [BorrowingRequestController::class, 'index'])->name('borrowing-requests.index');
    Route::post('/borrowing-requests/{borrowingRequest}/cancel', [BorrowingRequestController::class, 'cancel'])->name('borrowing-requests.cancel');
    Route::delete('/borrowing-requests/{borrowingRequest}', [BorrowingRequestController::class, 'destroy'])->name('borrowing-requests.destroy');
    
    // Manual trigger for expired requests (for testing)
    Route::get('/test-expire-requests', function() {
        $expiredRequests = \App\Models\BorrowingRequest::where('status', 'pending')
            ->where('expires_at', '<', now())
            ->with(['book'])
            ->get();

        $count = 0;
        foreach ($expiredRequests as $request) {
            $request->update(['status' => 'expired']);
            $book = $request->book;
            if ($book) {
                $book->increment('available_quantity');
            }
            $count++;
        }

        return "Processed {$count} expired requests and restored book availability.";
    })->name('test.expire.requests');

    // Admin-only routes
    Route::middleware('admin')->group(function () {
        // Book management
        Route::resource('books', BookController::class)->except(['index', 'show', 'create']);
        
        // Category management
        Route::resource('categories', CategoryController::class);
        
        // Author management
        Route::resource('authors', AuthorController::class);
        
        // Borrowing management
        Route::resource('borrowings', BorrowingController::class)->except(['store']);
        Route::resource('borrowing-requests', BorrowingRequestController::class)->except(['index']);
        Route::post('/borrowing-requests/{borrowingRequest}/approve', [BorrowingRequestController::class, 'approve'])->name('borrowing-requests.approve');
        Route::post('/borrowing-requests/{borrowingRequest}/reject', [BorrowingRequestController::class, 'reject'])->name('borrowing-requests.reject');
        Route::patch('borrowings/{borrowing}/return', [BorrowingController::class, 'update'])->name('borrowings.return');
        
        // Book return routes
        Route::post('borrowings/{borrowing}/mark-as-returned', [BorrowingController::class, 'markAsReturned'])->name('borrowings.mark-as-returned');
        Route::get('borrowings/{borrowing}/mark-as-returned', [BorrowingController::class, 'markAsReturned'])->name('borrowings.mark-as-returned-get');
        
        // Reports and settings
        Route::get('/admin/report', [BorrowingController::class, 'report'])->name('borrowings.report');
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
    });

    // Notification routes
    Route::post('/notifications/{notification}/read', function (\App\Models\Notification $notification) {
        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }
        $notification->markAsRead();
        return response()->json(['success' => true]);
    })->name('notifications.read');

    Route::get('/notifications/count', function () {
        $count = \App\Models\Notification::where('user_id', auth()->id())->unread()->count();
        return response()->json(['count' => $count]);
    })->name('notifications.count');

    // Manual notification trigger for testing (admin only)
    Route::post('/admin/trigger-notifications', function () {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        \Artisan::call('notifications:send-due-date-reminders', ['--test' => true]);

        $output = \Artisan::output();

        return redirect()->back()->with('success', 'Notifications processed (test mode). Check command output for details.');
    })->name('admin.trigger_notifications')->middleware('auth');

    // Borrowing management routes
    Route::post('/borrowings/{borrowing}/update-due-date', [BorrowingController::class, 'updateDueDate'])->name('borrowings.update_due_date');
    Route::post('/borrowings/{borrowing}/change-book', [BorrowingController::class, 'changeBook'])->name('borrowings.change_book');

    // Book reservation system
    Route::post('/books/{book}/reserve', [BookController::class, 'reserve'])->name('books.reserve');
    Route::delete('/books/{book}/reserve', [BookController::class, 'cancelReservation'])->name('books.cancel_reservation');
    Route::get('/my-reservations', [BookController::class, 'myReservations'])->name('books.my_reservations');

    // My Borrowed Books
    Route::get('/my-borrowed', [App\Http\Controllers\MyBorrowedController::class, 'index'])->name('my-borrowed.index');

    // Self-service routes
    // Redirect old self-checkout to books page
    Route::get('/self-checkout', function() {
        return redirect()->route('books.index');
    })->name('borrowings.self_checkout');

    Route::post('/borrowings/{borrowing}/renew', [BorrowingController::class, 'renew'])->name('borrowings.renew');
    Route::post('/borrowings/{borrowing}/pay-fine', [BorrowingController::class, 'payFine'])->name('borrowings.pay_fine');
});

Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])->group(function () {

    Route::get('/admin/qr-scanner', function() {
        return view('admin.qr-scanner');
    })->name('admin.qr_scanner');

    Route::get('/admin/borrow', [\App\Http\Controllers\BorrowingController::class, 'adminBorrow'])->name('borrowings.admin_borrow');
    Route::post('/admin/borrow', [\App\Http\Controllers\BorrowingController::class, 'adminBorrow'])->name('borrowings.admin_borrow.post');
    Route::post('/admin/borrow/barcode-lookup', [\App\Http\Controllers\BorrowingController::class, 'adminBarcodeLookup'])->name('borrowings.admin_barcode_lookup');
    Route::post('/admin/borrow/user-search', [\App\Http\Controllers\BorrowingController::class, 'adminUserSearch'])->name('borrowings.admin_user_search');
    Route::get('/admin/update-fines', [\App\Http\Controllers\BorrowingController::class, 'updateFines'])->name('borrowings.update_fines');
    Route::post('/admin/update-fines', [\App\Http\Controllers\BorrowingController::class, 'updateFines'])->name('borrowings.update_fines.post');
    // Announcements management for admins
    Route::get('/admin/announcements', [\App\Http\Controllers\Admin\AnnouncementController::class, 'index'])->name('admin.announcements.index');
    Route::post('/admin/announcements', [\App\Http\Controllers\Admin\AnnouncementController::class, 'store'])->name('admin.announcements.store');
    // Admin settings (SystemSettingsController expects an admin settings page)
    Route::get('/admin/settings', [\App\Http\Controllers\SystemSettingsController::class, 'index'])->name('admin.settings');
    Route::post('/admin/settings', [\App\Http\Controllers\SystemSettingsController::class, 'update'])->name('admin.settings.update');
    Route::post('/admin/settings/reset', [\App\Http\Controllers\SystemSettingsController::class, 'reset'])->name('admin.settings.reset');

    // Admin borrowing management
    Route::post('/admin/borrowings/{borrowing}/update-due-date', [\App\Http\Controllers\BorrowingController::class, 'updateDueDate'])->name('admin.borrowings.update_due_date');
    // User management for admins
    Route::get('/admin/users', [\App\Http\Controllers\Admin\UserManagementController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/users/create', [\App\Http\Controllers\Admin\UserManagementController::class, 'create'])->name('admin.users.create');
    Route::post('/admin/users', [\App\Http\Controllers\Admin\UserManagementController::class, 'store'])->name('admin.users.store');
    Route::get('/admin/users/{user}/edit', [\App\Http\Controllers\Admin\UserManagementController::class, 'edit'])->name('admin.users.edit');
    Route::put('/admin/users/{user}', [\App\Http\Controllers\Admin\UserManagementController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{user}', [\App\Http\Controllers\Admin\UserManagementController::class, 'destroy'])->name('admin.users.destroy');
    Route::get('/admin/users/{user}/borrow', [\App\Http\Controllers\Admin\UserManagementController::class, 'borrowForUser'])->name('admin.users.borrow_for_user');
    Route::get('/admin/users/{user}/history', [\App\Http\Controllers\Admin\UserManagementController::class, 'viewHistory'])->name('admin.users.view_history');
    Route::post('/admin/users/update-student-id', [\App\Http\Controllers\Admin\UserManagementController::class, 'updateStudentId'])->name('admin.users.update_student_id');
    
    // User approval routes
    Route::get('/admin/user-approvals', [\App\Http\Controllers\Admin\UserApprovalController::class, 'index'])->name('admin.users.approvals');
    Route::get('/admin/users/{user}/approval', [\App\Http\Controllers\Admin\UserApprovalController::class, 'show'])->name('admin.users.show');
    Route::post('/admin/users/{user}/approve', [\App\Http\Controllers\Admin\UserApprovalController::class, 'approve'])->name('admin.users.approve');
    Route::delete('/admin/users/{user}/reject', [\App\Http\Controllers\Admin\UserApprovalController::class, 'reject'])->name('admin.users.reject');
    Route::post('/admin/users/bulk-approve', [\App\Http\Controllers\Admin\UserApprovalController::class, 'bulkApprove'])->name('admin.users.bulk-approve');
    Route::post('/admin/users/bulk-reject', [\App\Http\Controllers\Admin\UserApprovalController::class, 'bulkReject'])->name('admin.users.bulk-reject');
    Route::get('/admin/users/approval-stats', [\App\Http\Controllers\Admin\UserApprovalController::class, 'stats'])->name('admin.users.stats');
    // Barcode management for admins
    Route::get('/admin/barcode-scan', [\App\Http\Controllers\Admin\BarcodeController::class, 'scan'])->name('admin.barcode.scan');
    Route::post('/admin/barcode-lookup', [\App\Http\Controllers\Admin\BarcodeController::class, 'lookup'])->name('admin.barcode.lookup');
    Route::post('/admin/assign-barcode', [\App\Http\Controllers\Admin\BarcodeController::class, 'assign'])->name('admin.barcode.assign');

    // RFID management removed
});

require __DIR__.'/auth.php';
require __DIR__.'/test.php';

// Testing endpoint (requires authentication and CSRF). Controller still enforces local environment.
Route::middleware('auth')->post('/testing/borrow', [\App\Http\Controllers\BorrowingController::class, 'testingBorrow'])->name('testing.borrow.auth');

// Local-only token-based testing endpoint: ensure API route is also registered so route:list shows it.
// Keep the API-only route in routes/api.php, but register a forwarding route here under the 'api' prefix
// to make sure php artisan route:list --path=api can find it when using the built-in server.
Route::prefix('api')->middleware('api')->group(function () {
    // Ensure this testing route bypasses CSRF middleware (page-expired). It is still
    // guarded inside the controller by app()->environment('local') and X-TEST-SECRET.
    Route::post('/testing/borrow/no-csrf', [\App\Http\Controllers\BorrowingController::class, 'testingBorrowNoCsrf'])
        ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
    
    // Get available books for borrowing (no auth required for QR scanner)
    Route::get('/books/available', function() {
        try {
            $books = \App\Models\Book::where('available_quantity', '>', 0)
                ->with(['author', 'category'])
                ->select('id', 'title', 'author_id', 'category_id', 'available_quantity', 'location')
                ->orderBy('created_at', 'desc') // Order by newest first
                ->limit(200) // Increased limit to accommodate more books
                ->get();
                
            return response()->json([
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
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading books: ' . $e->getMessage()
            ], 500);
        }
    });

    // Get user data with pending requests for QR scanner
    Route::get('/users/{user}', function($userId) {
        try {
            $user = \App\Models\User::with(['borrowingRequests' => function($query) {
                $query->with(['book', 'book.author', 'book.category'])
                      ->where('status', 'pending')
                      ->latest();
            }])->find($userId);

            if (!$user) {
                return response()->json(['success' => false, 'message' => 'User not found'], 404);
            }

            // Format the response for QR scanner
            $userData = [
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'lrn_number' => $user->lrn_number,
                'employee_id' => $user->employee_id,
                'barcode' => $user->barcode,
                'pending_requests' => $user->borrowingRequests->map(function($request) {
                    return [
                        'id' => $request->id,
                        'book_title' => $request->book->title,
                        'book_author' => $request->book->author->name ?? 'Unknown',
                        'book_category' => $request->book->category->name ?? 'Unknown',
                        'requested_at' => $request->requested_at->toISOString(),
                        'status' => $request->status
                    ];
                }),
                'pending_requests_count' => $user->borrowingRequests->count()
            ];

            return response()->json(['user' => $userData]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading user data: ' . $e->getMessage()
            ], 500);
        }
    });
});

// Database Seeder Routes
require __DIR__.'/database-seeder.php';

// Quick Admin Fix Route
require __DIR__.'/quick-fix.php';

// Debug Approval Route
require __DIR__.'/debug-approval.php';

// Comprehensive Approval Fix Route
require __DIR__.'/fix-approval-system.php';

// Fix Approved Users Route
require __DIR__.'/fix-approved-users.php';
