<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New leave request</title>
</head>
<body>
    <p>Nieuwe verlofaanvraag van: {{ $leave->user->name ?? $leave->user->email }}</p>
    <p>Start: {{ $leave->start_date->toDateString() }}</p>
    <p>Eind: {{ $leave->end_date->toDateString() }}</p>
    <p>Status: {{ ucfirst($leave->status) }}</p>
</body>
</html>
