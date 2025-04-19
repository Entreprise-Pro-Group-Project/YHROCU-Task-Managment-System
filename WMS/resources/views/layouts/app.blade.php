<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($title) ? $title . ' - ' : '' }}{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <script src="https://unpkg.com/feather-icons"></script>

    <!-- Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body>
    {{-- Fixed top nav (height ≈ 4rem + py-4) --}}
    @include('layouts.navigation')

    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-[#0284c7] text-white flex-shrink-0 hidden md:block">
            <div class="h-full flex flex-col justify-between p-6">
                <nav class="space-y-4">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center p-3 rounded-lg hover:bg-[#0273a3] transition">
                        <i data-feather="home" class="h-5 w-5"></i>
                        <span class="ml-3 font-medium">Dashboard</span>
                    </a>
                    <a href="{{ route('admin.user_management.index') }}" class="flex items-center p-3 rounded-lg hover:bg-[#0273a3] transition">
                        <i data-feather="users" class="h-5 w-5"></i>
                        <span class="ml-3 font-medium">Manage Users</span>
                    </a>
                    <a href="{{ route('projects.create') }}" class="flex items-center p-3 rounded-lg hover:bg-[#0273a3] transition">
                        <i data-feather="plus-square" class="h-5 w-5"></i>
                        <span class="ml-3 font-medium">Add Project</span>
                    </a>
                </nav>
            </div>
        </aside>

        <!-- Main content -->
        <div class="flex-1 flex flex-col">
            <main class="flex-1 p-8">
                @isset($header)
                    <header class="mb-8">
                        <h1 class="text-3xl font-bold text-gray-900">{{ $header }}</h1>
                    </header>
                @endisset

                @if(session('error'))
                    <div class="mb-6 p-4 bg-red-100 text-red-800 rounded-lg">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Feather Icons init -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            feather.replace();
        });
    </script>

    @livewireScripts
    @stack('scripts')
    @yield('scripts')
</body>
</html>
