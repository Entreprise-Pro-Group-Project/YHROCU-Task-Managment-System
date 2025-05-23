{{-- resources/views/profile/edit.blade.php --}}
@extends('layouts.app-no-sidebar')

@section('title', 'Profile')

@section('content')
    <!-- Top Bar: Page Title & Return Button -->
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-100">
            {{ __('Profile') }}
        </h2>
        <a href="{{ route('dashboard.redirect') }}"
           class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold
                  text-xs text-white uppercase tracking-widest hover:bg-blue-600 focus:outline-none focus:border-blue-700
                  focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
            {{ __('Return to Dashboard') }}
        </a>
    </div>

    <!-- Main Content -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Profile Information Section -->
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <section>
                    <header>
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ __('Profile Information') }}
                        </h2>
                    </header>

                    <div class="mt-6 space-y-6">
                        <!-- Name -->
                        <div>
                            <x-input-label for="name" :value="__('Name')" />
                            <p class="mt-1 block w-full text-gray-700 dark:text-gray-300">
                                {{ $user->name }}
                            </p>
                        </div>

                        <!-- Email -->
                        <div>
                            <x-input-label for="email" :value="__('Email')" />
                            <p class="mt-1 block w-full text-gray-700 dark:text-gray-300">
                                {{ $user->email }}
                            </p>

                            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                <div class="mt-2">
                                    <p class="text-sm text-gray-800 dark:text-gray-200">
                                        {{ __('Your email address is unverified.') }}
                                    </p>

                                    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                                        @csrf
                                        <button type="submit"
                                                class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900
                                                       dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2
                                                       focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                                            {{ __('Click here to re-send the verification email.') }}
                                        </button>
                                    </form>

                                    @if (session('status') === 'verification-link-sent')
                                        <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                                            {{ __('A new verification link has been sent to your email address.') }}
                                        </p>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <!-- Phone Number -->
                        <div>
                            <x-input-label for="phone_number" :value="__('Phone Number')" />
                            <p class="mt-1 block w-full text-gray-700 dark:text-gray-300">
                                {{ $user->phone_number }}
                            </p>
                        </div>

                        <!-- User Role -->
                        <div>
                            <x-input-label for="role" :value="__('User Role')" />
                            <p class="mt-1 block w-full text-gray-700 dark:text-gray-300">
                                {{ $user->role }}
                            </p>
                        </div>
                    </div>
                </section>
            </div>

            <!-- Update Password Section -->
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
        </div>
    </div>
@endsection
