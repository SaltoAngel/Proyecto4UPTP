<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión - {{ config('app.name', 'Laravel') }}</title>
    <link rel="shortcut icon" href="{{ asset('img/favicon.ico') }}" type="image/x-icon" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="webp" href="../img/logo.jpg">
    <style>
        /* Estilos generales */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        :root {
            --primary-color: #2e7d32;
            --secondary-color: #4caf50;
            --accent-color: #ff9800;
            --light-color: #f5f5f5;
            --dark-color: #333;
            --transition: all 0.3s ease;
        }
        
        body {
            background-color: #f9f9f9;
            color: var(--dark-color);
            line-height: 1.6;
            height: 100vh;
            overflow: hidden;
        }
        
        /* Botón para regresar al home */
        .back-home {
            position: absolute;
            top: 20px;
            left: 20px;
            z-index: 10;
        }
        
        .back-btn {
            background-color: rgba(255, 255, 255, 0.9);
            color: var(--primary-color);
            border: none;
            border-radius: 50px;
            padding: 10px 20px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: var(--transition);
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }
        
        .back-btn:hover {
            background-color: white;
            transform: translateX(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
        }
        
        /* Contenedor principal */
        .login-container {
            display: flex;
            height: 100vh;
            width: 100%;
        }
        
        /* Sección de la imagen */
        .image-section {
            flex: 1;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .farm-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: 1;
        }
        
        .image-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to right, rgba(45, 109, 48, 0.8), rgba(63, 145, 64, 0.6));
            z-index: 2;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 30px;
            text-align: center;
            color: white;
        }
        
        .image-overlay h1 {
            font-size: 2.8rem;
            margin-bottom: 15px;
            text-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }
        
        .image-overlay p {
            font-size: 1.2rem;
            max-width: 600px;
            margin-bottom: 30px;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
        }
        
        .image-overlay .logo {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            font-size: 2.2rem;
        }
        
        .logo {
            display: flex;
            align-items: center;
        }
        
        .logo img {
            height: 100px;
            margin-right: 10px;
            border-radius: 30px;
        }
        
        .logo h1 {
            font-size: 1.8rem;
            color: #ffff;
        }
        
        .image-overlay .logo i {
            margin-right: 15px;
            font-size: 2.8rem;
        }
        
        .farm-icons {
            display: flex;
            justify-content: center;
            margin-top: 20px;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .farm-icons i {
            background-color: rgba(255, 255, 255, 0.2);
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            transition: var(--transition);
        }
        
        .farm-icons i:hover {
            background-color: rgba(255, 255, 255, 0.3);
            transform: translateY(-5px);
        }
        
        /* Sección del formulario */
        .form-section {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            background-color: #f5f5f5;
            position: relative;
            overflow-y: auto;
        }
        
        .login-form-container {
            width: 100%;
            max-width: 450px;
            animation: fadeIn 0.8s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .form-header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .form-header h2 {
            font-size: 2.2rem;
            color: var(--primary-color);
            margin-bottom: 10px;
        }
        
        .form-header p {
            color: #666;
            font-size: 1.1rem;
        }
        
        /* Estilos del formulario */
        .login-form {
            width: 100%;
        }
        
        .form-group {
            margin-bottom: 25px;
            position: relative;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--dark-color);
            font-size: 1rem;
        }
        
        .input-with-icon {
            position: relative;
        }
        
        .input-with-icon i.fas {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary-color);
            font-size: 1.2rem;
            z-index: 2;
        }
        
        /* ESTILOS PARA EL OJITO DENTRO DEL INPUT */
        .toggle-password {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #666;
            z-index: 2;
            background: transparent;
            border: none;
            padding: 0;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .toggle-password:hover {
            color: var(--primary-color);
        }
        
        .form-control {
            width: 100%;
            padding: 15px 50px 15px 50px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 1rem;
            transition: var(--transition);
            background-color: #fafafa;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            background-color: white;
            box-shadow: 0 5px 15px rgba(46, 125, 50, 0.1);
        }
        
        .form-control::placeholder {
            color: #aaa;
        }
        
        /* Opciones de recordar y olvidar contraseña */
        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }
        
        .remember-me {
            display: flex;
            align-items: center;
        }
        
        .remember-me input {
            margin-right: 8px;
            accent-color: var(--primary-color);
        }
        
        .forgot-password {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
        }
        
        .forgot-password:hover {
            color: var(--accent-color);
            text-decoration: underline;
        }
        
        /* Botones */
        .btn {
            width: 100%;
            padding: 16px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 1.1rem;
            cursor: pointer;
            transition: var(--transition);
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            color: white;
            margin-bottom: 20px;
        }
        
        .btn-primary:hover {
            background-color: #1b5e20;
            box-shadow: 0 5px 15px rgba(46, 125, 50, 0.3);
            transform: translateY(-3px);
        }
        
        /* Animación de carga */
        .spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        /* Alertas */
        .alert {
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
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
        
        .alert ul {
            list-style: none;
            margin-bottom: 0;
        }
        
        .alert ul li {
            margin-bottom: 5px;
        }
        
        .alert ul li:last-child {
            margin-bottom: 0;
        }
        
        /* RESPONSIVE - Para tablets grandes y laptops pequeñas */
        @media (max-width: 1200px) {
            .image-overlay h1 {
                font-size: 2.4rem;
            }
            
            .image-overlay p {
                font-size: 1.1rem;
                max-width: 500px;
            }
            
            .form-header h2 {
                font-size: 2rem;
            }
        }
        
        /* RESPONSIVE - Para tablets */
        @media (max-width: 992px) {
            .login-container {
                flex-direction: column;
                min-height: 100vh;
                height: auto;
            }
            
            .back-home {
                position: fixed;
                top: 15px;
                left: 15px;
                z-index: 1000;
            }
            
            .back-btn {
                padding: 8px 16px;
                font-size: 0.9rem;
                backdrop-filter: blur(5px);
                background-color: rgba(255, 255, 255, 0.85);
            }
            
            .back-btn:hover {
                transform: none;
            }
            
            .image-section {
                height: 35vh;
                min-height: 250px;
                flex: none;
            }
            
            .image-overlay {
                padding: 20px;
            }
            
            .image-overlay .logo {
                flex-direction: row !important;
                margin-bottom: 15px;
                font-size: 1.8rem;
                flex-wrap: nowrap;
                white-space: nowrap;
            }
            
            .image-overlay .logo i {
                font-size: 2.2rem;
                margin-right: 10px;
                flex-shrink: 0;
            }
            
            .image-overlay .logo h1 {
                font-size: 1.6rem;
                white-space: normal;
                line-height: 1.2;
            }
            
            .image-overlay h1 {
                font-size: 1.8rem;
                margin-bottom: 10px;
                line-height: 1.2;
            }
            
            .image-overlay p {
                font-size: 0.95rem;
                max-width: 90%;
                margin-bottom: 20px;
                line-height: 1.4;
            }
            
            .farm-icons {
                margin-top: 15px;
                gap: 12px;
                justify-content: center;
            }
            
            .farm-icons i {
                width: 45px;
                height: 45px;
                font-size: 1.4rem;
            }
            
            .form-section {
                flex: 1;
                min-height: 65vh;
                padding: 25px 20px;
                overflow-y: auto;
                justify-content: flex-start;
                padding-top: 60px;
            }
            
            .login-form-container {
                max-width: 500px;
                margin: 0 auto;
            }
        }
        
        /* RESPONSIVE - Para tablets pequeñas y móviles grandes */
        @media (max-width: 768px) {
            body {
                overflow-y: auto;
            }
            
            .login-container {
                height: auto;
                min-height: 100vh;
            }
            
            .image-section {
                height: 32vh;
                min-height: 220px;
            }
            
            .image-overlay {
                padding: 15px;
            }
            
            .image-overlay .logo {
                flex-direction: row !important;
                align-items: center;
                margin-bottom: 12px;
                font-size: 1.6rem;
            }
            
            .image-overlay .logo i {
                font-size: 2rem;
                margin-right: 10px;
            }
            
            .image-overlay .logo h1 {
                font-size: 1.4rem;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                max-width: 70vw;
            }
            
            .image-overlay h1 {
                font-size: 1.6rem;
                margin-bottom: 8px;
            }
            
            .image-overlay p {
                font-size: 0.9rem;
                max-width: 95%;
                margin-bottom: 15px;
            }
            
            .farm-icons {
                gap: 10px;
                margin-top: 12px;
            }
            
            .farm-icons i {
                width: 42px;
                height: 42px;
                font-size: 1.3rem;
            }
            
            .form-section {
                padding: 20px 15px;
                padding-top: 60px;
                min-height: 68vh;
                display: block;
            }
            
            .login-form-container {
                width: 100%;
                margin-top: 0;
            }
            
            .form-header {
                margin-bottom: 30px;
            }
            
            .form-header h2 {
                font-size: 1.8rem;
                line-height: 1.2;
            }
            
            .form-header p {
                font-size: 1rem;
                line-height: 1.4;
            }
            
            .form-group {
                margin-bottom: 20px;
            }
            
            .form-group label {
                font-size: 0.95rem;
                margin-bottom: 6px;
            }
            
            .form-control {
                padding: 14px 45px 14px 45px;
                font-size: 0.95rem;
            }
            
            .input-with-icon i.fas {
                font-size: 1.1rem;
                left: 13px;
            }
            
            .toggle-password {
                right: 15px;
                width: 22px;
                height: 22px;
            }
            
            .btn {
                padding: 15px;
                font-size: 1rem;
            }
            
            .form-options {
                margin-bottom: 25px;
                gap: 15px;
            }
            
            .remember-me label {
                font-size: 0.95rem;
            }
            
            .forgot-password {
                font-size: 0.95rem;
            }
            
            .logo img {
                height: 70px;
            }
        }
        
        /* RESPONSIVE - Para móviles */
        @media (max-width: 576px) {
            .image-section {
                height: 30vh;
                min-height: 200px;
            }
            
            .image-overlay {
                padding: 12px 10px;
            }
            
            .image-overlay .logo {
                flex-direction: row !important;
                margin-bottom: 10px;
                font-size: 1.4rem;
                width: 100%;
                justify-content: center;
            }
            
            .image-overlay .logo i {
                font-size: 1.8rem;
                margin-right: 8px;
                flex-shrink: 0;
            }
            
            .image-overlay .logo h1 {
                font-size: 1.2rem;
                white-space: nowrap;
                max-width: 60vw;
            }
            
            .image-overlay h1 {
                font-size: 1.4rem;
                margin-bottom: 6px;
                padding: 0 5px;
            }
            
            .image-overlay p {
                font-size: 0.85rem;
                max-width: 98%;
                margin-bottom: 12px;
                padding: 0 5px;
            }
            
            .farm-icons {
                gap: 8px;
                margin-top: 10px;
            }
            
            .farm-icons i {
                width: 38px;
                height: 38px;
                font-size: 1.2rem;
            }
            
            .form-section {
                padding: 15px 12px;
                padding-top: 60px;
                min-height: 70vh;
            }
            
            .login-form-container {
                width: 100%;
            }
            
            .form-header h2 {
                font-size: 1.6rem;
            }
            
            .form-header p {
                font-size: 0.9rem;
            }
            
            .form-options {
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
                gap: 10px;
                margin-bottom: 20px;
            }
            
            .remember-me {
                width: auto;
                flex: 1;
            }
            
            .remember-me label {
                font-size: 0.9rem;
                white-space: nowrap;
            }
            
            .forgot-password {
                align-self: center;
                font-size: 0.9rem;
                text-align: right;
                white-space: nowrap;
            }
            
            .form-control {
                padding: 12px 42px 12px 42px;
                font-size: 0.9rem;
            }
            
            .input-with-icon i.fas {
                font-size: 1rem;
                left: 12px;
            }
            
            .toggle-password {
                right: 12px;
                width: 20px;
                height: 20px;
            }
            
            .toggle-password i {
                font-size: 0.9rem;
            }
            
            .alert {
                padding: 10px 12px;
                font-size: 0.85rem;
                margin-bottom: 15px;
            }
            
            .btn {
                padding: 14px;
                font-size: 0.95rem;
            }
        }
        
        /* RESPONSIVE - Para móviles muy pequeños */
        @media (max-width: 400px) {
            .image-section {
                height: 28vh;
                min-height: 180px;
            }
            
            .image-overlay .logo {
                font-size: 1.2rem;
            }
            
            .image-overlay .logo i {
                font-size: 1.6rem;
                margin-right: 6px;
            }
            
            .image-overlay .logo h1 {
                font-size: 1.1rem;
                max-width: 55vw;
            }
            
            .image-overlay h1 {
                font-size: 1.3rem;
            }
            
            .image-overlay p {
                font-size: 0.8rem;
                line-height: 1.3;
            }
            
            .farm-icons i {
                width: 35px;
                height: 35px;
                font-size: 1.1rem;
            }
            
            .form-section {
                padding: 12px 10px;
                padding-top: 55px;
            }
            
            .form-header h2 {
                font-size: 1.4rem;
            }
            
            .form-header p {
                font-size: 0.85rem;
            }
            
            .form-options {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }
            
            .remember-me {
                width: 100%;
            }
            
            .forgot-password {
                align-self: flex-end;
                font-size: 0.85rem;
            }
            
            .form-control {
                padding: 11px 40px 11px 40px;
                font-size: 0.85rem;
            }
            
            .input-with-icon i.fas {
                font-size: 0.95rem;
                left: 10px;
            }
            
            .btn {
                padding: 13px;
                font-size: 0.9rem;
            }
            
            .back-btn span {
                display: none;
            }
            
            .back-btn {
                padding: 8px;
                border-radius: 50%;
                width: 36px;
                height: 36px;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            .back-btn i {
                margin-right: 0;
                font-size: 1rem;
            }
        }
        
        /* RESPONSIVE - Para orientación horizontal en móviles */
        @media (max-height: 600px) and (orientation: landscape) {
            .login-container {
                flex-direction: row;
            }
            
            .image-section {
                height: 100vh;
                flex: 0 0 40%;
            }
            
            .image-overlay .logo {
                flex-direction: row !important;
            }
            
            .form-section {
                height: 100vh;
                overflow-y: auto;
                flex: 0 0 60%;
                padding-top: 20px;
            }
            
            .back-home {
                top: 10px;
                left: 10px;
            }
        }
        
        /* RESPONSIVE - Ajustes para pantallas muy altas */
        @media (min-height: 1000px) {
            .form-section {
                justify-content: center;
            }
            
            .login-form-container {
                max-width: 500px;
            }
        }
        
        /* Mejoras de accesibilidad y UX */
        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }
        
    
        
        /* Animación de entrada para elementos del formulario */
        .form-group, .form-options, .btn-primary {
            animation: slideUp 0.5s ease-out forwards;
            opacity: 0;
        }
        
        .form-group:nth-child(1) { animation-delay: 0.2s; }
        .form-group:nth-child(2) { animation-delay: 0.3s; }
        .form-options { animation-delay: 0.4s; }
        .btn-primary { animation-delay: 0.5s; }
        
        @keyframes slideUp {
            from { 
                opacity: 0; 
                transform: translateY(20px); 
            }
            to { 
                opacity: 1; 
                transform: translateY(0); 
            }
        }
        
        /* Keyframes para animación de mensajes */
        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        
        @keyframes slideOutRight {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
    </style>
</head>
<body>
    <!-- Botón para regresar al home -->
    <div class="back-home">
        <button class="back-btn" onclick="window.location.href='/'">
            <i class="fas fa-arrow-left"></i>
            <span>Volver al Inicio</span>
        </button>
    </div>
    
    <div class="login-container">
        <!-- Sección de la imagen -->
        <section class="image-section">
            <img src="https://i.pinimg.com/736x/47/2c/e7/472ce702a42826c727cf3cb88ae47f10.jpg" alt="Animales de granja" class="farm-background">
            
            <div class="image-overlay">
                <div class="logo">
                    <i class="fas fa-seedling"></i>
                    <h1>Bienvenido de nuevo</h1>
                </div>
                <h1>{{ config('app.name', 'Laravel') }}</h1>
                <p>Accede a tu cuenta para gestionar tu producción.</p>
                <div class="farm-icons">
                    <i class="fas fa-egg" title="Gallinas"></i>
                    <i class="fas fa-cow" title="Vacas"></i>
                    <i class="fas fa-piggy-bank" title="Cerdos"></i>
                    <i class="fas fa-horse" title="Caballos"></i>
                </div>
            </div>
        </section>
        
        <!-- Sección del formulario -->
        <section class="form-section">
            <div class="login-form-container">
                <div class="form-header">
                    <h2>Iniciar sesión</h2>
                    <p>Ingresa tus credenciales para acceder a tu cuenta</p>
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

                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                <form class="login-form" method="POST" action="{{ route('login') }}" id="loginForm">
                    @csrf

                    <div class="form-group">
                        <label for="email">Correo electrónico</label>
                        <div class="input-with-icon">
                            <i class="fas fa-envelope"></i>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                class="form-control @error('email') error @enderror" 
                                value="{{ old('email') }}" 
                                required 
                                autocomplete="email" 
                                autofocus
                                placeholder="ejemplo@correo.com"
                            >
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <div class="input-with-icon">
                            <i class="fas fa-lock"></i>
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                class="form-control @error('password') error @enderror" 
                                required 
                                autocomplete="current-password"
                                placeholder="Ingresa tu contraseña"
                            >
                            <span class="toggle-password" id="togglePassword">
                                <i class="fas fa-eye"></i>
                            </span>
                        </div>
                    </div>
                    
                    <div class="form-options">
                        <div class="remember-me">
                            <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label for="remember">Recordar mis datos</label>
                        </div>
                        <a href="{{ route('password.request') }}" class="forgot-password">¿Olvidaste tu contraseña?</a>
                    </div>
                    
                    <button type="submit" class="btn btn-primary" id="loginButton">
                        <span id="buttonText">Iniciar sesión</span>
                        <div class="spinner" id="spinner" style="display: none;"></div>
                    </button>
                </form>
            </div>
        </section>
    </div>

    <script>
        // Mostrar/ocultar contraseña
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        
        if (togglePassword && passwordInput) {
            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                
                // Cambiar icono del ojo
                const eyeIcon = this.querySelector('i');
                if (type === 'text') {
                    eyeIcon.classList.remove('fa-eye');
                    eyeIcon.classList.add('fa-eye-slash');
                } else {
                    eyeIcon.classList.remove('fa-eye-slash');
                    eyeIcon.classList.add('fa-eye');
                }
            });
        }
        
        // Manejo del formulario de login
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const loginButton = document.getElementById('loginButton');
            const buttonText = document.getElementById('buttonText');
            const spinner = document.getElementById('spinner');
            
            
            // Validación simple
            if (!email || !password) {
                showMessage('Por favor, completa todos los campos', 'error');
                e.preventDefault();
                return;
            }
            
            if (!isValidEmail(email)) {
                showMessage('Por favor, ingresa un correo electrónico válido', 'error');
                e.preventDefault();
                return;
            }
            
            // Mostrar estado de carga
            loginButton.disabled = true;
            buttonText.textContent = 'Iniciando sesión...';
            spinner.style.display = 'block';
        });
        
        // Función para validar email
        function isValidEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }
        
        // Función para mostrar mensajes
        function showMessage(message, type) {
            // Eliminar mensajes anteriores
            const existingMessage = document.querySelector('.message');
            if (existingMessage) {
                existingMessage.remove();
            }
            
            // Crear elemento de mensaje
            const messageDiv = document.createElement('div');
            messageDiv.className = `message ${type}`;
            messageDiv.textContent = message;
            
            // Estilos para el mensaje
            messageDiv.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 15px 20px;
                border-radius: 10px;
                color: white;
                font-weight: 500;
                z-index: 1000;
                animation: slideInRight 0.3s ease-out;
                box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            `;
            
            if (type === 'success') {
                messageDiv.style.backgroundColor = 'var(--primary-color)';
            } else if (type === 'error') {
                messageDiv.style.backgroundColor = '#f44336';
            } else if (type === 'info') {
                messageDiv.style.backgroundColor = '#2196F3';
            }
            
            document.body.appendChild(messageDiv);
            
            // Remover mensaje después de 5 segundos
            setTimeout(() => {
                messageDiv.style.animation = 'slideOutRight 0.3s ease-out forwards';
                setTimeout(() => {
                    if (messageDiv.parentNode) {
                        messageDiv.remove();
                    }
                }, 300);
            }, 5000);
        }
        
        // Cargar imagen alternativa si la principal falla
        const farmImage = document.querySelector('.farm-background');
        farmImage.addEventListener('error', function() {
            this.src = 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1632&q=80';
        });
        
        // Ajustar altura del formulario en móviles
        function adjustFormHeight() {
            const formSection = document.querySelector('.form-section');
            const imageSection = document.querySelector('.image-section');
            
            if (window.innerWidth <= 992) {
                const viewportHeight = window.innerHeight;
                const imageHeight = imageSection.offsetHeight;
                const formHeight = viewportHeight - imageHeight;
                
                formSection.style.minHeight = formHeight + 'px';
            } else {
                formSection.style.minHeight = '';
            }
        }
        
        // Ejecutar al cargar y al redimensionar
        window.addEventListener('load', adjustFormHeight);
        window.addEventListener('resize', adjustFormHeight);
    </script>
</body>
</html>