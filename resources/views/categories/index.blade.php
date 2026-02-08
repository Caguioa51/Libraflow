@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1"><i class="bi bi-tags text-primary me-2"></i>Categories</h2>
            <p class="text-muted mb-0">Browse books by category</p>
        </div>
        @if(auth()->user()->isAdmin())
            <a href="{{ route('categories.create') }}" class="btn btn-success">
                <i class="bi bi-plus-circle me-2"></i>Add Category
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
                    <form method="GET" action="{{ route('categories.index') }}" id="filterForm">
                        <div class="row g-3 align-items-end">
                            <div class="col-lg-8">
                                <label for="searchInput" class="form-label fw-semibold">
                                    <i class="bi bi-search me-2 text-primary"></i>Search Categories
                                </label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-primary text-white border-end-0">
                                        <i class="bi bi-search"></i>
                                    </span>
                                    <input type="text" id="searchInput" name="search" class="form-control border-start-0"
                                           placeholder="Search by category name or description..." value="{{ request('search') }}">
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
                        <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-x-circle me-1"></i>Clear Filters
                        </a>
                        <span class="ms-3 text-muted">
                            <strong>{{ $categories->total() }}</strong> categories found
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
                    <i class="bi bi-tags display-4 mb-2"></i>
                    <h3 class="mb-1">{{ number_format($categories->total()) }}</h3>
                    <p class="mb-0 opacity-75">Total Categories</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-primary text-white">
                <div class="card-body text-center">
                    <i class="bi bi-book-half display-4 mb-2"></i>
                    <h3 class="mb-1">{{ number_format($categories->sum('books_count')) }}</h3>
                    <p class="mb-0 opacity-75">Total Books</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-primary text-white">
                <div class="card-body text-center">
                    <i class="bi bi-check-circle display-4 mb-2"></i>
                    <h3 class="mb-1">{{ number_format($categories->sum(function($cat) { return $cat->books()->where('status', 'available')->count(); })) }}</h3>
                    <p class="mb-0 opacity-75">Available Books</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Categories Grid -->
    @if($categories->count() > 0)
        <div class="row">
            @foreach($categories as $category)
                <div class="col-lg-6 col-xl-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h5 class="card-title mb-0 text-primary">{{ $category->name }}</h5>
                                <span class="badge bg-primary">{{ $category->books_count }} books</span>
                            </div>

                            <p class="card-text text-muted mb-3">
                                {{ $category->description ?? 'No description available' }}
                            </p>

                            <!-- Quick Stats -->
                            <div class="row text-center mb-3">
                                <div class="col-4">
                                    <small class="text-success fw-bold">{{ $category->books()->where('status', 'available')->count() }}</small>
                                    <br><small class="text-muted">Available</small>
                                </div>
                                <div class="col-4">
                                    <small class="text-warning fw-bold">{{ $category->books()->where('status', 'borrowed')->count() }}</small>
                                    <br><small class="text-muted">Borrowed</small>
                                </div>
                                <div class="col-4">
                                    <small class="text-info fw-bold">{{ $category->books()->sum('available_quantity') }}</small>
                                    <br><small class="text-muted">Copies</small>
                                </div>
                            </div>

                            <div class="d-grid">
                                <a href="{{ route('categories.show', $category) }}" class="btn btn-primary">
                                    <i class="bi bi-eye me-2"></i>View Books ({{ $category->books_count }})
                                </a>
                            </div>
                        </div>

                        @if(auth()->user()->isAdmin())
                            <div class="card-footer bg-light">
                                <div class="btn-group w-100" role="group">
                                    <a href="{{ route('categories.edit', $category) }}" class="btn btn-outline-warning btn-sm">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="confirmCategoryDeletion('{{ addslashes($category->name) }}', {{ $category->books_count }}, {{ $category->books()->where('status', 'borrowed')->count() }}, '{{ route('categories.destroy', $category) }}')">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $categories->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <i class="bi bi-tags fs-1 text-muted mb-3"></i>
            <h4 class="text-muted">No categories with books yet</h4>
            <p class="text-muted mb-4">Categories that contain books will appear here.</p>
        @if(auth()->user()->isAdmin())
            <a href="{{ route('categories.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Create First Category
            </a>
        @endif
        </div>
    @endif
</div>
@endsection

<script>
// Enhanced category deletion confirmation
function confirmCategoryDeletion(categoryName, totalBooks, borrowedBooks, deleteUrl) {
    // Create a custom confirmation modal
    const modal = document.createElement('div');
    modal.className = 'custom-confirm-modal';
    modal.innerHTML = `
        <div class="modal-overlay">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-exclamation-triangle me-2"></i>⚠️ DELETE CATEGORY
                    </h5>
                    <button type="button" class="btn-close btn-close-white" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <div class="bg-danger bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="bi bi-tags text-danger fs-1"></i>
                        </div>
                        <h4 class="text-danger mb-1">Delete Category?</h4>
                        <h5 class="text-primary mb-3">"${categoryName}"</h5>
                        <p class="text-muted mb-4">
                            <strong>Warning:</strong> This action cannot be undone. Deleting this category will affect all associated books and data.
                        </p>
                    </div>

                    <!-- Impact Assessment -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="card border-primary h-100">
                                <div class="card-body text-center">
                                    <i class="bi bi-book-half fs-2 text-primary mb-2"></i>
                                    <h5 class="card-title text-primary mb-1">${totalBooks}</h5>
                                    <p class="card-text small mb-0">Total Books</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-warning h-100">
                                <div class="card-body text-center">
                                    <i class="bi bi-arrow-repeat fs-2 text-warning mb-2"></i>
                                    <h5 class="card-title text-warning mb-1">${borrowedBooks}</h5>
                                    <p class="card-text small mb-0">Currently Borrowed</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Consequences List -->
                    <div class="alert alert-danger">
                        <h6 class="fw-bold mb-2">
                            <i class="bi bi-list-ul me-2"></i>What Will Happen:
                        </h6>
                        <ul class="mb-0 small">
                            <li><strong>Books:</strong> All ${totalBooks} books in this category will be uncategorized</li>
                            <li><strong>Borrowing Records:</strong> All borrowing history will be preserved</li>
                            <li><strong>Active Loans:</strong> ${borrowedBooks} currently borrowed books will remain active</li>
                            <li><strong>Search:</strong> Books will no longer appear when filtering by this category</li>
                            <li><strong>Statistics:</strong> Category statistics will be permanently lost</li>
                        </ul>
                    </div>

                    <!-- Alternative Actions -->
                    <div class="alert alert-info">
                        <h6 class="fw-bold mb-2">
                            <i class="bi bi-lightbulb me-2"></i>Alternatives:
                        </h6>
                        <p class="mb-0 small">
                            Consider <strong>editing the category</strong> instead of deleting it, or
                            <strong>reassign books</strong> to other categories before deletion.
                        </p>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary btn-lg px-4" id="cancelBtn">
                        <i class="bi bi-arrow-left me-2"></i>Keep Category
                    </button>
                    <button type="button" class="btn btn-danger btn-lg px-4" id="confirmBtn">
                        <i class="bi bi-trash me-2"></i>Delete Permanently
                    </button>
                </div>
            </div>
        </div>
    `;

    // Add modal styles
    const style = document.createElement('style');
    style.textContent = `
        .custom-confirm-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1050;
            animation: fadeIn 0.3s ease;
        }

        .custom-confirm-modal .modal-overlay {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .custom-confirm-modal .modal-content {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 600px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
            animation: slideUp 0.3s ease;
        }

        .custom-confirm-modal .modal-header {
            padding: 20px 24px;
            border-bottom: 1px solid #eee;
            border-top-left-radius: 16px;
            border-top-right-radius: 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .custom-confirm-modal .modal-title {
            margin: 0;
            font-weight: 600;
        }

        .custom-confirm-modal .btn-close-white {
            filter: invert(1);
            opacity: 0.8;
        }

        .custom-confirm-modal .modal-body {
            padding: 24px;
            text-align: center;
        }

        .custom-confirm-modal .modal-footer {
            padding: 16px 24px;
            border-top: 1px solid #eee;
            display: flex;
            gap: 12px;
            justify-content: center;
            border-bottom-left-radius: 16px;
            border-bottom-right-radius: 16px;
        }

        .custom-confirm-modal .btn {
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .custom-confirm-modal .btn-secondary {
            background: #6c757d;
            border: none;
        }

        .custom-confirm-modal .btn-danger {
            border: none;
            box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
        }

        .custom-confirm-modal .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(220, 53, 69, 0.4);
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(50px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .custom-confirm-modal .alert {
            text-align: left;
            border-radius: 8px;
        }

        .custom-confirm-modal .card {
            border-radius: 8px;
            transition: transform 0.2s ease;
        }

        .custom-confirm-modal .card:hover {
            transform: translateY(-2px);
        }
    `;

    document.head.appendChild(style);
    document.body.appendChild(modal);

    // Get modal elements
    const closeBtn = modal.querySelector('.btn-close-white');
    const cancelBtn = modal.querySelector('#cancelBtn');
    const confirmBtn = modal.querySelector('#confirmBtn');

    // Handle close button
    closeBtn.onclick = () => {
        modal.remove();
    };

    // Handle cancel button
    cancelBtn.onclick = () => {
        modal.remove();
    };

    // Handle confirm button
    confirmBtn.onclick = () => {
        // Show loading state
        confirmBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Deleting...';
        confirmBtn.disabled = true;

        // Create and submit delete form
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = deleteUrl;
        form.style.display = 'none';

        // Add CSRF token
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);

        // Add method spoofing
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        form.appendChild(methodField);

        // Close modal and submit form
        modal.remove();
        document.body.appendChild(form);
        form.submit();
    };

    // Close modal when clicking outside
    modal.onclick = (e) => {
        if (e.target === modal) {
            modal.remove();
        }
    };
}
</script>
