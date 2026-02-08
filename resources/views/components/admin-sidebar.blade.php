<!-- Mobile Admin Sidebar Toggle -->
<button class="btn btn-dark d-lg-none position-fixed" 
        style="top: 70px; left: 10px; z-index: 1026; border-radius: 0.375rem; box-shadow: 0 2px 4px rgba(0,0,0,0.2);"
        onclick="toggleAdminSidebar()">
    <i class="bi bi-list"></i>
</button>

<div class="admin-sidebar bg-dark text-white" id="adminSidebar" style="width: 250px; min-height: 100vh; position: fixed; top: 56px; left: 0; z-index: 1024; overflow-y: auto;">
    <div class="p-3">
        <div class="d-flex align-items-center mb-4">
            <i class="bi bi-speedometer2 me-2"></i>
            <span class="fw-bold">Admin Panel</span>
        </div>
        
        <ul class="nav nav-pills flex-column">
            <li class="nav-item mb-2">
                <a class="nav-link text-white {{ request()->routeIs('dashboard') ? 'bg-primary' : '' }}" 
                   href="{{ route('dashboard') }}">
                    <i class="bi bi-house-door me-2"></i>Dashboard
                </a>
            </li>

            <li class="nav-item mb-2">
                <a class="nav-link text-white {{ request()->routeIs('books.*') ? 'bg-primary' : '' }}" 
                   href="{{ route('books.index') }}">
                    <i class="bi bi-book me-2"></i>Books
                </a>
            </li>

            <li class="nav-item mb-2">
                <a class="nav-link text-white {{ request()->routeIs('admin.announcements.*') ? 'bg-primary' : '' }}" 
                   href="{{ route('admin.announcements.index') }}">
                    <i class="bi bi-megaphone me-2"></i>Announcements
                </a>
            </li>

            <li class="nav-item mb-2">
                <a class="nav-link text-white d-flex justify-content-between align-items-center {{ request()->routeIs('admin.users.approvals') ? 'bg-primary' : '' }}" 
                   href="{{ route('admin.users.approvals') }}">
                    <div>
                        <i class="bi bi-person-check me-2"></i>Approvals
                    </div>
                    @php
                        $pendingCount = \App\Models\User::where('is_approved', false)->count();
                    @endphp
                    @if($pendingCount > 0)
                        <span class="badge bg-warning">{{ $pendingCount }}</span>
                    @endif
                </a>
            </li>

            <li class="nav-item mb-2">
                <a class="nav-link text-white {{ request()->routeIs('borrowings.borrowed') ? 'bg-primary' : '' }}" 
                   href="{{ route('borrowings.borrowed') }}">
                    <i class="bi bi-journal-bookmark me-2"></i>Borrowed
                </a>
            </li>

            <li class="nav-item mb-2">
                <a class="nav-link text-white {{ request()->routeIs('admin.qr_scanner') ? 'bg-primary' : '' }}" 
                   href="{{ route('admin.qr_scanner') }}">
                    <i class="bi bi-qr-code-scan me-2"></i>QR Scanner
                </a>
            </li>

            <li class="nav-item mb-2">
                <a class="nav-link text-white {{ request()->routeIs('admin.users.*') ? 'bg-primary' : '' }}" 
                   href="{{ route('admin.users.index') }}">
                    <i class="bi bi-people me-2"></i>Users
                </a>
            </li>
        </ul>
    </div>
</div>

<style>
    .admin-sidebar {
        box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        border-top: 1px solid #343a40; /* Match navbar border */
    }
    .admin-sidebar .nav-link {
        color: #dee2e6;
        border-radius: 0.25rem;
        padding: 0.5rem 1rem;
        transition: all 0.2s;
    }
    .admin-sidebar .nav-link:hover {
        background-color: rgba(255,255,255,0.1);
        color: white;
    }
    .admin-sidebar .nav-link i {
        width: 20px;
        text-align: center;
    }
    
    /* Ensure no gap between navbar and sidebar */
    .admin-sidebar::before {
        content: '';
        position: absolute;
        top: -56px;
        left: 0;
        right: 0;
        height: 56px;
        background: #343a40;
        z-index: -1;
    }

    /* Mobile sidebar overlay */
    .sidebar-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        z-index: 1024;
        display: none;
    }

    .sidebar-overlay.show {
        display: block;
    }

    /* Mobile sidebar animations */
    @media (max-width: 768px) {
        .admin-sidebar {
            transform: translateX(-100%);
            transition: transform 0.3s ease;
        }
        
        .admin-sidebar.show {
            transform: translateX(0);
        }
    }
</style>

<!-- Mobile Sidebar Overlay -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleAdminSidebar()"></div>

<script>
function toggleAdminSidebar() {
    const sidebar = document.getElementById('adminSidebar');
    const overlay = document.getElementById('sidebarOverlay');
    
    sidebar.classList.toggle('show');
    overlay.classList.toggle('show');
    
    // Prevent body scroll when sidebar is open on mobile
    if (sidebar.classList.contains('show')) {
        document.body.style.overflow = 'hidden';
    } else {
        document.body.style.overflow = '';
    }
}

// Close sidebar when clicking outside on mobile
document.addEventListener('click', function(event) {
    const sidebar = document.getElementById('adminSidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const toggleBtn = event.target.closest('[onclick="toggleAdminSidebar()"]');
    
    if (window.innerWidth <= 768 && 
        !sidebar.contains(event.target) && 
        !toggleBtn && 
        sidebar.classList.contains('show')) {
        toggleAdminSidebar();
    }
});

// Close sidebar on window resize if mobile
window.addEventListener('resize', function() {
    const sidebar = document.getElementById('adminSidebar');
    const overlay = document.getElementById('sidebarOverlay');
    
    if (window.innerWidth > 768) {
        sidebar.classList.remove('show');
        overlay.classList.remove('show');
        document.body.style.overflow = '';
    }
});
</script>
