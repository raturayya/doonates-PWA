@extends('user.layouts.app')

@section('title', 'Edit Profile')

@section('content')
<div class="mb-6 flex items-center gap-4">
    <a href="{{ route('user.profile.index') }}" class="text-gray-500 hover:text-gray-700">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
    </a>
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Edit Profile</h1>
        <p class="text-gray-600">Update your account information</p>
    </div>
</div>

<div class="max-w-2xl bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <form action="{{ route('user.profile.update') }}" method="POST" class="space-y-5">
        @csrf
        @method('PUT')

        <div class="grid md:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2E7D32]">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" value="{{ $user->email }}" disabled
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-lg bg-gray-50 text-gray-500">
            </div>
        </div>

        <div class="grid md:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Organization Name</label>
                <input type="text" name="organization_name" value="{{ old('organization_name', $user->organization_name) }}"
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2E7D32]">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Organization Type</label>
                <input type="text" name="organization_type" value="{{ old('organization_type', $user->organization_type) }}"
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2E7D32]">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2E7D32]">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
            <textarea name="address" rows="3"
                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2E7D32] resize-none">{{ old('address', $user->address) }}</textarea>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="flex-1 py-3 bg-[#2E7D32] text-white rounded-lg hover:bg-[#25662a] font-medium">
                Save Changes
            </button>
            <a href="{{ route('user.profile.index') }}"
               class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
