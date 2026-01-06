<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer contraseña - {{ config('app.name', 'Laravel') }}</title>
    <link rel="shortcut icon" href="{{ asset('img/favicon.ico') }}" type="image/x-icon" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="webp" href="../img/logo.jpg">
    
    <style>
        :root {
            --primary-color: #2e7d32;
            --secondary-color: #4caf50;
            --accent-color: #ff9800;
            --light-color: #f5f5f5;
            --dark-color: #333;
            --transition: all 0.3s ease;
        }
        
        * { 
            margin: 0; 
            padding: 0; 
            box-sizing: border-box; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }
        
        /* Fondo con imagen */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('https://i.pinimg.com/736x/47/2c/e7/472ce702a42826c727cf3cb88ae47f10.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            z-index: -2;
        }
        
        body::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to right, rgba(46, 125, 50, 0.85), rgba(76, 175, 80, 0.75));
            z-index: -1;
        }
        
        .reset-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 400px; /* Reducido de 450px a 400px */
            padding: 28px 30px; /* Reducido padding vertical de 40px a 35px */
            border: 1px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
            animation: fadeIn 0.6s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .reset-header { 
            text-align: center; 
            margin-bottom: 30px; /* Reducido de 35px a 30px */
        }
        
        .reset-icon {
            width: 65px; /* Reducido de 70px a 65px */
            height: 65px; /* Reducido de 70px a 65px */
            margin: 0 auto 18px; /* Reducido de 20px a 18px */
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 50%; 
            display: flex; 
            align-items: center;
            justify-content: center; 
            color: white;
            font-size: 26px; /* Reducido de 28px a 26px */
            box-shadow: 0 8px 20px rgba(46, 125, 50, 0.3);
        }
        
        .reset-title { 
            font-size: 24px; /* Reducido de 26px a 24px */
            font-weight: 700; 
            color: var(--primary-color); 
            margin-bottom: 8px; /* Reducido de 10px a 8px */
        }
        
        .reset-subtitle { 
            color: #666; 
            font-size: 14px; /* Reducido de 15px a 14px */
            line-height: 1.5;
            max-width: 280px; /* Reducido de 300px a 280px */
            margin: 0 auto;
        }
        
        .reset-form { 
            display: flex; 
            flex-direction: column; 
            gap: 22px; /* Reducido de 25px a 22px */
        }
        
        .form-group { 
            display: flex; 
            flex-direction: column; 
            gap: 8px; /* Reducido de 10px a 8px */
        }
        
        .form-label { 
            font-size: 14px; /* Reducido de 15px a 14px */
            font-weight: 600; 
            color: var(--dark-color); 
        }
        
        .form-input {
            padding: 13px 16px; /* Reducido de 14px 18px a 13px 16px */
            border: 2px solid #e0e0e0; 
            border-radius: 10px;
            font-size: 15px; /* Reducido de 16px a 15px */
            transition: var(--transition); 
            background: #fafafa;
            width: 100%;
        }
        
        .form-input:focus {
            outline: none; 
            border-color: var(--primary-color);
            background-color: white;
            box-shadow: 0 5px 15px rgba(46, 125, 50, 0.1);
        }
        
        .form-input.error { 
            border-color: #f44336; 
        }
        
        .reset-btn {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white; 
            border: none; 
            padding: 15px; /* Reducido de 16px a 15px */
            border-radius: 10px;
            font-size: 15px; /* Reducido de 16px a 15px */
            font-weight: 600; 
            cursor: pointer;
            transition: var(--transition); 
            margin-top: 8px; /* Reducido de 10px a 8px */
            font-family: inherit;
        }
        
        .reset-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(46, 125, 50, 0.3);
        }
        
        .alert {
            padding: 12px 16px; /* Reducido de 14px 18px a 12px 16px */
            border-radius: 10px; 
            margin-bottom: 22px; /* Reducido de 25px a 22px */
            font-size: 13px; /* Reducido de 14px a 13px */
            width: 100%;
        }
        
        .alert-error {
            background: #fed7d7; 
            color: #c53030; 
            border: 1px solid #feb2b2;
        }
        
        .alert-success {
            background: #c6f6d5;
            color: #276749;
            border: 1px solid #9ae6b4;
        }
        
        ul {
            list-style: none;
            margin: 0;
            padding-left: 18px; /* Reducido de 20px a 18px */
        }
        
        ul li {
            margin-bottom: 4px; /* Reducido de 5px a 4px */
            font-size: 13px; /* Reducido de 14px a 13px */
        }
        
        ul li:last-child {
            margin-bottom: 0;
        }
        
        @media (max-width: 576px) {
            .reset-container {
                padding: 25px 20px; /* Reducido de 30px 20px a 25px 20px */
                max-width: 350px; /* Más pequeño en móviles */
            }
            
            .reset-title { 
                font-size: 22px; 
            }
            
            .reset-subtitle { 
                font-size: 13px; /* Reducido de 14px a 13px */
            }
            
            .reset-icon {
                width: 55px; /* Reducido de 60px a 55px */
                height: 55px; /* Reducido de 60px a 55px */
                font-size: 22px; /* Reducido de 24px a 22px */
            }
            
            .form-input {
                padding: 12px 15px; /* Reducido para móviles */
                font-size: 14px;
            }
            
            .reset-btn {
                padding: 14px;
                font-size: 14px;
            }
        }
        
        /* Botón para volver atrás */
        .back-btn {
            position: absolute;
            top: 20px; /* Reducido de 25px a 20px */
            left: 20px; /* Reducido de 25px a 20px */
            background-color: rgba(255, 255, 255, 0.9);
            color: var(--primary-color);
            border: none;
            border-radius: 50px;
            padding: 10px 20px; /* Reducido de 12px 24px a 10px 20px */
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px; /* Reducido de 10px a 8px */
            transition: var(--transition);
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
            font-family: inherit;
            font-size: 14px; /* Reducido de 15px a 14px */
            z-index: 100;
        }
        
        .back-btn:hover {
            background-color: white;
            transform: translateX(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
        }
        
        /* Ajuste responsivo para el botón */
        @media (max-width: 576px) {
            .back-btn {
                top: 15px;
                left: 15px;
                padding: 8px 16px; /* Reducido de 10px 18px a 8px 16px */
                font-size: 13px; /* Reducido de 14px a 13px */
            }
        }
    </style>
</head>
<body>
    <!-- Botón para volver al login 
    <button class="back-btn" onclick="window.history.back()">
        <i class="fas fa-arrow-left"></i>
        <span>Volver</span>
    </button>-->
    
    <div class="reset-container">
        <div class="reset-header">
            <div class="reset-icon">
                <i class="fas fa-lock"></i>
            </div>
            <h1 class="reset-title">Nueva contraseña</h1>
            <p class="reset-subtitle">
                Ingresa tu nueva contraseña para restablecer el acceso a tu cuenta.
            </p>
        </div>

        @if ($errors->any())
            <div class="alert alert-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form class="reset-form" method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="form-group">
                <label for="email" class="form-label">Correo electrónico</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    class="form-input @error('email') error @enderror" 
                    value="{{ $email ?? old('email') }}" 
                    required 
                    autocomplete="email" 
                    autofocus
                    placeholder="ejemplo@correo.com"
                >
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Nueva contraseña</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    class="form-input @error('password') error @enderror" 
                    required 
                    autocomplete="new-password"
                    placeholder="Ingresa tu nueva contraseña"
                >
            </div>

            <div class="form-group">
                <label for="password_confirmation" class="form-label">Confirmar contraseña</label>
                <input 
                    type="password" 
                    id="password_confirmation" 
                    name="password_confirmation" 
                    class="form-input" 
                    required 
                    autocomplete="new-password"
                    placeholder="Confirma tu nueva contraseña"
                >
            </div>

            <button type="submit" class="reset-btn">
                Restablecer contraseña
            </button>
        </form>
    </div>

    <script>
        // Cargar imagen alternativa si la principal falla
        const body = document.querySelector('body');
        const originalImage = 'https://images.unsplash.com/photo-1542736667-069246bdbc6d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1471&q=80';
        const fallbackImage = 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1632&q=80';
        
        // Crear una imagen para probar si la principal carga
        const testImage = new Image();
        testImage.onload = function() {
            // La imagen principal carga correctamente
            document.body.style.backgroundImage = `url('${originalImage}')`;
        };
        testImage.onerror = function() {
            // La imagen principal falla, usar la alternativa
            document.body.style.backgroundImage = `url('${fallbackImage}')`;
        };
        testImage.src = originalImage;
    </script>
</body>
</html>