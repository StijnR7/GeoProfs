<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mijn Aanvragen - Geoprofs</title>
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
        .logout-btn { width: 100%; padding: 12px 16px; text-align: left; background: none; border: none; cursor: pointer; color: #ecf0f1; font-size: 14px; transition: background 0.3s; }
        .logout-btn:hover { background: #e74c3c; }

        .nav-buttons { display: flex; gap: 8px; background: #34495e; padding: 12px 20px; border-bottom: 1px solid #2c3e50; }
        .nav-btn { padding: 8px 16px; background: #3498db; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 13px; transition: all 0.3s; font-weight: 500; text-decoration: none; display: inline-block; }
        .nav-btn:hover { background: #2980b9; transform: translateY(-1px); }
        .nav-btn.active { background: #27ae60; }

        .container { max-width: 1200px; margin: 20px auto; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }

        .section h2 { margin: 0 0 20px 0; color: #2c3e50; font-size: 24px; font-weight: 300; }

        .leaves-table { width: 100%; border-collapse: collapse; margin-top: 15px; background: white; border-radius: 4px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .leaves-table th, .leaves-table td { padding: 12px; text-align: left; border-bottom: 1px solid #dee2e6; color: #495057; font-size: 14px; }
        .leaves-table th { background: #f8f9fa; font-weight: 600; color: #2c3e50; }
        .leaves-table tr:hover { background: #f8f9fa; }

        .status-approved { color: #27ae60; font-weight: 600; }
        .status-pending { color: #f39c12; font-weight: 600; }
        .status-rejected { color: #e74c3c; font-weight: 600; }

        .no-requests { text-align: center; padding: 40px; color: #6c757d; font-size: 16px; }

        .signature { position: fixed; bottom: 10px; right: 10px; font-size: 10px; color: #6c757d; text-decoration: none; z-index: 1000; }
        .signature:hover { color: #495057; }
    </style>
</head>
<body>
    <!-- Top Navbar -->
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
                <a href="{{ route('account') }}" class="dropdown-link">Accountgegevens</a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="logout-btn">Uitloggen</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Navigation Buttons -->
    <div class="nav-buttons">
        <a href="{{ route('dashboard') }}" class="nav-btn">Ziek/Verlofaanvraag</a>
        <a href="{{ route('my-requests') }}" class="nav-btn active">Mijn Aanvragen</a>
        <a href="{{ route('leave-days') }}" class="nav-btn">Verlofdagen</a>
        <a href="{{ route('department-calendar') }}" class="nav-btn">Afdelingsagenda</a>
        @if(Auth::user()->functie === 'admin')
            <a href="{{ route('manage-requests') }}" class="nav-btn">Beheer Aanvragen</a>
        @endif
    </div>

    <div class="container">
        <h2>Mijn Aanvragen</h2>

        @if($userLeaves->count() > 0)
            <table class="leaves-table">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Startdatum</th>
                        <th>Einddatum</th>
                        <th>Status</th>
                        <th>Aanvraagdatum</th>
                        <th>Toelichting</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($userLeaves as $leave)
                        <tr>
                            <td>{{ ucfirst($leave->type) }}</td>
                            <td>{{ $leave->leave_start->format('d-m-Y') }}</td>
                            <td>{{ $leave->leave_end->format('d-m-Y') }}</td>
                            <td>
                                <span class="status-{{ $leave->status }}">
                                    @switch($leave->status)
                                        @case('approved')
                                            Goedgekeurd
                                            @break
                                        @case('pending')
                                            In behandeling
                                            @break
                                        @case('rejected')
                                            Afgewezen
                                            @break
                                    @endswitch
                                </span>
                            </td>
                            <td>{{ $leave->created_at->format('d-m-Y H:i') }}</td>
                            <td>{{ $leave->reason ?: '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="no-requests">
                <p>Je hebt nog geen aanvragen ingediend.</p>
            </div>
        @endif
    </div>

    
</body>
</html>
