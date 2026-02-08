@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-6">
            <!-- Student Search Section -->
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-search me-2"></i>Find Student</h5>
                </div>
                <div class="card-body">
                    <!-- Search Form -->
                    <div class="mb-4">
                        <form method="POST" action="{{ route('borrowings.admin_borrow.post') }}" class="d-inline">
                            @csrf
                            <div class="input-group">
                                <input type="text" class="form-control" name="search_query"
                                       placeholder="Enter name, email, or ID..." required>
                                <button class="btn btn-info" type="submit">
                                    <i class="bi bi-search me-2"></i>Search
                                </button>
                            </div>
                        </form>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                        </div>
                    @endif

                    @if($selectedUser)
                        <!-- Student Found - Show Borrowing Interface -->
                        <div class="card mt-4 border-success">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0"><i class="bi bi-person-check me-2"></i>Student Selected</h5>
                            </div>
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-3 text-center">
                                        <h6 class="mb-0">{{ $selectedUser->name }}</h6>
                                        <small class="text-muted">{{ ucfirst($selectedUser->role) }}</small>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <p class="mb-1"><strong>Email:</strong></p>
                                                <p class="mb-3">{{ $selectedUser->email }}</p>
                                            </div>
                                            <div class="col-sm-6">
                                                <p class="mb-1"><strong>Student ID:</strong></p>
                                                <p class="mb-3">{{ $selectedUser->student_id ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                        <div class="mt-2">
                                            <button type="button" class="btn btn-success" id="borrow-books-btn">
                                                <i class="bi bi-book me-2"></i>Borrow Books for {{ $selectedUser->name }}
                                            </button>
                                            <a href="{{ route('borrowings.admin_borrow') }}" class="btn btn-outline-secondary">
                                                <i class="bi bi-arrow-repeat me-2"></i>Find Different Student
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Book Selection Form (Initially Hidden) -->
                        <div id="book-selection-section" class="card mt-4" style="display: none;">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="bi bi-books me-2"></i>Select Books to Borrow</h5>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('borrowings.store') }}" id="borrow-book-form">
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{ $selectedUser->id }}" id="borrow_user_id">

                                    <div class="row">
                                        <div class="col-md-8">
                                            <label for="book_search" class="form-label">Search and Select Book</label>
                                            <div class="position-relative">
                                                <input type="text" 
                                                       class="form-control" 
                                                       id="book_search" 
                                                       placeholder="Search books by title, author, or category..."
                                                       autocomplete="off">
                                                <input type="hidden" name="book_id" id="selected_book_id" required>
                                                
                                                <!-- Search Results Dropdown -->
                                                <div id="book_search_results" class="position-absolute w-100 bg-white border rounded shadow-sm" style="max-height: 300px; overflow-y: auto; z-index: 1000; display: none;">
                                                    <div class="p-2 text-muted small">Start typing to search books...</div>
                                                </div>
                                            </div>
                                            
                                            <!-- Selected Book Display -->
                                            <div id="selected_book_display" class="mt-3" style="display: none;">
                                                <div class="alert alert-success py-2">
                                                    <small class="mb-0">
                                                        <strong>Selected:</strong> <span id="selected_book_title"></span>
                                                        <button type="button" class="btn btn-sm btn-outline-secondary float-end" onclick="clearBookSelection()">
                                                            <i class="bi bi-x"></i> Clear
                                                        </button>
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">&nbsp;</label>
                                            <button type="submit" class="btn btn-success d-block w-100" id="borrow_book_btn" disabled>
                                                <i class="bi bi-check-circle me-2"></i>Borrow Book
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif

                    <!-- Instructions -->
                    <div class="alert alert-info mt-4">
                        <h6><i class="bi bi-info-circle me-2"></i>How to use:</h6>
                        <ol class="mb-0">
                            <li><strong>Search:</strong> Enter the student's name, email, or ID in the search field</li>
                            <li>Click the search button to find the student</li>
                            <li>Once found, click "Borrow Books" to proceed with book selection</li>
                            <li><strong>Search for books:</strong> Type in the search box to find books by title, author, or category</li>
                            <li>Select a book from the search results and click "Borrow Book"</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Borrow Books button click handler
    const borrowBtn = document.getElementById('borrow-books-btn');
    if (borrowBtn) {
        borrowBtn.addEventListener('click', function() {
            const bookSelectionSection = document.getElementById('book-selection-section');
            if (bookSelectionSection) {
                bookSelectionSection.style.display = 'block';
                bookSelectionSection.scrollIntoView({ behavior: 'smooth' });
            }
        });
    }

    // Book Search Functionality
    const bookSearchInput = document.getElementById('book_search');
    const searchResultsDiv = document.getElementById('book_search_results');
    const selectedBookId = document.getElementById('selected_book_id');
    const selectedBookTitle = document.getElementById('selected_book_title');
    const selectedBookDisplay = document.getElementById('selected_book_display');
    const borrowBookBtn = document.getElementById('borrow_book_btn');
    
    // Available books data (passed from controller)
    const availableBooks = {!! json_encode($books->map(function($book) {
        return [
            'id' => $book->id,
            'title' => $book->title,
            'author' => $book->author->name ?? 'Unknown',
            'category' => $book->category->name ?? 'Unknown',
            'available_quantity' => $book->available_quantity ?? ($book->quantity ?? 1),
            'location' => $book->location
        ];
    })) !!};

    let searchTimeout;

    if (bookSearchInput) {
        bookSearchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value.trim().toLowerCase();
            
            if (query.length < 2) {
                searchResultsDiv.style.display = 'none';
                return;
            }

            // Debounce search
            searchTimeout = setTimeout(() => {
                performSearch(query);
            }, 300);
        });

        // Hide results when clicking outside
        document.addEventListener('click', function(e) {
            if (!bookSearchInput.contains(e.target) && !searchResultsDiv.contains(e.target)) {
                searchResultsDiv.style.display = 'none';
            }
        });

        // Show results when focusing the input
        bookSearchInput.addEventListener('focus', function() {
            if (this.value.trim().length >= 2) {
                const query = this.value.trim().toLowerCase();
                performSearch(query);
            }
        });
    }

    function performSearch(query) {
        const results = availableBooks.filter(book => {
            return book.title.toLowerCase().includes(query) ||
                   book.author.toLowerCase().includes(query) ||
                   (book.category && book.category.toLowerCase().includes(query)) ||
                   (book.location && book.location.toLowerCase().includes(query));
        });

        displaySearchResults(results, query);
    }

    function displaySearchResults(results, query) {
        if (results.length === 0) {
            searchResultsDiv.innerHTML = `
                <div class="p-3 text-muted text-center">
                    <i class="bi bi-search me-2"></i>No books found matching "${query}"
                </div>
            `;
        } else {
            let html = '';
            results.forEach(book => {
                const availabilityBadge = book.available_quantity > 0 
                    ? '<span class="badge bg-success">Available: ' + book.available_quantity + '</span>'
                    : '<span class="badge bg-danger">Not Available</span>';
                
                html += `
                    <div class="search-result-item p-2 border-bottom hover-bg-light cursor-pointer" 
                         onclick="selectBook(${book.id}, '${escapeHtml(book.title)}', ${book.available_quantity})"
                         data-book-id="${book.id}">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <strong>${escapeHtml(book.title)}</strong>
                                <br>
                                <small class="text-muted">by ${escapeHtml(book.author)}</small>
                                ${book.category ? `<br><small class="text-muted">${escapeHtml(book.category)}</small>` : ''}
                                ${book.location ? `<br><small class="text-muted"><i class="bi bi-geo-alt"></i> ${escapeHtml(book.location)}</small>` : ''}
                            </div>
                            <div class="ms-2">
                                ${availabilityBadge}
                            </div>
                        </div>
                    </div>
                `;
            });
            searchResultsDiv.innerHTML = html;
        }
        
        searchResultsDiv.style.display = 'block';
    }

    window.selectBook = function(bookId, bookTitle, availableQuantity) {
        if (availableQuantity <= 0) {
            alert('This book is not available for borrowing.');
            return;
        }

        selectedBookId.value = bookId;
        selectedBookTitle.textContent = bookTitle;
        selectedBookDisplay.style.display = 'block';
        searchResultsDiv.style.display = 'none';
        bookSearchInput.value = bookTitle;
        borrowBookBtn.disabled = false;
        borrowBookBtn.classList.remove('btn-secondary');
        borrowBookBtn.classList.add('btn-success');
    };

    window.clearBookSelection = function() {
        selectedBookId.value = '';
        selectedBookDisplay.style.display = 'none';
        bookSearchInput.value = '';
        borrowBookBtn.disabled = true;
        borrowBookBtn.classList.remove('btn-success');
        borrowBookBtn.classList.add('btn-secondary');
        bookSearchInput.focus();
    };

    window.escapeHtml = function(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, m => map[m]);
    };

    // Add hover effect styles
    const style = document.createElement('style');
    style.textContent = `
        .search-result-item:hover {
            background-color: #f8f9fa;
        }
        .cursor-pointer {
            cursor: pointer;
        }
        .hover-bg-light:hover {
            background-color: #f8f9fa !important;
        }
    `;
    document.head.appendChild(style);
});
</script>
