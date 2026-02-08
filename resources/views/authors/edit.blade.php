@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">
    <!-- Modern Hero Section -->
    <div class="row justify-content-center mb-4">
        <div class="col-12">
            <div class="bg-gradient-primary text-white rounded-3 shadow-sm p-4">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h1 class="h2 mb-2">
                            <i class="bi bi-pencil-square-fill me-3"></i>
                            Edit Author Details
                        </h1>
                        <p class="mb-0 opacity-75 lead">Update information for: <strong>{{ $author->name }}</strong></p>
                    </div>
                    <div class="col-lg-4 text-center">
                        <div class="bg-white bg-opacity-20 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="bi bi-person-fill text-white" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-8">
            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

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

            <!-- Author Edit Form -->
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-primary text-white border-0">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-person-gear me-3 fs-5"></i>
                        <div>
                            <h5 class="mb-0">Author Information</h5>
                            <small>Modify author details and biography</small>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('authors.update', $author) }}" method="POST" id="editAuthorForm">
                        @csrf
                        @method('PUT')

                        <div class="row g-4">
                            <!-- Basic Information -->
                            <div class="col-12">
                                <h6 class="text-primary fw-bold mb-3">
                                    <i class="bi bi-info-circle me-2"></i>Basic Information
                                </h6>
                            </div>

                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="text" name="name" id="name" class="form-control form-control-lg @error('name') is-invalid @enderror"
                                           value="{{ old('name', $author->name ?? '') }}" placeholder="Enter author name" required>
                                    <label for="name" class="text-muted">
                                        <i class="bi bi-person me-1"></i>Author Name <span class="text-danger">*</span>
                                    </label>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-text">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Enter the full name of the author
                                </div>
                            </div>

                            <!-- Biography -->
                            <div class="col-12 mt-4">
                                <h6 class="text-primary fw-bold mb-3">
                                    <i class="bi bi-file-text me-2"></i>Biography
                                </h6>
                            </div>

                            <div class="col-12">
                                <div class="form-floating">
                                    <textarea name="bio" id="bio" class="form-control" style="height: 150px;"
                                              placeholder="Enter author biography...">{{ old('bio', $author->bio ?? '') }}</textarea>
                                    <label for="bio" class="text-muted">
                                        <i class="bi bi-book me-1"></i>Author Biography
                                    </label>
                                </div>
                                <div class="form-text">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Optional biography, background, or notes about the author
                                </div>
                            </div>

                            <!-- Author Statistics -->
                            <div class="col-12 mt-4">
                                <h6 class="text-primary fw-bold mb-3">
                                    <i class="bi bi-bar-chart me-2"></i>Author Statistics
                                </h6>
                            </div>

                            <div class="col-12">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <div class="card border-primary bg-primary bg-opacity-10 h-100">
                                            <div class="card-body text-center">
                                                <div class="text-primary mb-1">
                                                    <i class="bi bi-book-half fs-4"></i>
                                                </div>
                                                <div class="h5 mb-1 text-primary">{{ $author->books_count ?? $author->books->count() ?? 0 }}</div>
                                                <div class="small text-muted">Total Books</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card border-info bg-info bg-opacity-10 h-100">
                                            <div class="card-body text-center">
                                                <div class="text-info mb-1">
                                                    <i class="bi bi-person fs-4"></i>
                                                </div>
                                                <div class="h5 mb-1 text-info">{{ $author->books()->where('status', 'available')->count() }}</div>
                                                <div class="small text-muted">Available Books</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card border-success bg-success bg-opacity-10 h-100">
                                            <div class="card-body text-center">
                                                <div class="text-success mb-1">
                                                    <i class="bi bi-arrow-repeat fs-4"></i>
                                                </div>
                                                <div class="h5 mb-1 text-success">{{ $author->books()->where('status', 'borrowed')->count() }}</div>
                                                <div class="small text-muted">Currently Borrowed</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Recent Books by Author -->
                            @if($author->books && $author->books->count() > 0)
                            <div class="col-12 mt-4">
                                <h6 class="text-primary fw-bold mb-3">
                                    <i class="bi bi-clock-history me-2"></i>Recent Books by {{ explode(' ', $author->name)[0] }}
                                </h6>
                            </div>

                            <div class="col-12">
                                <div class="row g-3">
                                    @foreach($author->books->take(6) as $book)
                                    <div class="col-md-6 col-lg-4">
                                        <div class="card border-light shadow-sm h-100">
                                            <div class="card-body">
                                                <div class="d-flex align-items-start">
                                                    <div class="bg-primary bg-opacity-10 rounded me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; flex-shrink: 0;">
                                                        <i class="bi bi-book text-primary"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="card-title mb-1 text-truncate">{{ $book->title }}</h6>
                                                        <p class="card-text small text-muted mb-1">{{ $book->category->name ?? 'Uncategorized' }}</p>
                                                        <span class="badge bg-{{ $book->status === 'available' ? 'success' : 'danger' }} badge-sm">
                                                            {{ $book->status === 'available' ? 'Available' : 'Borrowed' }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Form Actions -->
                        <div class="row mt-5">
                            <div class="col-12">
                                <hr class="mb-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="text-muted">
                                        <small><i class="bi bi-info-circle me-1"></i>Last updated: {{ $author->updated_at->format('M d, Y H:i') }}</small>
                                    </div>
                                    <div class="d-flex gap-3">
                                        <a href="{{ route('authors.index') }}" class="btn btn-outline-secondary btn-lg px-4">
                                            <i class="bi bi-arrow-left me-2"></i>Back to Authors
                                        </a>
                                        <button type="submit" class="btn btn-success btn-lg px-4" id="submitBtn">
                                            <i class="bi bi-check-circle me-2"></i>Update Author
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
    const form = document.getElementById('editAuthorForm');
    const submitBtn = document.getElementById('submitBtn');

    form.addEventListener('submit', function(e) {
        submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Updating...';
        submitBtn.disabled = true;
    });

    // Enhanced form interactions
    const inputs = form.querySelectorAll('input, textarea');
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
