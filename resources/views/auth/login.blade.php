<a href="{{ url('/') }}"
   class="inline-flex items-center gap-2 mb-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
    ← Terug naar startpagina
</a>
<body class="bg-gray-100 p-8">
<div class="max-w-3xl mx-auto">


    <h1 class="text-4xl font-bold mb-8">Login</h1>


    <div class="bg-white p-6 rounded-lg shadow mb-6">


        <form method="POST" action="{{ route('authenticate') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block font-medium mb-1">Email Adres</label>
                <input type="email" name="email"
                       class="w-full border rounded p-2 focus:outline-none focus:ring focus:border-blue-300"
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
