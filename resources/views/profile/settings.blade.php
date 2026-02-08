@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="bi bi-gear me-2"></i>Settings
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('profile.settings.update') }}">
                        @csrf
                        @method('PATCH')
                        
                        <!-- User Information Section -->
                        <div class="mb-4">
                            <h5 class="fw-bold mb-3">
                                <i class="bi bi-person me-2"></i>User Information
                            </h5>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Full Name</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" required>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="lrn_number" class="form-label">LRN Number</label>
                                    <input type="text" class="form-control" id="lrn_number" name="lrn_number" value="{{ $user->lrn_number ?? '' }}" placeholder="Enter your LRN number">
                                    <div class="form-text">Learner Reference Number - This will be included in your QR code</div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Account Information Section -->
                        <div class="mb-4">
                            <h5 class="fw-bold mb-3">
                                <i class="bi bi-shield me-2"></i>Account Information
                            </h5>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Role</label>
                                    <input type="text" class="form-control" value="{{ ucfirst($user->role) }}" readonly>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Member Since</label>
                                    <input type="text" class="form-control" value="{{ $user->created_at->format('F d, Y') }}" readonly>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Account Status</label>
                                    <input type="text" class="form-control" value="{{ $user->is_approved ? 'Approved' : 'Pending Approval' }}" readonly>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Total Books Borrowed</label>
                                    <input type="text" class="form-control" value="{{ $totalBorrowed ?? 0 }}" readonly>
                                </div>
                            </div>
                        </div>
                        
                        <!-- QR Code Preview Section -->
                        <div class="mb-4">
                            <h5 class="fw-bold mb-3">
                                <i class="bi bi-qr-code me-2"></i>QR Code Preview
                            </h5>
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                Your QR code will contain the following information:
                            </div>
                            <ul class="list-unstyled">
                                <li><strong>Name:</strong> {{ $user->name }}</li>
                                <li><strong>Email:</strong> {{ $user->email }}</li>
                                <li><strong>LRN Number:</strong> <span id="qr-lrn-preview">{{ $user->lrn_number ?? 'Not set' }}</span></li>
                                <li><strong>Role:</strong> {{ ucfirst($user->role) }}</li>
                            </ul>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('profile.edit') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Back to Profile
                            </a>
                            <div>
                                <button type="reset" class="btn btn-outline-secondary me-2">
                                    <i class="bi bi-arrow-clockwise me-2"></i>Reset
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-lg me-2"></i>Save Changes
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Update QR code preview when LRN number changes
    const lrnInput = document.getElementById('lrn_number');
    const qrLrnPreview = document.getElementById('qr-lrn-preview');
    
    if (lrnInput && qrLrnPreview) {
        lrnInput.addEventListener('input', function() {
            qrLrnPreview.textContent = this.value || 'Not set';
        });
    }
});
</script>
@endsection
