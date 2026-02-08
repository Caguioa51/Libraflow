@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1">User Management</h1>
                <p class="text-muted mb-0">Manage and monitor all system users</p>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row g-3 mb-4">
    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
        <div class="card text-white bg-primary">
            <div class="card-body text-center">
                <i class="fas fa-users fa-2x mb-2"></i>
                <h5 class="card-title">{{ number_format($stats['total']) }}</h5>
                <p class="card-text small">Total Users</p>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
        <div class="card text-white bg-info">
            <div class="card-body text-center">
                <i class="fas fa-graduation-cap fa-2x mb-2"></i>
                <h5 class="card-title">{{ number_format($stats['students']) }}</h5>
                <p class="card-text small">Students</p>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
        <div class="card text-white bg-warning">
            <div class="card-body text-center">
                <i class="fas fa-chalkboard-teacher fa-2x mb-2"></i>
                <h5 class="card-title">{{ number_format($stats['teachers']) }}</h5>
                <p class="card-text small">Teachers</p>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
        <div class="card text-white bg-danger">
            <div class="card-body text-center">
                <i class="fas fa-user-shield fa-2x mb-2"></i>
                <h5 class="card-title">{{ number_format($stats['admins']) }}</h5>
                <p class="card-text small">Administrators</p>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
        <div class="card text-white bg-success">
            <div class="card-body text-center">
                <i class="fas fa-book-open fa-2x mb-2"></i>
                <h5 class="card-title">{{ number_format($stats['active_borrowers']) }}</h5>
                <p class="card-text small">Active Borrowers</p>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
        <div class="card text-white bg-secondary">
            <div class="card-body text-center">
                <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                <h5 class="card-title">{{ number_format($stats['overdue_borrowers']) }}</h5>
                <p class="card-text small">Overdue Users</p>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
        <div class="card text-white bg-light">
            <div class="card-body text-center">
                <i class="fas fa-book fa-2x mb-2 text-success"></i>
                <h5 class="card-title text-dark">{{ number_format($stats['available_books']) }}</h5>
                <p class="card-text small text-dark">Available Books</p>
            </div>
        </div>
    </div>
</div>

<!-- Filters and Search -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Filters & Search</h5>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-lg-4 col-md-6">
                <label class="form-label">Search Users</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Name, email, or student ID...">
                </div>
            </div>
            <div class="col-lg-2 col-md-3">
                <label class="form-label">Role</label>
                <select class="form-select" name="role">
                    <option value="">All Roles</option>
                    <option value="student" {{ request('role') === 'student' ? 'selected' : '' }}>Student</option>
                    <option value="teacher" {{ request('role') === 'teacher' ? 'selected' : '' }}>Teacher</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </div>
            <div class="col-lg-2 col-md-3">
                <label class="form-label">Status</label>
                <select class="form-select" name="status">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="overdue" {{ request('status') === 'overdue' ? 'selected' : '' }}>Overdue</option>
                </select>
            </div>
            <div class="col-lg-2 col-md-6">
                <label class="form-label">Sort By</label>
                <select class="form-select" name="sort">
                    <option value="latest" {{ request('sort', 'latest') === 'latest' ? 'selected' : '' }}>
                        <i class="fas fa-clock me-1"></i>Recently Updated
                    </option>
                    <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>
                        <i class="fas fa-calendar-plus me-1"></i>Newest Members
                    </option>
                    <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>
                        <i class="fas fa-calendar-minus me-1"></i>Oldest Members
                    </option>
                    <option value="name_asc" {{ request('sort') === 'name_asc' ? 'selected' : '' }}>
                        <i class="fas fa-sort-alpha-down me-1"></i>Name A-Z
                    </option>
                    <option value="name_desc" {{ request('sort') === 'name_desc' ? 'selected' : '' }}>
                        <i class="fas fa-sort-alpha-up me-1"></i>Name Z-A
                    </option>
                </select>
            </div>
            <div class="col-lg-2 col-md-6">
                <label class="form-label">&nbsp;</label>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-1"></i>Search
                    </button>
                </div>
            </div>
        </form>

        @if(request()->hasAny(['search', 'role', 'status', 'sort']) && request('sort') !== 'latest')
        <div class="mt-3">
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-times me-1"></i>Clear Filters
            </a>
            <span class="ms-3 text-muted">
                <strong>{{ $users->total() }}</strong> users found
            </span>
        </div>
        @endif
    </div>
</div>

<!-- Users Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-table me-2"></i>Registered Users</h5>
        <span class="badge bg-primary">{{ $users->total() }} total users</span>
    </div>
    <div class="card-body">
        @if($users->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>User</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Activity</th>
                            <th>Joined</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $users->firstItem() + $loop->index }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($user->profile_photo)
                                            <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}"
                                                 class="rounded-circle me-2" width="32" height="32"
                                                 onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=667eea&color=fff&size=32';">
                                        @else
                                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2"
                                                 style="width: 32px; height: 32px; font-size: 14px; font-weight: bold;">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <div class="fw-bold">{{ $user->name }}</div>
                                            <small class="text-muted">{{ $user->student_id ?? 'No ID' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>{{ $user->email }}</div>
                                    @if($user->isTeacher())
                                        @if($user->employee_id)
                                            <small class="text-muted"><i class="fas fa-id-badge me-1"></i>{{ $user->employee_id }}</small>
                                        @else
                                            <small class="text-muted"><i class="fas fa-times me-1"></i>No Employee ID</small>
                                        @endif
                                    @elseif($user->isStudent())
                                        @if($user->lrn_number)
                                            <small class="text-muted"><i class="fas fa-id-card me-1"></i>{{ $user->lrn_number }}</small>
                                        @else
                                            <small class="text-muted"><i class="fas fa-times me-1"></i>No LRN</small>
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    @if($user->role === 'admin')
                                        <span class="badge bg-danger"><i class="fas fa-user-shield me-1"></i>Admin</span>
                                    @elseif($user->role === 'teacher')
                                        <span class="badge bg-warning"><i class="fas fa-chalkboard-teacher me-1"></i>Teacher</span>
                                    @else
                                        <span class="badge bg-info"><i class="fas fa-graduation-cap me-1"></i>Student</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $activeBorrowings = $user->borrowings->count();
                                        $overdueCount = $user->borrowings->where('due_date', '<', now())->count();
                                    @endphp
                                    @if($overdueCount > 0)
                                        <span class="badge bg-danger"><i class="fas fa-exclamation-triangle me-1"></i>Overdue</span>
                                    @elseif($activeBorrowings > 0)
                                        <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <strong>{{ $user->borrowings->count() }}</strong><br>
                                    <small class="text-muted">books</small>
                                </td>
                                <td>
                                    <div>{{ $user->created_at->format('M d, Y') }}</div>
                                    <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('admin.users.view_history', $user->id) }}" class="btn btn-outline-info" title="View History">
                                            <i class="fas fa-history"></i> <span class="d-none d-md-inline">History</span>
                                        </a>
                                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-outline-warning" title="Edit User">
                                            <i class="fas fa-edit"></i> <span class="d-none d-md-inline">Edit</span>
                                        </a>
                                        <button type="button" class="btn btn-outline-danger" title="Delete User" onclick="confirmUserDeletion('{{ addslashes($user->name) }}', '{{ $user->email }}', {{ $user->borrowings->count() }}, {{ $user->borrowings->where('due_date', '<', now())->count() }}, '{{ route('admin.users.destroy', $user->id) }}')">
                                            <i class="fas fa-trash"></i> <span class="d-none d-md-inline">Delete</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} users
                </div>
                <div>{{ $users->links() }}</div>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-users fa-4x text-muted mb-3"></i>
                <h4>No users found</h4>
                <p class="text-muted">There are no users matching your search criteria.</p>
            </div>
        @endif
    </div>
</div>
@endsection

<script>
// Enhanced user deletion confirmation
function confirmUserDeletion(userName, userEmail, totalBorrowings, overdueCount, deleteUrl) {
    // Create a custom confirmation modal
    const modal = document.createElement('div');
    modal.className = 'custom-confirm-modal';
    modal.innerHTML = `
        <div class="modal-overlay">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title fw-bold">
                        <i class="fas fa-user-times me-2"></i>⚠️ DELETE USER
                    </h5>
                    <button type="button" class="btn-close btn-close-white" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <div class="bg-danger bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="fas fa-user-times text-danger fs-1"></i>
                        </div>
                        <h4 class="text-danger mb-1">Delete User Account?</h4>
                        <h5 class="text-primary mb-3">"${userName}"</h5>
                        <div class="text-muted mb-4">
                            <strong>Email:</strong> ${userEmail}<br>
                            <strong>Warning:</strong> This action cannot be undone. Deleting this user will affect all associated data.
                        </p>
                    </div>

                    <!-- Impact Assessment -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="card border-warning h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-book fs-2 text-warning mb-2"></i>
                                    <h5 class="card-title text-warning mb-1">${totalBorrowings}</h5>
                                    <p class="card-text small mb-0">Borrowing Records</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-danger h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-exclamation-triangle fs-2 text-danger mb-2"></i>
                                    <h5 class="card-title text-danger mb-1">${overdueCount}</h5>
                                    <p class="card-text small mb-0">Overdue Books</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Consequences List -->
                    <div class="alert alert-danger">
                        <h6 class="fw-bold mb-2">
                            <i class="fas fa-list-ul me-2"></i>What Will Happen:
                        </h6>
                        <ul class="mb-0 small">
                            <li><strong>Borrowing History:</strong> All ${totalBorrowings} borrowing records will be permanently deleted</li>
                            <li><strong>Reservations:</strong> Any active book reservations will be cancelled</li>
                            <li><strong>Fines:</strong> All outstanding fines and penalties will be lost</li>
                            <li><strong>Access:</strong> User will lose access to the library system</li>
                            <li><strong>Data:</strong> Personal information, RFID data, and account settings will be removed</li>
                        </ul>
                    </div>

                    <!-- Security Warning -->
                    <div class="alert alert-warning">
                        <h6 class="fw-bold mb-2">
                            <i class="fas fa-shield-alt me-2"></i>Security Consideration:
                        </h6>
                        <p class="mb-0 small">
                            This action is irreversible. If this user has administrative privileges, ensure another admin can take over their responsibilities.
                        </p>
                    </div>

                    <!-- Alternative Actions -->
                    <div class="alert alert-info">
                        <h6 class="fw-bold mb-2">
                            <i class="fas fa-lightbulb me-2"></i>Alternatives:
                        </h6>
                        <p class="mb-0 small">
                            Consider <strong>deactivating the account</strong> instead, or
                            <strong>changing the user's role</strong> to prevent access without deleting data.
                        </p>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary btn-lg px-4" id="cancelBtn">
                        <i class="fas fa-arrow-left me-2"></i>Keep User Account
                    </button>
                    <button type="button" class="btn btn-danger btn-lg px-4" id="confirmBtn">
                        <i class="fas fa-trash me-2"></i>Delete Permanently
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

</html>
