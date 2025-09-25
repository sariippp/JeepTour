{{-- resources/views/admin/layout/index.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | @yield('title', 'Dashboard')</title>
    @vite('resources/css/app.css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <style>
        /* Import modern font */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        /* Base styles */
        :root {
            --primary-color: #3b82f6;
            --primary-hover: #2563eb;
            --sidebar-bg: #ffffff;
            --sidebar-border: #e5e7eb;
            --content-bg: #f8fafc;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --border-radius: 0.75rem;
            --transition: all 0.2s ease-in-out;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--content-bg);
            color: var(--text-primary);
            line-height: 1.6;
        }
        
        /* Layout */
        .min-h-screen {
            min-height: 100vh;
        }
        
        /* Sidebar styles */
        .sidebar {
            transition: var(--transition);
            box-shadow: var(--shadow-lg);
            background: var(--sidebar-bg);
            border-right: 1px solid var(--sidebar-border);
            z-index: 50;
        }
        
        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--sidebar-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .sidebar-logo {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .sidebar-toggle {
            display: none;
            background: none;
            border: none;
            padding: 0.5rem;
            border-radius: 0.5rem;
            color: var(--text-secondary);
            transition: var(--transition);
        }
        
        .sidebar-toggle:hover {
            background-color: #f1f5f9;
            color: var(--primary-color);
        }
        
        .sidebar-nav {
            padding: 1rem 0;
        }
        
        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.875rem 1.5rem;
            color: var(--text-secondary);
            text-decoration: none;
            border-radius: var(--border-radius);
            margin: 0.25rem 1rem;
            transition: var(--transition);
            font-weight: 500;
            position: relative;
        }
        
        .sidebar-link:hover {
            background-color: #f8fafc;
            color: var(--primary-color);
            transform: translateX(4px);
        }
        
        .sidebar-link.active {
            background-color: #eff6ff;
            color: var(--primary-color);
            box-shadow: var(--shadow-sm);
        }
        
        .sidebar-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 20px;
            background-color: var(--primary-color);
            border-radius: 0 2px 2px 0;
        }
        
        .sidebar-icon {
            width: 1.25rem;
            height: 1.25rem;
            flex-shrink: 0;
            color: inherit;
        }
        
        /* Header styles */
        .header {
            background-color: var(--sidebar-bg);
            border-bottom: 1px solid var(--sidebar-border);
            box-shadow: var(--shadow-sm);
            position: sticky;
            top: 0;
            z-index: 40;
        }
        
        .header-content {
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .header-toggle {
            background: none;
            border: none;
            padding: 0.5rem;
            border-radius: 0.5rem;
            color: var(--text-secondary);
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .header-toggle:hover {
            background-color: #f1f5f9;
            color: var(--primary-color);
        }
        
        .header-toggle svg {
            width: 1.25rem;
            height: 1.25rem;
        }
        
        .welcome-section {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .welcome-text {
            font-size: 0.875rem;
            color: var(--text-secondary);
            font-weight: 500;
        }
        
        .welcome-name {
            color: var(--text-primary);
            font-weight: 600;
        }
        
        .profile-button {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 50%;
            background-color: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-secondary);
            text-decoration: none;
            transition: var(--transition);
            box-shadow: var(--shadow-sm);
        }
        
        .profile-button:hover {
            background-color: #e2e8f0;
            color: var(--primary-color);
            transform: scale(1.05);
        }
        
        .profile-button svg {
            width: 1.25rem;
            height: 1.25rem;
        }
        
        /* Logout button */
        .logout-btn {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            background: none;
            border: none;
            padding: 0.875rem 1.5rem;
            color: #ef4444;
            text-decoration: none;
            width: 100%;
            margin: 0.25rem 1rem;
            border-radius: var(--border-radius);
            font-weight: 500;
            transition: var(--transition);
            text-align: left;
        }
        
        .logout-btn:hover {
            background-color: #fef2f2;
            color: #dc2626;
        }
        
        /* Main content */
        main {
            padding: 2rem;
            min-height: calc(100vh - 80px); /* Adjust based on header height */
        }
        
        .content-wrapper {
            margin-left: 16rem; /* Sidebar width */
            transition: var(--transition);
        }
        
        /* Responsive design */
        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.open {
                transform: translateX(0);
            }
            
            .content-wrapper {
                margin-left: 0;
            }
            
            .sidebar-toggle {
                display: block;
            }
            
            main {
                padding: 1rem;
            }
        }
        
        @media (max-width: 768px) {
            .welcome-text {
                display: none;
            }
            
            .header-content {
                padding: 1rem;
            }
        }
        
        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        main {
            animation: fadeIn 0.3s ease-out;
        }
        
        /* Scrollbar styling for modern look */
        ::-webkit-scrollbar {
            width: 6px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div x-data="{ sidebarOpen: false }" class="min-h-screen">
        <!-- Mobile overlay -->
        <div x-show="!sidebarOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-30 bg-black bg-opacity-50 lg:hidden" @click="sidebarOpen = false"></div>
        
        <!-- Sidebar -->
        <aside class="fixed top-0 left-0 z-40 h-screen sidebar w-64"
               :class="{ 'open': sidebarOpen }">
            <div class="h-full flex flex-col">
                <!-- Sidebar Header -->
                <div class="sidebar-header">
                    <div class="sidebar-logo">
                        <i class="fas fa-cog text-xl"></i>
                        <span>Admin Panel</span>
                    </div>
                    <button @click="sidebarOpen = !sidebarOpen" class="sidebar-toggle lg:hidden">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 sidebar-nav">
                    <a href="{{ route('admin.index') }}" class="sidebar-link {{ request()->routeIs('admin.index') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt sidebar-icon"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('admin.financial') }}" class="sidebar-link {{ request()->routeIs('admin.financial') ? 'active' : '' }}">
                        <i class="fas fa-chart-line sidebar-icon"></i>
                        <span>Keuangan</span>
                    </a>
                    <a href="{{ route('admin.sessions') }}" class="sidebar-link {{ request()->routeIs('admin.sessions') ? 'active' : '' }}">
                        <i class="fas fa-clock sidebar-icon"></i>
                        <span>Sesi</span>
                    </a>
                    <a href="{{ route('admin.users') }}" class="sidebar-link {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                        <i class="fas fa-users sidebar-icon"></i>
                        <span>Daftar Pengguna</span>
                    </a>
                    <a href="{{ route('admin.jeeps') }}" class="sidebar-link {{ request()->routeIs('admin.jeeps') ? 'active' : '' }}">
                        <i class="fas fa-car sidebar-icon"></i>
                        <span>Jeep</span>
                    </a>
                    <a href="{{ route('admin.profile') }}" class="sidebar-link {{ request()->routeIs('admin.profile') ? 'active' : '' }}">
                        <i class="fas fa-user sidebar-icon"></i>
                        <span>Profil Saya</span>
                    </a>
                </nav>
                
                <!-- Logout -->
                <div class="pt-2 pb-4 px-4 border-t border-gray-200">
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="logout-btn">
                            <i class="fas fa-sign-out-alt sidebar-icon"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col content-wrapper lg:ml-64">
            <!-- Header -->
            <header class="header">
                <div class="header-content">
                    <div class="flex items-center gap-4">
                        <button @click="sidebarOpen = !sidebarOpen" class="header-toggle lg:hidden">
                            <i class="fas fa-bars"></i>
                        </button>
                        <div class="hidden lg:block">
                            <!-- Optional: Breadcrumb or search here -->
                        </div>
                    </div>
                    <div class="welcome-section">
                        <div class="welcome-text">
                            Selamat Datang, <span class="welcome-name">{{ Auth::user()->name }}</span>
                        </div>
                        <a href="{{ route('admin.profile') }}" class="profile-button">
                            <i class="fas fa-user-circle"></i>
                        </a>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main>
                @yield('content')
            </main>
        </div>
    </div>

    <!-- AJAX Setup -->
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    @stack('scripts')
</body>
</html>