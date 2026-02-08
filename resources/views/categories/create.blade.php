@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-6">
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

            <!-- Category Creation Form -->
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-primary text-white border-0">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-tags me-3 fs-5"></i>
                        <div>
                            <h5 class="mb-0">Category Information</h5>
                            <small>Define category details and description</small>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('categories.store') }}" method="POST" id="createCategoryForm">
                        @csrf

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
                                           value="{{ old('name') }}" placeholder="Enter category name" required>
                                    <label for="name" class="text-muted">
                                        <i class="bi bi-tag me-1"></i>Category Name <span class="text-danger">*</span>
                                    </label>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-text">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Choose a clear, descriptive name for the category
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="col-12 mt-4">
                                <h6 class="text-primary fw-bold mb-3">
                                    <i class="bi bi-file-text me-2"></i>Description
                                </h6>
                            </div>

                            <div class="col-12">
                                <div class="form-floating">
                                    <textarea name="description" id="description" class="form-control" style="height: 120px;"
                                              placeholder="Enter category description...">{{ old('description') }}</textarea>
                                    <label for="description" class="text-muted">
                                        <i class="bi bi-chat-quote me-1"></i>Category Description
                                    </label>
                                </div>
                                <div class="form-text">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Optional description to help users understand what this category contains
                                </div>
                            </div>

                            <!-- Category Preview -->
                            <div class="col-12 mt-4">
                                <h6 class="text-primary fw-bold mb-3">
                                    <i class="bi bi-eye me-2"></i>Preview
                                </h6>
                            </div>

                            <div class="col-12">
                                <div class="card border-light bg-light">
                                    <div class="card-body">
                                        <div class="text-center mb-3">
                                            <span class="badge bg-primary fs-6 px-4 py-2" id="previewBadge">Preview Category</span>
                                        </div>
                                        <p class="text-muted small mb-2" id="previewDescription">Category description will appear here...</p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted" id="previewCount">0 books</small>
                                            <small class="text-success">Available</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-text">
                                    <i class="bi bi-info-circle me-1"></i>
                                    This is how the category will appear in the library system
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="row mt-5">
                            <div class="col-12">
                                <hr class="mb-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="text-muted">
                                        <small><i class="bi bi-info-circle me-1"></i>Creating a new category for book organization</small>
                                    </div>
                                    <div class="d-flex gap-3">
                                        <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary btn-lg px-4">
                                            <i class="bi bi-arrow-left me-2"></i>Back to Categories
                                        </a>
                                        <button type="submit" class="btn btn-success btn-lg px-4" id="submitBtn">
                                            <i class="bi bi-check-circle me-2"></i>Create Category
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
    const form = document.getElementById('createCategoryForm');
    const submitBtn = document.getElementById('submitBtn');

    form.addEventListener('submit', function(e) {
        submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Creating...';
        submitBtn.disabled = true;
    });

    // Live preview functionality
    const nameInput = document.getElementById('name');
    const descriptionInput = document.getElementById('description');
    const previewBadge = document.getElementById('previewBadge');
    const previewDescription = document.getElementById('previewDescription');

    function updatePreview() {
        const name = nameInput.value.trim() || 'Preview Category';
        const description = descriptionInput.value.trim() || 'Category description will appear here...';

        previewBadge.textContent = name;
        previewDescription.textContent = description;
    }

    nameInput.addEventListener('input', updatePreview);
    descriptionInput.addEventListener('input', updatePreview);

    // Initialize preview
    updatePreview();

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
