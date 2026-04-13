<a href="{{ url('/') }}" class="inline-block mb-4 text-blue-600 hover:underline">
    ← Terug naar startpagina
</a>

<h1>Mijn stations</h1>

<ul>
    @foreach($stations as $station)
    <li>{{ $station->name }}</li>
    @endforeach
</ul>
