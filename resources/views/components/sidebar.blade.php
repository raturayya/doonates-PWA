<div class="w-64 bg-white border-r border-gray-200 flex flex-col">
    <div class="p-6">
        <x-logo size="sm" :showText="true" />
    </div>

    <nav class="flex-1 px-3">

        @if(auth()->check() && auth()->user()->isAdmin())
        {{-- ── MENU ADMIN ─────────────────────────────────────────── --}}

        <p class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Admin</p>

        <a href="{{ route('admin.dashboard') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-lg mb-1 transition-colors
                {{ request()->routeIs('admin.dashboard') ? 'bg-[#2E7D32] text-white' : 'text-gray-700 hover:bg-gray-100' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('admin.verification.index') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-lg mb-1 transition-colors
                {{ request()->routeIs('admin.verification.*') ? 'bg-[#2E7D32] text-white' : 'text-gray-700 hover:bg-gray-100' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>Verification</span>
            @php $pendingCount = \App\Models\User::where('status', 'pending')->count(); @endphp
            @if($pendingCount > 0)
                <span class="ml-auto bg-yellow-100 text-yellow-700 text-xs font-semibold px-2 py-0.5 rounded-full">
                    {{ $pendingCount }}
                </span>
            @endif
        </a>

        <a href="{{ route('admin.monitoring.index') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-lg mb-1 transition-colors
                {{ request()->routeIs('admin.monitoring.*') ? 'bg-[#2E7D32] text-white' : 'text-gray-700 hover:bg-gray-100' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            <span>Monitoring</span>
        </a>

        @elseif(auth()->check() && auth()->user()->role === 'user')
        {{-- ── MENU USER ─────────────────────────────────────────── --}}

        <p class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Menu</p>

        <a href="{{ route('user.dashboard') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-lg mb-1 transition-colors
                {{ request()->routeIs('user.dashboard') ? 'bg-[#2E7D32] text-white' : 'text-gray-700 hover:bg-gray-100' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('user.donations.index') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-lg mb-1 transition-colors
                {{ request()->routeIs('user.donations.*') ? 'bg-[#2E7D32] text-white' : 'text-gray-700 hover:bg-gray-100' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
            </svg>
            <span>Donations</span>
        </a>

        <a href="{{ route('user.requests.index') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-lg mb-1 transition-colors
                {{ request()->routeIs('user.requests.*') ? 'bg-[#2E7D32] text-white' : 'text-gray-700 hover:bg-gray-100' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
            </svg>
            <span>My Requests</span>
        </a>

        <a href="{{ route('user.profile.index') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-lg mb-1 transition-colors
                {{ request()->routeIs('user.profile.*') ? 'bg-[#2E7D32] text-white' : 'text-gray-700 hover:bg-gray-100' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            <span>Profile</span>
        </a>

        @else
        {{-- ── MENU ORGANISASI ─────────────────────────────────────── --}}

        <a href="{{ route('dashboard') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-lg mb-1 transition-colors
                {{ request()->routeIs('dashboard') ? 'bg-[#2E7D32] text-white' : 'text-gray-700 hover:bg-gray-100' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('donations.index') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-lg mb-1 transition-colors
                {{ request()->routeIs('donations.*') ? 'bg-[#2E7D32] text-white' : 'text-gray-700 hover:bg-gray-100' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
            </svg>
            <span>Donations</span>
        </a>

        <a href="{{ route('requests.index') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-lg mb-1 transition-colors
                {{ request()->routeIs('requests.*') ? 'bg-[#2E7D32] text-white' : 'text-gray-700 hover:bg-gray-100' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
            </svg>
            <span>Requests</span>
        </a>

        <a href="{{ route('organizations.index') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-lg mb-1 transition-colors
                {{ request()->routeIs('organizations.*') ? 'bg-[#2E7D32] text-white' : 'text-gray-700 hover:bg-gray-100' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
            <span>Organizations</span>
        </a>

        <a href="{{ route('profile.index') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-lg mb-1 transition-colors
                {{ request()->routeIs('profile.*') ? 'bg-[#2E7D32] text-white' : 'text-gray-700 hover:bg-gray-100' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            <span>Profile</span>
        </a>

        <a href="{{ route('settings') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-lg mb-1 transition-colors
                {{ request()->routeIs('settings') ? 'bg-[#2E7D32] text-white' : 'text-gray-700 hover:bg-gray-100' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            <span>Settings</span>
        </a>

        @endif
    </nav>

{{-- ── User Info + Logout ──────────────────────────────────────── --}}
<div class="p-4 border-t border-gray-200">
    @auth
    <div class="flex items-center gap-3 px-2 mb-3">
        <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center">
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-sm font-medium truncate">
                {{ auth()->user()->organization_name ?? auth()->user()->name }}
            </p>
            <p class="text-xs text-gray-500">
                {{ auth()->user()->isAdmin() ? 'Administrator' : auth()->user()->organization_type }}
            </p>
        </div>
    </div>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit"
            class="w-full flex items-center gap-3 px-4 py-2.5 rounded-lg text-red-600 hover:bg-red-50 transition-colors text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
            </svg>
            Logout
        </button>
    </form>
    @endauth
</div>
</div>
