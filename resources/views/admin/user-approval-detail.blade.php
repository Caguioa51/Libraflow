@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="bi bi-person-badge"></i> User Registration Details
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.users.approvals') }}" class="btn btn-sm btn-secondary">
                            <i class="bi bi-arrow-left"></i> Back to Approvals
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5><i class="bi bi-person"></i> User Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Name:</strong></td>
                                    <td>{{ $user->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td>{{ $user->email }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Role:</strong></td>
                                    <td>
                                        <span class="badge bg-{{ $user->role === 'teacher' ? 'info' : 'primary' }}">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                </tr>
                                
                                @if($user->role === 'student')
                                    <tr>
                                        <td><strong>Grade Level:</strong></td>
                                        <td>{{ $user->grade ?? 'Not specified' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Section:</strong></td>
                                        <td>{{ $user->section ?? 'Not specified' }}</td>
                                    </tr>
                                @elseif($user->role === 'teacher')
                                    <tr>
                                        <td><strong>Department:</strong></td>
                                        <td>{{ ucfirst($user->department ?? 'Not specified') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Employee ID:</strong></td>
                                        <td>{{ $user->employee_id ?? 'Not specified' }}</td>
                                    </tr>
                                @endif
                                
                                <tr>
                                    <td><strong>Registration Date:</strong></td>
                                    <td>{{ $user->created_at->format('M d, Y H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Waiting Time:</strong></td>
                                    <td>
                                        <span class="badge bg-{{ $user->created_at->diffInDays(now()) > 3 ? 'danger' : 'warning' }}">
                                            {{ $user->created_at->diffForHumans() }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        <span class="badge bg-warning">Pending Approval</span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5><i class="bi bi-shield-check"></i> Approval Actions</h5>
                            <div class="d-grid gap-2">
                                <form action="{{ route('admin.users.approve', $user) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-lg"
                                            onclick="return confirm('Are you sure you want to approve this user? This will give them access to the system.')">
                                        <i class="bi bi-check-circle"></i> Approve User
                                    </button>
                                </form>
                                
                                <form action="{{ route('admin.users.reject', $user) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger"
                                            onclick="return confirm('Are you sure you want to reject this user? This will permanently delete their registration.')">
                                        <i class="bi bi-x-circle"></i> Reject & Delete User
                                    </button>
                                </form>
                                
                                <a href="{{ route('admin.users.approvals') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Back to List
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-12">
                            <h5><i class="bi bi-info-circle"></i> Additional Information</h5>
                            <div class="alert alert-info">
                                <h6>Registration Details:</h6>
                                <ul class="mb-0">
                                    <li>User registered on: {{ $user->created_at->format('F j, Y \a\t g:i A') }}</li>
                                    <li>Registration IP: {{ request()->ip() }}</li>
                                    <li>User Agent: {{ request()->userAgent() }}</li>
                                    <li>Days waiting for approval: {{ $user->created_at->diffInDays(now()) }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
