@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1"><i class="bi bi-person-circle text-primary me-2"></i>Authors</h2>
            <p class="text-muted mb-0">Browse books by author</p>
        </div>
        @if(auth()->user()->isAdmin())
            <a href="{{ route('authors.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Add Author
            </a>
        @endif
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Enhanced Search and Filter Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form method="GET" action="{{ route('authors.index') }}" id="filterForm">
                        <div class="row g-3 align-items-end">
                            <div class="col-lg-8">
                                <label for="searchInput" class="form-label fw-semibold">
                                    <i class="bi bi-search me-2 text-primary"></i>Search Authors
                                </label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-primary text-white border-end-0">
                                        <i class="bi bi-search"></i>
                                    </span>
                                    <input type="text" id="searchInput" name="search" class="form-control border-start-0"
                                           placeholder="Search by author name or biography..." value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-bar-chart me-2 text-primary"></i>Sort By
                                </label>
                                <select name="sort" class="form-select form-select-lg">
                                    <option value="name_asc" {{ request('sort', 'name_asc') === 'name_asc' ? 'selected' : '' }}>
                                        <i class="bi bi-sort-alpha-down me-1"></i>Name A-Z
                                    </option>
                                    <option value="name_desc" {{ request('sort') === 'name_desc' ? 'selected' : '' }}>
                                        <i class="bi bi-sort-alpha-up me-1"></i>Name Z-A
                                    </option>
                                    <option value="books_desc" {{ request('sort') === 'books_desc' ? 'selected' : '' }}>
                                        <i class="bi bi-book-half me-1"></i>Most Books First
                                    </option>
                                    <option value="books_asc" {{ request('sort') === 'books_asc' ? 'selected' : '' }}>
                                        <i class="bi bi-book me-1"></i>Fewest Books First
                                    </option>
                                    <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>
                                        <i class="bi bi-calendar-plus me-1"></i>Newest First
                                    </option>
                                    <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>
                                        <i class="bi bi-calendar-minus me-1"></i>Oldest First
                                    </option>
                                </select>
                            </div>
                        </div>
                    </form>

                    @if(request()->hasAny(['search', 'sort']) && request('sort') !== 'name_asc')
                    <div class="mt-3">
                        <a href="{{ route('authors.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-x-circle me-1"></i>Clear Filters
                        </a>
                        <span class="ms-3 text-muted">
                            <strong>{{ $authors->total() }}</strong> authors found
                        </span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-primary text-white">
                <div class="card-body text-center">
                    <i class="bi bi-person-circle display-4 mb-2"></i>
                    <h3 class="mb-1">{{ number_format($authors->total()) }}</h3>
                    <p class="mb-0 opacity-75">Total Authors</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-primary text-white">
                <div class="card-body text-center">
                    <i class="bi bi-book-half display-4 mb-2"></i>
                    <h3 class="mb-1">{{ number_format($authors->sum('books_count')) }}</h3>
                    <p class="mb-0 opacity-75">Total Books</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-primary text-white">
                <div class="card-body text-center">
                    <i class="bi bi-check-circle display-4 mb-2"></i>
                    <h3 class="mb-1">{{ number_format($authors->sum(function($author) { return $author->books()->where('status', 'available')->count(); })) }}</h3>
                    <p class="mb-0 opacity-75">Available Books</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Authors Grid -->
    @if($authors->count() > 0)
        <div class="row">
            @foreach($authors as $author)
                <div class="col-lg-6 col-xl-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h5 class="card-title mb-0 text-primary">{{ $author->name }}</h5>
                                <span class="badge bg-primary">{{ $author->books_count }} books</span>
                            </div>

                            <p class="card-text text-muted mb-3">
                                {{ $author->bio ? Str::limit(strip_tags($author->bio), 100) : 'No biography available' }}
                            </p>

                            <!-- Quick Stats -->
                            <div class="row text-center mb-3">
                                <div class="col-4">
                                    <small class="text-primary fw-bold">{{ $author->books()->where('status', 'available')->count() }}</small>
                                    <br><small class="text-muted">Available</small>
                                </div>
                                <div class="col-4">
                                    <small class="text-warning fw-bold">{{ $author->books()->where('status', 'borrowed')->count() }}</small>
                                    <br><small class="text-muted">Borrowed</small>
                                </div>
                                <div class="col-4">
                                    <small class="text-info fw-bold">{{ $author->books()->sum('available_quantity') }}</small>
                                    <br><small class="text-muted">Copies</small>
                                </div>
                            </div>

                            <div class="d-grid">
                                <a href="{{ route('authors.show', $author) }}" class="btn btn-primary">
                                    <i class="bi bi-eye me-2"></i>View Books ({{ $author->books_count }})
                                </a>
                            </div>
                        </div>

                        @if(auth()->user()->isAdmin())
                            <div class="card-footer bg-light">
                                <div class="btn-group w-100" role="group">
                                    <a href="{{ route('authors.edit', $author) }}" class="btn btn-outline-warning btn-sm">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <form action="{{ route('authors.destroy', $author) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete this author? This action cannot be undone.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $authors->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <i class="bi bi-person-circle fs-1 text-muted mb-3"></i>
            <h4 class="text-muted">No authors with books yet</h4>
            <p class="text-muted mb-4">Authors that have books will appear here.</p>
            @if(auth()->user()->isAdmin())
                <a href="{{ route('authors.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Create First Author
                </a>
            @endif
        </div>
    @endif
</div>
@endsection
