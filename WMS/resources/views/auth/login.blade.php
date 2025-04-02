<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
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
          <a href="#" onclick="toggleModal('forgotPasswordModal'); return false;" class="text-sm text-gray-500 hover:underline">
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

  <!-- Forgot Password Modal -->
  <div id="forgotPasswordModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center pb-3 border-b">
            <h3 class="text-xl font-semibold text-gray-900">Reset Password</h3>
            <button onclick="toggleModal('forgotPasswordModal')" class="text-gray-400 hover:text-gray-500">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <div class="mt-4">
            <p class="text-sm text-gray-600 mb-4">Enter your email address and we'll send you a link to reset your password.</p>
            
            <!-- Error messages container -->
            <div id="resetFormErrors" class="mb-4 hidden">
                <ul class="list-disc list-inside text-sm text-red-600">
                </ul>
            </div>
            
            <!-- Success message container -->
            <div id="resetFormSuccess" class="mb-4 hidden">
                <p class="text-sm text-green-600"></p>
            </div>
            
            <form id="passwordResetForm" method="POST" action="{{ route('password.email') }}" class="space-y-5 text-left">
                @csrf
                <div>
                    <label for="email_reset" class="block text-sm font-medium text-gray-700">Email Address</label>
                    <div class="mt-1">
                        <input type="email" name="email" id="email_reset" required
                            class="shadow-sm focus:ring-[#0284c7] focus:border-[#0284c7] block w-full sm:text-sm border-gray-300 rounded-md"
                            placeholder="Enter your email">
                    </div>
                </div>
                
                <div class="flex items-center justify-end pt-4 border-t border-gray-200">
                    <button type="button" onclick="toggleModal('forgotPasswordModal')" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0284c7] mr-3">
                        Cancel
                    </button>
                    <button type="submit" id="resetSubmitBtn" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-[#0284c7] hover:bg-[#0369a1] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0284c7]">
                        Send Reset Link
                    </button>
                </div>
            </form>
        </div>
    </div>
  </div>

  <script>
    function toggleModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal.classList.contains('hidden')) {
            modal.classList.remove('hidden');
            // Prevent background scrolling
            document.body.style.overflow = 'hidden';
            // Add fade-in animation
            modal.classList.add('animate-fade-in');
        } else {
            // Add fade-out animation
            modal.classList.add('animate-fade-out');
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('animate-fade-out', 'animate-fade-in');
                // Restore background scrolling
                document.body.style.overflow = 'auto';
            }, 200);
        }
    }
    
    // Handle password reset form submission
    document.addEventListener('DOMContentLoaded', function() {
        const resetForm = document.getElementById('passwordResetForm');
        const resetBtn = document.getElementById('resetSubmitBtn');
        const errorsContainer = document.getElementById('resetFormErrors');
        const successContainer = document.getElementById('resetFormSuccess');
        
        if (resetForm) {
            resetForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Clear previous messages
                errorsContainer.classList.add('hidden');
                errorsContainer.querySelector('ul').innerHTML = '';
                successContainer.classList.add('hidden');
                
                // Get the email value
                const emailInput = document.getElementById('email_reset');
                const email = emailInput.value.trim();
                
                // First, check if the email exists in the database
                fetch('{{ route("api.check.email") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ email: email })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok: ' + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    if (!data.exists) {
                        // Email doesn't exist in database
                        errorsContainer.classList.remove('hidden');
                        const errorsList = errorsContainer.querySelector('ul');
                        const li = document.createElement('li');
                        li.textContent = 'We could not find a user with that email address.';
                        errorsList.appendChild(li);
                        return;
                    }
                    
                    // Email exists, proceed with password reset
                    
                    // Show loading state
                    resetBtn.disabled = true;
                    resetBtn.innerHTML = '<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Processing...';
                    
                    const formData = new FormData(resetForm);
                    
                    fetch(resetForm.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        redirect: 'follow'
                    })
                    .then(response => {
                        // Check if response is JSON
                        const contentType = response.headers.get('content-type');
                        if (contentType && contentType.includes('application/json')) {
                            return response.json().then(data => {
                                return { type: 'json', status: response.status, data: data };
                            });
                        } else {
                            // Handle HTML responses (typical Laravel redirect with message in session)
                            return response.text().then(text => {
                                return { type: 'html', status: response.status, data: text };
                            });
                        }
                    })
                    .then(result => {
                        resetBtn.disabled = false;
                        resetBtn.innerHTML = 'Send Reset Link';
                        
                        if (result.type === 'json') {
                            // Handle JSON response
                            if (result.data.errors) {
                                // Show validation errors
                                errorsContainer.classList.remove('hidden');
                                const errorsList = errorsContainer.querySelector('ul');
                                
                                Object.keys(result.data.errors).forEach(field => {
                                    result.data.errors[field].forEach(message => {
                                        const li = document.createElement('li');
                                        li.textContent = message;
                                        errorsList.appendChild(li);
                                    });
                                });
                            } else if (result.data.message) {
                                // Show success message
                                successContainer.classList.remove('hidden');
                                successContainer.querySelector('p').textContent = result.data.message;
                                resetForm.reset();
                            } else {
                                // Unknown JSON response format
                                errorsContainer.classList.remove('hidden');
                                const errorsList = errorsContainer.querySelector('ul');
                                const li = document.createElement('li');
                                li.textContent = 'Received an unexpected response from the server.';
                                errorsList.appendChild(li);
                                console.error('Unexpected JSON response:', result.data);
                            }
                        } else {
                            // For successful redirects, show a generic success message
                            if (result.status >= 200 && result.status < 300) {
                                successContainer.classList.remove('hidden');
                                successContainer.querySelector('p').textContent = 'Password reset link sent successfully. Please check your email.';
                                resetForm.reset();
                            } else {
                                // Show error for non-successful redirect
                                errorsContainer.classList.remove('hidden');
                                const errorsList = errorsContainer.querySelector('ul');
                                const li = document.createElement('li');
                                li.textContent = 'The server returned an error. Please try again.';
                                errorsList.appendChild(li);
                                console.error('Error response:', result.status, result.data);
                            }
                        }
                    })
                    .catch(error => {
                        resetBtn.disabled = false;
                        resetBtn.innerHTML = 'Send Reset Link';
                        
                        // Show generic error with more details
                        errorsContainer.classList.remove('hidden');
                        const errorsList = errorsContainer.querySelector('ul');
                        const li = document.createElement('li');
                        li.textContent = 'An error occurred. Please try again.';
                        errorsList.appendChild(li);
                        console.error('Fetch error:', error);
                    });
                })
                .catch(error => {
                    // Error checking email existence
                    console.error('Email check error:', error);
                    
                    // More detailed error message with debugging info
                    errorsContainer.classList.remove('hidden');
                    const errorsList = errorsContainer.querySelector('ul');
                    const li = document.createElement('li');
                    li.textContent = 'Unable to verify email. Please try again later. (' + error.message + ')';
                    errorsList.appendChild(li);
                });
            });
        }
    });
  </script>

  <style>
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    @keyframes fadeOut {
        from { opacity: 1; }
        to { opacity: 0; }
    }
    
    .animate-fade-in {
        animation: fadeIn 0.2s ease-in-out forwards;
    }
    
    .animate-fade-out {
        animation: fadeOut 0.2s ease-in-out forwards;
    }
  </style>
</body>
</html>