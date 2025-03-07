@extends('layouts.app-no-sidebar')

@section('title', 'Profile')

@section('content')
    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold text-gray-800">User Management</h2>
                        <a href="{{ route('logout') }}" 
                           class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 hover:text-gray-900" 
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 001 1h12a1 1 0 001-1V4a1 1 0 00-1-1H3zm4 10a1 1 0 110-2h4a1 1 0 110 2H7z" clip-rule="evenodd" />
                            </svg>
                            Log Out
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                            @csrf
                        </form>
                    </div>

                    <!-- Success Message -->
                    @if (session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <div class="flex">
                        <!-- Sidebar -->
                        <div class="w-1/4 pr-4">
                            <div class="space-y-2">
                                <button type="button" onclick="toggleModal('addUserModal')" style="background-color: #FFD100;" class="text-black w-full flex items-center justify-center py-2 px-4 rounded-md shadow-sm hover:bg-yellow-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6z" />
                                        <path d="M16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z" />
                                    </svg>
                                    Add User
                                </button>
                                <button type="button" class="bg-white text-gray-700 border border-gray-300 w-full flex items-center justify-center py-2 px-4 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                    </svg>
                                    Reset Password
                                </button>
                            </div>
                        </div>

                        <!-- Main Content -->
                        <div class="w-3/4">
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white">
                                    <thead>
                                        <tr class="bg-gray-50 text-gray-600 uppercase text-sm leading-normal">
                                            <th class="py-3 px-6 text-left">First Name</th>
                                            <th class="py-3 px-6 text-left">Last Name</th>
                                            <th class="py-3 px-6 text-left">Username</th>
                                            <th class="py-3 px-6 text-left">Email</th>
                                            <th class="py-3 px-6 text-left">Phone</th>
                                            <th class="py-3 px-6 text-left">Role</th>
                                            <th class="py-3 px-6 text-left">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-gray-600 text-sm">
                                        @forelse ($users as $user)
                                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                                <td class="py-3 px-6 text-left">{{ $user->first_name }}</td>
                                                <td class="py-3 px-6 text-left">{{ $user->last_name }}</td>
                                                <td class="py-3 px-6 text-left">{{ $user->username }}</td>
                                                <td class="py-3 px-6 text-left">{{ $user->email }}</td>
                                                <td class="py-3 px-6 text-left">{{ $user->phone_number }}</td>
                                                <td class="py-3 px-6 text-left">{{ ucfirst($user->role) }}</td>
                                                <td class="py-3 px-6 text-left">
                                                    <div class="flex items-center space-x-2">
                                                        <button onclick="viewUser({{ $user->id }})" class="text-black hover:text-gray-700">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                                            </svg>
                                                        </button>
                                                        <button onclick="editUser({{ $user->id }})" class="text-black hover:text-gray-700">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                                            </svg>
                                                        </button>
                                                        <form action="{{ route('admin.user_management.destroy', $user->id) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-black hover:text-gray-700" onclick="return confirm('Are you sure you want to delete this user?')">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                                </svg>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="py-6 px-6 text-center text-gray-500">No users found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add User Modal -->
    <div id="addUserModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Add New User</h3>
                <div class="mt-2 px-7 py-3">
                    <form action="{{ route('admin.user_management.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="first_name" class="block text-gray-700 text-sm font-bold mb-2 text-left">First Name</label>
                            <input type="text" name="first_name" id="first_name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        </div>
                        <div class="mb-4">
                            <label for="last_name" class="block text-gray-700 text-sm font-bold mb-2 text-left">Last Name</label>
                            <input type="text" name="last_name" id="last_name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        </div>
                        <div class="mb-4">
                            <label for="username" class="block text-gray-700 text-sm font-bold mb-2 text-left">Username</label>
                            <input type="text" name="username" id="username" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        </div>
                        <div class="mb-4">
                            <label for="email" class="block text-gray-700 text-sm font-bold mb-2 text-left">Email</label>
                            <input type="email" name="email" id="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        </div>
                        <div class="mb-4">
                            <label for="phone_number" class="block text-gray-700 text-sm font-bold mb-2 text-left">Phone Number</label>
                            <input type="text" name="phone_number" id="phone_number" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div class="mb-4">
                            <label for="role" class="block text-gray-700 text-sm font-bold mb-2 text-left">Role</label>
                            <select name="role" id="role" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                <option value="admin">Admin</option>
                                <option value="supervisor">Supervisor</option>
                                <option value="staff">Staff</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="password" class="block text-gray-700 text-sm font-bold mb-2 text-left">Password</label>
                            <input type="password" name="password" id="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        </div>
                        <div class="mb-4">
                            <label for="password_confirmation" class="block text-gray-700 text-sm font-bold mb-2 text-left">Confirm Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        </div>
                        <div class="flex items-center justify-between mt-4">
                            <button type="button" onclick="toggleModal('addUserModal')" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Cancel
                            </button>
                            <button type="submit" style="background-color: #FFD100;" class="text-black font-bold py-2 px-4 rounded hover:bg-yellow-400 focus:outline-none focus:shadow-outline">
                                Add User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal.classList.contains('hidden')) {
        modal.classList.remove('hidden');
    } else {
        modal.classList.add('hidden');
    }
}

function viewUser(userId) {
    fetch(`/admin/user-management/users/${userId}`)
        .then(response => response.json())
        .then(user => {
            if (!document.getElementById('viewUserModal')) {
                const modalHtml = `
                    <div id="viewUserModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
                        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                            <div class="mt-3 text-center">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">User Details</h3>
                                <div class="mt-2 px-7 py-3 text-left">
                                    <div class="mb-4">
                                        <p class="text-sm font-bold text-gray-700">First Name:</p>
                                        <p id="view_first_name" class="text-gray-600"></p>
                                    </div>
                                    <div class="mb-4">
                                        <p class="text-sm font-bold text-gray-700">Last Name:</p>
                                        <p id="view_last_name" class="text-gray-600"></p>
                                    </div>
                                    <div class="mb-4">
                                        <p class="text-sm font-bold text-gray-700">Username:</p>
                                        <p id="view_username" class="text-gray-600"></p>
                                    </div>
                                    <div class="mb-4">
                                        <p class="text-sm font-bold text-gray-700">Email:</p>
                                        <p id="view_email" class="text-gray-600"></p>
                                    </div>
                                    <div class="mb-4">
                                        <p class="text-sm font-bold text-gray-700">Phone Number:</p>
                                        <p id="view_phone_number" class="text-gray-600"></p>
                                    </div>
                                    <div class="mb-4">
                                        <p class="text-sm font-bold text-gray-700">Role:</p>
                                        <p id="view_role" class="text-gray-600"></p>
                                    </div>
                                    <div class="mb-4">
                                        <p class="text-sm font-bold text-gray-700">Created At:</p>
                                        <p id="view_created_at" class="text-gray-600"></p>
                                    </div>
                                    <div class="flex items-center justify-center mt-4">
                                        <button type="button" onclick="toggleModal('viewUserModal')" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                            Close
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                document.body.insertAdjacentHTML('beforeend', modalHtml);
            }

            // Fill the modal with user data
            document.getElementById('view_first_name').textContent = user.first_name;
            document.getElementById('view_last_name').textContent = user.last_name;
            document.getElementById('view_username').textContent = user.username;
            document.getElementById('view_email').textContent = user.email;
            document.getElementById('view_phone_number').textContent = user.phone_number || 'N/A';
            document.getElementById('view_role').textContent = user.role.charAt(0).toUpperCase() + user.role.slice(1);
            document.getElementById('view_created_at').textContent = new Date(user.created_at).toLocaleString();

            // Show the modal
            toggleModal('viewUserModal');
        })
        .catch(error => {
            console.error('Error fetching user data:', error);
            alert('Failed to load user details. Please try again.');
        });
}

function editUser(userId) {
    fetch(`/admin/user-management/users/${userId}`)
        .then(response => response.json())
        .then(user => {
            if (!document.getElementById('editUserModal')) {
                const modalHtml = `
                    <div id="editUserModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
                        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                            <div class="mt-3 text-center">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">Edit User</h3>
                                <div class="mt-2 px-7 py-3">
                                    <form id="editUserForm" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="mb-4">
                                            <label for="edit_first_name" class="block text-gray-700 text-sm font-bold mb-2 text-left">First Name</label>
                                            <input type="text" name="first_name" id="edit_first_name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                        </div>
                                        <div class="mb-4">
                                            <label for="edit_last_name" class="block text-gray-700 text-sm font-bold mb-2 text-left">Last Name</label>
                                            <input type="text" name="last_name" id="edit_last_name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                        </div>
                                        <div class="mb-4">
                                            <label for="edit_username" class="block text-gray-700 text-sm font-bold mb-2 text-left">Username</label>
                                            <input type="text" name="username" id="edit_username" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                        </div>
                                        <div class="mb-4">
                                            <label for="edit_email" class="block text-gray-700 text-sm font-bold mb-2 text-left">Email</label>
                                            <input type="email" name="email" id="edit_email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                        </div>
                                        <div class="mb-4">
                                            <label for="edit_phone_number" class="block text-gray-700 text-sm font-bold mb-2 text-left">Phone Number</label>
                                            <input type="text" name="phone_number" id="edit_phone_number" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                        </div>
                                        <div class="mb-4">
                                            <label for="edit_role" class="block text-gray-700 text-sm font-bold mb-2 text-left">Role</label>
                                            <select name="role" id="edit_role" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                                <option value="admin">Admin</option>
                                                <option value="supervisor">Supervisor</option>
                                                <option value="staff">Staff</option>
                                            </select>
                                        </div>
                                        <div class="flex items-center justify-between mt-4">
                                            <button type="button" onclick="toggleModal('editUserModal')" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                                Cancel
                                            </button>
                                            <button type="submit" style="background-color: #FFD100;" class="text-black font-bold py-2 px-4 rounded hover:bg-yellow-400 focus:outline-none focus:shadow-outline">
                                                Save Changes
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                document.body.insertAdjacentHTML('beforeend', modalHtml);
            }

            // Set the form action
            document.getElementById('editUserForm').action = `/admin/user-management/users/${userId}`;

            // Fill the form with user data
            document.getElementById('edit_first_name').value = user.first_name;
            document.getElementById('edit_last_name').value = user.last_name;
            document.getElementById('edit_username').value = user.username;
            document.getElementById('edit_email').value = user.email;
            document.getElementById('edit_phone_number').value = user.phone_number || '';
            document.getElementById('edit_role').value = user.role;

            // Show the modal
            toggleModal('editUserModal');
        })
        .catch(error => {
            console.error('Error fetching user data:', error);
            alert('Failed to load user details. Please try again.');
        });
}
    </script>
@endsection