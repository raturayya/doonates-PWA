@extends('layouts.auth')

@section('title', 'Awaiting Approval')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 via-white to-green-50 flex items-center justify-center p-4">
    <div class="w-full max-w-md">

        {{-- Logo --}}
        <div class="text-center mb-8">
            <x-logo size="lg" :showText="true" />
        </div>

        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8 text-center">

            {{-- Icon --}}
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-yellow-100 mb-6">
                <svg class="w-10 h-10 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>

            <h1 class="text-gray-900 text-2xl font-bold mb-2">Waiting for Approval</h1>

            <div class="flex justify-center mb-5">
                <x-badge status="Pending" />
            </div>

            {{-- Registered email --}}
            @if($user ?? null)
            <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 mb-5">
                <p class="text-xs text-gray-500 mb-1">Registered as</p>
                <p class="font-semibold text-gray-800">{{ $user->email }}</p>
                <p class="text-xs text-gray-500 mt-1">
                    Account type: <span class="font-medium capitalize">{{ $user->role }}</span>
                    &nbsp;·&nbsp;
                    Status: <span class="font-medium text-yellow-600">Pending</span>
                </p>
            </div>
            @elseif(session('registered_email'))
            <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 mb-5">
                <p class="text-xs text-gray-500 mb-1">Registered as</p>
                <p class="font-semibold text-gray-800">{{ session('registered_email') }}</p>
            </div>
            @endif

            {{-- Info message --}}
            <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 mb-6 text-left space-y-2">
                <div class="flex items-start gap-2">
                    <svg class="w-4 h-4 text-blue-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-sm text-blue-800">Your registration is under review by our admin team.</p>
                </div>
                <div class="flex items-start gap-2">
                    <svg class="w-4 h-4 text-blue-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <p class="text-sm text-blue-800">You will receive an <strong>email notification</strong> once your account is approved.</p>
                </div>
                <div class="flex items-start gap-2">
                    <svg class="w-4 h-4 text-blue-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-sm text-blue-800">This usually takes <strong>1–2 business days</strong>.</p>
                </div>
            </div>

            {{-- Status checked feedback --}}
            @if(session('status_checked'))
            <div class="bg-yellow-50 border border-yellow-200 rounded-xl px-4 py-3 mb-4">
                <p class="text-sm text-yellow-800 font-medium">Your account is still pending approval.</p>
            </div>
            @endif

            {{-- Actions --}}
            <div class="space-y-3">
                @auth
                <a href="{{ route('pending.check') }}"
                    class="block w-full bg-[#2E7D32] text-white py-3 rounded-xl hover:bg-[#25662a] transition-colors font-medium flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Refresh Status
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full border border-red-200 text-red-600 py-3 rounded-xl hover:bg-red-50 transition-colors font-medium">
                        Logout
                    </button>
                </form>
                @else
                <a href="{{ route('login') }}"
                    class="block w-full bg-gray-100 text-gray-700 py-3 rounded-xl hover:bg-gray-200 transition-colors font-medium">
                    Back to Login
                </a>
                @endauth
            </div>

        </div>

        <p class="text-center text-xs text-gray-400 mt-6">
            Need help? Contact <a href="mailto:admin@doonates.com" class="underline hover:text-gray-600">admin@doonates.com</a>
        </p>
    </div>
</div>
@endsection
