<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Afdelingsagenda - Geoprofs</title>

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fa;
            color: #333;
        }

        /* ===== NAVBAR ===== */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            background: #2c3e50;
            color: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .navbar h1 { font-size: 24px; }

        .account-dropdown { position: relative; }

        .account-btn {
            padding: 8px 15px;
            background: #34495e;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }

        .account-btn:hover { background: #2c3e50; }

        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background: #34495e;
            min-width: 160px;
            border-radius: 4px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
            z-index: 10;
        }

        .account-dropdown:hover .dropdown-content {
            display: block;
        }

        .dropdown-link,
        .logout-btn {
            display: block;
            width: 100%;
            padding: 12px 16px;
            color: #ecf0f1;
            text-decoration: none;
            background: none;
            border: none;
            text-align: left;
            cursor: pointer;
            font-size: 14px;
        }

        .dropdown-link:hover { background: #2c3e50; }
        .logout-btn:hover { background: #e74c3c; }

        /* ===== NAV BUTTONS ===== */
        .nav-buttons {
            display: flex;
            gap: 8px;
            background: #34495e;
            padding: 12px 20px;
            border-bottom: 1px solid #2c3e50;
        }

        .nav-btn {
            padding: 8px 16px;
            background: #3498db;
            color: white;
            border-radius: 4px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
        }

        .nav-btn:hover { background: #2980b9; }
        .nav-btn.active { background: #27ae60; }

        /* ===== CONTENT ===== */
        .container {
            max-width: 900px;
            margin: 30px auto;
            padding: 20px;
        }

        h2 {
            margin-bottom: 20px;
            font-weight: 300;
            color: #2c3e50;
        }

        /* ===== LEGEND ===== */
        .legend {
            display: flex;
            gap: 20px;
            margin-bottom: 15px;
            font-size: 14px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .legend-color {
            width: 14px;
            height: 14px;
            border-radius: 3px;
        }

        .legend-color.verlof { background: #27ae60; }
        .legend-color.ziek { background: #e74c3c; }
        .legend-color.today { background: #f1c40f; }

        /* ===== CALENDAR ===== */
        .calendar {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            padding: 20px;
        }

        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .calendar-title {
            font-size: 18px;
            font-weight: 600;
        }

        .calendar-nav-btn {
            padding: 6px 12px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .calendar-nav-btn:hover { background: #2980b9; }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 6px;
        }

        .calendar-day-header {
            text-align: center;
            font-weight: 600;
            padding: 6px;
            background: #ecf0f1;
            border-radius: 4px;
        }

        .calendar-day {
            min-height: 36px;
            padding: 8px;
            text-align: center;
            border-radius: 4px;
            background: #f8f9fa;
            cursor: pointer;
        }

        .calendar-day.today {
            background: #f1c40f;
            font-weight: 700;
        }

        .calendar-day.has-leave.verlof {
            background: #27ae60;
            color: white;
        }

        .calendar-day.has-leave.ziek {
            background: #e74c3c;
            color: white;
        }

        .calendar-day.disabled {
            background: #e0e0e0;
            color: #999;
            cursor: not-allowed;
        }

        /* ===== DETAILS ===== */
        .leave-details {
            margin-top: 20px;
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .leave-item {
            padding: 10px;
            border-left: 4px solid #3498db;
            margin-bottom: 8px;
            background: #f8f9fa;
        }

        .leave-item.verlof { border-color: #27ae60; }
        .leave-item.ziek { border-color: #e74c3c; }
    </style>
</head>

<body>

<div class="navbar">
    <h1>Geoprofs</h1>
    <div class="account-dropdown">
        <button class="account-btn">{{ Auth::user()->name }} ▼</button>
        <div class="dropdown-content">
            <a href="{{ route('dashboard') }}" class="dropdown-link">Ziek/Verlofaanvraag</a>
            <a href="{{ route('my-requests') }}" class="dropdown-link">Mijn Aanvragen</a>
            <a href="{{ route('leave-days') }}" class="dropdown-link">Verlofdagen</a>
            <a href="{{ route('department-calendar') }}" class="dropdown-link">Afdelingsagenda</a>
            @if(Auth::user()->functie === 'admin')
                <a href="{{ route('manage-requests') }}" class="dropdown-link">Beheer Aanvragen</a>
            @endif
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-btn">Uitloggen</button>
            </form>
        </div>
    </div>
</div>

<div class="nav-buttons">
    <a href="{{ route('dashboard') }}" class="nav-btn">Ziek/Verlofaanvraag</a>
    <a href="{{ route('my-requests') }}" class="nav-btn">Mijn Aanvragen</a>
    <a href="{{ route('leave-days') }}" class="nav-btn">Verlofdagen</a>
    <a href="{{ route('department-calendar') }}" class="nav-btn active">Afdelingsagenda</a>
</div>

<div class="container">
    <h2>Afdelingsagenda</h2>

    <div class="legend">
        <div class="legend-item"><div class="legend-color verlof"></div>Verlof</div>
        <div class="legend-item"><div class="legend-color ziek"></div>Ziek</div>
        <div class="legend-item"><div class="legend-color today"></div>Vandaag</div>
    </div>

    <div class="calendar">
        <div class="calendar-header">
            <div class="calendar-title" id="calendar-title"></div>
            <div>
                <button class="calendar-nav-btn" onclick="changeMonth(-1)">◀</button>
                <button class="calendar-nav-btn" onclick="changeMonth(1)">▶</button>
            </div>
        </div>
        <div class="calendar-grid" id="calendar-grid"></div>
    </div>

    <div class="leave-details" id="leave-details">
        Klik op een dag om details te zien
    </div>
</div>

<script>
    let currentDate = new Date();
    let approvedLeaves = @json($approvedLeaves);

    function generateCalendar() {
        const grid = document.getElementById('calendar-grid');
        const title = document.getElementById('calendar-title');

        title.textContent = currentDate.toLocaleDateString('nl-NL', {
            month: 'long',
            year: 'numeric'
        });

        grid.innerHTML = '';

        ['Ma','Di','Wo','Do','Vr','Za','Zo'].forEach(d => {
            const h = document.createElement('div');
            h.className = 'calendar-day-header';
            h.textContent = d;
            grid.appendChild(h);
        });

        const firstDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
        const start = new Date(firstDay);
        start.setDate(start.getDate() - firstDay.getDay() + 1);

        for (let i = 0; i < 42; i++) {
            const d = new Date(start);
            d.setDate(start.getDate() + i);

            const el = document.createElement('div');
            el.className = 'calendar-day';
            el.textContent = d.getDate();

            if (d.getMonth() !== currentDate.getMonth()) {
                el.classList.add('disabled');
            } else {
                if (d.toDateString() === new Date().toDateString()) {
                    el.classList.add('today');
                }

                approvedLeaves.forEach(l => {
                    if (d >= new Date(l.leave_start) && d <= new Date(l.leave_end)) {
                        el.classList.add('has-leave', l.type);
                    }
                });

                el.onclick = () => showDetails(d);
            }

            grid.appendChild(el);
        }
    }

    function showDetails(date) {
        const box = document.getElementById('leave-details');
        const items = approvedLeaves.filter(l =>
            date >= new Date(l.leave_start) && date <= new Date(l.leave_end)
        );

        if (!items.length) {
            box.textContent = 'Geen verlof of ziekte op deze dag';
            return;
        }

        box.innerHTML = items.map(l => `
            <div class="leave-item ${l.type}">
                <strong>${l.user_name}</strong><br>
                ${l.type.toUpperCase()} • ${l.leave_start} – ${l.leave_end}
            </div>
        `).join('');
    }

    function changeMonth(step) {
        currentDate.setMonth(currentDate.getMonth() + step);
        generateCalendar();
        document.getElementById('leave-details').textContent =
            'Klik op een dag om details te zien';
    }

    document.addEventListener('DOMContentLoaded', generateCalendar);
</script>

</body>
</html>
