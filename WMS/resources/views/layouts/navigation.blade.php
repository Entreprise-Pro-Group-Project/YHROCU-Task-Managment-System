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
    <!-- Profile Link -->
    <a
        href="{{ route('profile.edit') }}"
        class="block px-4 py-2 hover:bg-gray-100"
    >
        <div class="flex items-center space-x-2">
            <!-- Inline SVG for Profile Icon -->
            <svg
                width="25px"
                height="25px"
                viewBox="0 0 0.75 0.75"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
            >
                <path
                    d="M0.616 0.639c-0.014 -0.04 -0.046 -0.075 -0.089 -0.1S0.43 0.5 0.375 0.5s-0.109 0.014 -0.152 0.039 -0.075 0.06 -0.089 0.1"
                    stroke="#222222"
                    stroke-width="0.0375"
                    stroke-linecap="round"
                />
                <path
                    cx="12"
                    cy="8"
                    r="4"
                    fill="#2A4157"
                    fill-opacity="0.24"
                    stroke="#222222"
                    stroke-width="0.0375"
                    stroke-linecap="round"
                    d="M0.5 0.25A0.125 0.125 0 0 1 0.375 0.375A0.125 0.125 0 0 1 0.25 0.25A0.125 0.125 0 0 1 0.5 0.25z"
                />
            </svg>
            <span>Profile</span>
        </div>
    </a>

    <!-- Logout Form -->
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <a
            href="#"
            class="block px-4 py-2 hover:bg-gray-100"
            onclick="event.preventDefault(); this.closest('form').submit();"
        >
            <div class="flex items-center space-x-2">
                <!-- Inline SVG for Logout Icon -->
                <svg
                    width="25px"
                    height="25px"
                    viewBox="0 0 2 2"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        d="M1.527 0.176H0.684a0.203 0.203 0 0 0 -0.203 0.203v0.421h0.249a0.187 0.187 0 0 1 0.055 -0.131 0.188 0.188 0 0 1 0.265 -0.001l0.187 0.186c0 0 0 0 0 0.001a0.188 0.188 0 0 1 0.035 0.048l0 0 0 0.001c0.002 0.003 0.003 0.007 0.005 0.01l0 0.001c0.001 0.002 0.002 0.003 0.002 0.005 0.002 0.004 0.003 0.008 0.004 0.012 0.006 0.018 0.009 0.037 0.008 0.055 0 0.018 -0.003 0.037 -0.008 0.054a0.113 0.113 0 0 1 -0.006 0.017 0.156 0.156 0 0 1 -0.008 0.017 0.156 0.156 0 0 1 -0.009 0.016q-0.01 0.016 -0.024 0.029l-0.187 0.188a0.187 0.187 0 0 1 -0.133 0.055 0.187 0.187 0 0 1 -0.132 -0.054 0.188 0.188 0 0 1 -0.055 -0.133v-0.001h-0.249v0.421a0.203 0.203 0 0 0 0.203 0.203h0.843a0.203 0.203 0 0 0 0.203 -0.203v-1.217a0.203 0.203 0 0 0 -0.203 -0.203"
                        fill="#999999"
                    />
                    <path
                        d="M0.481 1.05h0.474l-0.066 0.066 -0.015 0.016c-0.001 0.001 -0.002 0.002 -0.003 0.003a0.063 0.063 0 0 0 -0.005 0.006c-0.001 0.001 -0.001 0.003 -0.002 0.004q-0.002 0.003 -0.004 0.007c0 0.001 -0.001 0.002 -0.001 0.003q-0.002 0.004 -0.003 0.009v0.001q-0.001 0.005 -0.001 0.01h0a0.062 0.062 0 0 0 0.018 0.045 0.063 0.063 0 0 0 0.044 0.018 0.062 0.062 0 0 0 0.044 -0.018l0.187 -0.188c0.001 0 0.001 -0.001 0.001 -0.002q0.003 -0.004 0.006 -0.008c0.001 -0.001 0.001 -0.002 0.002 -0.004q0.002 -0.003 0.004 -0.007c0.001 -0.002 0.001 -0.003 0.002 -0.005q0.001 -0.003 0.002 -0.007 0.001 -0.004 0.001 -0.009c0 -0.001 0 -0.002 0 -0.003v0a0.063 0.063 0 0 0 -0.005 -0.024v0a0.063 0.063 0 0 0 -0.006 -0.01l0 0a0.063 0.063 0 0 0 -0.008 -0.009l-0.187 -0.186a0.063 0.063 0 0 0 -0.088 0 0.063 0.063 0 0 0 -0.018 0.043h0v0.001q0 0.005 0.001 0.009c0 0.001 0 0.002 0 0.003s0 0.001 0.001 0.002q0.001 0.005 0.003 0.01 0 0.001 0.001 0.001a0.063 0.063 0 0 0 0.013 0.019l0.08 0.079H0.357a0.063 0.063 0 1 0 0 0.125h0.125z"
                        fill="#000000"
                    />
                </svg>
                <span>Logout</span>
            </div>
        </a>
    </form>
</div>
        </div>
    </div>
</nav>