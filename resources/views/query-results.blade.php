<div style="font-family: sans-serif; padding: 20px;">
    <h1>Resultaten: {{ $queryRecord->omschrijving }}</h1>
    <p>Gevonden: {{ $results->count() }} stations</p>
    
    <table border="1" style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background: #eee;">
                <th>Naam</th>
                <th>Landcode</th> <th>Longitude</th>
                <th>Latitude</th>
                <th>Elevation</th>
            </tr>
        </thead>
        <tbody>
            @foreach($results as $row)
                <tr>
                    <td>{{ $row->name }}</td>
                    <td style="text-align: center; font-weight: bold;">{{ $row->geolocation->first()->country_code ?? '-' }}</td> <td>{{ $row->longitude }}</td>
                    <td>{{ $row->latitude }}</td>
                    <td>{{ $row->elevation }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <br>
    <a href="{{ route('query.index') }}" style="text-decoration: none; color: blue;">← Terug naar de Builder</a>
</div>