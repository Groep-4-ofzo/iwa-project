<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-8">

<div class="max-w-3xl w-full mx-auto">

    <a href="{{ url('/') }}"
       class="inline-flex items-center gap-2 mb-6 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
        ← Terug naar startpagina
    </a>

    <h1 class="text-4xl font-bold mb-8">Login</h1>

    <div class="bg-white p-6 rounded-lg shadow mb-6">
        <form method="POST" action="{{ route('authenticate') }}" class="space-y-4">
            @csrf

            @if($errors->any())
                <div class="bg-red-50 border border-red-300 text-red-700 px-4 py-3 rounded">
                    {{ $errors->first() }}
                </div>
            @endif

            <div>
                <label class="block font-medium mb-1">Email Adres</label>
                <input type="email" name="email"
                       class="w-full border rounded p-2 focus:outline-none focus:ring focus:border-blue-300"
                       value="{{ old('email') }}"
                       required>
            </div>

            <div>
                <label class="block font-medium mb-1">Wachtwoord</label>
                <input type="password" name="password"
                       class="w-full border rounded p-2 focus:outline-none focus:ring focus:border-blue-300"
                       required>
            </div>

            <button type="submit"
                    class="w-full bg-blue-600 text-white p-2 rounded hover:bg-blue-700">
                Login
            </button>
        </form>
    </div>

</div>
</body>
</html>
