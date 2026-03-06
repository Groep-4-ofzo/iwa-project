<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Users</title>
</head>
<body>  
    <h1>Gebruikerslijst</h1>
    <pre>{{ var_dump($users) }}</pre>
</body>
</html>