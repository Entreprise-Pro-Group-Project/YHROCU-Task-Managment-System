<nav class="bg-[#0284c7] text-white px-6 py-4 flex items-center">
    <!-- Left Column: App Name -->
    <div class="flex-1 font-bold text-lg">
        <span class="text-3xl">YHROCU</span> <span class="text-sm">Workflow Management System</span>
    </div>

    <!-- Center Column: Welcome Message -->
    <div class="flex-1 text-center text-xl">
        Welcome, <span class="font-bold">{{ Auth::user()->first_name }}</span>
    </div>

    <!-- Right Column: User Role Button with Dropdown -->
    <div class="flex-1 flex justify-end">
        <div class="relative inline-block">
            <!-- Role Button -->
            <button
                id="adminButton"
                class="bg-[#0284c7] text-white px-3 py-1 rounded inline-flex items-center space-x-1 hover:bg-[#0273a3]"
            >
                <!-- Display the user's role instead of "Admin" -->
                <span>
                    {{ Auth::check() ? Auth::user()->role : 'Guest' }}
                </span>
                <!-- Dropdown Arrow -->
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <!-- Dropdown Menu (hidden by default) -->
            <div
                id="adminDropdown"
                class="hidden absolute top-full right-0 mt-1 w-40 bg-white text-[#0284c7] rounded shadow-lg z-10"
            >
                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 hover:bg-gray-100">Profile</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a
                        href="#"
                        class="block px-4 py-2 hover:bg-gray-100"
                        onclick="event.preventDefault(); this.closest('form').submit();"
                    >
                        Logout
                    </a>
                </form>
            </div>
        </div>
    </div>
</nav>
