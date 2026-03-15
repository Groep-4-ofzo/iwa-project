<!DOCTYPE html>
<html>
<head>
    <title>Station {{ $data->name }} </title>
</head>
<body>

<h1>Station {{ $data->name }}</h1>

@if($geo)
    <p>Country: {{ $geo->country }}</p>
    <p>City: {{ $geo->city }}</p>
    <p>Region: {{ $geo->region }}</p>
@endif

</body>
</html>
