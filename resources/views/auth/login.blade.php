@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<div x-data="{ isLogin: {{ old('_from') === 'register' ? 'false' : 'true' }}, registerType: '{{ old('register_type', 'organization') }}' }" class="min-h-screen bg-gradient-to-br from-green-50 via-white to-green-50 flex">
    <div class="flex-1 flex items-center justify-center p-8">
        <div class="w-full max-w-md">
            <div class="text-center mb-8">
                <div class="inline-block mb-6">
                    <x-logo size="lg" :showText="true" />
                </div>
                <h1 class="text-gray-900 mb-2" x-text="isLogin ? 'Welcome Back!' : 'Join Doonates'"></h1>
                <p class="text-gray-600" x-text="isLogin ? 'Sign in to continue your impact' : 'Start making a difference today'"></p>
            </div>

            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
                <!-- Tab Buttons -->
                <div class="flex gap-2 mb-6">
                    <button
                        @click="isLogin = true"
                        :class="isLogin ? 'bg-[#2E7D32] text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                        class="flex-1 py-2.5 rounded-lg transition-all"
                    >
                        Login
                    </button>
                    <button
                        @click="isLogin = false"
                        :class="!isLogin ? 'bg-[#2E7D32] text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                        class="flex-1 py-2.5 rounded-lg transition-all"
                    >
                        Register
                    </button>
                </div>

                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}" x-show="isLogin" class="space-y-5">
                    @csrf

                    <div>
                        <label class="block text-gray-700 mb-2">Email Address</label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <input
                                type="email"
                                name="email"
                                required
                                value="{{ old('email') }}"
                                class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2E7D32] focus:border-transparent"
                                placeholder="organization@example.com"
                            />
                        </div>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-gray-700 mb-2">Password</label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            <input
                                type="password"
                                name="password"
                                required
                                class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2E7D32] focus:border-transparent"
                                placeholder="Enter your password"
                            />
                        </div>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="remember" class="w-4 h-4 text-[#2E7D32] border-gray-300 rounded focus:ring-[#2E7D32]" />
                            <span class="text-sm text-gray-600">Remember me</span>
                        </label>
                        <a href="{{ route('password.request') }}" class="text-sm text-[#2E7D32] hover:underline">Forgot password?</a>
                    </div>

                    <button
                        type="submit"
                        class="w-full bg-[#2E7D32] text-white py-3 rounded-lg hover:bg-[#25662a] transition-all flex items-center justify-center gap-2 group shadow-lg shadow-green-900/20"
                    >
                        Sign In
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </button>
                </form>

                <!-- Register Form -->
                <form method="POST" action="{{ route('register') }}" x-show="!isLogin" class="space-y-4" style="display: none;">
                    @csrf

                    {{-- Validation errors summary --}}
                    @if($errors->any() && old('_from') === 'register')
                    <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                        @foreach($errors->all() as $error)
                            <p class="text-sm text-red-600">{{ $error }}</p>
                        @endforeach
                    </div>
                    @endif

                    {{-- Hidden field to identify which form submitted --}}
                    <input type="hidden" name="_from" value="register">

                    {{-- Register type toggle --}}
                    <div class="flex gap-2 p-1 bg-gray-100 rounded-lg">
                        <button type="button"
                            @click="registerType = 'organization'"
                            :class="registerType === 'organization' ? 'bg-white shadow text-gray-900' : 'text-gray-500 hover:text-gray-700'"
                            class="flex-1 py-2 rounded-md text-sm font-medium transition-all">
                            🏢 Organization
                        </button>
                        <button type="button"
                            @click="registerType = 'user'"
                            :class="registerType === 'user' ? 'bg-white shadow text-gray-900' : 'text-gray-500 hover:text-gray-700'"
                            class="flex-1 py-2 rounded-md text-sm font-medium transition-all">
                            👤 Individual
                        </button>
                    </div>
                    <input type="hidden" name="register_type" :value="registerType">

                    {{-- Organization fields --}}
                    <div x-show="registerType === 'organization'" class="space-y-4">
                        <div>
                            <label class="block text-gray-700 mb-1.5 text-sm">Organization Name</label>
                            <input type="text" name="organization_name" value="{{ old('organization_name') }}"
                                class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2E7D32] focus:border-transparent"
                                placeholder="Enter organization name" />
                            @error('organization_name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-gray-700 mb-1.5 text-sm">Organization Type</label>
                            <select name="organization_type"
                                class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2E7D32] focus:border-transparent">
                                <option value="">Select type</option>
                                <option value="Restaurant" {{ old('organization_type') === 'Restaurant' ? 'selected' : '' }}>Restaurant</option>
                                <option value="Hotel" {{ old('organization_type') === 'Hotel' ? 'selected' : '' }}>Hotel</option>
                                <option value="Catering" {{ old('organization_type') === 'Catering' ? 'selected' : '' }}>Catering</option>
                                <option value="Social Institution" {{ old('organization_type') === 'Social Institution' ? 'selected' : '' }}>Social Institution</option>
                            </select>
                            @error('organization_type')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-gray-700 mb-1.5 text-sm">Phone Number</label>
                            <input type="tel" name="phone" value="{{ old('phone') }}"
                                class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2E7D32] focus:border-transparent"
                                placeholder="08xx-xxxx-xxxx" />
                            @error('phone')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-gray-700 mb-1.5 text-sm">Address</label>
                            <textarea name="address" rows="2"
                                class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2E7D32] focus:border-transparent resize-none"
                                placeholder="Full address">{{ old('address') }}</textarea>
                            @error('address')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    {{-- Individual user fields --}}
                    <div x-show="registerType === 'user'" class="space-y-4">
                        <div>
                            <label class="block text-gray-700 mb-1.5 text-sm">Full Name</label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2E7D32] focus:border-transparent"
                                placeholder="Your full name" />
                            @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-gray-700 mb-1.5 text-sm">Phone Number</label>
                            <input type="tel" name="user_phone" value="{{ old('user_phone') }}"
                                class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2E7D32] focus:border-transparent"
                                placeholder="08xx-xxxx-xxxx" />
                        </div>
                        <div>
                            <label class="block text-gray-700 mb-1.5 text-sm">Address</label>
                            <textarea name="user_address" rows="2"
                                class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2E7D32] focus:border-transparent resize-none"
                                placeholder="Full address">{{ old('user_address') }}</textarea>
                        </div>
                    </div>

                    {{-- Shared fields --}}
                    <div>
                        <label class="block text-gray-700 mb-1.5 text-sm">Email Address</label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2E7D32] focus:border-transparent"
                                placeholder="you@example.com" />
                        </div>
                        @error('email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-gray-700 mb-1.5 text-sm">Password</label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            <input type="password" name="password" required
                                class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2E7D32] focus:border-transparent"
                                placeholder="Create a password" />
                        </div>
                        @error('password')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-gray-700 mb-1.5 text-sm">Confirm Password</label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            <input type="password" name="password_confirmation" required
                                class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2E7D32] focus:border-transparent"
                                placeholder="Repeat your password" />
                        </div>
                    </div>

                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                        <p class="text-sm text-blue-800">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span x-text="registerType === 'organization' ? 'Organization accounts require admin verification before activation.' : 'Individual accounts require admin verification before activation.'"></span>
                        </p>
                    </div>

                    <button type="submit"
                        class="w-full bg-[#2E7D32] text-white py-3 rounded-lg hover:bg-[#25662a] transition-all flex items-center justify-center gap-2 group shadow-lg shadow-green-900/20">
                        Create Account
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Right Side Illustration -->
    <div class="hidden lg:flex flex-1 bg-gradient-to-br from-[#2E7D32] via-[#388E3C] to-[#1b5e20] items-center justify-center p-12 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-20 left-20 w-64 h-64 bg-white rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 right-20 w-96 h-96 bg-white rounded-full blur-3xl"></div>
        </div>

        <div class="max-w-md text-white text-center relative z-10">
            <div class="mb-8">
                <div class="w-80 h-80 mx-auto relative">
                    <div class="absolute inset-0 bg-white/10 rounded-full flex items-center justify-center backdrop-blur-sm">
                        <!-- Food donation illustration SVG -->
                        <svg viewBox="0 0 200 200" class="w-48 h-48">
                            <circle cx="100" cy="100" r="80" fill="white" opacity="0.1" />
                            <path d="M100 40C70 40 60 50 60 70C60 80 64 86 70 90C64 94 60 100 60 110C60 130 70 140 100 140C130 140 140 130 140 110C140 100 136 94 130 90C136 86 140 80 140 70C140 50 130 40 100 40Z" fill="white" opacity="0.9" />
                            <circle cx="85" cy="75" r="5" fill="#2E7D32" />
                            <circle cx="115" cy="75" r="5" fill="#2E7D32" />
                            <path d="M85 105C85 105 90 110 100 110C110 110 115 105 115 105" stroke="#2E7D32" stroke-width="4" stroke-linecap="round" fill="none" />
                            <path d="M60 50C60 50 70 35 100 35C130 35 140 50 140 50" stroke="white" stroke-width="5" stroke-linecap="round" fill="none" opacity="0.5" />
                        </svg>
                    </div>
                </div>
            </div>

            <h2 class="mb-4">Transform Food Waste into Impact</h2>
            <p class="text-green-100 mb-8 text-lg">
                Join a thriving community of organizations collaborating to reduce food waste and create positive social change.
            </p>

            <div class="space-y-4">
                <div class="flex items-center gap-3 bg-white/10 backdrop-blur-sm rounded-lg p-4">
                    <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <p class="text-white">Connect with verified organizations</p>
                </div>

                <div class="flex items-center gap-3 bg-white/10 backdrop-blur-sm rounded-lg p-4">
                    <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </div>
                    <p class="text-white">Make meaningful social impact</p>
                </div>

                <div class="flex items-center gap-3 bg-white/10 backdrop-blur-sm rounded-lg p-4">
                    <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                        </svg>
                    </div>
                    <p class="text-white">Reduce food waste together</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
