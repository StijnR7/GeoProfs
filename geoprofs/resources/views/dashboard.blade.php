<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; }

        .navbar { display: flex; justify-content: space-between; align-items: center; padding: 15px 20px; background: #2c3e50; color: white; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .navbar h1 { font-size: 24px; }

        .account-dropdown { position: relative; display: inline-block; }
        .account-btn { padding: 8px 15px; background: #3498db; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; }
        .account-btn:hover { background: #2980b9; }
        .dropdown-content { display: none; position: absolute; right: 0; background: white; min-width: 150px; box-shadow: 0 8px 16px rgba(0,0,0,0.2); z-index: 1; border-radius: 4px; top: 100%;  }
        .account-dropdown:hover .dropdown-content { display: block; }
        .logout-btn { width: 100%; padding: 10px 15px; text-align: left; background: none; border: none; cursor: pointer; color: #333; font-size: 14px; }
        .logout-btn:hover { background: #f0f0f0; }

        .nav-buttons { display: flex; gap: 10px; background: #34495e; padding: 10px 20px; }
        .nav-btn { padding: 10px 20px; background: #3498db; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; transition: background 0.3s; }
        .nav-btn:hover { background: #2980b9; }
        .nav-btn.active { background: #27ae60; }

        .container { max-width: 1200px; margin: 20px auto; padding: 0 20px; }
        .section { display: none; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .section.active { display: block; }

        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; color: #333; }
        .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; }
        .form-group textarea { resize: vertical; }

        .btn-submit { padding: 10px 20px; background: #27ae60; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; }
        .btn-submit:hover { background: #229954; }

        .success { background: #d4edda; color: #155724; padding: 12px; border-radius: 4px; margin-bottom: 15px; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; padding: 12px; border-radius: 4px; margin-bottom: 15px; border: 1px solid #f5c6cb; }

        .leaves-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .leaves-table th, .leaves-table td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        .leaves-table th { background: #f0f0f0; font-weight: bold; }
        .leaves-table tr:hover { background: #f9f9f9; }
    </style>
</head>
<body>
    <!-- Top Navbar -->
    <div class="navbar">
        <h1>Dashboard</h1>
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

    <!-- Navigation Buttons -->
    <div class="nav-buttons">
        <button class="nav-btn active" onclick="showSection('request')">Ziek/Verlofaanvraag</button>
        <button class="nav-btn" onclick="showSection('myrequests')">Mijn Aanvragen</button>
        <button class="nav-btn" onclick="showSection('leavedays')">Verlofdagen</button>
        @if(Auth::user()->functie === 'admin')
            <button class="nav-btn" onclick="showSection('manage')">Beheer Aanvragen</button>
        @endif
    </div>

    <div class="container">
        <!-- Section 1: Request Leave -->
        <div id="request" class="section active">
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

            <form action="{{ route('leave.submit') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="leave_start">Startdatum:</label>
                    <input type="date" id="leave_start" name="leave_start" required value="{{ old('leave_start') }}">
                </div>

                <div class="form-group">
                    <label for="leave_end">Einddatum:</label>
                    <input type="date" id="leave_end" name="leave_end" required value="{{ old('leave_end') }}">
                </div>

                <div class="form-group">
                    <label for="type">Type:</label>
                    <select id="type" name="type" required>
                        <option value="">-- Kies reden --</option>
                        <option value="ziek" {{ old('type') == 'ziek' ? 'selected' : '' }}>Ziek</option>
                        <option value="verlof" {{ old('type') == 'verlof' ? 'selected' : '' }}>Verlof</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="reason">Toelichting (optioneel):</label>
                    <textarea id="reason" name="reason" rows="4">{{ old('reason') }}</textarea>
                </div>

                <button type="submit" class="btn-submit">Aanvraag Indienen</button>
            </form>
        </div>

        <!-- Section 2: My Requests -->
        <div id="myrequests" class="section">
            <h2>Mijn Aanvragen</h2>

            @if(isset($userLeaves) && $userLeaves->count() > 0)
                <table class="leaves-table">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Startdatum</th>
                            <th>Einddatum</th>
                            <th>Status</th>
                            <th>Aanvraagdatum</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($userLeaves as $leave)
                            <tr>
                                <td>{{ ucfirst($leave->type) }}</td>
                                <td>{{ $leave->leave_start->format('d-m-Y') }}</td>
                                <td>{{ $leave->leave_end->format('d-m-Y') }}</td>
                                <td>{{ ucfirst($leave->status) }}</td>
                                <td>{{ $leave->created_at->format('d-m-Y H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>Je hebt nog geen aanvragen ingediend.</p>
            @endif
        </div>

        <!-- Section 3: Leave Days -->
        <div id="leavedays" class="section">
            <h2>Mijn Verlofdagen</h2>

            <p>Je hebt momenteel <strong>{{ $leaveBalance ?? 0 }}</strong> verlofdagen beschikbaar.</p>
        </div>

        @if(Auth::user()->functie === 'admin')
        <!-- Section 4: Manage Requests -->
        <div id="manage" class="section">
            <h2>Beheer Aanvragen</h2>

            @if($pendingLeaves && $pendingLeaves->count() > 0)
                <table class="leaves-table">
                    <thead>
                        <tr>
                            <th>Gebruiker</th>
                            <th>Type</th>
                            <th>Startdatum</th>
                            <th>Einddatum</th>
                            <th>Reden</th>
                            <th>Acties</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingLeaves as $leave)
                            <tr>
                                <td>{{ $leave->user->name }}</td>
                                <td>{{ ucfirst($leave->type) }}</td>
                                <td>{{ $leave->leave_start->format('d-m-Y') }}</td>
                                <td>{{ $leave->leave_end->format('d-m-Y') }}</td>
                                <td>{{ $leave->reason ?? '-' }}</td>
                                <td>
                                    <form action="{{ route('leave.approve', $leave->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn-submit" style="background:#27ae60;">Goedkeuren</button>
                                    </form>
                                    <form action="{{ route('leave.reject', $leave->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn-submit" style="background:#e74c3c;">Afwijzen</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>Geen openstaande aanvragen.</p>
            @endif
        </div>
        @endif
    </div>

    <script>
        function showSection(sectionId) {
            // Hide all sections
            document.querySelectorAll('.section').forEach(section => {
                section.classList.remove('active');
            });

            // Remove active class from all buttons
            document.querySelectorAll('.nav-btn').forEach(btn => {
                btn.classList.remove('active');
            });

            // Show selected section
            document.getElementById(sectionId).classList.add('active');

            // Add active class to clicked button
            event.target.classList.add('active');
        }
    </script>
</body>
</html>
