<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#2E7D32">
    <title>@yield('title', 'Doonates')</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@100..900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('styles')
    <style>
        #pwa-bottom-nav { 
            display: flex !important; 
            position: fixed !important;
            bottom: 0 !important;
            left: 0 !important;
            right: 0 !important;
            z-index: 9999 !important;
            background: white !important;
            border-top: 1px solid #e5e7eb !important;
        }
        #main-sidebar { display: none !important; }
    </style>
    {{-- @laravelPWA --}}
    @laravelPWA
</head>

<body class="font-sans antialiased bg-gray-50">
    <script>
        if (window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true) {
            document.body.classList.add('is-pwa');
        }
    </script>
    
    <div class="flex h-screen">
        @include('user.layouts.sidebar')
        <div class="flex-1 flex flex-col overflow-hidden">
            @include('components.navbar')
            <main class="flex-1 overflow-y-auto p-8" id="main-content">
                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">{{ session('error') }}</div>
                @endif
                @if($errors->any())
                    <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                        <ul class="list-disc pl-5">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>
    @stack('scripts')

    @if(auth()->check() && auth()->user()->role === 'user')

    <div
        id="installPrompt"
        class="hidden fixed bottom-4 left-4 right-4 bg-white rounded-xl shadow-xl p-4 border z-50"
    >
        <div class="flex items-center justify-between">
            <div>
                <h3 class="font-semibold">
                    Install Doo Nates
                </h3>

                <p class="text-sm text-gray-500">
                    Install app on your device
                </p>
            </div>

            <button
                id="installBtn"
                class="px-4 py-2 bg-green-600 text-white rounded-lg"
            >
                Install
            </button>
        </div>
    </div>

    <script>
    let deferredPrompt;

    window.addEventListener('beforeinstallprompt', (e) => {

        e.preventDefault();

        deferredPrompt = e;

        document
            .getElementById('installPrompt')
            ?.classList.remove('hidden');
    });

    document
    .getElementById('installBtn')
    ?.addEventListener('click', async () => {

        if (!deferredPrompt) return;

        deferredPrompt.prompt();

        await deferredPrompt.userChoice;

        deferredPrompt = null;

        document
            .getElementById('installPrompt')
            ?.classList.add('hidden');
    });
    </script>

    @endif
    
    <script>
        // Tambah padding bottom di main content saat PWA
        document.addEventListener('DOMContentLoaded', () => {
            const isPWA = window.matchMedia('(display-mode: standalone)').matches
                || window.navigator.standalone === true;
            if (isPWA) {
                const main = document.getElementById('main-content');
                if (main) main.classList.add('pb-20');
            }
        });
    </script>

    {{-- Push Notification: request permission on dashboard --}}
    @auth
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const vapidKey = @json(config('webpush.vapid.public_key'));
            if (vapidKey) {
                DoonautesPush.requestPermissionAndSubscribe(vapidKey);
            }
        });
    </script>
    @endauth
</body>
</html>