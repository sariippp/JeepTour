{{-- resources/views/admin/layout/index.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>
    @vite('resources/css/app.css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <style>
        /* Base styles */
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f5f7fa;
        }
        
        /* Sidebar styles */
        .sidebar {
            transition: all 0.3s;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
        }
        
        .sidebar-link {
            border-radius: 0.5rem;
            transition: all 0.2s;
            margin-bottom: 0.25rem;
        }
        
        .sidebar-link:hover {
            background-color: #f0f5ff;
        }
        
        .sidebar-link.active {
            background-color: #e0eaff;
            color: #3b82f6;
        }
        
        /* Header styles */
        .header {
            background-color: white;
            border-bottom: 1px solid #e5e7eb;
        }
        
        /* Profile button */
        .profile-button {
            cursor: pointer;
            transition: all 0.2s;
            width: 2.5rem;
            height: 2.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background-color: #f3f4f6;
        }
        
        .profile-button:hover {
            background-color: #e5e7eb;
        }
        
        /* Welcome text */
        .welcome-text {
            font-size: 0.95rem;
            color: #4b5563;
            font-weight: 500;
        }
        
        /* Logout button */
        .logout-btn {
            display: flex;
            align-items: center;
            background: none;
            border: none;
            padding: 0.75rem 1rem;
            color: #dc2626;
            cursor: pointer;
            width: 100%;
            text-align: left;
        }
        
        .logout-btn:hover {
            background-color: #fef2f2;
        }
        
        /* Responsive tweaks */
        @media (max-width: 1024px) {
            .sidebar {
                width: 240px;
            }
        }
        
        @media (max-width: 767px) {
            .welcome-text {
                display: none;
            }
        }
    </style>
</head>
<body class="bg-gray-50">
    <div x-data="{ sidebarOpen: true }" class="min-h-screen">
        <!-- Sidebar -->
        <aside class="fixed top-0 left-0 z-40 h-screen transition-transform duration-300 sidebar"
               :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}">
            <div class="h-full w-64 bg-white border-r border-gray-200">
                <!-- Logo -->
                <div class="flex items-center justify-between p-4 border-b">
                    <h1 class="text-xl font-semibold text-gray-800">Admin</h1>
                    <button @click="sidebarOpen = false" class="lg:hidden p-2 rounded-md hover:bg-gray-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Navigation -->
                <nav class="p-4 space-y-2">
                    <a href="{{ route('admin.index') }}" class="flex items-center space-x-3 p-3 rounded-lg sidebar-link {{ request()->routeIs('admin.index') ? 'active' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                        <span>Dasbor</span>
                    </a>
                    <a href="{{ route('admin.users') }}" class="flex items-center space-x-3 p-3 rounded-lg sidebar-link {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <span>Daftar Pengguna</span>
                    </a>
                    <a href="{{ route('admin.financial') }}" class="flex items-center space-x-3 p-3 rounded-lg sidebar-link {{ request()->routeIs('admin.financial') ? 'active' : '' }}">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
                          </svg>                          
                        <span>Keuangan</span>
                    </a>
                    <a href="{{ route('admin.jeeps') }}" class="flex items-center space-x-3 p-3 rounded-lg sidebar-link {{ request()->routeIs('admin.jeeps') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M2.52 3.515A2.5 2.5 0 0 1 4.82 2h6.362c1 0 1.904.596 2.298 1.515l.792 1.848c.075.175.21.319.38.404.5.25.855.715.965 1.262l.335 1.679q.05.242.049.49v.413c0 .814-.39 1.543-1 1.997V13.5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-1.338c-1.292.048-2.745.088-4 .088s-2.708-.04-4-.088V13.5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-1.892c-.61-.454-1-1.183-1-1.997v-.413a2.5 2.5 0 0 1 .049-.49l.335-1.68c.11-.546.465-1.012.964-1.261a.8.8 0 0 0 .381-.404l.792-1.848ZM3 10a1 1 0 1 0 0-2 1 1 0 0 0 0 2m10 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2M6 8a1 1 0 0 0 0 2h4a1 1 0 1 0 0-2zM2.906 5.189a.51.51 0 0 0 .497.731c.91-.073 3.35-.17 4.597-.17s3.688.097 4.597.17a.51.51 0 0 0 .497-.731l-.956-1.913A.5.5 0 0 0 11.691 3H4.309a.5.5 0 0 0-.447.276L2.906 5.19Z"/>
                        </svg>                       
                        <span>Jeep</span>
                    </a>
                    <a href="{{ route('admin.profile') }}" class="flex items-center space-x-3 p-3 rounded-lg sidebar-link {{ request()->routeIs('admin.profile') ? 'active' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span>Profil Saya</span>
                    </a>
                    <div class="pt-4">
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="flex items-center space-x-3 p-3 rounded-lg sidebar-link text-red-600 hover:bg-red-50 w-full text-left">
                                <i class="fas fa-sign-out-alt w-5 h-5"></i>
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>
                </nav>
            </div>
        </aside>

        <!-- Main Content -->
        <div :class="{'lg:ml-64': sidebarOpen}" class="transition-all duration-300">
            <!-- Header -->
            <header class="bg-white border-b border-gray-200 header">
                <div class="flex items-center justify-between px-4 py-3">
                    <div class="flex items-center space-x-4">
                        <button @click="sidebarOpen = !sidebarOpen" class="p-2 hover:bg-gray-100 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="welcome-text">
                            Selamat Datang, <span class="font-medium">{{ Auth::user()->name }}</span>
                        </div>
                        <a href="{{ route('admin.profile') }}" class="profile-button" title="My Profile">
    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
    </svg>
</a>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="p-6">
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