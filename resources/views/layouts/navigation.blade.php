<nav class="navbar navbar-expand-lg navbar-dark bg-teal border-bottom border-teal-700 py-0 fixed-top" style="z-index: 1040; padding-left: 0; height: 56px; background-color: #0d9488;">
    <div class="container-fluid gx-0 h-100">
        <div class="d-flex align-items-center h-100">
            <!-- Mobile Menu Toggle for All Users -->
            @auth
                @if(!request()->routeIs(['welcome', 'login', 'register']))
                    @if(auth()->user()->isAdmin())
                        <button class="btn btn-outline-light btn-sm d-lg-none border-0 text-white ms-2 me-0" type="button" id="adminDrawerToggle" data-bs-toggle="offcanvas" data-bs-target="#adminNavOffcanvas" aria-controls="adminNavOffcanvas">
                            <i class="bi bi-list fs-4"></i>
                        </button>
                    @else
                        <button class="btn btn-outline-light btn-sm d-lg-none border-0 text-white ms-2 me-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#userMobileNavOffcanvas" aria-controls="userMobileNavOffcanvas">
                            <i class="bi bi-list fs-4"></i>
                        </button>
                    @endif
                @endif
            @endauth
            
            <!-- Left side - LibraFlow logo -->
            <a class="navbar-brand libraflow-logo text-white fw-bold mb-0 d-flex align-items-center h-100 py-0 ps-0 pe-3" href="{{ route('dashboard') }}" style="font-size: 1.1rem; cursor: pointer; white-space: nowrap; overflow: visible; text-overflow: clip; max-width: none; margin-right: 0; position: relative;">
                <i class="bi bi-book me-2" style="font-size: 1.3rem;"></i>LibraFlow
            </a>
            <style>
                /* Default mobile view */
                .libraflow-logo {
                    left: 1px;
                    padding-left: 15px;
                }
                /* Web view - applies when viewport is 992px or wider */
                @media (min-width: 992px) {
                    .libraflow-logo {
                        left: -210px;
                        padding-left: 15px;
                    }
                }
            </style>
        </div>

        <!-- Right side - User Navigation and Actions -->
        <div class="d-flex align-items-center ms-auto">
            <!-- Mobile menu toggle - Hidden on welcome, login, and register pages -->
            @if(!request()->routeIs(['welcome', 'login', 'register']))
            <button class="navbar-toggler d-lg-none border-0 text-white me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileNavOffcanvas" aria-controls="mobileNavOffcanvas">
            </button>
            @endif
            
            <ul class="navbar-nav flex-row mb-0 d-none d-lg-flex">
                @auth
                    @if(!auth()->user()->isAdmin())
                        <!-- Books for non-admin users -->
                        <li class="nav-item me-3">
                            <a class="nav-link text-white px-2 py-1 {{ request()->routeIs('books.index') ? 'rounded' : '' }}" style="{{ request()->routeIs('books.index') ? 'background-color: #0b7a6f !important;' : '' }}"
                               href="{{ route('books.index') }}">
                                <i class="bi bi-book me-1"></i>Books
                            </a>
                        </li>
                        
                        <li class="nav-item me-3">
                            <a class="nav-link text-white px-2 py-1 {{ request()->routeIs('profile.qr') ? 'rounded' : '' }}" style="{{ request()->routeIs('profile.qr') ? 'background-color: #0b7a6f !important;' : '' }}"
                               href="{{ route('profile.qr') }}">
                                <i class="bi bi-qr-code me-1"></i>My QR Code
                            </a>
                        </li>
                        <li class="nav-item me-3">
                            <a class="nav-link text-white px-2 py-1 {{ request()->routeIs('borrowing-requests.*') ? 'rounded' : '' }}" style="{{ request()->routeIs('borrowing-requests.*') ? 'background-color: #0b7a6f !important;' : '' }}"
                               href="{{ route('borrowing-requests.index') }}">
                                <i class="bi bi-clock-history me-1"></i>My Requests
                            </a>
                        </li>
                        <li class="nav-item me-3">
                            <a class="nav-link text-white px-2 py-1 {{ request()->routeIs('my-borrowed.*') ? 'rounded' : '' }}" style="{{ request()->routeIs('my-borrowed.*') ? 'background-color: #0b7a6f !important;' : '' }}"
                               href="{{ route('my-borrowed.index') }}">
                                <i class="bi bi-journal-bookmark me-1"></i>My Borrowed
                            </a>
                        </li>
                    @endif
                @endauth
            </ul>

            @auth
                @if(!auth()->user()->isAdmin())
                <!-- User Dropdown Menu - Only for non-admin users (desktop) -->
                <div class="dropdown ms-3 d-none d-lg-block">
                    <button class="btn btn-link text-white text-decoration-none dropdown-toggle d-flex align-items-center" 
                            type="button" 
                            id="userDropdown" 
                            data-bs-toggle="dropdown" 
                            data-bs-auto-close="true" 
                            aria-expanded="false"
                            onclick="toggleDropdown()">
                        <i class="bi bi-person-circle fs-4 me-1"></i>
                        <span class="d-none d-lg-inline">{{ Auth::user()->name }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" id="userDropdownMenu" aria-labelledby="userDropdown">
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="{{ route('profile.settings') }}">
                                <i class="bi bi-gear me-2"></i>Settings
                            </a>
                        </li>
                        <li><hr class="dropdown-divider m-0"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" class="w-100">
                                @csrf
                                <button type="submit" class="dropdown-item d-flex align-items-center w-100">
                                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
                
                <script>
                    // Toggle dropdown manually
                    function toggleDropdown() {
                        const dropdown = document.getElementById('userDropdownMenu');
                        dropdown.classList.toggle('show');
                    }
                    
                    // Close dropdown when clicking outside
                    document.addEventListener('click', function(event) {
                        const dropdown = document.getElementById('userDropdownMenu');
                        const button = document.getElementById('userDropdown');
                        if (!dropdown.contains(event.target) && !button.contains(event.target)) {
                            dropdown.classList.remove('show');
                        }
                    });
                </script>
                @else
                <!-- Simple user info for admin -->
                
                @endif
                
                <script>
                    // Close the dropdown when clicking outside
                    document.addEventListener('click', function(event) {
                        const dropdown = document.getElementById('userDropdownMenu');
                        const button = document.getElementById('userDropdown');
                        if (!dropdown.contains(event.target) && !button.contains(event.target)) {
                            dropdown.classList.remove('show');
                        }
                    });
                </script>
            @else
                <!-- Login Button for Non-Authenticated Users -->
                <div class="d-flex align-items-center">
                    @if(!request()->routeIs(['welcome', 'login', 'register']))
                    <button class="navbar-toggler d-lg-none border-0 text-white me-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                        <i class="bi bi-list fs-4"></i>
                    </button>
                    @endif
                    <a href="{{ route('login') }}" class="btn btn-outline-light {{ request()->routeIs('welcome') ? '' : (request()->routeIs(['login', 'register']) ? 'd-none' : 'd-none d-lg-inline') }}">
                        <i class="bi bi-box-arrow-in-right me-1"></i>Login
                    </a>
                </div>
            @endauth
        </div>
    </div>
</nav>

<!-- Admin Sidebar for Desktop -->
@auth
    @if(auth()->user()->isAdmin())
        <style>
            .admin-sidebar {
                background-color: #0d9488 !important; /* Match main nav color */
                scrollbar-width: thin;
                scrollbar-color: rgba(255, 255, 255, 0.2) transparent;
                overflow-y: scroll !important;
                scrollbar-gutter: stable;
                width: 250px; /* Fixed width */
            }
            /* Webkit browsers (Chrome, Safari) */
            .admin-sidebar::-webkit-scrollbar {
                width: 6px;
            }
            .admin-sidebar::-webkit-scrollbar-track {
                background: transparent;
            }
            .admin-sidebar::-webkit-scrollbar-thumb {
                background-color: rgba(255, 255, 255, 0.2);
                border-radius: 3px;
            }
            .admin-sidebar .list-group {
                background-color: transparent;
            }
            .admin-sidebar .list-group-item {
                background-color: transparent !important;
                color: #dee2e6 !important;
                padding: 0.75rem 1.5rem !important;
                border: none !important;
                border-radius: 0 !important;
                transition: all 0.2s;
                font-size: 0.9rem;
            }
            .admin-sidebar .list-group-item:hover,
            .admin-sidebar .list-group-item:focus {
                background-color: rgba(255, 255, 255, 0.1) !important;
                color: #fff !important;
            }
            .admin-sidebar .list-group-item.active {
                background-color: #0b7a6f !important; /* Slightly darker for active state */
                color: #fff !important;
                font-weight: 500;
                border-left: 3px solid #fff !important;
            }
            .admin-sidebar .list-group-item i {
                width: 24px;
                text-align: center;
                margin-right: 12px;
                color: inherit !important;
                font-size: 1.1rem;
            }
            .admin-sidebar .btn-outline-light {
                border-color: rgba(255, 255, 255, 0.3);
                color: #fff;
                font-size: 0.85rem;
                padding: 0.35rem 0.75rem;
                background-color: transparent;
            }
            .admin-sidebar .btn-outline-light:hover {
                background-color: rgba(255, 255, 255, 0.1);
                border-color: rgba(255, 255, 255, 0.3);
                color: #fff;
            }
            .admin-sidebar .border-secondary {
                border-color: #2c3034 !important;
            }
        </style>
        <div class="d-none d-lg-block">
            <div class="admin-sidebar position-fixed top-0 start-0 h-100 d-flex flex-column" style="width: 250px; z-index: 1039; padding-top: 60px; overflow-y: scroll !important; padding-right: 0; background-color: #0d9488 !important;">
                
                
                <!-- Navigation Menu -->
                <div class="flex-grow-1" style="width: 100%;">
                    <div class="list-group list-group-flush" style="background-color: transparent !important;">
                        <a href="{{ route('dashboard') }}" class="list-group-item list-group-item-action {{ request()->routeIs('dashboard') ? 'active' : '' }} border-0 rounded-0 py-3" style="{{ request()->routeIs('dashboard') ? 'background-color: #0d9488 !important;' : '' }}">
                            <i class="bi bi-speedometer2 me-3"></i>Dashboard
                        </a>
                        <a href="{{ route('books.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('books.*') ? 'active' : '' }} border-0 rounded-0 py-3" style="{{ request()->routeIs('books.*') ? 'background-color: #0d9488 !important;' : '' }}">
                            <i class="bi bi-book me-3"></i>Books
                        </a>
                        <a href="{{ route('admin.announcements.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.announcements.*') ? 'active' : '' }} border-0 rounded-0 py-3" style="{{ request()->routeIs('admin.announcements.*') ? 'background-color: #0d9488 !important;' : '' }}">
                            <i class="bi bi-megaphone me-3"></i>Announcements
                        </a>
                        <a href="{{ route('borrowings.borrowed') }}" class="list-group-item list-group-item-action {{ request()->routeIs('borrowings.borrowed') ? 'active' : '' }} border-0 rounded-0 py-3" style="{{ request()->routeIs('borrowings.borrowed') ? 'background-color: #0d9488 !important;' : '' }}">
                            <i class="bi bi-journal-bookmark me-3"></i>Borrowed
                        </a>
                        <a href="{{ route('admin.qr_scanner') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.qr_scanner') ? 'active' : '' }} border-0 rounded-0 py-3" style="{{ request()->routeIs('admin.qr_scanner') ? 'background-color: #0d9488 !important;' : '' }}">
                            <i class="bi bi-qr-code-scan me-3"></i>QR Scanner
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.users.*') ? 'active' : '' }} border-0 rounded-0 py-3" style="{{ request()->routeIs('admin.users.*') ? 'background-color: #0d9488 !important;' : '' }}">
                            <i class="bi bi-people me-3"></i>Users
                        </a>
                        <a href="{{ route('admin.users.approvals') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.users.approvals') ? 'active' : '' }} border-0 rounded-0 py-3" style="{{ request()->routeIs('admin.users.approvals') ? 'background-color: #0d9488 !important;' : '' }}">
                            <i class="bi bi-person-check me-3"></i>Approvals
                            @php
                                $pendingCount = \App\Models\User::where('is_approved', false)->count();
                            @endphp
                            @if($pendingCount > 0)
                                <span class="badge bg-warning ms-auto">{{ $pendingCount }}</span>
                            @endif
                        </a>
                    </div>
                </div>
                
                <!-- User Profile Section - Pushed to bottom -->
                <div class="border-top border-secondary pt-3 pb-2">
                    <div class="px-4 mb-3">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                <i class="bi bi-person text-white"></i>
                            </div>
                            <div class="text-white">
                                <div class="fw-bold">{{ auth()->user()->name }}</div>
                                <small class="text-white-50">System Administrator</small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Settings and Logout Buttons -->
                    <div class="px-4 mb-2">
                        <a href="{{ route('admin.settings') }}" class="btn btn-outline-light w-100 btn-sm mb-3">
                            <i class="bi bi-gear me-2"></i>Settings
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-light w-100 btn-sm">
                                <i class="bi bi-box-arrow-right me-2"></i>Logout
                            </button>
                        </form>
                    </div>
                </div>
                </div>
            </div>
        </div>
    @endif
@endauth

<!-- User Mobile Navigation Offcanvas -->
<div class="offcanvas offcanvas-start d-lg-none" tabindex="-1" id="userMobileNavOffcanvas" aria-labelledby="userMobileNavOffcanvasLabel" style="top: 56px; height: calc(100vh - 56px); z-index: 1030; width: 280px; background-color: #0d9488; color: #fff;" data-bs-backdrop="false" data-bs-keyboard="false">
    <style>
        #userMobileNavOffcanvas .list-group-item {
            background-color: transparent !important;
            color: #fff !important;
            border: none;
            padding: 0.75rem 1.5rem;
        }
        #userMobileNavOffcanvas .list-group-item:hover,
        #userMobileNavOffcanvas .list-group-item:focus {
            background-color: rgba(255, 255, 255, 0.1) !important;
        }
        #userMobileNavOffcanvas .list-group-item.active {
            background-color: #0b7a6f !important;
            border-left: 3px solid #fff;
        }
        #userMobileNavOffcanvas .list-group-item i {
            color: #fff !important;
            width: 24px;
            text-align: center;
            margin-right: 12px;
        }
    </style>
    <div class="offcanvas-header border-bottom border-secondary py-2">
        <!-- Close button removed as per request -->
    </div>
    <div class="offcanvas-body p-0 d-flex flex-column">
        @auth
            @if(!auth()->user()->isAdmin())
                <div class="p-3">
                    
                    <div class="list-group list-group-flush">
                        <a href="{{ route('dashboard') }}" class="list-group-item list-group-item-action {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="bi bi-speedometer2"></i>Dashboard
                        </a>
                        <a href="{{ route('books.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('books.index') ? 'active' : '' }}">
                            <i class="bi bi-book"></i>Books
                        </a>
                        <a href="{{ route('profile.qr') }}" class="list-group-item list-group-item-action {{ request()->routeIs('profile.qr') ? 'active' : '' }}">
                            <i class="bi bi-qr-code"></i>My QR Code
                        </a>
                        <a href="{{ route('borrowing-requests.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('borrowing-requests.*') ? 'active' : '' }}">
                            <i class="bi bi-clock-history"></i>My Requests
                        </a>
                        <a href="{{ route('my-borrowed.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('my-borrowed.*') ? 'active' : '' }}">
                            <i class="bi bi-journal-bookmark"></i>My Borrowed
                        </a>
                    </div>
                </div>
                </div>
            @endif
        @endauth
        
        @auth
            <div class="mt-auto border-top border-secondary">
                <div class="p-3">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary rounded-circle me-3 d-flex align-items-center justify-content-center"
                             style="width: 40px; height: 40px;">
                            <i class="bi bi-person text-white" style="font-size: 20px;"></i>
                        </div>
                        <div>
                            <div class="fw-bold">{{ auth()->user()->name }}</div>
                            <small class="text-muted">{{ auth()->user()->email }}</small>
                        </div>
                    </div>
                    
                    @php
                        $unreadCount = auth()->user()->unreadNotifications->count();
                    @endphp
                    @if($unreadCount > 0)
                        <div class="alert alert-info mb-3">
                            <i class="bi bi-bell me-2"></i>You have {{ $unreadCount }} new notification{{ $unreadCount > 1 ? 's' : '' }}
                        </div>
                    @endif
                    
                    <div class="d-grid gap-2">
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.settings') }}" class="btn btn-outline-light mb-2" style="border-color: rgba(255, 255, 255, 0.3);">
                                <i class="bi bi-gear me-2"></i>Admin Settings
                            </a>
                        @endif
                        
                        <a href="{{ route('profile.settings') }}" class="btn btn-outline-light" style="border-color: rgba(255, 255, 255, 0.3);">
                            <i class="bi bi-gear me-2"></i>Settings
                        </a>
                        
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-teal w-100" style="border-color: rgba(255, 255, 255, 0.3);">
                                <i class="bi bi-box-arrow-right me-2"></i>Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endauth
        
        @guest
            <div class="mt-auto">
                <div class="d-grid gap-2">
                    <a href="{{ route('login') }}" class="btn btn-primary">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Login
                    </a>
                </div>
            </div>
        @endguest
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const adminDrawerToggle = document.getElementById('adminDrawerToggle');
    const adminNavOffcanvas = document.getElementById('adminNavOffcanvas');
    
    if (adminDrawerToggle && adminNavOffcanvas) {
        let adminOffcanvas;
        
        // Initialize the offcanvas
        try {
            adminOffcanvas = new bootstrap.Offcanvas(adminNavOffcanvas);
        } catch (error) {
            console.error('Error initializing admin offcanvas:', error);
            return;
        }
        
        // Toggle functionality for the drawer icon
        adminDrawerToggle.addEventListener('click', function(e) {
            e.preventDefault();
            
            if (adminNavOffcanvas._element.classList.contains('show')) {
                // If offcanvas is open, close it
                adminOffcanvas.hide();
            } else {
                // If offcanvas is closed, open it
                adminOffcanvas.show();
            }
        });
    }
});
</script>

<!-- Admin Mobile Navigation Offcanvas -->
<style>
    #adminNavOffcanvas {
        background-color: #212529 !important;
    }
    #adminNavOffcanvas .list-group-item {
        background-color: transparent !important;
        border: none !important;
        color: #fff !important;
        padding: 0.75rem 1.5rem !important;
    }
    #adminNavOffcanvas .list-group-item:hover,
    #adminNavOffcanvas .list-group-item:focus {
        background-color: rgba(255, 255, 255, 0.1) !important;
    }
    #adminNavOffcanvas .list-group-item.active {
        background-color: #0d6efd !important;
        border-left: 4px solid #fff !important;
    }
    #adminNavOffcanvas .list-group-item i {
        color: #fff !important;
        width: 20px;
        text-align: center;
        margin-right: 10px;
    }
    #adminNavOffcanvas .offcanvas-header {
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        background-color: #0f766e;
    }
    #adminNavOffcanvas .offcanvas-title {
        color: #fff !important;
    }
    #adminNavOffcanvas .list-group-item {
        background-color: transparent;
        color: #fff;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 0;
    }
    #adminNavCanvas .list-group-item:hover,
    #adminNavOffcanvas .list-group-item:focus {
        background-color: rgba(255, 255, 255, 0.1) !important;
    }
    #adminNavOffcanvas .list-group-item.active {
        background-color: #0d9488 !important;
        color: #fff !important;
        font-weight: 500;
    }
    #adminNavOffcanvas .list-group-item i {
        width: 24px;
        text-align: center;
        margin-right: 12px;
        color: inherit !important;
        font-size: 1.1rem;
    }
</style>

@auth
    @if(auth()->user()->isAdmin())
        <div class="offcanvas offcanvas-start d-lg-none" tabindex="-1" id="adminNavOffcanvas" aria-labelledby="adminNavOffcanvasLabel" style="top: 56px; height: calc(100vh - 56px); z-index: 1030; width: 280px; background-color: #0d9488 !important; color: #fff;" data-bs-backdrop="false" data-bs-keyboard="false">
            <div class="offcanvas-header p-3 border-0" style="background-color: #0d9488 !important;">
                <!-- Removed Admin Menu title and close button -->
            </div>
            <div class="offcanvas-body p-0 d-flex flex-column" style="background-color: #0d9488 !important;">
                <div class="list-group list-group-flush" style="background-color: transparent !important;">
                    <a href="{{ route('dashboard') }}" class="list-group-item list-group-item-action {{ request()->routeIs('dashboard') ? 'active' : '' }}" style="background-color: transparent !important; {{ request()->routeIs('dashboard') ? 'background-color: #0b7a6f !important;' : '' }} color: #fff !important;">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                    <a href="{{ route('books.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('books.*') ? 'active' : '' }}" style="background-color: transparent !important; {{ request()->routeIs('books.*') ? 'background-color: #0b7a6f !important;' : '' }} color: #fff !important;">
                        <i class="bi bi-book"></i> Books
                    </a>
                    <a href="{{ route('admin.announcements.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.announcements.*') ? 'active' : '' }}" style="background-color: transparent !important; {{ request()->routeIs('admin.announcements.*') ? 'background-color: #0b7a6f !important;' : '' }} color: #fff !important;">
                        <i class="bi bi-megaphone"></i> Announcements
                    </a>
                    <a href="{{ route('admin.users.approvals') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.users.approvals') ? 'active' : '' }}" style="background-color: transparent !important; {{ request()->routeIs('admin.users.approvals') ? 'background-color: #0b7a6f !important;' : '' }} color: #fff !important;">
                        <i class="bi bi-person-check"></i> Approvals
                        @php
                            $pendingCount = \App\Models\User::where('is_approved', false)->count();
                        @endphp
                        @if($pendingCount > 0)
                            <span class="badge bg-warning ms-auto">{{ $pendingCount }}</span>
                        @endif
                    </a>
                    <a href="{{ route('borrowings.borrowed') }}" class="list-group-item list-group-item-action {{ request()->routeIs('borrowings.borrowed') ? 'active' : '' }}" style="background-color: transparent !important; {{ request()->routeIs('borrowings.borrowed') ? 'background-color: #0b7a6f !important;' : '' }} color: #fff !important;">
                        <i class="bi bi-journal-bookmark"></i> Borrowed
                    </a>
                    <a href="{{ route('admin.qr_scanner') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.qr_scanner') ? 'active' : '' }}" style="background-color: transparent !important; {{ request()->routeIs('admin.qr_scanner') ? 'background-color: #0b7a6f !important;' : '' }} color: #fff !important;">
                        <i class="bi bi-upc-scan"></i> QR Scanner
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.users.*') && !request()->routeIs('admin.users.approvals') ? 'active' : '' }}" style="background-color: transparent !important; {{ request()->routeIs('admin.users.*') && !request()->routeIs('admin.users.approvals') ? 'background-color: #0b7a6f !important;' : '' }} color: #fff !important;">
                        <i class="bi bi-people"></i> Users
                    </a>
                </div>
                
                @auth
                    <div class="mt-auto border-top border-secondary">
                        <div class="p-3">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-primary rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="bi bi-person text-white" style="font-size: 20px;"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-white">{{ auth()->user()->name }}</div>
                                    <small class="text-white-50">{{ auth()->user()->email }}</small>
                                </div>
                            </div>
                            <div class="d-grid">
                                <a href="{{ route('admin.settings') }}" class="btn btn-outline-light btn-sm mb-2" style="border-color: rgba(255, 255, 255, 0.3);">
                                    <i class="bi bi-gear me-2"></i>Settings
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-light btn-sm w-100" style="border-color: rgba(255, 255, 255, 0.3);">
                                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endauth
            </div>
        </div>
    @endif
@endauth
