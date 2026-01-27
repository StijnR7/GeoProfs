<!DOCTYPE html>
<html>
<head>
    <title>Verlof & Ziekmeldingen</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f8f9fa; color: #333; }

        .navbar { display: flex; justify-content: space-between; align-items: center; padding: 15px 20px; background: #2c3e50; color: white; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .navbar h1 { font-size: 24px; }

        .account-dropdown { position: relative; display: inline-block; }
        .account-btn { padding: 8px 15px; background: #3498db; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; }
        .account-btn:hover { background: #2980b9; }
        .dropdown-content { display: none; position: absolute; right: 0; background: white; min-width: 150px; box-shadow: 0 8px 16px rgba(0,0,0,0.2); z-index: 1; border-radius: 4px; top: 100%;  }
        .account-dropdown:hover .dropdown-content { display: block; }
        .dropdown-link { display: block; padding: 10px 15px; text-align: left; background: none; border: none; cursor: pointer; color: #333; font-size: 14px; text-decoration: none; }
        .dropdown-link:hover { background: #f0f0f0; }
        .logout-btn { width: 100%; padding: 10px 15px; text-align: left; background: none; border: none; cursor: pointer; color: #333; font-size: 14px; }
        .logout-btn:hover { background: #f0f0f0; }

        .container { max-width: 1000px; margin: 30px auto; padding: 0 20px; }

        .header { text-align: center; margin-bottom: 30px; }
        .header h2 { font-size: 32px; font-weight: 300; color: #2c3e50; margin-bottom: 10px; }
        .header p { color: #6c757d; font-size: 16px; }

        .leaves-container { background: white; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); overflow: hidden; }

        .leave-item {
            display: flex;
            align-items: center;
            padding: 20px;
            border-bottom: 1px solid #e9ecef;
            transition: all 0.2s;
        }
        .leave-item:hover { background: #f8f9fa; }
        .leave-item:last-child { border-bottom: none; }

        .leave-type {
            width: 120px;
            flex-shrink: 0;
        }
        .leave-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .leave-verlof { background: #d4edda; color: #155724; }
        .leave-ziek { background: #f8d7da; color: #721c24; }

        .leave-dates {
            flex: 1;
            margin: 0 20px;
        }
        .date-range {
            font-size: 18px;
            font-weight: 500;
            color: #495057;
            margin-bottom: 4px;
        }
        .date-detail {
            font-size: 14px;
            color: #6c757d;
        }

        .leave-status {
            width: 100px;
            text-align: right;
            flex-shrink: 0;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-approved { background: #d1ecf1; color: #0c5460; }
        .status-rejected { background: #f8d7da; color: #721c24; }

        .leave-reason {
            flex: 1;
            font-size: 14px;
            color: #6c757d;
            font-style: italic;
        }

        .no-leaves {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
        }
        .no-leaves h3 { font-size: 24px; margin-bottom: 10px; color: #495057; }
        .no-leaves p { font-size: 16px; }

        .back-link { display: inline-block; margin-top: 20px; color: #3498db; text-decoration: none; font-size: 16px; }
        .back-link:hover { text-decoration: underline; }

        @media (max-width: 768px) {
            .leave-item { flex-direction: column; align-items: flex-start; gap: 10px; }
            .leave-type, .leave-status { width: auto; text-align: left; }
            .leave-dates { margin: 0; }
        }
    </style>
</head>
<body>
    <!-- Top Navbar -->
    <div class="navbar">
        <h1>Verlof & Ziekmeldingen</h1>
        <div class="account-dropdown">
            <button class="account-btn">{{ Auth::user()->name }} ▼</button>
            <div class="dropdown-content">
                <a href="{{ route('dashboard') }}" class="dropdown-link">Dashboard</a>
                <a href="{{ route('account') }}" class="dropdown-link">Accountgegevens</a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="logout-btn">Uitloggen</button>
                </form>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="header">
            <h2>Mijn Verlof & Ziekmeldingen</h2>
            <p>Overzicht van al je ingediende aanvragen</p>
        </div>

        <div class="leaves-container">
            @if($userLeaves->count() > 0)
                @foreach($userLeaves as $leave)
                    <div class="leave-item">
                        <div class="leave-type">
                            <span class="leave-badge leave-{{ $leave->type }}">
                                {{ ucfirst($leave->type) }}
                            </span>
                        </div>

                        <div class="leave-dates">
                            <div class="date-range">
                                {{ $leave->leave_start->format('d-m-Y') }} t/m {{ $leave->leave_end->format('d-m-Y') }}
                            </div>
                            <div class="date-detail">
                                Ingediend op {{ $leave->created_at->format('d-m-Y H:i') }}
                            </div>
                        </div>

                        <div class="leave-reason">
                            @if($leave->reason)
                                "{{ $leave->reason }}"
                            @else
                                Geen reden opgegeven
                            @endif
                        </div>

                        <div class="leave-status">
                            <span class="status-badge status-{{ $leave->status }}">
                                {{ ucfirst($leave->status) }}
                            </span>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="no-leaves">
                    <h3>Geen aanvragen gevonden</h3>
                    <p>Je hebt nog geen verlof- of ziekmeldingen ingediend.</p>
                </div>
            @endif
        </div>

        <div style="text-align: center; margin-top: 30px;">
            <a href="{{ route('dashboard') }}" class="back-link">Terug naar Dashboard</a>
        </div>
    </div>
</body>
</html>
