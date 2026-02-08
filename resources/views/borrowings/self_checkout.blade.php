@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="display-5 fw-bold text-primary mb-2">
                        <i class="bi bi-cart-check me-3"></i>Self-Service Checkout
                    </h1>
                    <p class="text-muted lead">Browse and borrow books instantly</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('books.index') }}" class="btn btn-outline-primary btn-lg">
                        <i class="bi bi-grid-3x3-gap me-2"></i>Browse All Books
                    </a>
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-lg">
                        <i class="bi bi-speedometer2 me-2"></i>Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <strong>Success!</strong> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <strong>Error!</strong> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        </div>
    @endif

    <!-- Enhanced Search and Filter Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="row g-3 align-items-end">
                        <div class="col-lg-6">
                            <label for="searchInput" class="form-label fw-semibold">
                                <i class="bi bi-search me-2 text-primary"></i>Search Books
                            </label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-search text-muted"></i>
                                </span>
                                <input type="text" id="searchInput" class="form-control border-start-0"
                                       placeholder="Search by title, author, or category...">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <label for="categoryFilter" class="form-label fw-semibold">
                                <i class="bi bi-filter me-2 text-primary"></i>Category
                            </label>
                            <select id="categoryFilter" class="form-select form-select-lg">
                                <option value="">All Categories</option>
                                @foreach($books->pluck('category.name')->unique() as $category)
                                    <option value="{{ $category }}">{{ $category }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2">
                            <button id="clearFilters" class="btn btn-outline-secondary btn-lg w-100">
                                <i class="bi bi-x-circle me-2"></i>Clear Filters
                            </button>
                        </div>
                    </div>

                    <!-- Active Filters Display -->
                    <div id="activeFilters" class="mt-3 d-none">
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <span class="text-muted small">Active filters:</span>
                            <div id="filterTags" class="d-flex gap-2 flex-wrap"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-primary text-white">
                <div class="card-body text-center">
                    <i class="bi bi-book display-4 mb-2"></i>
                    <h3 class="mb-1">{{ $books->count() }}</h3>
                    <p class="mb-0 opacity-75">Available Books</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-success text-white">
                <div class="card-body text-center">
                    <i class="bi bi-check-circle display-4 mb-2"></i>
                    <h3 class="mb-1">{{ $books->where('status', 'available')->count() }}</h3>
                    <p class="mb-0 opacity-75">Ready to Borrow</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-info text-white">
                <div class="card-body text-center">
                    <i class="bi bi-grid display-4 mb-2"></i>
                    <h3 class="mb-1">{{ $books->pluck('category.name')->unique()->count() }}</h3>
                    <p class="mb-0 opacity-75">Categories</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-warning text-white">
                <div class="card-body text-center">
                    <i class="bi bi-people display-4 mb-2"></i>
                    <h3 class="mb-1">{{ $books->pluck('author.name')->unique()->count() }}</h3>
                    <p class="mb-0 opacity-75">Authors</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Books Grid -->
    <div id="booksGrid" class="row">
        @forelse($books as $book)
            @if($book->author && $book->category)
                <div class="col-xl-3 col-lg-4 col-md-6 mb-4 book-card"
                     data-title="{{ strtolower($book->title) }}"
                     data-author="{{ strtolower($book->author->name) }}"
                     data-category="{{ strtolower($book->category->name) }}">
                    <div class="card h-100 border-0 shadow-sm hover-lift">
                        <!-- Book Cover -->
                        <div class="card-img-top d-flex align-items-center justify-content-center bg-light" style="height: 200px;">
                            <div class="text-center">
                                <i class="bi bi-book text-primary" style="font-size: 4rem;"></i>
                                <p class="text-muted small mt-2 mb-0">No Cover</p>
                            </div>
                        </div>

                        <div class="card-body d-flex flex-column">
                            <!-- Book Title -->
                            <h5 class="card-title text-truncate mb-2" title="{{ $book->title }}">
                                {{ $book->title }}
                            </h5>

                            <!-- Book Info -->
                            <div class="book-info mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-person text-muted me-2"></i>
                                    <span class="text-truncate">{{ $book->author->name }}</span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-tag text-muted me-2"></i>
                                    <span class="badge bg-light text-dark">{{ $book->category->name }}</span>
                                </div>
                                @if($book->location)
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-geo-alt text-muted me-2"></i>
                                        <small class="text-muted">{{ $book->location }}</small>
                                    </div>
                                @endif
                            </div>

                            <!-- Availability Status -->
                            <div class="availability-status mb-3">
                                @if($book->status === 'available')
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle me-1"></i>Available
                                    </span>
                                @else
                                    <span class="badge bg-danger">
                                        <i class="bi bi-x-circle me-1"></i>Borrowed
                                    </span>
                                @endif
                            </div>

                            <!-- Action Button -->
                            <div class="mt-auto">
                                @if($book->status === 'available')
                                    <button type="button" class="btn btn-primary w-100 btn-lg borrow-confirm-btn"
                                            data-book-id="{{ $book->id }}"
                                            data-book-title="{{ $book->title }}"
                                            data-book-author="{{ $book->author->name }}"
                                            data-book-category="{{ $book->category->name }}"
                                            data-bs-toggle="modal"
                                            data-bs-target="#borrowConfirmModal">
                                        <i class="bi bi-cart-plus me-2"></i>Borrow Book
                                    </button>
                                @else
                                    <button class="btn btn-outline-secondary w-100 btn-lg" disabled>
                                        <i class="bi bi-dash-circle me-2"></i>Currently Borrowed
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <div class="empty-state">
                        <i class="bi bi-book display-1 text-muted mb-4"></i>
                        <h3 class="text-muted">No Available Books</h3>
                        <p class="text-muted mb-4">All books are currently borrowed or no books match your search criteria.</p>
                        <a href="{{ route('books.index') }}" class="btn btn-primary btn-lg">
                            <i class="bi bi-grid-3x3-gap me-2"></i>Browse All Books
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Loading Indicator -->
    <div id="loadingIndicator" class="text-center d-none">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p class="mt-2 text-muted">Searching books...</p>
    </div>
</div>

<!-- Borrow Confirmation Modal -->
<div class="modal fade" id="borrowConfirmModal" tabindex="-1" aria-labelledby="borrowConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title" id="borrowConfirmModalLabel">
                    <i class="bi bi-question-circle text-warning me-2"></i>Confirm Book Borrowing
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <div class="mx-auto mb-3" style="width: 60px; height: 60px; background: linear-gradient(135deg, #007bff, #0056b3); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-book text-white" style="font-size: 1.5rem;"></i>
                    </div>
                    <h6 class="mb-2">You are about to borrow:</h6>
                </div>

                <div class="book-details text-center mb-4">
                    <h5 class="book-title mb-2"></h5>
                    <p class="book-author mb-2 text-muted"></p>
                    <span class="badge bg-primary book-category"></span>
                </div>

                <div class="alert alert-info">
                    <div class="d-flex align-items-start">
                        <i class="bi bi-info-circle me-2 mt-1"></i>
                        <div>
                            <strong>Important:</strong>
                            <ul class="mb-0 mt-2 small">
                                <li>Please ensure you can return this book by the due date</li>
                                <li>Late returns may incur fines</li>
                                <li>You can renew books before the due date if needed</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6">
                        <button type="button" class="btn btn-outline-secondary w-100" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-2"></i>Cancel
                        </button>
                    </div>
                    <div class="col-6">
                        <form id="borrowConfirmForm" method="POST" action="{{ route('borrowings.store') }}" style="display: inline;">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                            <input type="hidden" name="book_id" id="confirmBookId">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-check-circle me-2"></i>Confirm Borrow
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Self-Checkout functionality
    initializeSelfCheckout();
});

function initializeSelfCheckout() {
    const searchInput = document.getElementById('searchInput');
    const categoryFilter = document.getElementById('categoryFilter');
    const clearFilters = document.getElementById('clearFilters');
    const bookCards = document.querySelectorAll('.book-card');
    const loadingIndicator = document.getElementById('loadingIndicator');
    const activeFilters = document.getElementById('activeFilters');
    const filterTags = document.getElementById('filterTags');

    // Enhanced filtering with debouncing
    let filterTimeout;

    function showLoading() {
        loadingIndicator.classList.remove('d-none');
    }

    function hideLoading() {
        loadingIndicator.classList.add('d-none');
    }

    function updateActiveFilters() {
        const searchTerm = searchInput.value.trim();
        const selectedCategory = categoryFilter.value;

        // Clear existing tags
        filterTags.innerHTML = '';

        if (searchTerm) {
            const searchTag = document.createElement('span');
            searchTag.className = 'badge bg-primary';
            searchTag.innerHTML = `<i class="bi bi-search me-1"></i>Search: "${searchTerm}" <button type="button" class="btn-close btn-close-white ms-2" onclick="clearSearchFilter()"></button>`;
            filterTags.appendChild(searchTag);
        }

        if (selectedCategory) {
            const categoryTag = document.createElement('span');
            categoryTag.className = 'badge bg-info';
            categoryTag.innerHTML = `<i class="bi bi-tag me-1"></i>Category: ${selectedCategory} <button type="button" class="btn-close btn-close-white ms-2" onclick="clearCategoryFilter()"></button>`;
            filterTags.appendChild(categoryTag);
        }

        // Show/hide active filters container
        if (searchTerm || selectedCategory) {
            activeFilters.classList.remove('d-none');
        } else {
            activeFilters.classList.add('d-none');
        }
    }

    function filterBooks() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        const selectedCategory = categoryFilter.value.toLowerCase();

        showLoading();

        // Clear existing timeout
        clearTimeout(filterTimeout);

        // Debounce the filtering
        filterTimeout = setTimeout(() => {
            let visibleCount = 0;

            bookCards.forEach(card => {
                const title = card.dataset.title;
                const author = card.dataset.author;
                const category = card.dataset.category;

                const matchesSearch = !searchTerm ||
                    title.includes(searchTerm) ||
                    author.includes(searchTerm) ||
                    category.includes(searchTerm);

                const matchesCategory = !selectedCategory || category === selectedCategory;

                if (matchesSearch && matchesCategory) {
                    card.style.display = 'block';
                    card.classList.add('animate-fade-in');
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                    card.classList.remove('animate-fade-in');
                }
            });

            // Show no results message if needed
            showNoResultsMessage(visibleCount === 0 && (searchTerm || selectedCategory));

            updateActiveFilters();
            hideLoading();
        }, 300); // 300ms debounce
    }

    function showNoResultsMessage(show) {
        let noResultsMsg = document.getElementById('noResultsMessage');
        if (show && !noResultsMsg) {
            noResultsMsg = document.createElement('div');
            noResultsMsg.id = 'noResultsMessage';
            noResultsMsg.className = 'col-12';
            noResultsMsg.innerHTML = `
                <div class="text-center py-5">
                    <div class="empty-state">
                        <i class="bi bi-search display-1 text-muted mb-4"></i>
                        <h3 class="text-muted">No Books Found</h3>
                        <p class="text-muted mb-4">Try adjusting your search terms or filters.</p>
                        <button class="btn btn-primary" onclick="clearAllFilters()">
                            <i class="bi bi-x-circle me-2"></i>Clear All Filters
                        </button>
                    </div>
                </div>
            `;
            document.getElementById('booksGrid').appendChild(noResultsMsg);
        } else if (!show && noResultsMsg) {
            noResultsMsg.remove();
        }
    }

    function clearAllFilters() {
        searchInput.value = '';
        categoryFilter.value = '';
        filterBooks();
    }

    window.clearSearchFilter = function() {
        searchInput.value = '';
        filterBooks();
    };

    window.clearCategoryFilter = function() {
        categoryFilter.value = '';
        filterBooks();
    };

    // Event listeners
    searchInput.addEventListener('input', filterBooks);
    categoryFilter.addEventListener('change', filterBooks);

    clearFilters.addEventListener('click', function() {
        clearAllFilters();
    });

    // Initialize
    updateActiveFilters();
}



// Enhanced hover effects
document.addEventListener('DOMContentLoaded', function() {
    // Add hover effects to book cards
    const bookCards = document.querySelectorAll('.book-card');
    bookCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.classList.add('shadow-lg');
        });

        card.addEventListener('mouseleave', function() {
            this.classList.remove('shadow-lg');
        });
    });
});

// Add CSS animations and modal functionality
document.addEventListener('DOMContentLoaded', function() {
    const style = document.createElement('style');
    style.textContent = `
        .hover-lift {
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .hover-lift:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
        }

        .animate-fade-in {
            animation: fadeInUp 0.3s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .empty-state {
            padding: 3rem;
        }

        .book-info .text-truncate {
            max-width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .availability-status .badge {
            font-size: 0.75rem;
        }

        .card-img-top {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }

        /* Enhanced button styles */
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        /* Loading animation */
        .spinner-border {
            width: 2rem;
            height: 2rem;
        }

        /* Statistics cards hover effect */
        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-2px);
        }

        /* Modal enhancements */
        .modal-content {
            border-radius: 1rem;
            border: none;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }

        .modal-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 1rem 1rem 0 0;
        }

        .book-details .book-title {
            color: #2c3e50;
            font-weight: 600;
        }

        .book-details .book-author {
            font-size: 1.1rem;
        }

        .book-details .badge {
            font-size: 0.9rem;
            padding: 0.5rem 1rem;
        }
    `;
    document.head.appendChild(style);

    // Modal functionality for borrow confirmation
    const borrowConfirmModal = document.getElementById('borrowConfirmModal');
    if (borrowConfirmModal) {
        borrowConfirmModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const bookId = button.getAttribute('data-book-id');
            const bookTitle = button.getAttribute('data-book-title');
            const bookAuthor = button.getAttribute('data-book-author');
            const bookCategory = button.getAttribute('data-book-category');

            // Update modal content
            const modal = this;
            modal.querySelector('.book-title').textContent = bookTitle;
            modal.querySelector('.book-author').textContent = 'by ' + bookAuthor;
            modal.querySelector('.book-category').textContent = bookCategory;

            // Update hidden form field
            const form = modal.querySelector('#borrowConfirmForm');
            form.querySelector('#confirmBookId').value = bookId;
        });
    }
});
</script>
@endsection
