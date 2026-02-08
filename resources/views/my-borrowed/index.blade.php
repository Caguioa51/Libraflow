@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">My Borrowed Books</h5>
                        <a href="{{ route('books.index') }}" class="btn btn-light btn-sm">
                            <i class="bi bi-arrow-left me-1"></i> Back to Books
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($borrowings->isEmpty())
                        <div class="text-center py-5">
                            <i class="bi bi-journal-x display-4 text-muted mb-3"></i>
                            <h5>No books borrowed yet</h5>
                            <p class="text-muted">You haven't borrowed any books yet. Visit the books section to find something interesting!</p>
                            <a href="{{ route('books.index') }}" class="btn btn-primary mt-2">
                                <i class="bi bi-book me-1"></i> Browse Books
                            </a>
                        </div>
                    @else
                        <!-- Mobile Card View -->
                        <div class="d-block d-lg-none">
                            @foreach($borrowings as $borrowing)
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <div class="d-flex align-items-start mb-3">
                                            @if($borrowing->book->cover_image)
                                                <img src="{{ asset('storage/' . $borrowing->book->cover_image) }}" 
                                                     alt="{{ $borrowing->book->title }}" 
                                                     class="img-thumbnail me-3" 
                                                     style="width: 60px; height: 80px; object-fit: cover; flex-shrink: 0;">
                                            @else
                                                <div class="bg-light d-flex align-items-center justify-content-center me-3" 
                                                     style="width: 60px; height: 80px; flex-shrink: 0;">
                                                    <i class="bi bi-book text-muted" style="font-size: 1.5rem;"></i>
                                                </div>
                                            @endif
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1">{{ $borrowing->book->title }}</h6>
                                                <p class="text-muted mb-1 small">
                                                    {{ $borrowing->book->author->name ?? 'Unknown Author' }}
                                                </p>
                                                <div class="mb-2">
                                                    @if($borrowing->returned_at)
                                                        <span class="badge bg-success">Returned</span>
                                                    @else
                                                        <span class="badge bg-primary">Borrowed</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row text-center mb-3">
                                            <div class="col-6">
                                                <small class="text-muted d-block">Borrowed</small>
                                                <strong>{{ $borrowing->borrowed_at->format('M d, Y') }}</strong>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted d-block">Due Date</small>
                                                <strong class="{{ $borrowing->isOverdue() ? 'text-danger' : '' }}">
                                                    {{ $borrowing->due_date->format('M d, Y') }}
                                                </strong>
                                                @if($borrowing->isOverdue())
                                                    <div><small class="badge bg-danger">Overdue</small></div>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="d-flex gap-2 justify-content-center">
                                            @if(!$borrowing->returned_at)
                                                
                                                @if($borrowing->canRenew())
                                                    <form action="{{ route('borrowings.renew', $borrowing) }}" 
                                                          method="POST" 
                                                          class="flex-grow-1"
                                                          onsubmit="return confirm('Are you sure you want to renew this book?')">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-outline-secondary w-100">
                                                            <i class="bi bi-arrow-repeat me-1"></i> Renew
                                                        </button>
                                                    </form>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Desktop Table View -->
                        <div class="table-responsive d-none d-lg-block">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Book</th>
                                        <th>Borrowed Date</th>
                                        <th>Due Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($borrowings as $borrowing)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($borrowing->book->cover_image)
                                                        <img src="{{ asset('storage/' . $borrowing->book->cover_image) }}" 
                                                             alt="{{ $borrowing->book->title }}" 
                                                             class="img-thumbnail me-3" 
                                                             style="width: 50px; height: 70px; object-fit: cover;">
                                                    @else
                                                        <div class="bg-light d-flex align-items-center justify-content-center me-3" 
                                                             style="width: 50px; height: 70px;">
                                                            <i class="bi bi-book text-muted" style="font-size: 1.5rem;"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <h6 class="mb-1">{{ $borrowing->book->title }}</h6>
                                                        <p class="text-muted mb-0">
                                                            {{ $borrowing->book->author->name ?? 'Unknown Author' }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $borrowing->borrowed_at->format('M d, Y') }}</td>
                                            <td class="{{ $borrowing->isOverdue() ? 'text-danger fw-bold' : '' }}">
                                                {{ $borrowing->due_date->format('M d, Y') }}
                                                @if($borrowing->isOverdue())
                                                    <br>
                                                    <small class="badge bg-danger">Overdue</small>
                                                @endif
                                            </td>
                                            <td>
                                                @if($borrowing->returned_at)
                                                    <span class="badge bg-success">Returned</span>
                                                    <div class="text-muted small">
                                                        {{ $borrowing->returned_at->format('M d, Y') }}
                                                    </div>
                                                @else
                                                    <span class="badge bg-primary">Borrowed</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if(!$borrowing->returned_at)
                                                    
                                                    @if($borrowing->canRenew())
                                                        <form action="{{ route('borrowings.renew', $borrowing) }}" 
                                                              method="POST" 
                                                              class="d-inline ms-1"
                                                              onsubmit="return confirm('Are you sure you want to renew this book?')">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-outline-secondary">
                                                                <i class="bi bi-arrow-repeat me-1"></i> Renew
                                                            </button>
                                                        </form>
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-center mt-4">
                            {{ $borrowings->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
    }
    
    .table td {
        vertical-align: middle;
    }
    
    .badge {
        font-weight: 500;
        padding: 0.35em 0.65em;
    }
    
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
    
    /* Mobile-specific improvements */
    @media (max-width: 991.98px) {
        .container {
            padding-left: 1rem;
            padding-right: 1rem;
        }
        
        .card {
            margin-bottom: 1rem;
        }
        
        .card-body {
            padding: 1rem;
        }
        
        /* Mobile card styling */
        .card .img-thumbnail {
            border-radius: 0.375rem;
        }
        
        .card h6 {
            font-size: 0.95rem;
            line-height: 1.3;
        }
        
        .card .small {
            font-size: 0.8rem;
        }
        
        /* Button improvements for mobile */
        .btn-sm {
            padding: 0.5rem 0.75rem;
            font-size: 0.8rem;
            white-space: nowrap;
        }
        
        /* Fix overflow issues */
        .row {
            margin-left: -0.5rem;
            margin-right: -0.5rem;
        }
        
        .col-6 {
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }
    }
    
    /* Small mobile improvements */
    @media (max-width: 575.98px) {
        .card-body {
            padding: 0.75rem;
        }
        
        .card .img-thumbnail {
            width: 50px !important;
            height: 70px !important;
        }
        
        .card h6 {
            font-size: 0.9rem;
        }
        
        .btn-sm {
            padding: 0.4rem 0.6rem;
            font-size: 0.75rem;
        }
    }
</style>
@endpush
@endsection
