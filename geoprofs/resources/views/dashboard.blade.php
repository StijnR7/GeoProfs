<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Dashboard</title>
</head>
<body>
    <h1>Dashboard</h1>

    @auth
        <p>Welkom, {{ auth()->user()->name ?? auth()->user()->email }}.</p>
        <p>This is the dashboard page. Implement your dashboard UI here.</p>
    @else
        <p>You are not logged in. <a href="{{ route('login') }}">Login</a></p>
    @endauth
</body>
</html>
