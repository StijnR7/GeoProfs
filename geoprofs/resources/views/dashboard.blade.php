<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <style>
        .navbar { display: flex; justify-content: space-between; align-items: center; padding: 15px; background: #f0f0f0; margin-bottom: 20px; }
        .account-dropdown { position: relative; display: inline-block; }
        .account-btn { padding: 8px 15px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .account-btn:hover { background: #0056b3; }
        .dropdown-content { display: none; position: absolute; right: 0; background: white; min-width: 150px; box-shadow: 0 8px 16px rgba(0,0,0,0.2); z-index: 1; border-radius: 4px; }
        .account-dropdown:hover .dropdown-content { display: block; }
        .dropdown-content form { margin: 0; }
        .logout-btn { width: 100%; padding: 10px 15px; text-align: left; background: none; border: none; cursor: pointer; color: #333; }
        .logout-btn:hover { background: #f0f0f0; }
    </style>
</head>
<body>
    <div class="navbar">
        <h2>Verlof / Ziekmelding</h2>
        <div class="account-dropdown">
            <button class="account-btn">{{ Auth::user()->name }} ▼</button>
            <div class="dropdown-content">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="logout-btn">Uitloggen</button>
                </form>
            </div>
        </div>
    </div>

<form action="{{ route('leave.submit') }}" method="POST">
    @csrf

    <div style="margin-bottom: 15px;">
        <label for="leave_start">Startdatum:</label><br>
        <input type="date" id="leave_start" name="leave_start" required>
    </div>

    <div style="margin-bottom: 15px;">
        <label for="leave_end">Einddatum:</label><br>
        <input type="date" id="leave_end" name="leave_end" required>
    </div>

    <div style="margin-bottom: 15px;">
        <label for="type">Type:</label><br>
        <select id="type" name="type" required>
            <option value="">-- Kies reden --</option>
            <option value="ziek">Ziek</option>
            <option value="verlof">Verlof</option>
        </select>
    </div>

    <div style="margin-bottom: 15px;">
        <label for="reason">Toelichting (optioneel):</label><br>
        <textarea id="reason" name="reason" rows="4" cols="40"></textarea>
    </div>

    <button type="submit">Aanvraag indienen</button>
</form>

@if(session('success'))
    <p style="color: green;">{{ session('success') }}</p>
@endif
