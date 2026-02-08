@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-cogs"></i> System Settings</h1>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>



    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>❌ Validation Errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- System Configuration -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-cogs"></i> System Configuration</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.settings.update') }}">
                        @csrf

                        <!-- Library Information Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3"><i class="fas fa-building"></i> Library Information</h6>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="library_hours" class="form-label fw-semibold">
                                    <i class="fas fa-clock text-primary"></i> Library Hours
                                </label>
                                <textarea class="form-control" id="library_hours" name="library_hours"
                                          rows="3" placeholder="Enter library operating hours...">{{ $settings['library_hours']->value ?? 'Monday - Friday: 7:00 AM - 5:00 PM
Saturday: 8:00 AM - 12:00 PM
Sunday: Closed' }}</textarea>
                                <div class="form-text">Operating hours displayed on the welcome page</div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="library_location" class="form-label fw-semibold">
                                    <i class="fas fa-map-marker-alt text-danger"></i> Library Location
                                </label>
                                <input type="text" class="form-control" id="library_location" name="library_location"
                                       value="{{ $settings['library_location']->value ?? 'Dagupan City National High School, Dagupan City, Pangasinan' }}"
                                       placeholder="Enter library address...">
                                <div class="form-text">Physical address shown on welcome page</div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="featured_books_text" class="form-label fw-semibold">
                                    <i class="fas fa-star text-warning"></i> Featured Books Text
                                </label>
                                <textarea class="form-control" id="featured_books_text" name="featured_books_text"
                                          rows="3" placeholder="Enter featured books description...">{{ $settings['featured_books_text']->value ?? 'Discover our most popular and recently added books. From classic literature to modern bestsellers, find your next great read in our carefully curated collection.' }}</textarea>
                                <div class="form-text">Description text for featured books section</div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Borrowing Configuration Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-success mb-3"><i class="fas fa-book"></i> Borrowing Configuration</h6>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="borrowing_duration_days" class="form-label">Default Borrowing Period (Days)</label>
                                <input type="number" class="form-control" id="borrowing_duration_days"
                                       name="borrowing_duration_days"
                                       value="{{ $settings['borrowing_duration_days']->value ?? 14 }}"
                                       min="1" max="365" required>
                                <div class="form-text">Standard loan period for books (students can return earlier)</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="max_renewals" class="form-label">Maximum Renewals Allowed</label>
                                <input type="number" class="form-control" id="max_renewals"
                                       name="max_renewals"
                                       value="{{ $settings['max_renewals']->value ?? 2 }}"
                                       min="0" max="10" required>
                                <div class="form-text">How many times a book can be renewed before it must be returned</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="fine_per_day" class="form-label">Overdue Fine Rate (₱ per day)</label>
                                <input type="number" class="form-control" id="fine_per_day"
                                       name="fine_per_day"
                                       value="{{ $settings['fine_per_day']->value ?? 5.00 }}"
                                       min="0" max="1000" step="0.01" required>
                                <div class="form-text">Penalty charged for each day a book is overdue</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="max_books_per_user" class="form-label">Maximum Books Per Student</label>
                                <input type="number" class="form-control" id="max_books_per_user"
                                       name="max_books_per_user"
                                       value="{{ $settings['max_books_per_user']->value ?? 3 }}"
                                       min="1" max="20" required>
                                <div class="form-text">Maximum number of books a student can borrow simultaneously</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="grace_period_days" class="form-label">Grace Period (Days)</label>
                                <input type="number" class="form-control" id="grace_period_days"
                                       name="grace_period_days"
                                       value="{{ $settings['grace_period_days']->value ?? 3 }}"
                                       min="0" max="30" required>
                                <div class="form-text">Days after due date before fines start accruing</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="max_overdue_days" class="form-label">Maximum Overdue Days</label>
                                <input type="number" class="form-control" id="max_overdue_days"
                                       name="max_overdue_days"
                                       value="{{ $settings['max_overdue_days']->value ?? 30 }}"
                                       min="1" max="365" required>
                                <div class="form-text">Maximum days a book can be overdue before special action is required</div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Date and Notification Settings -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-info mb-3"><i class="fas fa-calendar"></i> Date & Notification Settings</h6>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="weekend_due_dates" class="form-label">Weekend Due Dates</label>
                                <select class="form-control" id="weekend_due_dates" name="weekend_due_dates" required>
                                    <option value="allow" {{ ($settings['weekend_due_dates']->value ?? 'move_to_monday') === 'allow' ? 'selected' : '' }}>Allow due dates on weekends</option>
                                    <option value="move_to_monday" {{ ($settings['weekend_due_dates']->value ?? 'move_to_monday') === 'move_to_monday' ? 'selected' : '' }}>Move weekend due dates to Monday</option>
                                    <option value="move_to_friday" {{ ($settings['weekend_due_dates']->value ?? 'move_to_monday') === 'move_to_friday' ? 'selected' : '' }}>Move weekend due dates to Friday</option>
                                </select>
                                <div class="form-text">How to handle due dates that fall on weekends</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="holiday_handling" class="form-label">Holiday Due Dates</label>
                                <select class="form-control" id="holiday_handling" name="holiday_handling" required>
                                    <option value="extend" {{ ($settings['holiday_handling']->value ?? 'extend') === 'extend' ? 'selected' : '' }}>Extend due date past holidays</option>
                                    <option value="strict" {{ ($settings['holiday_handling']->value ?? 'extend') === 'strict' ? 'selected' : '' }}>Keep original due date</option>
                                </select>
                                <div class="form-text">How to handle due dates that fall on school holidays</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="due_date_reminder_days" class="form-label">Due Date Reminder (Days Before)</label>
                                <input type="number" class="form-control" id="due_date_reminder_days"
                                       name="due_date_reminder_days"
                                       value="{{ $settings['due_date_reminder_days']->value ?? 3 }}"
                                       min="1" max="14" required>
                                <div class="form-text">Days before due date to send reminder notifications</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="overdue_notification_days" class="form-label">Overdue Notification Delay (Days)</label>
                                <input type="number" class="form-control" id="overdue_notification_days"
                                       name="overdue_notification_days"
                                       value="{{ $settings['overdue_notification_days']->value ?? 1 }}"
                                       min="0" max="7" required>
                                <div class="form-text">Days after due date to send first overdue notification</div>
                            </div>
                        </div>

                        <hr class="my-4">



                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary" id="saveBtn">
                                <i class="fas fa-save"></i> Save All Settings
                            </button>
                            <button type="button" class="btn btn-outline-secondary" id="resetBtn">
                                <i class="fas fa-undo"></i> Reset to Defaults
                            </button>
                            <form method="POST" action="{{ route('admin.trigger_notifications') }}" class="d-inline" onsubmit="return confirm('Send due date notifications to all users? This will create notifications for books due soon and overdue books.')">
                                @csrf
                                <button type="submit" class="btn btn-info">
                                    <i class="fas fa-bell"></i> Send Notifications
                                </button>
                            </form>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const saveBtn = document.getElementById('saveBtn');

    form.addEventListener('submit', function(e) {
        // Prevent default form submission to handle it manually
        e.preventDefault();

        // Add loading state to button
        saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
        saveBtn.disabled = true;

        // Basic client-side validation
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
                field.classList.add('is-invalid');
            } else {
                field.classList.remove('is-invalid');
            }
        });

        if (!isValid) {
            saveBtn.innerHTML = '<i class="fas fa-save"></i> Save Settings';
            saveBtn.disabled = false;
            alert('Please fill in all required fields.');
            return false;
        }

        // Manually submit the form to prevent duplicate submissions
        form.submit();
    });

    // Reset button functionality
    const resetBtn = document.getElementById('resetBtn');
    if (resetBtn) {
        resetBtn.addEventListener('click', function(e) {
            e.preventDefault();

            if (!confirm('⚠️ WARNING: This will reset ALL system settings to their default values. This action cannot be undone.\n\nAre you sure you want to reset all settings?')) {
                return false;
            }

            // Show loading state
            resetBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Resetting...';
            resetBtn.disabled = true;
            saveBtn.disabled = true;

            // Create and submit reset form
            const resetForm = document.createElement('form');
            resetForm.method = 'POST';
            resetForm.action = '{{ route("admin.settings.reset") }}';
            resetForm.style.display = 'none';

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            resetForm.appendChild(csrfToken);

            document.body.appendChild(resetForm);
            resetForm.submit();
        });
    }

    // Real-time validation feedback
    form.querySelectorAll('input, select').forEach(field => {
        field.addEventListener('blur', function() {
            if (this.hasAttribute('required') && !this.value.trim()) {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
            }
        });

        field.addEventListener('input', function() {
            if (this.value.trim()) {
                this.classList.remove('is-invalid');
            }
        });
    });
});
</script>
@endsection
