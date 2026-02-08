<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <script>
        function previewImage(input) {
            const preview = document.getElementById('image-preview');
            const previewImg = document.getElementById('preview-img');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    preview.classList.remove('hidden');
                };

                reader.readAsDataURL(input.files[0]);
            } else {
                preview.classList.add('hidden');
            }
        }
    </script>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Account Summary</h5>
                        <div class="row mb-2">
                            <div class="col-md-6"><b>Role:</b> {{ ucfirst($user->role) }}</div>
                            <div class="col-md-6"><b>Member Since:</b> {{ $user->created_at->format('F d, Y') }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6"><b>Total Books Borrowed:</b> {{ $totalBorrowed }}</div>
                            <div class="col-md-6"><b>Outstanding Fines:</b> ₱{{ number_format($totalFine, 2) }}</div>
                        </div>
                        <a href="{{ route('profile.download_data') }}" class="btn btn-outline-primary btn-sm mt-2"><i class="bi bi-download"></i> Download My Data</a>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Recent Borrowing Activity</h5>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th>Book</th>
                                        <th>Borrowed At</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($recentBorrowings as $borrowing)
                                        <tr>
                                            <td>{{ $borrowing->book->title ?? 'N/A' }}</td>
                                            <td>{{ $borrowing->created_at->format('M d, Y') }}</td>
                                            <td>{{ ucfirst($borrowing->status) }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="3" class="text-center">No recent borrowings.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            @if($user->hasQrCode())
            <div class="mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-3">My Library QR Code</h5>
                        <div class="row align-items-center">
                            <div class="col-md-4 text-center">
                                <div id="profile-qr-code"></div>
                            </div>
                            <div class="col-md-8">
                                <p class="text-muted mb-2">Show this QR code to the librarian for quick identification and borrowing.</p>
                                <div class="mb-2">
                                    <span class="badge bg-secondary">Student ID: {{ $user->student_id ?? 'N/A' }}</span>
                                    <span class="badge bg-info ms-1">{{ ucfirst($user->role) }}</span>
                                </div>
                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="window.open('{{ route('profile.qr') }}', '_blank')">
                                    <i class="fas fa-expand me-2"></i>View Full QR Code
                                </button>
                                <button type="button" class="btn btn-success btn-sm ms-2" onclick="window.open('{{ route('profile.mobile_qr') }}', '_blank')">
                                    <i class="fas fa-mobile-alt me-2"></i>Borrowing QR
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form', ['user' => $user])
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form', ['user' => $user])
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form', ['user' => $user])
                </div>
            </div>
        </div>
    </div>

@if($user->hasQrCode())
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Generate QR code data properly escaped
    const userInfo = {
        name: @json($user->name),
        email: @json($user->email),
        role: @json($user->role),
        student_id: @json($user->student_id ?? 'N/A'),
        barcode: @json($user->barcode ?? 'N/A'),
        user_id: {{ $user->id }}
    };

    console.log('Profile QR user data:', userInfo);

    // Generate QR code in profile
    const qrContainer = document.getElementById('profile-qr-code');
    
    if (typeof QRCode !== 'undefined' && qrContainer) {
        new QRCode(qrContainer, {
            text: JSON.stringify(userInfo),
            width: 150,
            height: 150,
            colorDark: "#000000",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.H
        });
    }
});
</script>
@endif
</x-app-layout>
