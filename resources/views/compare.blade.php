<!DOCTYPE html>
<html>
<head>
    <title>Compare Stations</title>
</head>
<body>

<h1>Vergelijk twee weerstations</h1>

<form method="POST" action="/compare">
    @csrf

    <select name="station1">
        @foreach($stations as $station)
        <option value="{{ $station->name }}">{{ $station->name }}</option>
        @endforeach
    </select>

    <select name="station2">
        @foreach($stations as $station)
        <option value="{{ $station->name }}">{{ $station->name }}</option>
        @endforeach
    </select>

    <button type="submit">Vergelijk</button>
</form>

@isset($station1)
<h2>Station {{ $station1 }}</h2>

@if(isset($data1))
<p>Longitude: {{ $data1->longitude }}</p>
<p>Latitude: {{ $data1->latitude }}</p>
<p>Elevation: {{ $data1->elevation }}</p>
@endif

@if($geo1)
<p>Country: {{ $geo1->country }}</p>
<p>City: {{ $geo1->city }}</p>
<p>Region: {{ $geo1->region }}</p>
@endif

@endisset
@isset($station2)
<h2>Station {{ $station2 }}</h2>

@if(isset($data2))
<p>Longitude: {{ $data2->longitude }}</p>
<p>Latitude: {{ $data2->latitude }}</p>
<p>Elevation: {{ $data2->elevation }}</p>
@endif

@if($geo2)
<p>Country: {{ $geo2->country }}</p>
<p>City: {{ $geo2->city }}</p>
<p>Region: {{ $geo2->region }}</p>
@endif
@endisset

</body>
</html>
