<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <style>
            body { font-family: Arial, sans-serif; background-color: #f3f4f6; padding: 32px; }
        </style>
    @endif
</head>
<body class="bg-gray-100 min-h-screen p-8">

<div class="max-w-3xl mx-auto">

    <div class="flex items-center gap-3 mb-8">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-8 h-8">
        <h1 class="text-4xl font-bold">Startpagina</h1>
    </div>

    <div class="bg-white p-6 rounded-lg shadow mb-6">
        <h2 class="text-2xl font-bold border-b pb-2 mb-4">Overzicht</h2>
        <ul class="space-y-2">
            <li>
                <a href="{{ route('compare') }}" class="block p-2 rounded hover:bg-gray-100">
                    Vergelijken
                </a>
            </li>
            <li>
                <a href="{{ url('/stations') }}" class="block p-2 rounded hover:bg-gray-100">
                    Mijn stations
                </a>
            </li>
            @auth
                @role('Administrator')
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="block p-2 rounded hover:bg-gray-100">
                        Admin panel
                    </a>
                </li>
                @endrole
            @endauth
        </ul>
    </div>

    <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-2xl font-bold border-b pb-2 mb-4">Account</h2>
        <ul class="space-y-2">
            @guest
                <li>
                    <a href="{{ route('login') }}" class="block p-3 rounded hover:bg-gray-100 transition">
                        Login
                    </a>
                </li>
            @endguest

            @auth
                <li class="p-2 text-gray-700">
                    Ingelogd als: <strong>{{ auth()->user()->name }}</strong>
                    <span class="text-blue-600 text-sm ml-2">{{ auth()->user()->userRole->role ?? '' }}</span>
                </li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="block w-full text-left p-3 rounded hover:bg-gray-100 transition">
                            Logout
                        </button>
                    </form>
                </li>
            @endauth
        </ul>
    </div>

</div>

</body>
</html>
