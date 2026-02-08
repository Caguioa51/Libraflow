@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-8">
            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Book Creation Form -->
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-primary text-white border-0">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-journal-plus me-3 fs-5"></i>
                        <div>
                            <h5 class="mb-0">Book Information</h5>
                            <small>Enter book details and settings</small>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('books.store') }}" id="createBookForm">
                        @csrf

                        <div class="row g-4">
                            <!-- Basic Information -->
                            <div class="col-12">
                                <h6 class="text-primary fw-bold mb-3">
                                    <i class="bi bi-info-circle me-2"></i>Basic Information
                                </h6>
                            </div>

                            <div class="col-md-8">
                                <div class="form-floating">
                                    <input type="text" name="title" id="title" class="form-control form-control-lg @error('title') is-invalid @enderror"
                                           value="{{ old('title') }}" placeholder="Enter book title" required>
                                    <label for="title" class="text-muted">
                                        <i class="bi bi-type me-1"></i>Book Title <span class="text-danger">*</span>
                                    </label>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-text">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Enter the complete title of the book
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-floating">
                                    <input type="number" name="quantity" id="quantity" min="1" class="form-control form-control-lg @error('quantity') is-invalid @enderror"
                                           value="{{ old('quantity', 1) }}" placeholder="1" required>
                                    <label for="quantity" class="text-muted">
                                        <i class="bi bi-hash me-1"></i>Quantity <span class="text-danger">*</span>
                                    </label>
                                    @error('quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-text">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Number of copies available
                                </div>
                            </div>

                            <!-- Author Selection -->
                            <div class="col-12 mt-4">
                                <h6 class="text-primary fw-bold mb-3">
                                    <i class="bi bi-person me-2"></i>Author Information
                                </h6>
                            </div>

                            <div class="col-12">
                                <div class="card border-light bg-light">
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="form-floating">
                                                    <select name="author_id" id="author_id" class="form-select form-select-lg @error('author_id') is-invalid @enderror">
                                                        <option value="">Choose existing author...</option>
                                                        @foreach($authors as $author)
                                                            <option value="{{ $author->id }}" {{ old('author_id') == $author->id ? 'selected' : '' }}>
                                                                ✍️ {{ $author->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <label for="author_id" class="text-muted">
                                                        <i class="bi bi-person-check me-1"></i>Select Existing Author
                                                    </label>
                                                    @error('author_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-floating">
                                                    <input type="text" name="new_author" id="new_author" class="form-control form-control-lg @error('new_author') is-invalid @enderror"
                                                           placeholder="Enter new author name..." value="{{ old('new_author') }}">
                                                    <label for="new_author" class="text-muted">
                                                        <i class="bi bi-person-plus me-1"></i>Or Add New Author
                                                    </label>
                                                    @error('new_author')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mt-3">
                                            <div class="alert alert-info border-0" role="alert">
                                                <i class="bi bi-info-circle me-2"></i>
                                                <strong>How to add authors:</strong> Select an existing author from the dropdown, or type a new author name in the second field. If both fields are filled, the new author will be created.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Category Selection -->
                            <div class="col-12 mt-4">
                                <h6 class="text-primary fw-bold mb-3">
                                    <i class="bi bi-tags me-2"></i>Category Information
                                </h6>
                            </div>

                            <div class="col-12">
                                <div class="card border-light bg-light">
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="form-floating">
                                                    <select name="category_id" id="category_id" class="form-select form-select-lg @error('category_id') is-invalid @enderror">
                                                        <option value="">Choose existing category...</option>
                                                        @foreach($categories as $category)
                                                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                                📚 {{ $category->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <label for="category_id" class="text-muted">
                                                        <i class="bi bi-tag me-1"></i>Select Existing Category
                                                    </label>
                                                    @error('category_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-floating">
                                                    <input type="text" name="new_category" id="new_category" class="form-control form-control-lg @error('new_category') is-invalid @enderror"
                                                           placeholder="Enter new category name..." value="{{ old('new_category') }}">
                                                    <label for="new_category" class="text-muted">
                                                        <i class="bi bi-tag-plus me-1"></i>Or Add New Category
                                                    </label>
                                                    @error('new_category')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mt-3">
                                            <div class="alert alert-info border-0" role="alert">
                                                <i class="bi bi-info-circle me-2"></i>
                                                <strong>How to add categories:</strong> Select an existing category from the dropdown, or type a new category name in the second field. If both fields are filled, the new category will be created.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Information -->
                            <div class="col-12 mt-4">
                                <h6 class="text-primary fw-bold mb-3">
                                    <i class="bi bi-plus-circle me-2"></i>Additional Information
                                </h6>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" name="genre" id="genre" class="form-control form-control-lg"
                                           value="{{ old('genre') }}" placeholder="Fiction, Mystery, Science Fiction, etc.">
                                    <label for="genre" class="text-muted">
                                        <i class="bi bi-palette me-1"></i>Genre
                                    </label>
                                </div>
                                <div class="form-text">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Literary genre or style of the book
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" name="location" id="location" class="form-control form-control-lg"
                                           value="{{ old('location') }}" placeholder="Shelf A-12, Room 203, etc.">
                                    <label for="location" class="text-muted">
                                        <i class="bi bi-geo-alt me-1"></i>Location
                                    </label>
                                </div>
                                <div class="form-text">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Physical location in the library
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-floating">
                                    <textarea name="description" id="description" class="form-control" style="height: 120px;"
                                              placeholder="Enter book description, summary, or notes...">{{ old('description') }}</textarea>
                                    <label for="description" class="text-muted">
                                        <i class="bi bi-file-text me-1"></i>Book Description & Notes
                                    </label>
                                </div>
                                <div class="form-text">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Optional description, summary, or additional notes about the book
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="row mt-5">
                            <div class="col-12">
                                <hr class="mb-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="text-muted">
                                        <small><i class="bi bi-info-circle me-1"></i>Adding a new book to the library collection</small>
                                    </div>
                                    <div class="d-flex gap-3">
                                        <a href="{{ route('books.index') }}" class="btn btn-outline-secondary btn-lg px-4">
                                            <i class="bi bi-arrow-left me-2"></i>Back to Books
                                        </a>
                                        <button type="submit" class="btn btn-success btn-lg px-4" id="submitBtn">
                                            <i class="bi bi-check-circle me-2"></i>Add Book to Library
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<style>
/* Custom gradient backgrounds */
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

/* Enhanced form styling */
.form-floating > .form-control {
    border-radius: 0.5rem;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}

.form-floating > .form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.form-floating > .form-control.is-invalid {
    border-color: #dc3545;
}

.form-floating > .form-control.is-invalid:focus {
    border-color: #dc3545;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
}

.form-select {
    border-radius: 0.5rem;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}

.form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.card {
    transition: all 0.3s ease;
}

.btn {
    border-radius: 0.5rem;
    font-weight: 500;
    transition: all 0.2s ease;
}

.btn:hover {
    transform: translateY(-1px);
}

.alert {
    border: none;
    border-radius: 0.5rem;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .d-flex.justify-content-between {
        flex-direction: column;
        gap: 1rem;
        align-items: stretch !important;
    }

    .bg-gradient-primary .h2 {
        font-size: 1.5rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form validation and submission
    const form = document.getElementById('createBookForm');
    const submitBtn = document.getElementById('submitBtn');

    form.addEventListener('submit', function(e) {
        submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Adding Book...';
        submitBtn.disabled = true;
    });

    // Enhanced form interactions
    const inputs = form.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
        });

        input.addEventListener('blur', function() {
            this.parentElement.classList.remove('focused');
        });
    });
});
</script>
