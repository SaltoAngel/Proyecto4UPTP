<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña - {{ config('app.name', 'Laravel') }}</title>
    <link rel="shortcut icon" href="{{ asset('img/favicon.ico') }}" type="image/x-icon" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <style>
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
        .reset-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            padding: 40px 30px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .reset-header { text-align: center; margin-bottom: 30px; }
        .reset-icon {
            width: 60px; height: 60px; margin: 0 auto 20px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 12px; display: flex; align-items: center;
            justify-content: center; color: white;
        }
        .reset-icon svg { width: 30px; height: 30px; }
        .reset-title { font-size: 24px; font-weight: 700; color: #2d3748; margin-bottom: 8px; }
        .reset-subtitle { color: #718096; font-size: 14px; line-height: 1.5; }
        .reset-form { display: flex; flex-direction: column; gap: 20px; }
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
        .reset-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white; border: none; padding: 14px; border-radius: 10px;
            font-size: 16px; font-weight: 600; cursor: pointer;
            transition: all 0.3s ease; margin-top: 10px;
        }
        .reset-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }
        .back-link {
            text-align: center; margin-top: 20px; padding-top: 20px;
            border-top: 1px solid #e2e8f0;
        }
        .back-link a {
            color: #667eea; text-decoration: none; font-size: 14px;
            font-weight: 500; transition: color 0.3s ease;
        }
        .back-link a:hover { color: #5a67d8; }
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
        .reset-container { animation: fadeIn 0.6s ease-out; }
    </style>
</head>
<body>
    <div class="reset-container">
        <div class="reset-header">
            <div class="reset-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                </svg>
            </div>
            <h1 class="reset-title">Recuperar Contraseña</h1>
            <p class="reset-subtitle">
                Ingresa tu correo electrónico y te enviaremos un enlace para restablecer tu contraseña.
            </p>
        </div>

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-error">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form class="reset-form" method="POST" action="{{ route('password.email') }}">
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

            <button type="submit" class="reset-btn">
                Enviar Enlace de Recuperación
            </button>
        </form>

        <div class="back-link">
            <a href="{{ route('login') }}">← Volver al inicio de sesión</a>
        </div>
    </div>
</body>
</html>
