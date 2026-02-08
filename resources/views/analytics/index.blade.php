@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 mb-2">
                <i class="fas fa-chart-line text-primary me-3"></i>
                Library Analytics Dashboard
            </h1>
            <p class="text-muted mb-0">Monitor library performance and generate reports</p>
        </div>
        <div class="d-flex gap-3">
            <!-- Enhanced Basic Reports Button - Most Prominent -->
            <a href="{{ route('borrowings.report') }}" class="btn btn-success btn-lg px-5 py-3 shadow-lg position-relative"
               style="transform: scale(1.05); border: 3px solid rgba(255,255,255,0.3); background: linear-gradient(135deg, #28a745 0%, #20c997 50%, #28a745 100%);"
               data-bs-toggle="tooltip" data-bs-placement="bottom" title="🚀 Generate comprehensive library reports - Most popular feature!">
                <div class="d-flex align-items-center">
                    <div class="bg-white bg-opacity-30 rounded-circle d-flex align-items-center justify-content-center me-3 pulse-icon" style="width: 40px; height: 40px;">
                        <i class="fas fa-chart-bar text-white fs-4"></i>
                    </div>
                    <div>
                        <span class="fw-bold text-white fs-5">📊 BASIC REPORTS</span>
                        <div class="small text-white-75 fw-semibold">Generate Library Reports</div>
                    </div>
                    <i class="fas fa-external-link-alt ms-4 text-white fs-6"></i>
                </div>
                <!-- Enhanced Popularity indicator -->
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark fw-bold px-3 py-2 shadow-sm" style="font-size: 0.75rem;">
                    <i class="fas fa-fire me-1"></i>HOT
                    <span class="visually-hidden">Most popular feature</span>
                </span>
                <!-- Glow effect -->
                <div class="position-absolute top-50 start-50 translate-middle" style="width: 120%; height: 120%; background: radial-gradient(circle, rgba(40,167,69,0.3) 0%, transparent 70%); border-radius: 50%; z-index: -1;"></div>
            </a>

            <!-- Enhanced Update Fines Button -->
            <a href="{{ route('borrowings.update_fines') }}" class="btn btn-warning btn-lg px-4 shadow-sm"
               onclick="return confirm('Update fines for all overdue books? This will recalculate fines based on current due dates.')"
               data-bs-toggle="tooltip" data-bs-placement="bottom" title="Recalculate and update all overdue fines">
                <i class="fas fa-calculator me-2"></i>
                <span class="fw-semibold">💰 Update Fines</span>
            </a>
        </div>
    </div>

    <!-- Key Metrics -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Books</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalBooks) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-book fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2" style="cursor: pointer;" onclick="window.location.href='{{ route('books.index', ['status' => 'available']) }}'">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Available Books</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($availableBooks) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2" style="cursor: pointer;" onclick="window.location.href='{{ route('borrowings.report', ['status' => 'borrowed']) }}'">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Active Borrowings</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($activeBorrowings) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-hand-holding fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Overdue Books</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($overdueBorrowings) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Fine Statistics -->
    <div class="row mb-4">
        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Total Fines</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">₱{{ number_format($totalFines, 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">Unpaid Fines</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">₱{{ number_format($unpaidFines, 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-credit-card fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Popular Books -->
        <div class="col-xl-6 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Most Popular Books</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Book Title</th>
                                    <th>Author</th>
                                    <th>Borrowings</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($popularBooks as $book)
                                <tr>
                                    <td>{{ $book->title }}</td>
                                    <td>{{ $book->author->name }}</td>
                                    <td><span class="badge badge-primary">{{ $book->borrowings_count }}</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Popular Categories -->
        <div class="col-xl-6 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-success">Popular Categories</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Category</th>
                                    <th>Borrowings</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($popularCategories as $category)
                                <tr>
                                    <td>{{ $category->name }}</td>
                                    <td><span class="badge badge-success">{{ $category->borrowings_count }}</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Overdue Books -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-warning">Overdue Books</h6>
                </div>
                <div class="card-body">
                    @if($overdueDetails->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Book</th>
                                        <th>Borrower</th>
                                        <th>Due Date</th>
                                        <th>Days Overdue</th>
                                        <th>Fine</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($overdueDetails as $borrowing)
                                    <tr>
                                        <td>{{ $borrowing->book->title }}</td>
                                        <td>{{ $borrowing->user->name }}</td>
                                        <td>{{ $borrowing->due_date->format('M d, Y') }}</td>
                                        <td><span class="badge badge-danger">{{ now()->diffInDays($borrowing->due_date) }}</span></td>
                                        <td>₱{{ number_format($borrowing->calculateFine(), 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No overdue books at the moment.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-info">Recent Activity</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Book</th>
                                    <th>Borrower</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentBorrowings as $borrowing)
                                <tr>
                                    <td>{{ Str::limit($borrowing->book->title, 20) }}</td>
                                    <td>{{ Str::limit($borrowing->user->name, 15) }}</td>
                                    <td>{{ $borrowing->created_at->format('M d') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row">
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Monthly Borrowing Trends</h6>
                </div>
                <div class="card-body">
                    <canvas id="monthlyChart" width="400" height="100"></canvas>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-success">Daily Activity (Last 30 Days)</h6>
                </div>
                <div class="card-body">
                    <canvas id="dailyChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Initialize tooltips when document is ready
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
<script>
// Monthly borrowing trends chart
const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
const monthlyData = @json($monthlyBorrowings);
const monthlyLabels = monthlyData.map(item => {
    const date = new Date(item.year, item.month - 1);
    return date.toLocaleDateString('en-US', { month: 'short', year: 'numeric' });
});
const monthlyCounts = monthlyData.map(item => item.count);

new Chart(monthlyCtx, {
    type: 'line',
    data: {
        labels: monthlyLabels,
        datasets: [{
            label: 'Borrowings',
            data: monthlyCounts,
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Daily activity chart
const dailyCtx = document.getElementById('dailyChart').getContext('2d');
const dailyData = @json($dailyBorrowings);
const dailyLabels = dailyData.map(item => {
    const date = new Date(item.date);
    return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
});
const dailyCounts = dailyData.map(item => item.count);

new Chart(dailyCtx, {
    type: 'bar',
    data: {
        labels: dailyLabels,
        datasets: [{
            label: 'Daily Borrowings',
            data: dailyCounts,
            backgroundColor: 'rgba(54, 162, 235, 0.8)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>
@endpush

<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}
.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}
.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}
.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}
.border-left-danger {
    border-left: 0.25rem solid #e74a3b !important;
}
.border-left-secondary {
    border-left: 0.25rem solid #858796 !important;
}

/* Enhanced Basic Reports Button Styles */
.btn-success:hover {
    background: linear-gradient(135deg, #218838 0%, #17a2b8 50%, #218838 100%) !important;
    transform: scale(1.08) !important;
    box-shadow: 0 8px 25px rgba(40, 167, 69, 0.4) !important;
}

.pulse-icon {
    animation: pulse-glow 2s ease-in-out infinite;
}

@keyframes pulse-glow {
    0%, 100% {
        box-shadow: 0 0 5px rgba(255, 255, 255, 0.5);
        transform: scale(1);
    }
    50% {
        box-shadow: 0 0 20px rgba(255, 255, 255, 0.8), 0 0 30px rgba(40, 167, 69, 0.6);
        transform: scale(1.05);
    }
}

/* Make the reports button stand out even more */
.btn-success {
    transition: all 0.3s ease !important;
    position: relative !important;
    overflow: visible !important;
}

.btn-success::before {
    content: '';
    position: absolute;
    top: -2px;
    left: -2px;
    right: -2px;
    bottom: -2px;
    background: linear-gradient(45deg, #28a745, #20c997, #28a745);
    border-radius: inherit;
    z-index: -1;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.btn-success:hover::before {
    opacity: 0.8;
}
</style>
@endsection
