{{-- Cek apakah PWA (standalone mode) via class yang di-set JS di body --}}
<div
    id="main-sidebar"
    class="pwa-hide w-64 bg-white border-r border-gray-200 flex flex-col transition-all duration-200"
    x-data="{ collapsed: false }"
    :class="collapsed ? 'w-16' : 'w-64'"
>
    <div class="p-6 overflow-hidden whitespace-nowrap" x-show="!collapsed">
        <x-logo size="sm" :showText="true" />
    </div>
    <div class="p-4 flex justify-center" x-show="collapsed">
        <x-logo size="sm" :showText="false" />
    </div>

    <nav class="flex-1 px-3">
        <p class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider overflow-hidden"
            x-show="!collapsed">Menu</p>

        @php
        $navItems = [
            ['route' => 'user.dashboard', 'label' => 'Dashboard', 'pattern' => 'user.dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
            ['route' => 'user.donations.index', 'label' => 'Donations', 'pattern' => 'user.donations.*', 'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
            ['route' => 'user.requests.index', 'label' => 'My Requests', 'pattern' => 'user.requests.*', 'icon' => 'M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4'],
            ['route' => 'user.profile.index', 'label' => 'Profile', 'pattern' => 'user.profile.*', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
        ];
        @endphp

        @foreach($navItems as $item)
        <a href="{{ route($item['route']) }}"
            class="flex items-center gap-3 px-4 py-3 rounded-lg mb-1 transition-colors
                {{ request()->routeIs($item['pattern']) ? 'bg-[#2E7D32] text-white' : 'text-gray-700 hover:bg-gray-100' }}"
            :class="collapsed ? 'justify-center px-2' : ''"
            title="{{ $item['label'] }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"></path>
            </svg>
            <span x-show="!collapsed" class="whitespace-nowrap overflow-hidden">{{ $item['label'] }}</span>
        </a>
        @endforeach
    </nav>

    {{-- User info & logout --}}
    <div class="p-4 border-t border-gray-200">
        @auth
        <div class="flex items-center gap-3 px-2 mb-3" x-show="!collapsed">
            <div class="w-10 h-10 rounded-full bg-[#2E7D32]/10 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-[#2E7D32]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium truncate">{{ auth()->user()->name }}</p>
                <p class="text-xs text-gray-500">{{ auth()->user()->organization_name ?? 'User' }}</p>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="w-full flex items-center gap-3 px-4 py-2.5 rounded-lg text-red-600 hover:bg-red-50 transition-colors text-sm"
                :class="collapsed ? 'justify-center px-2' : ''"
                title="Logout">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                </svg>
                <span x-show="!collapsed">Logout</span>
            </button>
        </form>
        @endauth
    </div>

    {{-- Tombol minimize/maximize --}}
    <button
        @click="collapsed = !collapsed; localStorage.setItem('sidebar_collapsed', collapsed)"
        class="flex items-center justify-center p-3 border-t border-gray-200 text-gray-400 hover:text-gray-600 hover:bg-gray-50 transition-colors"
        :title="collapsed ? 'Expand sidebar' : 'Minimize sidebar'">
        <svg class="w-4 h-4 transition-transform" :class="collapsed ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
    </button>
</div>

{{-- Bottom Navbar — hanya tampil saat PWA (standalone) --}}
@if(auth()->check() && auth()->user()->role === 'user')
<nav id="pwa-bottom-nav" class="hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 z-50">
    <div style="display:flex; width:100%; align-items:center;">
        <a href="{{ route('user.dashboard') }}"
            style="flex:1; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:4px; padding:10px 0; text-decoration:none;"
            class="{{ request()->routeIs('user.dashboard') ? 'text-[#2E7D32]' : 'text-gray-500' }}">
            <div style="width:24px; height:24px; display:flex; align-items:center; justify-content:center; {{ request()->routeIs('user.dashboard') ? 'background:#e8f5e9; border-radius:6px;' : '' }}">
                <svg style="width:20px;height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
            </div>
            <span style="font-size:10px; font-weight:500;">Home</span>
        </a>

        <a href="{{ route('user.donations.index') }}"
            style="flex:1; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:4px; padding:10px 0; text-decoration:none;"
            class="{{ request()->routeIs('user.donations.*') ? 'text-[#2E7D32]' : 'text-gray-500' }}">
            <div style="width:24px; height:24px; display:flex; align-items:center; justify-content:center; {{ request()->routeIs('user.donations.*') ? 'background:#e8f5e9; border-radius:6px;' : '' }}">
                <svg style="width:20px;height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
            </div>
            <span style="font-size:10px; font-weight:500;">Donations</span>
        </a>

        <a href="{{ route('user.requests.index') }}"
            style="flex:1; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:4px; padding:10px 0; text-decoration:none;"
            class="{{ request()->routeIs('user.requests.*') ? 'text-[#2E7D32]' : 'text-gray-500' }}">
            <div style="width:24px; height:24px; display:flex; align-items:center; justify-content:center; {{ request()->routeIs('user.requests.*') ? 'background:#e8f5e9; border-radius:6px;' : '' }}">
                <svg style="width:20px;height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                </svg>
            </div>
            <span style="font-size:10px; font-weight:500;">Requests</span>
        </a>

        <a href="{{ route('user.profile.index') }}"
            style="flex:1; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:4px; padding:10px 0; text-decoration:none;"
            class="{{ request()->routeIs('user.profile.*') ? 'text-[#2E7D32]' : 'text-gray-500' }}">
            <div style="width:24px; height:24px; display:flex; align-items:center; justify-content:center; {{ request()->routeIs('user.profile.*') ? 'background:#e8f5e9; border-radius:6px;' : '' }}">
                <svg style="width:20px;height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </div>
            <span style="font-size:10px; font-weight:500;">Profile</span>
        </a>
    </div>
</nav>
@endif

<script>
// Restore state minimize dari localStorage
document.addEventListener('DOMContentLoaded', () => {
    const isCollapsed = localStorage.getItem('sidebar_collapsed') === 'true';
    if (isCollapsed) {
        // trigger Alpine x-data collapsed = true
        const sidebar = document.getElementById('main-sidebar');
        if (sidebar && sidebar._x_dataStack) {
            sidebar._x_dataStack[0].collapsed = true;
        }
    }

    // Deteksi PWA standalone mode
    const isPWA = window.matchMedia('(display-mode: standalone)').matches
        || window.navigator.standalone === true;

    if (isPWA) {
        // Sembunyikan sidebar desktop
        const desktopSidebar = document.getElementById('main-sidebar');
        if (desktopSidebar) desktopSidebar.classList.add('hidden');

        // Tampilkan bottom nav
        const bottomNav = document.getElementById('pwa-bottom-nav');
        if (bottomNav) bottomNav.classList.remove('hidden');
    }
});
</script>