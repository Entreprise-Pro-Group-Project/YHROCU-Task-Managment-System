<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin</title>

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title', config('app.name', 'Laravel'))</title><title>My Custom Title - {{ config('app.name', 'Laravel') }}
        </title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        <!-- Include Navigation -->
        @include('layouts.navigation')

        <!-- Main Layout: Sidebar + Content -->
        <div class="flex">
            <!-- Sidebar -->
            <aside class="w-64 bg-[#0284c7] text-white min-h-screen p-6">
                <nav class="space-y-2">
                    <!-- Dashboard Link -->
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center p-2 rounded hover:bg-[#0273a3]">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M3 13h2v-2H3v2zm0 4h2v-2H3v2zM3 9h2V7H3v2zm4 4h14v-2H7v2zm0 4h14v-2H7v2zM7 7v2h14V7H7z"/>
                        </svg>
                        <span class="ml-2">Dashboard</span>
                    </a>

                    <!-- Manage Users Link -->
                    <a href="{{ route('admin.user_management.index') }}" class="flex items-center p-2 rounded hover:bg-[#0273a3]">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M4 6h16v2H4V6zm0 10h10v2H4v-2zm0-5h16v2H4v-2z"/>
                        </svg>
                        <span class="ml-2">Manage Users</span>
                    </a>

                    <!-- Add Projects Link -->
                    <a href="{{ route('projects.create') }}" class="flex items-center p-2 rounded hover:bg-[#0273a3]">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M14 2H4c-1.1 0-2 .9-2 2v16l4-4h8c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM20 6h2v12h-2z"/>
                        </svg>
                        <span class="ml-2">Add Projects</span>
                    </a>
                </nav>
            </aside>

            <!-- Main Content -->
            <main class="flex-1 p-6">
                <!-- Page Heading -->
                @isset($header)
                    <header class="bg-white dark:bg-gray-800 shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Page Content -->
                <div>
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Admin dropdown
            const adminButton = document.getElementById('adminButton');
            const adminDropdown = document.getElementById('adminDropdown');
            
            // Filter dropdown
            const filterButton = document.getElementById('filterButton');
            const filterDropdown = document.getElementById('filterDropdown');

            if (adminButton && adminDropdown) {
                adminButton.addEventListener('click', function (e) {
                    e.stopPropagation(); 
                    // Close filter dropdown if open
                    if (filterDropdown) filterDropdown.classList.add('hidden');
                    // Toggle admin dropdown visibility
                    adminDropdown.classList.toggle('hidden');
                });
            }

            if (filterButton && filterDropdown) {
                filterButton.addEventListener('click', function (e) {
                    e.stopPropagation();
                    // Close admin dropdown if open
                    if (adminDropdown) adminDropdown.classList.add('hidden');
                    // Toggle filter dropdown visibility
                    filterDropdown.classList.toggle('hidden');
                });
            }

            // Hide both dropdowns when clicking anywhere else
            document.addEventListener('click', function () {
                if (adminDropdown) adminDropdown.classList.add('hidden');
                if (filterDropdown) filterDropdown.classList.add('hidden');
            });
        });
    </script>

    <script src="{{ asset('js/app.js') }}" defer></script>
    
    <!-- Add script stack -->
    @stack('scripts')
    @yield('scripts')
    @livewireScripts
</body>
</html>