<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Laravel'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        <!-- Include Navigation -->
        @include('layouts.navigation')

        <!-- Main Content Only (No Sidebar) -->
        <main class="p-6">
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
</body>
</html>
