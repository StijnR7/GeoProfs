<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inloggen - Geoprofs</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #333;
        }
        .login-container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        .login-container h1 {
            margin-bottom: 10px;
            color: #2c3e50;
            font-size: 28px;
        }
        .login-container p {
            margin-bottom: 30px;
            color: #7f8c8d;
            font-size: 14px;
        }
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #34495e;
        }
        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #ecf0f1;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }
        .form-group input:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }
        .btn-login {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(52, 152, 219, 0.4);
        }
        .error {
            background: #fee;
            color: #c0392b;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #f8d7da;
        }
        .back-link {
            margin-top: 20px;
            display: block;
            color: #3498db;
            text-decoration: none;
            font-size: 14px;
        }
        .back-link:hover {
            text-decoration: underline;
        }
        .signature {
            position: absolute;
            bottom: 10px;
            left: 10px;
            font-size: 12px;
            color: rgba(255, 255, 255, 0.5);
            text-decoration: none;
        }
        .signature:hover {
            color: rgba(255, 255, 255, 0.8);
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Welkom bij Geoprofs</h1>
        <p>Log in om toegang te krijgen tot je dashboard</p>

        @if($errors->has('email'))
            <div class="error">{{ $errors->first('email') }}</div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            <div class="form-group">
                <label for="email">E-mailadres:</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
            </div>
            <div class="form-group">
                <label for="password">Wachtwoord:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn-login">Inloggen</button>
        </form>
    </div>

    <a href="#" class="signature">Niek Brock</a>
</body>
</html>
