<nav class="bg-[#0284c7] text-white px-6 py-4 flex items-center">
    <!-- Left Column: App Name -->
    <div class="flex-1 font-bold text-lg">
        <span class="text-3xl">YHROCU</span> <span class="text-sm">Workflow Management System</span>
    </div>

    <!-- Center Column: Enhanced Welcome Message -->
    <div class="flex-1 flex justify-center">
        <div class="bg-white/10 backdrop-blur-sm px-6 py-2 rounded-full shadow-inner flex items-center space-x-3 border border-white/20 animate-pulse-slow">
            <!-- Decorative Wave Icon -->
            <div class="text-yellow-300 animate-wave">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 8C19.6569 8 21 6.65685 21 5C21 3.34315 19.6569 2 18 2C16.3431 2 15 3.34315 15 5C15 6.65685 16.3431 8 18 8Z"></path>
                    <path d="M18 8C18 11.3137 15.3137 14 12 14C8.68629 14 6 11.3137 6 8"></path>
                    <path d="M6 8C7.65685 8 9 6.65685 9 5C9 3.34315 7.65685 2 6 2C4.34315 2 3 3.34315 3 5C3 6.65685 4.34315 8 6 8Z"></path>
                </svg>
            </div>
            
            <!-- Welcome Text with Enhanced Typography -->
            <div class="flex flex-col items-center">
                <span class="text-xs uppercase tracking-wider text-white/70">Welcome to your dashboard</span>
                <span class="text-xl font-bold tracking-wide text-white relative group">
                    {{ ucfirst(Auth::user()->first_name) }}
                    <!-- Underline Animation on Hover -->
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-yellow-300 group-hover:w-full transition-all duration-300"></span>
                </span>
            </div>
            
            <!-- Decorative Status Indicator -->
            <div class="flex items-center">
                <span class="relative flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                </span>
                <span class="ml-2 text-xs text-white/70">Online</span>
            </div>
        </div>
    </div>

    <!-- Right Column: User Role Button with Dropdown -->
    <div class="flex-1 flex justify-end">
        <div class="relative inline-block">
            <!-- Role Button - Enhanced for prominence -->
            <button
                id="adminButton"
                class="bg-white text-[#0284c7] px-4 py-2 rounded-lg inline-flex items-center space-x-2 hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-white focus:ring-opacity-50 shadow-md transition-all duration-200 font-medium"
            >
                <!-- User Avatar Circle -->
                <div class="bg-[#0284c7] text-white rounded-full h-8 w-8 flex items-center justify-center mr-1 border-2 border-white">
                    <span class="font-bold text-sm">{{ Auth::check() ? strtoupper(substr(Auth::user()->first_name, 0, 1)) : 'G' }}</span>
                </div>
                
                <!-- Role Display with Badge Style -->
                <div class="flex flex-col items-start">
                    <span class="text-xs text-[#0284c7] opacity-80">Your Role</span>
                    <div class="flex items-center">
                        <!-- Role Badge -->
                        <span class="font-semibold">
                            {{ Auth::check() ? ucfirst(Auth::user()->role) : 'Guest' }}
                        </span>
                        
                        <!-- Animated Dropdown Arrow -->
                        <svg class="h-5 w-5 ml-1 transition-transform duration-200 group-hover:rotate-180" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>
            </button>

            <!-- Dropdown Menu (hidden by default) - Enhanced styling -->
            <div
                id="adminDropdown"
                class="hidden absolute top-full right-0 mt-2 w-48 bg-white rounded-lg shadow-xl z-10 border border-gray-100 overflow-hidden transition-all duration-200 transform origin-top-right"
            >
                <!-- Profile Link -->
                <a
                    href="{{ route('profile.edit') }}"
                    class="flex items-center space-x-3 px-4 py-3 hover:bg-gray-50 transition-colors duration-150 border-b border-gray-100"
                >
                    <div class="bg-[#0284c7] bg-opacity-10 p-2 rounded-full">
                        <!-- Inline SVG for Profile Icon -->
                        <svg
                            width="20px"
                            height="20px"
                            viewBox="0 0 24 24"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                            class="text-[#0284c7]"
                        >
                            <path
                                d="M12 12C14.2091 12 16 10.2091 16 8C16 5.79086 14.2091 4 12 4C9.79086 4 8 5.79086 8 8C8 10.2091 9.79086 12 12 12Z"
                                stroke="#0284c7"
                                stroke-width="1.5"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                            <path
                                d="M20 19C20 16.7909 16.4183 15 12 15C7.58172 15 4 16.7909 4 19"
                                stroke="#0284c7"
                                stroke-width="1.5"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                        </svg>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-medium text-gray-800">Profile</span>
                        <span class="text-xs text-gray-500">View and edit your profile</span>
                    </div>
                </a>

                <!-- Logout Form -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a
                        href="#"
                        class="flex items-center space-x-3 px-4 py-3 hover:bg-gray-50 transition-colors duration-150 text-red-600"
                        onclick="event.preventDefault(); this.closest('form').submit();"
                    >
                        <div class="bg-red-100 p-2 rounded-full">
                            <!-- Inline SVG for Logout Icon -->
                            <svg
                                width="20px"
                                height="20px"
                                viewBox="0 0 24 24"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                                class="text-red-600"
                            >
                                <path
                                    d="M15 12L6 12M6 12L8 14M6 12L8 10"
                                    stroke="currentColor"
                                    stroke-width="1.5"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                                <path
                                    d="M12 8V7C12 5.89543 12.8954 5 14 5H18C19.1046 5 20 5.89543 20 7V17C20 18.1046 19.1046 19 18 19H14C12.8954 19 12 18.1046 12 17V16"
                                    stroke="currentColor"
                                    stroke-width="1.5"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                            </svg>
                        </div>
                        <div class="flex flex-col">
                            <span class="font-medium">Logout</span>
                            <span class="text-xs text-red-400">Sign out of your account</span>
                        </div>
                    </a>
                </form>
            </div>
        </div>
    </div>
</nav>

<style>
    @keyframes wave {
        0% { transform: rotate(0deg); }
        10% { transform: rotate(14deg); }
        20% { transform: rotate(-8deg); }
        30% { transform: rotate(14deg); }
        40% { transform: rotate(-4deg); }
        50% { transform: rotate(10deg); }
        60% { transform: rotate(0deg); }
        100% { transform: rotate(0deg); }
    }
    
    .animate-wave {
        animation: wave 2.5s ease infinite;
        transform-origin: 70% 70%;
    }
    
    .animate-pulse-slow {
        animation: pulse 6s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.9; }
    }
</style>

<script>
    // JavaScript to toggle dropdown visibility
    document.addEventListener('DOMContentLoaded', function() {
        const adminButton = document.getElementById('adminButton');
        const adminDropdown = document.getElementById('adminDropdown');
        
        // Toggle dropdown when button is clicked
        adminButton.addEventListener('click', function() {
            
            // Add animation classes when showing
            if (!adminDropdown.classList.contains('hidden')) {
                adminDropdown.classList.add('animate-in');
                adminButton.classList.add('ring-2', 'ring-white', 'ring-opacity-50');
            } else {
                adminButton.classList.remove('ring-2', 'ring-white', 'ring-opacity-50');
            }
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            if (!adminButton.contains(event.target) && !adminDropdown.contains(event.target)) {
                adminDropdown.classList.add('hidden');
                adminButton.classList.remove('ring-2', 'ring-white', 'ring-opacity-50');
            }
        });
    });
</script>