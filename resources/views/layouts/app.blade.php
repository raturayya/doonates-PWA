<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Doonates')</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@100..900&display=swap" rel="stylesheet">

    <!-- Tailwind -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @stack('styles')

    @laravelPWA
</head>

<body class="font-sans antialiased bg-gray-50">

    <div class="flex h-screen">

        <!-- Sidebar -->
        @include('components.sidebar')

        <div class="flex-1 flex flex-col overflow-hidden">

            <!-- Navbar -->
            @include('components.navbar')

            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto p-8">

                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Validation Errors -->
                @if($errors->any())
                    <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                        <ul class="list-disc pl-5">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- CONTENT DINAMIS -->
                @yield('content')

            </main>

        </div>
    </div>

    @stack('scripts')

    {{-- Push Notification: request permission on first dashboard visit --}}
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