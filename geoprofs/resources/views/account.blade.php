<!DOCTYPE html>
<html>
<head>
    <title>Accountgegevens</title>
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
        .dropdown-link { display: block; padding: 10px 15px; text-align: left; background: none; border: none; cursor: pointer; color: #333; font-size: 14px; text-decoration: none; }
        .dropdown-link:hover { background: #f0f0f0; }
        .logout-btn { width: 100%; padding: 10px 15px; text-align: left; background: none; border: none; cursor: pointer; color: #333; font-size: 14px; }
        .logout-btn:hover { background: #f0f0f0; }

        .container { max-width: 600px; margin: 50px auto; padding: 0 20px; }
        .card { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }

        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; color: #333; }
        .form-group input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; }

        .btn-submit { padding: 12px 24px; background: #27ae60; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
        .btn-submit:hover { background: #229954; }

        .success { background: #d4edda; color: #155724; padding: 12px; border-radius: 4px; margin-bottom: 15px; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; padding: 12px; border-radius: 4px; margin-bottom: 15px; border: 1px solid #f5c6cb; }

        .back-link { display: inline-block; margin-top: 20px; color: #3498db; text-decoration: none; }
        .back-link:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <!-- Top Navbar -->
    <div class="navbar">
        <h1>Accountgegevens</h1>
        <div class="account-dropdown">
            <button class="account-btn">{{ Auth::user()->name }} ▼</button>
            <div class="dropdown-content">
                <a href="{{ route('dashboard') }}" class="dropdown-link">Dashboard</a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="logout-btn">Uitloggen</button>
                </form>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="card">
            <h2>Uw Accountgegevens</h2>

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

            <form action="{{ route('user.update') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="name">Naam:</label>
                    <input type="text" id="name" name="name" value="{{ Auth::user()->name }}" readonly>
                </div>

                <div class="form-group">
                    <label for="email">E-mail:</label>
                    <input type="email" id="email" name="email" value="{{ Auth::user()->email }}" readonly>
                </div>

                <div class="form-group">
                    <label for="age">Leeftijd:</label>
                    <input type="number" id="age" name="age" value="{{ Auth::user()->age }}" min="18" max="100">
                </div>

                <div class="form-group">
                    <label for="functie">Functie:</label>
                    <input type="text" id="functie" name="functie" value="{{ Auth::user()->functie }}">
                </div>

                <button type="submit" class="btn-submit">Opslaan</button>
            </form>

            <a href="{{ route('dashboard') }}" class="back-link">Terug naar Dashboard</a>
        </div>
    </div>
</body>
</html>
