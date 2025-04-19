@php
    use Illuminate\Support\Facades\Auth;
@endphp
<nav class="bg-[#0284c7] text-white px-6 py-4 flex items-center">
    <!-- Left Column: Logo -->
    <div class="flex-1">
        <a href="{{ route('dashboard.redirect') }}" class="logo-container">
            <div class="flex items-center">
                <div class="logo-wrapper" style="width: 3.5rem; height: 3.5rem;">
                    <!-- Main logo image -->
                    <img src="{{ asset('logo/logo.png') }}" alt="YHROCU Logo" class="logo-image">
                    <!-- Animated accent element -->
                    <div class="logo-accent"></div>
                </div>
                <div class="logo-text ml-3">
                    <span class="text-2xl font-bold">YHROCU</span>
                    <span class="text-sm block">Workflow Management System</span>
                </div>
            </div>
        </a>
    </div>

    <!-- Center Column: Welcome Message -->
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

            <!-- Welcome Text -->
            <div class="flex flex-col items-center relative">
                <span class="text-xs uppercase tracking-wider text-white/70">
                    Welcome to your dashboard,
                </span>
                <span class="font-semibold">
                    {{ Auth::check() ? ucfirst(Auth::user()->first_name) : 'Guest' }}
                </span>
                <!-- Underline Animation (optional) -->
                <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-yellow-300 transition-all duration-300 group-hover:w-full"></span>
            </div>

            <!-- Status Indicator -->
            <div class="flex items-center">
                <span class="relative flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                </span>
                <span class="ml-2 text-xs text-white/70">Online</span>
            </div>
        </div>
    </div>

    <!-- Right Column: User Role Dropdown -->
    <div class="flex-1 flex justify-end">
        <div class="relative inline-block">
            <!-- Role Button -->
            <button id="adminButton" class="bg-white text-[#0284c7] px-4 py-2 rounded-lg inline-flex items-center space-x-2 hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-white focus:ring-opacity-50 shadow-md transition-all duration-200 font-medium">
                <!-- User Avatar -->
                <div class="bg-[#0284c7] text-white rounded-full h-8 w-8 flex items-center justify-center border-2 border-white">
                    <span class="font-bold text-sm">
                        {{ Auth::check() ? strtoupper(substr(Auth::user()->first_name, 0, 1)) : 'G' }}
                    </span>
                </div>
                <!-- Role Display -->
                <div class="flex flex-col items-start">
                    <span class="text-xs text-[#0284c7] opacity-80">Your Role</span>
                    <div class="flex items-center">
                        <span class="font-semibold">
                            {{ Auth::check() ? ucfirst(Auth::user()->role) : 'Guest' }}
                        </span>
                        <!-- Dropdown Arrow -->
                        <svg class="h-5 w-5 ml-1 transition-transform duration-200 transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>
            </button>

            <!-- Dropdown Menu -->
            <div id="adminDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl z-50 border border-gray-100 overflow-hidden transform transition-all duration-200 ease-out">
                <!-- Profile Link -->
                <a href="{{ route('profile.edit') }}" class="flex items-center space-x-3 px-4 py-3 hover:bg-gray-50 transition-colors duration-150 border-b border-gray-100">
                    <div class="bg-[#0284c7] bg-opacity-10 p-2 rounded-full">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#0284c7" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 12C14.2091 12 16 10.2091 16 8C16 5.79086 14.2091 4 12 4C9.79086 4 8 5.79086 8 8C8 10.2091 9.79086 12 12 12Z"></path>
                            <path d="M20 19C20 16.7909 16.4183 15 12 15C7.58172 15 4 16.7909 4 19"></path>
                        </svg>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-medium text-gray-800">Profile</span>
                        <span class="text-xs text-gray-500">View and edit your profile</span>
                    </div>
                </a>

                <!-- Logout -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="#" class="flex items-center space-x-3 px-4 py-3 hover:bg-gray-50 transition-colors duration-150 text-red-600" onclick="event.preventDefault(); this.closest('form').submit();">
                        <div class="bg-red-100 p-2 rounded-full">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M15 12L6 12"></path>
                                <path d="M6 12L8 14"></path>
                                <path d="M6 12L8 10"></path>
                                <path d="M12 8V7C12 5.89543 12.8954 5 14 5H18C19.1046 5 20 5.89543 20 7V17C20 18.1046 19.1046 19 18 19H14C12.8954 19 12 18.1046 12 17V16"></path>
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
</nav><script>
    window.addEventListener('DOMContentLoaded', () => {
        const adminButton = document.getElementById('adminButton');
        const adminDropdown = document.getElementById('adminDropdown');

        console.log("Admin Button Element:", adminButton);
        console.log("Admin Dropdown Element:", adminDropdown);

        if (!adminButton || !adminDropdown) {
            console.log("Admin button or dropdown not found!");
            return;
        }

        // Ensure the dropdown starts hidden (redundant if HTML is correct, but safer)
        if (!adminDropdown.classList.contains('hidden')) {
        }

        // Toggle visibility
        adminButton.addEventListener('click', (e) => {
            e.stopPropagation();
            console.log("Admin button clicked!");
            if (adminDropdown.classList.contains('hidden')) {
                adminDropdown.classList.remove('hidden');
                console.log("Hidden class removed. Current classList:", adminDropdown.classList);
            } else {
                console.log("Hidden class added. Current classList:", adminDropdown.classList);
            }
        });

        // Click outside to close
        document.addEventListener('click', (e) => {
            if (!adminDropdown.classList.contains('hidden') &&
                !adminButton.contains(e.target) &&
                !adminDropdown.contains(e.target)
            ) {
                console.log("Click outside detected, hiding dropdown.");
                adminDropdown.classList.add('hidden');
            }
        });

        // ESC to close
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                console.log("Escape key pressed, hiding dropdown.");
                adminDropdown.classList.add('hidden');
            }
        });
    });
</script>

