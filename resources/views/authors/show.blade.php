@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1"><i class="bi bi-person-circle text-success me-2"></i>{{ $author->name }}</h2>
            <p class="text-muted mb-0">{{ $author->bio ? Str::limit(strip_tags($author->bio), 100) : 'No biography available' }}</p>
        </div>
        <div>
            <a href="{{ route('authors.index') }}" class="btn btn-outline-secondary me-2">
                <i class="bi bi-arrow-left me-2"></i>Back to Authors
            </a>
            @if(auth()->user()->isAdmin())
                <a href="{{ route('authors.edit', $author) }}" class="btn btn-primary">
                    <i class="bi bi-pencil me-2"></i>Edit Author
                </a>
            @endif
        </div>
    </div>

    <!-- Author Statistics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center border-success">
                <div class="card-body">
                    <div class="fs-2 text-success mb-2"><i class="bi bi-book"></i></div>
                    <h4 class="mb-0 text-success">{{ $author->books()->count() }}</h4>
                    <small class="text-muted">Total Books</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center border-primary">
                <div class="card-body">
                    <div class="fs-2 text-primary mb-2"><i class="bi bi-check-circle"></i></div>
                    <h4 class="mb-0 text-primary">{{ $author->books()->where('status', 'available')->count() }}</h4>
                    <small class="text-muted">Available</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center border-warning">
                <div class="card-body">
                    <div class="fs-2 text-warning mb-2"><i class="bi bi-arrow-repeat"></i></div>
                    <h4 class="mb-0 text-warning">{{ $author->books()->where('status', 'borrowed')->count() }}</h4>
                    <small class="text-muted">Borrowed</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center border-info">
                <div class="card-body">
                    <div class="fs-2 text-info mb-2"><i class="bi bi-bar-chart"></i></div>
                    <h4 class="mb-0 text-info">{{ $author->books()->sum('available_quantity') }}</h4>
                    <small class="text-muted">Total Copies</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Books by this Author -->
    <div class="card">
        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-books me-2"></i>Books by {{ $author->name }}</h5>
            <span class="badge bg-light text-success">{{ $author->books()->count() }} books</span>
        </div>
        <div class="card-body">
            @if($author->books()->count() > 0)
                <div class="row">
                    @foreach($author->books as $book)
                        <div class="col-lg-6 col-xl-4 mb-4">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="card-title mb-0">{{ $book->title }}</h6>
                                        <span class="badge bg-{{ $book->status === 'available' ? 'success' : 'warning' }}">
                                            {{ ucfirst($book->status) }}
                                        </span>
                                    </div>

                                    <p class="card-text text-muted small mb-2">
                                        <i class="bi bi-tags me-1"></i>{{ $book->category->name ?? 'No Category' }}
                                    </p>

                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between text-sm">
                                            <span><strong>Location:</strong> {{ $book->location ?? 'Not specified' }}</span>
                                            <span><strong>Copies:</strong> {{ $book->available_quantity ?? $book->quantity ?? 1 }}</span>
                                        </div>
                                    </div>

                                    @if($book->description)
                                        <p class="card-text small text-muted mb-3">
                                            {{ Str::limit(strip_tags($book->description), 100) }}
                                        </p>
                                    @endif

                                    <div class="d-grid gap-2">
                                        <a href="{{ route('books.show', $book) }}" class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-eye me-1"></i>View Details
                                        </a>
                                        @if($book->status === 'available')
                                            <a href="{{ route('borrowings.create') }}?book={{ $book->id }}" class="btn btn-success btn-sm">
                                                <i class="bi bi-bookmark-plus me-1"></i>Borrow Book
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-book fs-1 text-muted mb-3"></i>
                    <h5 class="text-muted">No books by this author yet</h5>
                    <p class="text-muted">Books written by this author will appear here.</p>
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('books.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>Add First Book
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!-- Author Information -->
    <div class="row mt-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-person-circle me-2"></i>Author Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <p class="mb-2"><strong>Name:</strong> {{ $author->name }}</p>
                        </div>
                        <div class="col-sm-6">
                            <p class="mb-2"><strong>Added:</strong> {{ $author->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                    @if($author->bio)
                        <p class="mb-0"><strong>Biography:</strong></p>
                        <p>{{ $author->bio }}</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-graph-up me-2"></i>Author Stats</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Total Books:</span>
                            <strong>{{ $author->books()->count() }}</strong>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Available:</span>
                            <strong class="text-success">{{ $author->books()->where('status', 'available')->count() }}</strong>
                        </div>
                    </div>
                    <div class="mb-0">
                        <div class="d-flex justify-content-between">
                            <span>Borrowed:</span>
                            <strong class="text-warning">{{ $author->books()->where('status', 'borrowed')->count() }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
