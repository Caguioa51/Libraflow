@extends('layouts.app')

@php
use App\Models\User;
@endphp

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">
                        <i class="bi bi-person-check"></i> User Registration Approvals
                    </h3>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-warning me-2" id="pending-count">
                            {{ $pendingUsers->count() }} Pending
                        </span>
                        <button class="btn btn-sm btn-outline-primary" onclick="refreshStats()">
                            <i class="bi bi-arrow-clockwise"></i> Refresh
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    
                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Pending Approval</h5>
                                    <h3>{{ $pendingUsers->count() }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Total Approved</h5>
                                    <h3>{{ User::where('is_approved', true)->count() }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Total Users</h5>
                                    <h3>{{ User::count() }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Approval Rate</h5>
                                    <h3>{{ User::count() > 0 ? round((User::where('is_approved', true)->count() / User::count()) * 100, 1) : 0 }}%</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pending Users Section -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4><i class="bi bi-clock-history"></i> Pending Approvals</h4>
                            @if($pendingUsers->count() > 0)
                                <div>
                                    <form id="bulk-approve-form" method="POST" action="{{ route('admin.users.bulk-approve') }}" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="user_ids" id="bulk-approve-input">
                                        <button type="submit" class="btn btn-success btn-sm me-2" onclick="return prepareBulkApprove(event)">
                                            <i class="bi bi-check-all"></i> Approve Selected
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.users.bulk-reject') }}" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="user_ids" id="bulk-reject-input">
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="prepareBulkReject(event)">
                                            <i class="bi bi-x-circle"></i> Reject Selected
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>

                        @if($pendingUsers->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" id="select-all" onchange="toggleSelectAll()"></th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Registration Date</th>
                                            <th>Waiting Time</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($pendingUsers as $user)
                                            <tr data-user-id="{{ $user->id }}">
                                                <td><input type="checkbox" class="user-checkbox" value="{{ $user->id }}"></td>
                                                <td>
                                                    <strong>{{ $user->name }}</strong>
                                                    <br><small class="text-muted">ID: {{ $user->id }}</small>
                                                </td>
                                                <td>{{ $user->email }}</td>
                                                <td>{{ \Carbon\Carbon::parse($user->created_at)->format('M d, Y H:i') }}</td>
                                                <td>
                                                    <span class="badge bg-{{ \Carbon\Carbon::parse($user->created_at)->diffInDays(now()) > 3 ? 'danger' : 'warning' }}">
                                                        {{ \Carbon\Carbon::parse($user->created_at)->diffForHumans() }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-outline-primary">
                                                            <i class="bi bi-eye"></i> View
                                                        </a>
                                                        <form action="{{ route('admin.users.approve', $user) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-success"
                                                                    onclick="return confirm('Approve this user?')">
                                                                <i class="bi bi-check-circle"></i> Approve
                                                            </button>
                                                        </form>
                                                        <form action="{{ route('admin.users.reject', $user) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger"
                                                                    onclick="return confirm('Reject and remove this user?')">
                                                                <i class="bi bi-x-circle"></i> Reject
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-success text-center">
                                <i class="bi bi-check-circle"></i> No pending user registrations. All caught up!
                            </div>
                        @endif
                    </div>

                    <!-- Recently Approved Section -->
                    @if($recentlyApproved->count() > 0)
                        <div class="mb-4">
                            <h4><i class="bi bi-check-circle"></i> Recently Approved</h4>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Approved By</th>
                                            <th>Approval Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentlyApproved as $user)
                                            <tr>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>
                                                    @if($user->approvedBy)
                                                        {{ $user->approvedBy->name }}
                                                    @else
                                                        <span class="text-muted">System</span>
                                                    @endif
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($user->approved_at)->format('M d, Y H:i') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleSelectAll() {
    const selectAll = document.getElementById('select-all');
    const checkboxes = document.querySelectorAll('.user-checkbox');
    checkboxes.forEach(checkbox => checkbox.checked = selectAll.checked);
}

function prepareBulkApprove(event) {
    event.preventDefault();
    
    const selected = [];
    document.querySelectorAll('.user-checkbox:checked').forEach(checkbox => {
        selected.push(checkbox.value);
    });

    if (selected.length === 0) {
        showAlert('Please select at least one user to approve.', 'danger');
        return false;
    }
    
    const form = document.getElementById('bulk-approve-form');
    const formData = new FormData(form);
    
    // Send user IDs as an array
    selected.forEach((id, index) => {
        formData.append(`user_ids[${index}]`, id);
    });
    
    // Disable the button and show loading state
    const button = event.target;
    const originalText = button.innerHTML;
    button.disabled = true;
    button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Approving...';
    
    // Submit via AJAX
    fetch(form.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.redirect) {
            // If there's a redirect, let it handle the message
            window.location.href = data.redirect;
        } else if (data.success) {
            showAlert(data.success, 'success');
            // Remove the approved users from the UI
            selected.forEach(id => {
                const checkbox = document.querySelector(`.user-checkbox[value="${id}"]`);
                if (checkbox) {
                    const row = checkbox.closest('tr');
                    if (row) row.remove();
                }
            });
            // Update the pending count
            updatePendingCount(-selected.length);
            
            // If no more pending users, show the success message and refresh after a delay
            const remainingRows = document.querySelectorAll('tbody tr[data-user-id]');
            if (remainingRows.length === 0) {
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('An error occurred while processing your request.', 'danger');
    })
    .finally(() => {
        // Re-enable the button
        button.disabled = false;
        button.innerHTML = originalText;
    });
    
    return false;
}

// Track active alerts to prevent duplicates
const activeAlerts = new Set();

function showAlert(message, type = 'success') {
    // Create a unique key for this alert
    const alertKey = `${type}:${message}`;
    
    // Don't show duplicate alerts
    if (activeAlerts.has(alertKey)) {
        return;
    }
    
    // Add to active alerts
    activeAlerts.add(alertKey);
    
    // Remove any existing alerts of the same type
    document.querySelectorAll(`.alert-${type}`).forEach(alert => {
        const bsAlert = bootstrap.Alert.getInstance(alert);
        if (bsAlert) {
            bsAlert.close();
        } else {
            alert.remove();
        }
    });
    
    // Create and show new alert
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.role = 'alert';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    // Handle alert close event
    alertDiv.addEventListener('closed.bs.alert', () => {
        activeAlerts.delete(alertKey);
    });
    
    // Insert the alert after the page header
    const header = document.querySelector('.card-header');
    if (header && header.nextElementSibling) {
        header.parentNode.insertBefore(alertDiv, header.nextElementSibling);
    } else if (header) {
        header.parentNode.appendChild(alertDiv);
    } else {
        document.body.prepend(alertDiv);
    }
    
    // Initialize Bootstrap alert
    const bsAlert = new bootstrap.Alert(alertDiv);
    
    // Auto-remove the alert after 5 seconds
    setTimeout(() => {
        if (alertDiv.parentNode) { // Check if element is still in the DOM
            bsAlert.close();
        }
    }, 5000);
}

function updatePendingCount(change) {
    const countElement = document.getElementById('pending-count');
    if (countElement) {
        const currentCount = parseInt(countElement.textContent) || 0;
        const newCount = Math.max(0, currentCount + change);
        countElement.textContent = `${newCount} Pending`;
    }
}

function prepareBulkReject(event) {
    event.preventDefault();
    
    const selected = [];
    document.querySelectorAll('.user-checkbox:checked').forEach(checkbox => {
        selected.push(checkbox.value);
    });

    if (selected.length === 0) {
        showAlert('Please select at least one user to reject.', 'danger');
        return false;
    }
    
    if (!confirm(`Are you sure you want to reject and remove ${selected.length} user(s)?`)) {
        return false;
    }
    
    const form = event.target.closest('form');
    const formData = new FormData(form);
    
    // Send user IDs as an array
    selected.forEach((id, index) => {
        formData.append(`user_ids[${index}]`, id);
    });
    
    // Disable the button and show loading state
    const button = event.target;
    const originalText = button.innerHTML;
    button.disabled = true;
    button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Rejecting...';
    
    // Submit via AJAX
    fetch(form.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.redirect) {
            window.location.href = data.redirect;
        } else if (data.success) {
            showAlert(data.success, 'success');
            // Remove the rejected users from the UI
            selected.forEach(id => {
                const checkbox = document.querySelector(`.user-checkbox[value="${id}"]`);
                if (checkbox) {
                    const row = checkbox.closest('tr');
                    if (row) row.remove();
                }
            });
            // Update the pending count
            updatePendingCount(-selected.length);
            
            // If no more pending users, refresh after a delay
            const remainingRows = document.querySelectorAll('tbody tr[data-user-id]');
            if (remainingRows.length === 0) {
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('An error occurred while processing your request.', 'danger');
    })
    .finally(() => {
        // Re-enable the button
        button.disabled = false;
        button.innerHTML = originalText;
    });
    
    return false;
}

function getSelectedIds() {
    const checkboxes = document.querySelectorAll('.user-checkbox:checked');
    return Array.from(checkboxes).map(cb => cb.value);
}

function refreshStats() {
    fetch('{{ route("admin.users.stats") }}')
        .then(response => response.json())
        .then(data => {
            // Update stats cards if needed
            console.log('Stats refreshed:', data);
        });
}

// Auto-refresh every 30 seconds
setInterval(refreshStats, 30000);

// Handle server-side session messages
document.addEventListener('DOMContentLoaded', function() {
    // Check for server-side success messages
    @if(session('success'))
        showAlert('{{ session('success') }}', 'success');
    @endif
    
    // Check for server-side error messages
    @if(session('error'))
        showAlert('{{ session('error') }}', 'danger');
    @endif
});
</script>
@endsection
