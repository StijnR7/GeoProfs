<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ziek/Verlofaanvraag - Geoprofs</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f8f9fa; color: #333; }

        .navbar { display: flex; justify-content: space-between; align-items: center; padding: 15px 20px; background: #2c3e50; color: white; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .navbar h1 { font-size: 24px; margin: 0; }

        .account-dropdown { position: relative; display: inline-block; }
        .account-btn { padding: 8px 15px; background: #34495e; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; transition: background 0.3s; }
        .account-btn:hover { background: #2c3e50; }
        .dropdown-content { display: none; position: absolute; right: 0; background: #34495e; min-width: 160px; box-shadow: 0 8px 16px rgba(0,0,0,0.2); z-index: 1; border-radius: 4px; top: 100%; border: 1px solid #2c3e50; }
        .account-dropdown:hover .dropdown-content { display: block; }
        .dropdown-link { display: block; padding: 12px 16px; text-align: left; background: none; border: none; cursor: pointer; color: #ecf0f1; font-size: 14px; text-decoration: none; transition: background 0.3s; }
        .dropdown-link:hover { background: #2c3e50; }

        .nav-buttons { display: flex; gap: 8px; background: #34495e; padding: 12px 20px; border-bottom: 1px solid #2c3e50; }
        .nav-btn { padding: 8px 16px; background: #3498db; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 13px; transition: all 0.3s; font-weight: 500; text-decoration: none; display: inline-block; }
        .nav-btn:hover { background: #2980b9; transform: translateY(-1px); }
        .nav-btn.active { background: #27ae60; }

        .container { display: flex; justify-content: center; min-height: calc(100vh - 120px); padding: 20px; background: #f8f9fa; }
        .main-content { display: flex; gap: 25px; align-items: flex-start; max-width: 900px; }
        .calendar-section { flex: 0 0 450px; }
        .form-section { flex: 1; max-width: 380px; }

        .section { background: white; padding: 18px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .section h2 { margin: 0 0 18px 0; color: #2c3e50; font-size: 22px; font-weight: 300; }

        .calendar { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 16px; box-shadow: 0 8px 32px rgba(0,0,0,0.12); padding: 20px; margin-bottom: 0; position: relative; overflow: hidden; }
        .calendar::before { content: ''; position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(255,255,255,0.05); border-radius: 16px; }
        .calendar-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; position: relative; z-index: 1; }
        .calendar-title { font-size: 20px; font-weight: 600; color: #ffffff; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.2); }
        .calendar-nav { display: flex; gap: 6px; }
        .calendar-nav-btn { padding: 8px 14px; background: rgba(255,255,255,0.2); color: white; border: 1px solid rgba(255,255,255,0.3); border-radius: 10px; cursor: pointer; font-size: 14px; font-weight: 600; transition: all 0.3s; backdrop-filter: blur(10px); }
        .calendar-nav-btn:hover { background: rgba(255,255,255,0.3); transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.15); }

        .calendar-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 4px; position: relative; z-index: 1; }
        .calendar-day-header { padding: 10px; text-align: center; font-weight: 700; color: #ffffff; background: rgba(255,255,255,0.15); border-radius: 10px; font-size: 13px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.1); }
        .calendar-day { padding: 10px; text-align: center; cursor: pointer; border-radius: 10px; transition: all 0.3s; min-height: 36px; display: flex; align-items: center; justify-content: center; position: relative; font-size: 14px; color: #ffffff; background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.1); }
        .calendar-day:hover { background: rgba(255,255,255,0.25); transform: scale(1.05); box-shadow: 0 4px 16px rgba(0,0,0,0.1); }
        .calendar-day.selected { background: #ff6b6b; color: white; font-weight: 700; border: 2px solid rgba(255,255,255,0.5); box-shadow: 0 0 20px rgba(255,107,107,0.4); }
        .calendar-day.selected-range { background: rgba(255,107,107,0.6); color: white; border: 1px solid rgba(255,107,107,0.8); font-weight: 600; box-shadow: 0 0 15px rgba(255,107,107,0.3); }
        .calendar-day.today { background: #ffd93d; color: #2c3e50; font-weight: 700; border: 2px solid #ffb142; box-shadow: 0 0 20px rgba(255,217,61,0.5); }
        .calendar-day.disabled { color: rgba(255,255,255,0.4); cursor: not-allowed; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.05); }
        .calendar-day.disabled:hover { background: rgba(255,255,255,0.05); transform: none; box-shadow: none; }
        .calendar-day.has-leave { background: #51cf66; color: white; border: 1px solid #40c057; box-shadow: 0 0 15px rgba(81,207,102,0.3); }
        .calendar-day.has-leave.verlof { background: #51cf66; color: white; border: 1px solid #40c057; box-shadow: 0 0 15px rgba(81,207,102,0.3); }

        .leave-form { background: white; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); padding: 18px; }
        .leave-form h3 { margin: 0 0 15px 0; color: #2c3e50; font-size: 17px; font-weight: 500; }
        .selected-dates { margin-bottom: 15px; padding: 12px 15px; background: #f8f9fa; border-radius: 4px; color: #495057; font-size: 14px; border: 1px solid #dee2e6; font-weight: 500; }

        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: 500; color: #495057; font-size: 14px; }
        .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 4px; font-size: 14px; background: white; color: #495057; }
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus { outline: none; border-color: #3498db; box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.25); }
        .form-group textarea { resize: vertical; min-height: 70px; }

        .btn-submit { padding: 12px 18px; background: #27ae60; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; font-weight: 500; transition: background 0.3s; width: 100%; }
        .btn-submit:hover { background: #229954; }
        .btn-submit:disabled { background: #6c757d; cursor: not-allowed; }

        .success { background: #d4edda; color: #155724; padding: 12px; border-radius: 4px; margin-bottom: 15px; border: 1px solid #c3e6cb; font-size: 14px; }
        .error { background: #f8d7da; color: #721c24; padding: 12px; border-radius: 4px; margin-bottom: 15px; border: 1px solid #f5c6cb; font-size: 14px; }

        .signature { position: fixed; bottom: 10px; right: 10px; font-size: 10px; color: #6c757d; text-decoration: none; z-index: 1000; }
        .signature:hover { color: #495057; }

        /* Extra style voor logout knop in navbar */
        .navbar .logout-btn {
            background: #e74c3c;
            color: white;
            border-radius: 4px;
            padding: 8px 15px;
            border: none;
            cursor: pointer;
            font-size: 14px;
            transition: background 0.3s;
        }
        .navbar .logout-btn:hover {
            background: #c0392b;
        }
    </style>
</head>
<body>
    <!-- Top Navbar -->
    <div class="navbar">
        <h1>Geoprofs</h1>
        <div style="display: flex; align-items: center; gap: 10px;">
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
                    <a href="{{ route('account') }}" class="dropdown-link">Accountgegevens</a>
                </div>
            </div>
            <!-- Logout knop naast user -->
            <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                @csrf
                <button type="submit" class="logout-btn">Uitloggen</button>
            </form>
        </div>
    </div>

    <!-- Navigation Buttons -->
    <div class="nav-buttons">
        <a href="{{ route('dashboard') }}" class="nav-btn active">Ziek/Verlofaanvraag</a>
        <a href="{{ route('my-requests') }}" class="nav-btn">Mijn Aanvragen</a>
        <a href="{{ route('leave-days') }}" class="nav-btn">Verlofdagen</a>
        <a href="{{ route('department-calendar') }}" class="nav-btn">Afdelingsagenda</a>
        @if(Auth::user()->functie === 'admin')
            <a href="{{ route('manage-requests') }}" class="nav-btn">Beheer Aanvragen</a>
        @endif
    </div>

    <div class="container">
        <div class="main-content">
            <!-- Section 1: Request Leave -->
            <div class="calendar-section">
                <div class="section">
                    <h2>Ziek/Verlofaanvraag Indienen</h2>

                    @if(session('success'))
                        <div class="success">{{ session('success') }}</div>
                    @endif

                    @if($errors->any())
                        <div class="error">
                            <strong>Fouten:</strong>
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="calendar">
                        <div class="calendar-header">
                            <h3 class="calendar-title" id="calendar-title">{{ now()->format('F Y') }}</h3>
                            <div class="calendar-nav">
                                <button class="calendar-nav-btn" onclick="changeMonth(-1)">◀</button>
                                <button class="calendar-nav-btn" onclick="changeMonth(1)">▶</button>
                            </div>
                        </div>
                        <div class="calendar-grid" id="calendar-grid"></div>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <div class="leave-form">
                    <h3>Verlof/Ziekmelding Indienen</h3>
                    <div class="selected-dates" id="selected-dates">
                        Selecteer datums in de kalender hiernaast of gebruik de datumvelden
                    </div>

                    <form action="{{ route('leave.submit') }}" method="POST" id="leave-form">
                        @csrf

                        <!-- Date Inputs -->
                        <div class="form-group">
                            <label for="leave_start">Startdatum:</label>
                            <input type="date" id="leave_start" name="leave_start">
                        </div>
                        <div class="form-group">
                            <label for="leave_end">Einddatum:</label>
                            <input type="date" id="leave_end" name="leave_end">
                        </div>

                        <div class="form-group">
                            <label for="type">Type:</label>
                            <select id="type" name="type" required>
                                <option value="">-- Kies reden --</option>
                                <option value="ziek">Ziek</option>
                                <option value="verlof">Verlof</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="reason">Toelichting (optioneel):</label>
                            <textarea id="reason" name="reason" rows="3"></textarea>
                        </div>

                        <button type="submit" class="btn-submit" id="submit-btn" disabled>Aanvraag Indienen</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentDate = new Date();
        let selectedDates = [];
        let firstSelectedDate = null;
        let userLeaves = @json([]);

        function generateCalendar() {
            const calendarGrid = document.getElementById('calendar-grid');
            const calendarTitle = document.getElementById('calendar-title');
            calendarTitle.textContent = currentDate.toLocaleDateString('nl-NL', { month: 'long', year: 'numeric' });

            calendarGrid.innerHTML = '';
            const daysOfWeek = ['Ma', 'Di', 'Wo', 'Do', 'Vr', 'Za', 'Zo'];
            daysOfWeek.forEach(day => {
                const dayHeader = document.createElement('div');
                dayHeader.className = 'calendar-day-header';
                dayHeader.textContent = day;
                calendarGrid.appendChild(dayHeader);
            });

            const firstDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
            const lastDay = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0);
            const startDate = new Date(firstDay);
            startDate.setDate(startDate.getDate() - firstDay.getDay() + 1);

            for (let i = 0; i < 42; i++) {
                const dayElement = document.createElement('div');
                const dayDate = new Date(startDate);
                dayDate.setDate(startDate.getDate() + i);

                dayElement.className = 'calendar-day';
                dayElement.textContent = dayDate.getDate();

                if (dayDate.getMonth() !== currentDate.getMonth()) {
                    dayElement.classList.add('disabled');
                } else {
                    const today = new Date();
                    if (dayDate.toDateString() === today.toDateString()) dayElement.classList.add('today');

                    userLeaves.forEach(leave => {
                        const leaveStart = new Date(leave.leave_start);
                        const leaveEnd = new Date(leave.leave_end);
                        if (dayDate >= leaveStart && dayDate <= leaveEnd) {
                            dayElement.classList.add('has-leave');
                            if (leave.type === 'verlof') dayElement.classList.add('verlof');
                        }
                    });

                    const year = dayDate.getFullYear();
                    const month = String(dayDate.getMonth() + 1).padStart(2, '0');
                    const day = String(dayDate.getDate()).padStart(2, '0');
                    const dateString = `${year}-${month}-${day}`;
                    if (selectedDates.includes(dateString)) {
                        if (firstSelectedDate === dateString || selectedDates[selectedDates.length - 1] === dateString) dayElement.classList.add('selected');
                        else dayElement.classList.add('selected-range');
                    }

                    dayElement.addEventListener('click', () => selectDate(dayDate));
                }

                calendarGrid.appendChild(dayElement);
            }

            updateSelectedDates();
        }

        function selectDate(date) {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            const dateString = `${year}-${month}-${day}`;

            if (!firstSelectedDate) {
                firstSelectedDate = dateString;
                selectedDates = [dateString];
            } else if (firstSelectedDate === dateString) {
                firstSelectedDate = null;
                selectedDates = [];
            } else {
                let start = new Date(firstSelectedDate);
                let end = new Date(dateString);
                if (start > end) [start, end] = [end, start];

                selectedDates = [];
                const current = new Date(start);
                while (current <= end) {
                    const y = current.getFullYear();
                    const m = String(current.getMonth() + 1).padStart(2, '0');
                    const d = String(current.getDate()).padStart(2, '0');
                    selectedDates.push(`${y}-${m}-${d}`);
                    current.setDate(current.getDate() + 1);
                }
            }

            generateCalendar();
        }

        function updateSelectedDates() {
            const selectedDatesDiv = document.getElementById('selected-dates');
            const submitBtn = document.getElementById('submit-btn');
            const startInput = document.getElementById('leave_start').value;
            const endInput = document.getElementById('leave_end').value;

            if (selectedDates.length === 0 && (!startInput || !endInput)) {
                selectedDatesDiv.textContent = 'Selecteer datums in de kalender hiernaast of gebruik de datumvelden';
                submitBtn.disabled = true;
                return;
            }

            // Als gebruiker de velden invult, update selectedDates
            if (startInput && endInput) {
                let startDate = new Date(startInput);
                let endDate = new Date(endInput);
                if (startDate > endDate) [startDate, endDate] = [endDate, startDate];

                selectedDates = [];
                const current = new Date(startDate);
                while (current <= endDate) {
                    const y = current.getFullYear();
                    const m = String(current.getMonth() + 1).padStart(2, '0');
                    const d = String(current.getDate()).padStart(2, '0');
                    selectedDates.push(`${y}-${m}-${d}`);
                    current.setDate(current.getDate() + 1);
                }
                firstSelectedDate = selectedDates[0];
            }

            const startDate = new Date(selectedDates[0]);
            const endDate = new Date(selectedDates[selectedDates.length - 1]);
            document.getElementById('leave_start').value = selectedDates[0];
            document.getElementById('leave_end').value = selectedDates[selectedDates.length - 1];

            selectedDatesDiv.textContent = `Geselecteerd: ${startDate.toLocaleDateString('nl-NL')} - ${endDate.toLocaleDateString('nl-NL')} (${selectedDates.length} dagen)`;
            submitBtn.disabled = false;
        }

        function changeMonth(delta) {
            currentDate.setMonth(currentDate.getMonth() + delta);
            generateCalendar();
        }

        document.addEventListener('DOMContentLoaded', () => {
            generateCalendar();

            // Voeg listeners toe aan date-inputs
            document.getElementById('leave_start').addEventListener('change', updateSelectedDates);
            document.getElementById('leave_end').addEventListener('change', updateSelectedDates);
        });
    </script>
</body>
</html>
