@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-8">
            <!-- Error Messages Only -->
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <strong>Please fix the following errors:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Book Edit Form -->
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-primary text-white border-0">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-book-half me-3 fs-5"></i>
                        <div>
                            <h5 class="mb-0">Book Information</h5>
                            <small>Modify book details and settings</small>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('books.update', $book) }}" method="POST" id="editBookForm">
                        @csrf
                        @method('PUT')

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
                                           value="{{ old('title', $book->title ?? '') }}" placeholder="Enter book title" required>
                                    <label for="title" class="text-muted">
                                        <i class="bi bi-type me-1"></i>Book Title <span class="text-danger">*</span>
                                    </label>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-floating">
                                    <input type="number" name="quantity" id="quantity" min="1" class="form-control form-control-lg @error('quantity') is-invalid @enderror"
                                           value="{{ old('quantity', $book->quantity ?? 1) }}" placeholder="1" required>
                                    <label for="quantity" class="text-muted">
                                        <i class="bi bi-hash me-1"></i>Quantity <span class="text-danger">*</span>
                                    </label>
                                    @error('quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select name="category_id" id="category_id" class="form-select form-select-lg @error('category_id') is-invalid @enderror" required>
                                        <option value="">Choose a category...</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" @if(old('category_id', $book->category_id ?? '') == $category->id) selected @endif>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label for="category_id" class="text-muted">
                                        <i class="bi bi-tags me-1"></i>Book Category <span class="text-danger">*</span>
                                    </label>
                                    @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select name="author_id" id="author_id" class="form-select form-select-lg @error('author_id') is-invalid @enderror" required>
                                        <option value="">Choose an author...</option>
                                        @foreach($authors as $author)
                                            <option value="{{ $author->id }}" @if(old('author_id', $book->author_id ?? '') == $author->id) selected @endif>
                                                {{ $author->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label for="author_id" class="text-muted">
                                        <i class="bi bi-person me-1"></i>Author <span class="text-danger">*</span>
                                    </label>
                                    @error('author_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
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
                                           value="{{ old('genre', $book->genre ?? '') }}" placeholder="Fiction, Mystery, etc.">
                                    <label for="genre" class="text-muted">
                                        <i class="bi bi-palette me-1"></i>Genre
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" name="location" id="location" class="form-control form-control-lg"
                                           value="{{ old('location', $book->location ?? '') }}" placeholder="Shelf A-12, Room 203, etc.">
                                    <label for="location" class="text-muted">
                                        <i class="bi bi-geo-alt me-1"></i>Location
                                    </label>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-floating">
                                    <textarea name="description" id="description" class="form-control" style="height: 120px;"
                                              placeholder="Enter book description, summary, or notes...">{{ old('description', $book->description ?? '') }}</textarea>
                                    <label for="description" class="text-muted">
                                        <i class="bi bi-file-text me-1"></i>Book Description & Notes
                                    </label>
                                </div>
                            </div>

                            <!-- Book Statistics -->
                            <div class="col-12 mt-4">
                                <h6 class="text-primary fw-bold mb-3">
                                    <i class="bi bi-bar-chart me-2"></i>Book Statistics
                                </h6>
                            </div>

                            <div class="col-12">
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <div class="card border-primary bg-primary bg-opacity-10 h-100">
                                            <div class="card-body text-center">
                                                <div class="text-primary mb-1">
                                                    <i class="bi bi-check-circle-fill fs-4"></i>
                                                </div>
                                                <div class="h5 mb-1 text-primary">{{ $book->available_quantity ?? $book->quantity ?? 0 }}</div>
                                                <div class="small text-muted">Available</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card border-info bg-info bg-opacity-10 h-100">
                                            <div class="card-body text-center">
                                                <div class="text-info mb-1">
                                                    <i class="bi bi-book-half fs-4"></i>
                                                </div>
                                                <div class="h5 mb-1 text-info">{{ $book->borrowings->where('status', 'borrowed')->count() }}</div>
                                                <div class="small text-muted">Borrowed</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card border-success bg-success bg-opacity-10 h-100">
                                            <div class="card-body text-center">
                                                <div class="text-success mb-1">
                                                    <i class="bi bi-arrow-clockwise fs-4"></i>
                                                </div>
                                                <div class="h5 mb-1 text-success">{{ $book->borrowings->where('status', 'returned')->count() }}</div>
                                                <div class="small text-muted">Total Returns</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card border-warning bg-warning bg-opacity-10 h-100">
                                            <div class="card-body text-center">
                                                <div class="text-warning mb-1">
                                                    <i class="bi bi-clock-history fs-4"></i>
                                                </div>
                                                <div class="h5 mb-1 text-warning">{{ $book->borrowings->count() }}</div>
                                                <div class="small text-muted">Total Loans</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="row mt-5">
                            <div class="col-12">
                                <hr class="mb-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="text-muted">
                                        <small><i class="bi bi-info-circle me-1"></i>Last updated: {{ $book->updated_at->format('M d, Y H:i') }}</small>
                                    </div>
                                    <div class="d-flex gap-3">
                                        <a href="{{ route('books.index') }}" class="btn btn-outline-secondary btn-lg px-4">
                                            <i class="bi bi-arrow-left me-2"></i>Back to Books
                                        </a>
                                        <button type="submit" class="btn btn-success btn-lg px-4" id="submitBtn">
                                            <i class="bi bi-check-circle me-2"></i>Update Book
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
    const form = document.getElementById('editBookForm');
    const submitBtn = document.getElementById('submitBtn');

    form.addEventListener('submit', function(e) {
        submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Updating...';
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

    // Auto-save draft (optional enhancement)
    let autoSaveTimer;
    const autoSave = () => {
        // Could implement auto-save functionality here
        console.log('Form data changed - auto-save could be implemented');
    };

    inputs.forEach(input => {
        input.addEventListener('input', () => {
            clearTimeout(autoSaveTimer);
            autoSaveTimer = setTimeout(autoSave, 1000);
        });
    });
});
</script>
