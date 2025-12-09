<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - {{ config('app.name', 'Laravel') }}</title>
    <link rel="shortcut icon" href="{{ asset('img/favicon.ico') }}" type="image/x-icon" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <style>
        /* Mantener el mismo CSS del login minimalista anterior */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Inter', 'Segoe UI', system-ui, sans-serif;
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .login-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            padding: 40px 30px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .login-header { text-align: center; margin-bottom: 40px; }
        .login-logo {
            width: 60px; height: 60px; margin: 0 auto 20px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 12px; display: flex; align-items: center;
            justify-content: center; color: white; font-weight: bold;
            font-size: 24px;
        }
        .login-title { font-size: 28px; font-weight: 700; color: #2d3748; margin-bottom: 8px; }
        .login-subtitle { color: #718096; font-size: 14px; }
        .login-form { display: flex; flex-direction: column; gap: 20px; }
        .form-group { display: flex; flex-direction: column; gap: 8px; }
        .form-label { font-size: 14px; font-weight: 500; color: #4a5568; }
        .form-input {
            padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 10px;
            font-size: 16px; transition: all 0.3s ease; background: #fff;
        }
        .form-input:focus {
            outline: none; border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        .form-input.error { border-color: #fc8181; }
        .login-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white; border: none; padding: 14px; border-radius: 10px;
            font-size: 16px; font-weight: 600; cursor: pointer;
            transition: all 0.3s ease; margin-top: 10px;
        }
        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }
        .login-links {
            display: flex; justify-content: space-between; align-items: center;
            margin-top: 25px; padding-top: 25px; border-top: 1px solid #e2e8f0;
        }
        .remember-me {
            display: flex; align-items: center; gap: 8px;
            font-size: 14px; color: #4a5568;
        }
        .forgot-password {
            color: #667eea; text-decoration: none; font-size: 14px;
            font-weight: 500; transition: color 0.3s ease;
        }
        .forgot-password:hover { color: #5a67d8; }
        .register-section {
            text-align: center; margin-top: 30px; padding-top: 25px;
            border-top: 1px solid #e2e8f0;
        }
        .register-text { color: #718096; font-size: 14px; margin-bottom: 10px; }
        .register-link {
            color: #667eea; text-decoration: none; font-weight: 600;
            transition: color 0.3s ease;
        }
        .register-link:hover { color: #5a67d8; }
        .alert {
            padding: 12px 16px; border-radius: 10px; margin-bottom: 20px;
            font-size: 14px;
        }
        .alert-error {
            background: #fed7d7; color: #c53030; border: 1px solid #feb2b2;
        }
        .alert-success {
            background: #c6f6d5; color: #276749; border: 1px solid #9ae6b4;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .login-container { animation: fadeIn 0.6s ease-out; }
        @media (max-width: 480px) {
            .login-container { padding: 30px 20px; }
            .login-title { font-size: 24px; }
            .login-links { flex-direction: column; gap: 15px; align-items: flex-start; }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <div class="login-logo">
                {{ substr(config('app.name', 'L'), 0, 1) }}
            </div>
            <h1 class="login-title">Iniciar Sesión</h1>
            <p class="login-subtitle">Accede a tu cuenta</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-error">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <form class="login-form" method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label for="email" class="form-label">Correo Electrónico</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    class="form-input @error('email') error @enderror" 
                    value="{{ old('email') }}" 
                    required 
                    autocomplete="email" 
                    autofocus
                    placeholder="tu@email.com"
                >
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Contraseña</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    class="form-input @error('password') error @enderror" 
                    required 
                    autocomplete="current-password"
                    placeholder="••••••••"
                >
            </div>

            <div class="login-links">
                <label class="remember-me">
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    Recordar sesión
                </label>
                <a href="{{ route('password.request') }}" class="forgot-password">
                    ¿Olvidaste tu contraseña?
                </a>
            </div>

            <button type="submit" class="login-btn">
                Iniciar Sesión
            </button>
        </form>
    </div>
</body>
</html>