@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">


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

    <!-- Create User Form -->
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0 text-dark fw-semibold">
                        <i class="bi bi-form me-2 text-primary"></i>User Information
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.users.store') }}" id="createUserForm">
                        @csrf

                        <div class="row g-4">
                            <!-- Personal Information -->
                            <div class="col-12">
                                <h6 class="text-primary fw-bold mb-3">
                                    <i class="bi bi-person me-2"></i>Personal Information
                                </h6>
                            </div>

                            <div class="col-md-6">
                                <label for="name" class="form-label fw-semibold">
                                    Full Name <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                       class="form-control @error('name') is-invalid @enderror"
                                       id="name"
                                       name="name"
                                       value="{{ old('name') }}"
                                       required
                                       autofocus
                                       placeholder="Enter full name">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label fw-semibold">
                                    Email Address <span class="text-danger">*</span>
                                </label>
                                <input type="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       id="email"
                                       name="email"
                                       value="{{ old('email') }}"
                                       required
                                       placeholder="Enter email address">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="student_id" class="form-label fw-semibold">
                                    Student ID <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                       class="form-control @error('student_id') is-invalid @enderror"
                                       id="student_id"
                                       name="student_id"
                                       value="{{ old('student_id') }}"
                                       required
                                       placeholder="Enter student ID">
                                <div class="form-text">Unique identifier for the user</div>
                                @error('student_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="role" class="form-label fw-semibold">
                                    User Role <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('role') is-invalid @enderror"
                                        id="role"
                                        name="role"
                                        required>
                                    <option value="">Select a role</option>
                                    <option value="student" {{ old('role') === 'student' ? 'selected' : '' }}>
                                        Student
                                    </option>
                                    <option value="teacher" {{ old('role') === 'teacher' ? 'selected' : '' }}>
                                        Teacher
                                    </option>
                                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>
                                        Administrator
                                    </option>
                                </select>
                                <div class="form-text">Determines user permissions and access levels</div>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- RFID Card Information -->
                            <div class="col-md-6">
                                <label for="rfid_card" class="form-label fw-semibold">
                                    RFID Card Number
                                </label>
                                <div class="input-group">
                                    <input type="text"
                                           class="form-control @error('rfid_card') is-invalid @enderror"
                                           id="rfid_card"
                                           name="rfid_card"
                                           value="{{ old('rfid_card') }}"
                                           placeholder="Enter RFID card number (optional)">
                                    <button class="btn btn-outline-secondary"
                                            type="button"
                                            onclick="generateRfid()"
                                            title="Generate RFID">
                                        <i class="bi bi-magic"></i>
                                    </button>
                                </div>
                                <div class="form-text">RFID card for library access (optional)</div>
                                @error('rfid_card')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Password Information -->
                            <div class="col-12 mt-4">
                                <h6 class="text-primary fw-bold mb-3">
                                    <i class="bi bi-lock me-2"></i>Security Information
                                </h6>
                            </div>

                            <div class="col-md-6">
                                <label for="password" class="form-label fw-semibold">
                                    Password <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="password"
                                           class="form-control @error('password') is-invalid @enderror"
                                           id="password"
                                           name="password"
                                           required
                                           placeholder="Enter password">
                                    <button class="btn btn-outline-secondary"
                                            type="button"
                                            id="togglePassword"
                                            title="Show/Hide Password">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div class="form-text">Minimum 8 characters</div>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label fw-semibold">
                                    Confirm Password <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="password"
                                           class="form-control"
                                           id="password_confirmation"
                                           name="password_confirmation"
                                           required
                                           placeholder="Confirm password">
                                    <button class="btn btn-outline-secondary"
                                            type="button"
                                            id="toggleConfirmPassword"
                                            title="Show/Hide Password">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div class="form-text">Must match the password above</div>
                            </div>

                            <!-- User Information -->
                            <div class="col-12 mt-4">
                                <h6 class="text-primary fw-bold mb-3">
                                    <i class="bi bi-info-circle me-2"></i>System Information
                                </h6>
                            </div>

                            <div class="col-12">
                                <div class="alert alert-info" role="alert">
                                    <i class="bi bi-info-circle me-2"></i>
                                    <strong>What happens next:</strong>
                                    <ul class="mb-0 mt-2">
                                        <li><strong>Barcode Generation:</strong> Automatically created as "STUDENT-{Student ID}" or uses provided RFID</li>
                                        <li><strong>Email Verification:</strong> User must verify email before first login</li>
                                        <li><strong>Account Activation:</strong> Account becomes active immediately</li>
                                        <li><strong>Default Permissions:</strong> Based on selected role</li>
                                    </ul>
                                </div>
                            </div>

                        <!-- Form Actions -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <hr class="mb-4">
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary" id="submitBtn">
                                        <i class="bi bi-check-circle me-1"></i>Create User
                                    </button>
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
/* Custom styles for enhanced UI */
.form-control:focus, .form-select:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.card {
    transition: all 0.3s ease;
}

.breadcrumb-item a:hover {
    color: #007bff !important;
}

.alert {
    border: none;
    border-radius: 0.5rem;
}

.btn {
    border-radius: 0.375rem;
    font-weight: 500;
    transition: all 0.2s ease;
}

.btn:hover {
    transform: translateY(-1px);
}

.badge {
    font-size: 0.75rem;
    font-weight: 500;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .d-flex.justify-content-between {
        flex-direction: column;
        gap: 1rem;
        align-items: stretch !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form validation
    const form = document.getElementById('createUserForm');
    const submitBtn = document.getElementById('submitBtn');

    form.addEventListener('submit', function(e) {
        submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Creating...';
        submitBtn.disabled = true;
    });

    // Password confirmation validation
    const password = document.getElementById('password');
    const passwordConfirmation = document.getElementById('password_confirmation');

    function validatePassword() {
        if (passwordConfirmation.value && password.value !== passwordConfirmation.value) {
            passwordConfirmation.setCustomValidity('Passwords do not match');
        } else {
            passwordConfirmation.setCustomValidity('');
        }
    }

    password.addEventListener('input', validatePassword);
    passwordConfirmation.addEventListener('input', validatePassword);
});

function generateRfid() {
    const rfidInput = document.getElementById('rfid_card');
    const timestamp = Date.now().toString().slice(-8);
    const random = Math.floor(Math.random() * 1000).toString().padStart(3, '0');
    rfidInput.value = 'RFID' + timestamp + random;
}

// Password toggle functionality
document.addEventListener('DOMContentLoaded', function() {
    const togglePasswordBtn = document.getElementById('togglePassword');
    const toggleConfirmPasswordBtn = document.getElementById('toggleConfirmPassword');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('password_confirmation');

    togglePasswordBtn.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        this.querySelector('i').classList.toggle('fa-eye');
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });

    toggleConfirmPasswordBtn.addEventListener('click', function() {
        const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        confirmPasswordInput.setAttribute('type', type);
        this.querySelector('i').classList.toggle('fa-eye');
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });
});

</script>
