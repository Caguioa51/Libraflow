
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

    <!-- Edit User Form -->
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="h4 mb-1 text-dark fw-bold">
                                <i class="fas fa-user-edit me-2 text-primary"></i>Edit User: {{ $user->name }}
                            </h1>
                            <p class="text-muted mb-0 small">Update user information and settings</p>
                        </div>
                        <div class="text-muted">
                            <small><i class="fas fa-info-circle me-1"></i>User Information</small>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.users.update', $user) }}" id="editUserForm">
                        @csrf
                        @method('PUT')
                        
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
                                       value="{{ old('name', $user->name) }}"
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
                                <x-text-input id="email" class="form-control" type="email" name="email" :value="old('email', $user->email)" required autocomplete="email" placeholder="Enter email address">
                                </x-text-input>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            @if($user->isStudent())
                                <!-- Student-specific fields -->
                                <div class="col-md-6">
                                    <label for="grade" class="form-label fw-semibold">
                                        Grade Level <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('grade') is-invalid @enderror"
                                            id="grade"
                                            name="grade"
                                            required>
                                        <option value="">Select Grade Level</option>
                                        @for($i = 7; $i <= 12; $i++)
                                            <option value="{{ $i }}" {{ old('grade', $user->grade) == $i ? 'selected' : '' }}>
                                                Grade {{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                    <div class="form-text">Select the student's grade level</div>
                                    @error('grade')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="section" class="form-label fw-semibold">
                                        Section <span class="text-danger">*</span>
                                    </label>
                                    <input type="text"
                                           class="form-control @error('section') is-invalid @enderror"
                                           id="section"
                                           name="section"
                                           value="{{ old('section', $user->section) }}"
                                           required
                                           placeholder="Enter section (e.g., A, B, C)">
                                    <div class="form-text">Enter the student's section</div>
                                    @error('section')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="lrn_number" class="form-label fw-semibold">
                                        LRN Number <span class="text-danger">*</span>
                                    </label>
                                    <input type="text"
                                           class="form-control @error('lrn_number') is-invalid @enderror"
                                           id="lrn_number"
                                           name="lrn_number"
                                           value="{{ old('lrn_number', $user->lrn_number) }}"
                                           required
                                           placeholder="Enter LRN number">
                                    <div class="form-text">Enter the Learner Reference Number</div>
                                    @error('lrn_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @elseif($user->isTeacher())
                                <!-- Teacher-specific fields -->
                                <div class="col-md-6">
                                    <label for="department" class="form-label fw-semibold">
                                        Department <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('department') is-invalid @enderror"
                                            id="department"
                                            name="department"
                                            required>
                                        <option value="">Select Department</option>
                                        <option value="Mathematics" {{ old('department', $user->department) == 'Mathematics' ? 'selected' : '' }}>Mathematics</option>
                                        <option value="Science" {{ old('department', $user->department) == 'Science' ? 'selected' : '' }}>Science</option>
                                        <option value="English" {{ old('department', $user->department) == 'English' ? 'selected' : '' }}>English</option>
                                        <option value="Filipino" {{ old('department', $user->department) == 'Filipino' ? 'selected' : '' }}>Filipino</option>
                                        <option value="Social Studies" {{ old('department', $user->department) == 'Social Studies' ? 'selected' : '' }}>Social Studies</option>
                                        <option value="Physical Education" {{ old('department', $user->department) == 'Physical Education' ? 'selected' : '' }}>Physical Education</option>
                                        <option value="Arts and Design" {{ old('department', $user->department) == 'Arts and Design' ? 'selected' : '' }}>Arts and Design</option>
                                        <option value="STEM" {{ old('department', $user->department) == 'STEM' ? 'selected' : '' }}>STEM</option>
                                        <option value="ABM" {{ old('department', $user->department) == 'ABM' ? 'selected' : '' }}>ABM</option>
                                        <option value="HUMSS" {{ old('department', $user->department) == 'HUMSS' ? 'selected' : '' }}>HUMSS</option>
                                        <option value="GAS" {{ old('department', $user->department) == 'GAS' ? 'selected' : '' }}>GAS</option>
                                        <option value="ICT" {{ old('department', $user->department) == 'ICT' ? 'selected' : '' }}>ICT</option>
                                    </select>
                                    <div class="form-text">Select the teacher's department</div>
                                    @error('department')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="employee_id" class="form-label fw-semibold">
                                        Employee ID <span class="text-danger">*</span>
                                    </label>
                                    <input type="text"
                                           class="form-control @error('employee_id') is-invalid @enderror"
                                           id="employee_id"
                                           name="employee_id"
                                           value="{{ old('employee_id', $user->employee_id) }}"
                                           required
                                           placeholder="Enter employee ID">
                                    <div class="form-text">Enter the teacher's employee ID</div>
                                    @error('employee_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @else
                                <!-- Admin fields (minimal information) -->
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <i class="bi bi-info-circle me-2"></i>
                                        <strong>Admin User:</strong> Admin users have minimal personal information requirements.
                                    </div>
                                </div>
                            @endif

                            <!-- Password Information -->
                            <div class="col-12 mt-4">
                                <h6 class="text-primary fw-bold mb-3">
                                    <i class="bi bi-lock me-2"></i>Security Information
                                </h6>
                            </div>

                            <div class="col-md-6">
                                <label for="password" class="form-label fw-semibold">
                                    New Password (Optional)
                                </label>
                                <input type="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       id="password"
                                       name="password"
                                       placeholder="Leave blank to keep current password">
                                <div class="form-text">Minimum 8 characters (leave blank to keep current password)</div>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label fw-semibold">
                                    Confirm New Password
                                </label>
                                <input type="password"
                                       class="form-control"
                                       id="password_confirmation"
                                       name="password_confirmation"
                                       placeholder="Confirm new password">
                                <div class="form-text">Must match the new password above</div>
                            </div>

                            <!-- User Information -->
                            <div class="col-12 mt-4">
                                <h6 class="text-primary fw-bold mb-3">
                                    <i class="bi bi-info-circle me-2"></i>User Details
                                </h6>
                            </div>

                            <div class="col-12">
                                <div class="alert alert-info" role="alert">
                                    <i class="bi bi-info-circle me-2"></i>
                                    <strong>User Information:</strong>
                                    <ul class="mb-0 mt-2">
                                        <li><strong>Account Created:</strong> {{ $user->created_at->format('M d, Y H:i') }}</li>
                                        <li><strong>Last Updated:</strong> {{ $user->updated_at->format('M d, Y H:i') }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <hr class="mb-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="text-muted">
                                        <small><i class="fas fa-info-circle me-1"></i>All changes are saved automatically</small>
                                    </div>
                                    <div class="d-flex gap-3">
                                        <button type="submit" class="btn btn-success btn-lg px-4" id="submitBtn">
                                            <i class="fas fa-save me-2"></i>Update User
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

<!-- Update Confirmation Modal -->
<div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="updateModalLabel">
                    <i class="fas fa-save me-2"></i>Confirm User Update
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <i class="fas fa-user-edit fa-3x text-success mb-3"></i>
                    <h5>Update User: <strong>{{ $user->name }}</strong></h5>
                </div>
                <div id="updateDetails" class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Changes to be applied:</strong>
                    <ul class="mb-0 mt-2" id="changeList">
                        <!-- Changes will be populated by JavaScript -->
                    </ul>
                </div>
                <div class="alert alert-warning" id="passwordWarning" style="display: none;">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Password Change:</strong> A new password will be set for this user. The user will need to use the new password for future logins.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cancel
                </button>
                <button type="button" class="btn btn-success" id="confirmUpdateBtn">
                    <i class="fas fa-check me-1"></i>Update User
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-danger shadow-lg">
            <div class="modal-header bg-danger text-white">
                <h4 class="modal-title fw-bold" id="deleteModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>⚠️ CRITICAL ACTION REQUIRED
                </h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <!-- User Identity Section -->
                <div class="text-center mb-4 p-3 bg-light rounded">
                    <div class="mb-3">
                        @if($user->profile_photo)
                            <img src="{{ asset('storage/profile_photos/' . $user->profile_photo) }}" alt="Profile"
                                 class="rounded-circle border border-danger border-3 mb-2" width="80" height="80">
                        @else
                            <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2"
                                 style="width: 80px; height: 80px; font-size: 2rem;">
                                <i class="fas fa-user"></i>
                            </div>
                        @endif
                    </div>
                    <h4 class="text-danger mb-1 fw-bold">{{ $user->name }}</h4>
                    <p class="text-muted mb-2">{{ $user->email }}</p>
                    <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'teacher' ? 'warning' : 'info') }} fs-6 px-3 py-1">
                        <i class="fas fa-{{ $user->role === 'admin' ? 'user-shield' : ($user->role === 'teacher' ? 'chalkboard-teacher' : 'graduation-cap') }} me-1"></i>
                        {{ ucfirst($user->role) }}
                    </span>
                </div>

                <!-- Critical Warning Section -->
                <div class="alert alert-danger border-2 border-danger mb-4" role="alert">
                    <div class="d-flex align-items-start">
                        <i class="fas fa-exclamation-triangle fa-2x text-danger me-3 mt-1"></i>
                        <div>
                            <h5 class="alert-heading mb-2">🚫 IRREVERSIBLE DELETION</h5>
                            <p class="mb-0 fw-semibold">This action cannot be undone! Once deleted, all user data will be permanently lost.</p>
                        </div>
                    </div>
                </div>

                <!-- Impact Assessment -->
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="card border-danger h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-chart-bar fa-2x text-danger mb-2"></i>
                                <h5 class="card-title text-danger">{{ $user->borrowings->count() }}</h5>
                                <p class="card-text small mb-0">Total Borrowing Records</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-warning h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-clock fa-2x text-warning mb-2"></i>
                                <h5 class="card-title text-warning">{{ $user->borrowings->where('status', 'borrowed')->count() }}</h5>
                                <p class="card-text small mb-0">Active Borrowings</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Consequences List -->
                <div class="card border-danger mb-4">
                    <div class="card-header bg-danger text-white">
                        <h6 class="mb-0">
                            <i class="fas fa-list-ul me-2"></i>What Will Be Deleted
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-2">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-user-times text-danger me-2"></i>
                                    <span class="small">User account & profile</span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-envelope text-danger me-2"></i>
                                    <span class="small">Email & contact info</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-book text-danger me-2"></i>
                                    <span class="small">All borrowing history</span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-calendar-times text-danger me-2"></i>
                                    <span class="small">Active reservations</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-shield-alt text-danger me-2"></i>
                                    <span class="small">System permissions</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Final Confirmation -->
                <div class="text-center p-3 bg-light rounded">
                    <h6 class="text-danger fw-bold mb-2">FINAL CONFIRMATION REQUIRED</h6>
                    <p class="mb-0 text-muted">Type <strong class="text-danger">"DELETE"</strong> to confirm deletion:</p>
                    <input type="text" class="form-control form-control-sm mt-2 text-center fw-bold"
                           id="deleteConfirmation" placeholder="Type DELETE to confirm"
                           maxlength="6" style="text-transform: uppercase;">
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-outline-secondary btn-lg px-4" data-bs-dismiss="modal">
                    <i class="fas fa-arrow-left me-2"></i>Cancel & Keep User
                </button>
                <button type="button" class="btn btn-danger btn-lg px-4" id="confirmDeleteBtn" disabled>
                    <i class="fas fa-trash-alt me-2"></i>Delete User Permanently
                </button>
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
    const form = document.getElementById('editUserForm');
    const submitBtn = document.getElementById('submitBtn');

    // Store original form values for comparison
    const originalValues = {
        name: '{{ $user->name }}',
        email: '{{ $user->email }}',
        @if($user->isStudent())
        grade: '{{ $user->grade }}',
        section: '{{ $user->section }}',
        lrn_number: '{{ $user->lrn_number }}',
        @elseif($user->isTeacher())
        department: '{{ $user->department }}',
        employee_id: '{{ $user->employee_id }}',
        @endif
        password: ''
    };

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

    // Update modal functionality
    const confirmUpdateBtn = document.getElementById('confirmUpdateBtn');
    confirmUpdateBtn.addEventListener('click', function() {
        // Disable button and show loading state
        confirmUpdateBtn.disabled = true;
        confirmUpdateBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Updating...';

        // Close modal and submit form
        const modal = bootstrap.Modal.getInstance(document.getElementById('updateModal'));
        if (modal) modal.hide();

        // Submit the form
        setTimeout(() => {
            form.submit();
        }, 300);
    });

    // Delete modal functionality
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    confirmDeleteBtn.addEventListener('click', function() {
        // Disable the button to prevent double-clicks
        confirmDeleteBtn.disabled = true;
        confirmDeleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Deleting...';

        // Force close modal and remove backdrop
        const modalElement = document.getElementById('deleteModal');
        const modal = bootstrap.Modal.getInstance(modalElement);
        if (modal) {
            modal.hide();
        }

        // Manually remove modal backdrop and classes
        document.querySelectorAll('.modal-backdrop').forEach(backdrop => backdrop.remove());
        document.body.classList.remove('modal-open');
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';

        // Small delay to ensure cleanup before form submission
        setTimeout(function() {
            // Create and submit delete form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("admin.users.destroy", $user) }}';
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

            document.body.appendChild(form);
            form.submit();
        }, 500);
    });
});

function showUpdateModal() {
    const form = document.getElementById('editUserForm');
    const changeList = document.getElementById('changeList');
    const passwordWarning = document.getElementById('passwordWarning');

    // Get current form values
    const currentValues = {
        name: document.getElementById('name').value,
        email: document.getElementById('email').value,
        password: document.getElementById('password').value
    };

    // Add role-specific fields to current values
    @if($user->isStudent())
    if (document.getElementById('grade')) {
        currentValues.grade = document.getElementById('grade').value;
    }
    if (document.getElementById('section')) {
        currentValues.section = document.getElementById('section').value;
    }
    if (document.getElementById('lrn_number')) {
        currentValues.lrn_number = document.getElementById('lrn_number').value;
    }
    @elseif($user->isTeacher())
    if (document.getElementById('department')) {
        currentValues.department = document.getElementById('department').value;
    }
    if (document.getElementById('employee_id')) {
        currentValues.employee_id = document.getElementById('employee_id').value;
    }
    @endif

    // Compare with original values
    const changes = [];
    if (currentValues.name !== '{{ $user->name }}') changes.push(`Name: "{{ $user->name }}" → "${currentValues.name}"`);
    if (currentValues.email !== '{{ $user->email }}') changes.push(`Email: "{{ $user->email }}" → "${currentValues.email}"`);
    
    @if($user->isStudent())
    if (currentValues.grade !== '{{ $user->grade }}') changes.push(`Grade: "Grade {{ $user->grade }}" → "Grade ${currentValues.grade}"`);
    if (currentValues.section !== '{{ $user->section }}') changes.push(`Section: "{{ $user->section }}" → "${currentValues.section}"`);
    if (currentValues.lrn_number !== '{{ $user->lrn_number }}') changes.push(`LRN Number: "{{ $user->lrn_number }}" → "${currentValues.lrn_number}"`);
    @elseif($user->isTeacher())
    if (currentValues.department !== '{{ $user->department }}') changes.push(`Department: "{{ $user->department }}" → "${currentValues.department}"`);
    if (currentValues.employee_id !== '{{ $user->employee_id }}') changes.push(`Employee ID: "{{ $user->employee_id }}" → "${currentValues.employee_id}"`);
    @endif

    // Clear and populate change list
    changeList.innerHTML = '';
    if (changes.length > 0) {
        changes.forEach(change => {
            const li = document.createElement('li');
            li.textContent = change;
            changeList.appendChild(li);
        });
    } else {
        const li = document.createElement('li');
        li.textContent = 'No changes detected';
        changeList.appendChild(li);
    }

    // Show/hide password warning
    if (currentValues.password) {
        passwordWarning.style.display = 'block';
    } else {
        passwordWarning.style.display = 'none';
    }

    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('updateModal'));
    modal.show();

    return false; // Prevent form submission
}

function showDeleteModal() {
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));

    // Reset the confirmation input when modal opens
    const deleteConfirmation = document.getElementById('deleteConfirmation');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');

    deleteConfirmation.value = '';
    confirmDeleteBtn.disabled = true;

    // Add input listener to enable/disable delete button
    deleteConfirmation.addEventListener('input', function() {
        const value = this.value.toUpperCase();
        confirmDeleteBtn.disabled = value !== 'DELETE';
        this.value = value; // Keep it uppercase
    });

    modal.show();
}

function confirmUserDeletion() {
    const userName = '{{ $user->name }}';

    // Simple initial confirmation prompt
    const initialConfirm = confirm(`⚠️ WARNING: Delete User\n\nYou are about to delete user "${userName}".\n\nThis action is irreversible and will permanently remove all user data.\n\nDo you want to continue?`);

    if (!initialConfirm) {
        return false; // Cancel deletion
    }

    // If user confirms, proceed to the complex modal system
    return confirmDeleteUser();
}

function confirmDeleteUser() {
    const userName = '{{ $user->name }}';
    const totalBorrowings = {{ $user->borrowings->count() }};
    const activeBorrowings = {{ $user->borrowings->where('status', 'borrowed')->count() }};

    // Show the complex delete modal
    showDeleteModal();
    return false; // Prevent immediate form submission, let modal handle it

    // Second confirmation with detailed information
    let message = `🚨 FINAL DELETION CONFIRMATION\n\n`;
    message += `User: ${userName}\n`;
    message += `Role: {{ ucfirst($user->role) }}\n\n`;

    message += `⚠️  DATA THAT WILL BE PERMANENTLY DELETED:\n\n`;
}

    // Add loading state to main submit button when clicked
    const deleteBtn = document.getElementById('deleteBtn');
    deleteBtn.addEventListener('click', function(event) {
        confirmDelete(event, '{{ $user->name }}');
        deleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Loading...';
    });

    // Override form submission to show modal - ensure this runs after all other listeners
    setTimeout(function() {
        const form = document.getElementById('editUserForm');
        const submitBtn = document.getElementById('submitBtn');

        // Add click handler to submit button as backup
        submitBtn.addEventListener('click', function(e) {
            e.preventDefault();
            showUpdateModal();
            return false;
        });

        // Add form submit handler
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('Form submit intercepted'); // Debug log
            showUpdateModal();
            return false;
        }, true); // Use capture phase to ensure it runs first
    }, 100);
</script>
