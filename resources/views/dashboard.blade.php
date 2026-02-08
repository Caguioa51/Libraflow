@extends('layouts.app')

@section('content')
<div class="py-4">
    
    @if(auth()->user()->isStudent() || auth()->user()->isTeacher())
    <div class="row mb-4 align-items-stretch">
        <div class="col-lg-8 col-12">
            <div class="card mb-4 h-100">
                <div class="card-header bg-info text-white"><i class="bi bi-exclamation-circle"></i> My Borrowing Status</div>
                <div class="card-body">
                    <p><b>Borrowing Limit:</b> {{ \App\Models\SystemSetting::get('max_books_per_user', 3) }} books at a time.</p>
                    @php
                        $myBorrowings = \App\Models\Borrowing::where('user_id', auth()->id())->where('status', 'borrowed')->get();
                        $overdue = $myBorrowings->filter(fn($b) => $b->isOverdue());
                        $dueSoon = $myBorrowings->filter(fn($b) => $b->due_date && $b->due_date->diffInDays(now()) <= 3 && !$b->isOverdue());
                        $totalFine = $myBorrowings->sum(fn($b) => $b->calculateFine());
                    @endphp
                    <p><b>Currently Borrowed:</b> {{ $myBorrowings->count() }}</p>
                    @if($overdue->count())
                        <div class="alert alert-danger"><b>Overdue Books:</b>
                            <ul class="mb-0">
                                @foreach($overdue as $b)
                                    <li>{{ $b->book->title }} (Due: {{ $b->due_date->format('M d, Y') }}) - Fine: ₱{{ number_format($b->calculateFine(), 2) }}</li>
                                @endforeach
                            </ul>
                            <div class="mt-2"><b>Total Fine:</b> ₱{{ number_format($totalFine, 2) }}</div>
                        </div>
                    @endif
                    @if($dueSoon->count())
                        <div class="alert alert-warning"><b>Books Due Soon:</b>
                            <ul class="mb-0">
                                @foreach($dueSoon as $b)
                                    <li>{{ $b->book->title }} (Due: {{ $b->due_date->format('M d, Y') }})</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mt-3">
                        <a href="{{ route('borrowings.my_history') }}" class="btn btn-outline-primary">
                            <i class="bi bi-clock-history me-2"></i>View My Complete Borrowing History
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-12">
            <div class="card mb-4 h-100">
                <div class="card-header bg-success text-white"><i class="bi bi-info-circle"></i> Quick Actions</div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('books.index') }}" class="btn btn-primary">
                            <i class="bi bi-book me-2"></i>Browse Books
                        </a>
                        <a href="{{ route('profile.qr') }}" class="btn btn-outline-primary">
                            <i class="bi bi-qr-code me-2"></i>My QR Code
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if(auth()->user()->isAdmin())
    <!-- Admin Summary Dashboard -->
    <div class="row mb-4">
        <!-- Quick Stats Cards -->
        <div class="col-lg-3 col-md-6 col-12 mb-4">
            <div class="card bg-primary text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase small">Total Books</h6>
                            <h2 class="mb-0">{{ number_format(\App\Models\Book::count()) }}</h2>
                        </div>
                        <i class="bi bi-book display-6 opacity-50"></i>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('books.index') }}" class="text-white text-decoration-underline">View All</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-12 mb-4">
            <div class="card bg-success text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase small">Total Users</h6>
                            <h2 class="mb-0">{{ number_format(\App\Models\User::count()) }}</h2>
                        </div>
                        <i class="bi bi-people display-6 opacity-50"></i>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('admin.users.index') }}" class="text-white text-decoration-underline">Manage Users</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-12 mb-4">
            <div class="card bg-warning text-dark h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase small">Active Borrowings</h6>
                            <h2 class="mb-0">{{ number_format(\App\Models\Borrowing::where('status', 'borrowed')->count()) }}</h2>
                        </div>
                        <i class="bi bi-arrow-repeat display-6 opacity-50"></i>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('borrowings.borrowed') }}" class="text-dark text-decoration-underline">View All</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-12 mb-4">
            <div class="card bg-danger text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase small">Pending Approvals</h6>
                            <h2 class="mb-0">{{ number_format(\App\Models\User::where('is_approved', false)->count()) }}</h2>
                        </div>
                        <i class="bi bi-person-plus display-6 opacity-50"></i>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('admin.users.approvals') }}" class="text-white text-decoration-underline">Review Now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row">
        <div class="col-lg-6 col-12 mb-4">
            <div class="card h-100">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-clock-history text-primary me-2"></i>Recent Borrowings</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @php
                            $recentBorrowings = \App\Models\Borrowing::with(['book', 'user'])
                                ->latest()
                                ->take(5)
                                ->get();
                        @endphp
                        @if($recentBorrowings->count() > 0)
                            @foreach($recentBorrowings as $borrowing)
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 text-truncate">{{ $borrowing->book->title }}</h6>
                                            <p class="mb-1 small">Borrowed by: {{ $borrowing->user->name }}</p>
                                            <small class="text-muted">Due: {{ $borrowing->due_date->format('M d, Y') }}</small>
                                        </div>
                                        <div class="text-end ms-2">
                                            <small class="text-muted d-block mb-1">{{ $borrowing->created_at->diffForHumans() }}</small>
                                            @if($borrowing->isOverdue())
                                                <span class="badge bg-danger">Overdue</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="p-3 text-center text-muted">
                                <i class="bi bi-inbox display-6 d-block mb-2"></i>
                                No recent borrowings found
                            </div>
                        @endif
                    </div>
                </div>
                <div class="card-footer bg-transparent">
                    <a href="{{ route('borrowings.borrowed') }}" class="btn btn-sm btn-outline-primary w-100">
                        View All Borrowings
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-12 mb-4">
            <div class="card h-100">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-bell text-primary me-2"></i>System Notifications</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @php
                            $notifications = \App\Models\Notification::latest()
                                ->take(5)
                                ->get();
                        @endphp
                        @if($notifications->count() > 0)
                            @foreach($notifications as $notification)
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">{{ $notification->title }}</h6>
                                            <p class="mb-1 small text-truncate">{{ $notification->message }}</p>
                                            @if(!$notification->read_at)
                                                <span class="badge bg-primary">New</span>
                                            @endif
                                        </div>
                                        <div class="text-end ms-2">
                                            <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="p-3 text-center text-muted">
                                <i class="bi bi-bell-slash display-6 d-block mb-2"></i>
                                No new notifications
                            </div>
                        @endif
                    </div>
                </div>
                <div class="card-footer bg-transparent">
                    <a href="#" class="btn btn-sm btn-outline-primary w-100">
                        View All Notifications
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>

<!-- Enhanced Mobile CSS -->
<style>
    /* Mobile improvements */
    @media (max-width: 767.98px) {
        .py-4 {
            padding-top: 1rem !important;
            padding-bottom: 1rem !important;
        }
        
        .card-body {
            padding: 1rem;
        }
        
        .list-group-item {
            padding: 0.75rem;
        }
        
        .btn {
            font-size: 0.875rem;
        }
        
        .h2 {
            font-size: 1.5rem;
        }
        
        .display-6 {
            font-size: 1.25rem;
        }
        
        /* Fix overflow issues */
        .row {
            margin-left: -0.5rem;
            margin-right: -0.5rem;
        }
        
        .col, .col-12, .col-6 {
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }
        
        /* Ensure cards don't overflow */
        .card {
            margin-bottom: 1rem;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        
        /* Fix text truncation in mobile */
        .text-truncate {
            max-width: 100%;
        }
        
        /* Mobile stats cards */
        .card.bg-primary, .card.bg-success, .card.bg-warning, .card.bg-danger {
            margin-bottom: 1rem;
        }
        
        .card.bg-primary .h2, 
        .card.bg-success .h2, 
        .card.bg-warning .h2, 
        .card.bg-danger .h2 {
            font-size: 1.75rem;
        }
        
        .card.bg-primary .small, 
        .card.bg-success .small, 
        .card.bg-warning .small, 
        .card.bg-danger .small {
            font-size: 0.75rem;
        }
        
        /* Mobile list items */
        .list-group-item {
            padding: 0.75rem;
            border-bottom: 1px solid #dee2e6;
        }
        
        .list-group-item h6 {
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }
        
        .list-group-item p {
            font-size: 0.8rem;
            margin-bottom: 0.25rem;
        }
        
        .list-group-item small {
            font-size: 0.75rem;
        }
        
        /* Mobile badges */
        .badge {
            font-size: 0.6rem;
            padding: 0.2rem 0.4rem;
        }
        
        /* Mobile buttons */
        .btn-sm {
            font-size: 0.8rem;
            padding: 0.375rem 0.75rem;
        }
        
        /* Mobile alerts */
        .alert {
            padding: 0.75rem;
            font-size: 0.875rem;
        }
        
        .alert ul {
            margin-bottom: 0;
            padding-left: 1.25rem;
        }
        
        .alert li {
            font-size: 0.8rem;
            margin-bottom: 0.25rem;
        }
        
        /* Mobile card headers */
        .card-header {
            padding: 0.75rem;
            font-size: 0.9rem;
        }
        
        .card-header h5 {
            font-size: 1rem;
            margin-bottom: 0;
        }
        
        /* Mobile card footers */
        .card-footer {
            padding: 0.75rem;
        }
        
        /* Mobile grid adjustments */
        .row.mb-4 > .col-lg-3,
        .row.mb-4 > .col-lg-6 {
            margin-bottom: 1rem;
        }
    }
    
    /* Tablet improvements */
    @media (min-width: 768px) and (max-width: 991.98px) {
        .list-group-item {
            padding: 1rem;
        }
        
        .card-body {
            padding: 1.25rem;
        }
        
        .h2 {
            font-size: 1.75rem;
        }
    }
    
    /* Touch-friendly interactions */
    @media (hover: none) and (pointer: coarse) {
        .btn:active {
            transform: scale(0.98);
        }
        
        .list-group-item:active {
            background-color: #f8f9fa;
        }
        
        .card:active {
            transform: scale(0.99);
        }
    }

    /* Ensure proper scrolling */
    * {
        -webkit-overflow-scrolling: touch;
    }
    
    /* Landscape mobile optimizations */
    @media (max-width: 767.98px) and (orientation: landscape) {
        .py-4 {
            padding-top: 0.5rem !important;
            padding-bottom: 0.5rem !important;
        }
        
        .card-body {
            padding: 0.75rem;
        }
        
        .list-group-item {
            padding: 0.5rem;
        }
    }
</style>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
@endsection
