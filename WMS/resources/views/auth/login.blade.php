<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>YHROCU - Login</title>
  <!-- Tailwind CSS via CDN for demo purposes. 
       In a real Laravel app, use your compiled CSS . -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#0284c7] min-h-screen flex flex-col">

  <!-- Header / Title -->
  <header class="py-4">
    <div class="flex items-left justify-left px-10">
      <h1 class="text-white font-bold uppercase">
        <span class="text-white text-6xl">YHROCU</span> <br>
        Workflow Management System
      </h1>
    </div>
  </header>

  <!-- Main Content: Centered Login Card -->
  <main class="flex-grow flex items-center justify-center">
    <div class="bg-white/90 backdrop-blur-md rounded-lg shadow-lg p-10 w-full max-w-lg">
      <!-- Top Circle Icon -->
      <div class="flex justify-center mb-4">
        <div class="h-12 w-12 bg-black rounded-full flex items-center justify-center">
          <!-- SVG User Icon -->
          <svg
            class="h-6 w-6 text-white"
            fill="currentColor"
            viewBox="0 0 24 24"
          >
            <circle cx="12" cy="7" r="4" />
            <path d="M12 12c-2.67 0-8 1.33-8 4v2h16v-2c0-2.67-5.33-4-8-4z" />
          </svg>
        </div>
      </div>

      <!-- Heading -->
      <h2 class="text-3xl font-bold text-gray-800 text-center mb-8">
        Login
      </h2>

      <!-- Display Session Status (e.g. password reset link sent) -->
      @if (session('status'))
        <div class="mb-4 text-sm text-green-600">
          {{ session('status') }}
        </div>
      @endif

      <!-- Display Validation Errors -->
      @if ($errors->any())
        <div class="mb-4">
          <ul class="list-disc list-inside text-sm text-red-600">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <!-- Login Form -->
      <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Field -->
        <div class="mb-6">
          <label for="email" class="block text-lg font-semibold text-gray-700 mb-2">
            Email
          </label>
          <div class="flex items-center border border-gray-300 rounded">
            <span class="px-3 text-gray-400">
              <!-- Improved Email Icon (Heroicon style) -->
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" 
                   viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M16 12H8m8 4H8m8-8H8M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z" />
              </svg>
            </span>
            <input
              type="email"
              id="email"
              name="email"
              placeholder="Enter your email"
              class="flex-1 px-3 py-2 focus:outline-none"
              value="{{ old('email') }}"
              required
            />
          </div>
        </div>

        <!-- Password Field -->
        <div class="mb-6">
          <label for="password" class="block text-lg font-semibold text-gray-700 mb-2">
            Password
          </label>
          <div class="flex items-center border border-gray-300 rounded">
            <span class="px-3 text-gray-400">
              <!-- Lock Icon SVG -->
              <svg
                class="h-5 w-5"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
                viewBox="0 0 24 24"
              >
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                <path d="M7 11V7a5 5 0 0110 0v4" />
              </svg>
            </span>
            <input
              type="password"
              id="password"
              name="password"
              placeholder="Enter your password"
              class="flex-1 px-3 py-2 focus:outline-none"
              required
            />
          </div>
        </div>

        <!-- Forgot Password & Login Button -->
        <div class="flex items-center justify-between mb-6">
          <a href="{{ route('password.request') }}" class="text-sm text-gray-500 hover:underline">
            Forgot Password?
          </a>
        </div>

        <button
          type="submit"
          class="w-full bg-[#FFD100] text-black font-semibold py-3 px-4 rounded hover:bg-yellow-400 text-lg">
          LOGIN
        </button>
      </form>
    </div>
  </main>
</body>
</html>