<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>Supervisor</title>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

  <!-- Scripts & Styles -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @livewireStyles
  @livewireScripts
  <script src="https://unpkg.com/feather-icons"></script>
</head>

{{-- add pt-16 to push content below the fixed nav --}}
<body>
  
  {{-- Fixed top navigation --}}
  @include('layouts.navigation')

  {{-- Main layout: sidebar + content --}}
  <div class="flex min-h-[calc(100vh-4rem)]">
    <!-- Sidebar -->
    <aside class="w-64 bg-[#0284c7] text-white p-6 hidden md:block">
      <nav class="space-y-2">
        <a href="{{ route('supervisor.dashboard') }}"
           class="flex items-center p-2 rounded hover:bg-[#0273a3] transition">
          <i data-feather="home" class="h-5 w-5"></i>
          <span class="ml-2">Dashboard</span>
        </a>

        <a href="{{ route('projects.create') }}"
           class="flex items-center p-2 rounded hover:bg-[#0273a3] transition">
          <i data-feather="plus-square" class="h-5 w-5"></i>
          <span class="ml-2">Add Projects</span>
        </a>
      </nav>
    </aside>

    <!-- Content Area -->
    <main class="flex-1 p-6 overflow-auto">
      @isset($header)
        <header class="bg-white dark:bg-gray-800 shadow mb-6">
          <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            {{ $header }}
          </div>
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

  <!-- Feather Icons + Dropdown JS -->
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      feather.replace();

      const adminButton   = document.getElementById('adminButton');
      const adminDropdown = document.getElementById('adminDropdown');
      const filterButton  = document.getElementById('filterButton');
      const filterDropdown= document.getElementById('filterDropdown');

      if (adminButton && adminDropdown) {
        adminButton.addEventListener('click', e => {
          e.stopPropagation();
          filterDropdown?.classList.add('hidden');
          adminDropdown.classList.toggle('hidden');
        });
      }

      if (filterButton && filterDropdown) {
        filterButton.addEventListener('click', e => {
          e.stopPropagation();
          adminDropdown?.classList.add('hidden');
          filterDropdown.classList.toggle('hidden');
        });
      }

      document.addEventListener('click', () => {
        adminDropdown?.classList.add('hidden');
        filterDropdown?.classList.add('hidden');
      });
    });
  </script>
</body>
</html>
