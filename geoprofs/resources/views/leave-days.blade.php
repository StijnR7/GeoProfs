<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verlofdagen - Geoprofs</title>
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

        .container { max-width: 800px; margin: 20px auto; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }

        .section h2 { margin: 0 0 20px 0; color: #2c3e50; font-size: 24px; font-weight: 300; }

        .leave-balance-card { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; border-radius: 12px; text-align: center; margin-bottom: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.15); }
        .leave-balance-card h3 { font-size: 36px; margin: 0 0 5px 0; font-weight: 300; }
        .leave-balance-card p { font-size: 14px; margin: 0; opacity: 0.9; }

        .leave-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 20px; }
        .stat-card { background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-left: 4px solid #3498db; }
        .stat-card h4 { margin: 0 0 5px 0; color: #2c3e50; font-size: 14px; font-weight: 600; }
        .stat-card .value { font-size: 24px; font-weight: 300; color: #3498db; margin: 0; }
        .stat-card.sick { border-left-color: #e74c3c; }
        .stat-card.sick .value { color: #e74c3c; }
        .stat-card.used { border-left-color: #f39c12; }
        .stat-card.used .value { color: #f39c12; }

        .leave-info { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px; margin-top: 20px; }
        .info-card { background: #f8f9fa; padding: 15px; border-radius: 8px; border-left: 4px solid #3498db; }
        .info-card h4 { margin: 0 0 8px 0; color: #2c3e50; font-size: 14px; }
        .info-card p { margin: 0; color: #6c757d; font-size: 13px; line-height: 1.4; }

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
        <a href="{{ route('my-requests') }}" class="nav-btn">Mijn Aanvragen</a>
        <a href="{{ route('leave-days') }}" class="nav-btn active">Verlofdagen</a>
        <a href="{{ route('department-calendar') }}" class="nav-btn">Afdelingsagenda</a>
        @if(Auth::user()->functie === 'admin')
            <a href="{{ route('manage-requests') }}" class="nav-btn">Beheer Aanvragen</a>
        @endif
    </div>

    <div class="container">
        <h2>Verlofdagen</h2>

        <div class="leave-balance-card">
            <h3>{{ $remainingVacationDays }}</h3>
            <p>Beschikbare verlofdagen</p>
        </div>

        <div class="leave-stats">
            <div class="stat-card">
                <h4>Totaal toegewezen</h4>
                <div class="value">{{ $totalLeaveBalance }}</div>
            </div>
            <div class="stat-card used">
                <h4>Gebruikte vakantiedagen</h4>
                <div class="value">{{ $usedVacationDays }}</div>
            </div>
            <div class="stat-card sick">
                <h4>Ziekmeldingen</h4>
                <div class="value">{{ $usedSickDays }}</div>
            </div>
        </div>

        <div class="leave-info">
            <div class="info-card">
                <h4>Hoe werken verlofdagen?</h4>
                <p>Bij elke goedgekeurde verlofaanvraag worden de dagen automatisch van je saldo afgetrokken.</p>
            </div>

            <div class="info-card">
                <h4>Ziekmeldingen</h4>
                <p>Ziekmeldingen hebben geen invloed op je verlofdagen. Deze worden apart bijgehouden.</p>
            </div>

            <div class="info-card">
                <h4>Status aanvragen</h4>
                <p>Bekijk de status van je aanvragen in "Mijn Aanvragen". Aanvragen moeten eerst worden goedgekeurd.</p>
            </div>
        </div>
    </div>

   
</body>
</html>
