@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>My Borrowing History</h1>
        <div>
            <a href="{{ route('borrowings.self_checkout') }}" class="btn btn-primary me-2">
                <i class="fas fa-book"></i> Self-Service Checkout
            </a>
            <a href="{{ route('books.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-search"></i> Browse Books
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Current Borrowings -->
    @php
        $currentBorrowings = $borrowings->where('status', 'borrowed');
        $overdueCount = $currentBorrowings->where('due_date', '<', now())->count();
    @endphp

    @if($currentBorrowings->count() > 0)
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Currently Borrowed Books ({{ $currentBorrowings->count() }})</h5>
                @if($overdueCount > 0)
                    <span class="badge bg-danger">{{ $overdueCount }} Overdue</span>
                @endif
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Book</th>
                                <th>Author</th>
                                <th>Borrowed Date</th>
                                <th>Due Date</th>
                                <th>Fine Amount</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($currentBorrowings as $borrowing)
                                @php
                                    $isOverdue = $borrowing->isOverdue();
                                    $fineAmount = $borrowing->calculateFine();
                                @endphp
                                <tr class="{{ $isOverdue ? 'table-danger' : '' }}">
                                    <td>
                                        <strong>{{ $borrowing->book->title ?? 'Unknown Book' }}</strong>
                                        @if($isOverdue)
                                            <br><small class="text-danger">⚠️ Overdue</small>
                                        @endif
                                    </td>
                                    <td>{{ $borrowing->book->author->name ?? 'Unknown Author' }}</td>
                                    <td>{{ $borrowing->borrowed_at ? $borrowing->borrowed_at->format('M d, Y') : '-' }}</td>
                                    <td>
                                        {{ $borrowing->due_date ? $borrowing->due_date->format('M d, Y') : '-' }}
                                        @if($borrowing->due_date)
                                            <br><small class="text-muted">
                                                {{ $borrowing->due_date->diffForHumans() }}
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($fineAmount > 0)
                                            <span class="text-danger fw-bold">₱{{ number_format($fineAmount, 2) }}</span>
                                            @if(!$borrowing->fine_paid)
                                                <br><small class="text-danger">Unpaid</small>
                                            @else
                                                <br><small class="text-success">Paid</small>
                                            @endif
                                        @else
                                            <span class="text-success">No Fine</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            @if($borrowing->canRenew())
                                                <form method="POST" action="{{ route('borrowings.renew', $borrowing) }}" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-primary" 
                                                            onclick="return confirm('Renew this book?')">
                                                        <i class="fas fa-redo"></i> Renew
                                                    </button>
                                                </form>
                                            @endif

                                            @if($fineAmount > 0 && !$borrowing->fine_paid)
                                                <form method="POST" action="{{ route('borrowings.pay_fine', $borrowing) }}" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-success" 
                                                            onclick="return confirm('Pay fine of ₱{{ number_format($fineAmount, 2) }}?')">
                                                        <i class="fas fa-credit-card"></i> Pay Fine
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <!-- Borrowing History -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">All Borrowing History</h5>
        </div>
        <div class="card-body">
            @if($borrowings->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Book</th>
                                <th>Author</th>
                                <th>Borrowed Date</th>
                                <th>Returned Date</th>
                                <th>Status</th>
                                <th>Renewals</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($borrowings as $borrowing)
                                <tr>
                                    <td>{{ $borrowing->book->title ?? 'Unknown Book' }}</td>
                                    <td>{{ $borrowing->book->author->name ?? 'Unknown Author' }}</td>
                                    <td>{{ $borrowing->borrowed_at ? $borrowing->borrowed_at->format('M d, Y') : '-' }}</td>
                                    <td>{{ $borrowing->returned_at ? $borrowing->returned_at->format('M d, Y') : '-' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $borrowing->status === 'borrowed' ? 'primary' : 'success' }}">
                                            {{ ucfirst($borrowing->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ $borrowing->renewal_count }} times
                                        @if($borrowing->last_renewed_at)
                                            <br><small class="text-muted">
                                                Last: {{ $borrowing->last_renewed_at->format('M d, Y') }}
                                            </small>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-book fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No Borrowing History</h5>
                    <p class="text-muted">You haven't borrowed any books yet.</p>
                    <a href="{{ route('borrowings.self_checkout') }}" class="btn btn-primary">
                        <i class="fas fa-book"></i> Start Borrowing
                    </a>
                </div>
            @endif
        </div>
    </div>

    <div class="mt-4">
        {{ $borrowings->links() }}
    </div>
</div>
@endsection
