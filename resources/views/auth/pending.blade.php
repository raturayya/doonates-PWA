@extends('layouts.app')

@section('title', 'Registration Pending')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 via-white to-green-50 flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-yellow-100 mb-6">
                <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>

            <h1 class="text-gray-900 mb-3">Registration Submitted</h1>

            <div class="flex justify-center mb-6">
                <x-badge status="Pending" />
            </div>

            <p class="text-gray-600 mb-8">
                Your organization registration is pending admin verification. You will receive an email notification once your account has been approved.
            </p>

            <a
                href="{{ route('login') }}"
                class="inline-block w-full bg-gray-100 text-gray-700 py-3 rounded-lg hover:bg-gray-200 transition-colors"
            >
                Back to Login
            </a>
        </div>
    </div>
</div>
@endsection

