<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambio de Contraseña - Primer Acceso - {{ config('app.name', 'Laravel') }}</title>
    <link rel="shortcut icon" href="{{ asset('img/favicon.ico') }}" type="image/x-icon" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="webp" href="../img/logo.jpg">
    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        :root {
            --primary-color: #2e7d32;
            --secondary-color: #4caf50;
            --accent-color: #ff9800;
            --warning-color: #ff9800;
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
            padding: 15px;
            position: relative;
            overflow: auto;
            background-color: #f5f5f5;
        }
        
        /* Fondo con imagen */
        body::before {
            content: '';
            position: fixed;
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
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to right, rgba(46, 125, 50, 0.85), rgba(76, 175, 80, 0.75));
            z-index: -1;
        }
        
        .first-time-container {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            padding: 25px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(8px);
            animation: fadeIn 0.4s ease-out;
            position: relative;
            padding-top: 70px; /* Espacio para el botón superior */
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Botón superior "Cambiar después" */
        .top-action-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            background: rgba(255, 255, 255, 0.95);
            border: 1.5px solid #e0e0e0;
            color: #666;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(5px);
            z-index: 10;
        }
        
        .top-action-btn:hover {
            background: white;
            border-color: #ff9800;
            color: #ff9800;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        .top-action-btn i {
            font-size: 14px;
        }
        
        .first-time-header { 
            text-align: center; 
            margin-bottom: 20px;
        }
        
        .first-time-icon {
            width: 50px;
            height: 50px;
            margin: 0 auto 12px;
            background: linear-gradient(135deg, var(--warning-color), var(--accent-color));
            border-radius: 50%; 
            display: flex; 
            align-items: center;
            justify-content: center; 
            color: white;
            font-size: 22px;
            box-shadow: 0 4px 12px rgba(255, 152, 0, 0.25);
        }
        
        .first-time-title { 
            font-size: 22px;
            font-weight: 700; 
            color: var(--warning-color); 
            margin-bottom: 6px;
        }
        
        .first-time-subtitle { 
            color: #666; 
            font-size: 13px;
            line-height: 1.4;
            max-width: 280px;
            margin: 0 auto;
        }
        
        .alert-warning {
            background: #fff8e1;
            color: #856404;
            border-left: 4px solid #ffc107;
            padding: 10px 12px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 13px;
        }
        
        .alert-title {
            font-weight: 600;
            margin-bottom: 3px;
            font-size: 14px;
        }
        
        .first-time-form { 
            display: flex; 
            flex-direction: column; 
            gap: 18px;
        }
        
        .form-group { 
            display: flex; 
            flex-direction: column; 
            gap: 6px;
        }
        
        .form-label { 
            font-size: 14px;
            font-weight: 600; 
            color: var(--dark-color);
            display: flex;
            align-items: center;
            gap: 4px;
        }
        
        .form-label .required {
            color: #f44336;
        }
        
        .input-group {
            position: relative;
            width: 100%;
        }
        
        .form-input {
            padding: 10px 12px;
            border: 1.5px solid #ddd; 
            border-radius: 8px;
            font-size: 14px;
            transition: var(--transition); 
            background: #fff;
            width: 100%;
            padding-right: 40px;
        }
        
        .form-input:focus {
            outline: none; 
            border-color: var(--warning-color);
            box-shadow: 0 0 0 2px rgba(255, 152, 0, 0.1);
        }
        
        .form-input.error { 
            border-color: #f44336; 
        }
        
        .password-toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            border: none;
            color: #777;
            cursor: pointer;
            font-size: 16px;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            transition: var(--transition);
            padding: 0;
        }
        
        .password-toggle:hover {
            color: var(--warning-color);
        }
        
        .password-strength {
            margin-top: 6px;
        }
        
        .strength-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 6px;
            font-size: 13px;
        }
        
        .strength-text {
            font-weight: 600;
            font-size: 13px;
        }
        
        .strength-bar {
            height: 4px;
            background-color: #e0e0e0;
            border-radius: 2px;
            overflow: hidden;
            margin-bottom: 8px;
        }
        
        .strength-fill {
            height: 100%;
            width: 0%;
            border-radius: 2px;
            transition: width 0.3s ease;
        }
        
        .strength-requirements {
            font-size: 12px;
            color: #666;
            line-height: 1.4;
        }
        
        .first-time-btn {
            background: linear-gradient(135deg, var(--warning-color), var(--accent-color));
            color: white; 
            border: none; 
            padding: 12px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600; 
            cursor: pointer;
            transition: var(--transition); 
            margin-top: 8px;
            font-family: inherit;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
        }
        
        .first-time-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 152, 0, 0.25);
        }
        
        .error-message {
            color: #f44336;
            font-size: 12px;
            margin-top: 3px;
        }
        
        .help-text {
            color: #777;
            font-size: 12px;
            margin-top: 4px;
            line-height: 1.4;
        }
        
        @media (max-width: 576px) {
            .first-time-container {
                padding: 20px 18px;
                max-width: 340px;
                padding-top: 65px;
            }
            
            .top-action-btn {
                top: 15px;
                right: 15px;
                padding: 6px 12px;
                font-size: 12px;
            }
            
            .first-time-title { 
                font-size: 20px; 
            }
            
            .first-time-subtitle { 
                font-size: 12px;
            }
            
            .first-time-icon {
                width: 45px;
                height: 45px;
                font-size: 20px;
            }
            
            .form-input {
                padding: 9px 11px;
                padding-right: 38px;
                font-size: 13px;
            }
            
            .first-time-btn {
                padding: 11px;
                font-size: 13px;
            }
            
            .alert-warning {
                padding: 8px 10px;
                font-size: 12px;
            }
            
            .first-time-form {
                gap: 16px;
            }
        }
        
        @media (max-width: 360px) {
            .first-time-container {
                max-width: 320px;
                padding: 18px 16px;
                padding-top: 60px;
            }
            
            .top-action-btn {
                top: 12px;
                right: 12px;
                font-size: 11px;
            }
        }
        
        /* Barra de progreso con colores */
        .strength-0 { background-color: #f44336; width: 20%; }
        .strength-1 { background-color: #ff9800; width: 40%; }
        .strength-2 { background-color: #ffc107; width: 60%; }
        .strength-3 { background-color: #4caf50; width: 80%; }
        .strength-4 { background-color: #2e7d32; width: 100%; }
        
        .text-strength-0 { color: #f44336; }
        .text-strength-1 { color: #ff9800; }
        .text-strength-2 { color: #ffc107; }
        .text-strength-3 { color: #4caf50; }
        .text-strength-4 { color: #2e7d32; }
    </style>
</head>
<body>
    <div class="first-time-container">
        <!-- Botón superior para "Cambiar después" -->
        <form method="POST" action="{{ route('logout') }}" id="logoutFormTop" style="display: inline;">
            @csrf
            <button type="submit" class="top-action-btn" id="logoutTopBtn">
                <i class="fas fa-clock"></i>
                Cambiar después
            </button>
        </form>
        
        <div class="first-time-header">
            <div class="first-time-icon">
                <i class="fas fa-lock"></i>
            </div>
            <h1 class="first-time-title">Cambio de Contraseña</h1>
            <p class="first-time-subtitle">
                Primer acceso - Complete el formulario para continuar
            </p>
        </div>

        <div class="alert-warning">
            <div class="alert-title">Primer acceso detectado</div>
            <p>Por seguridad, debe cambiar su contraseña antes de continuar.</p>
        </div>

        <form method="POST" action="{{ route('password.first_time') }}" class="first-time-form" id="passwordForm">
            @csrf

            <!-- Contraseña Actual -->
            <div class="form-group">
                <label for="current_password" class="form-label">
                    Contraseña Actual <span class="required">*</span>
                </label>
                <div class="input-group">
                    <input type="password" 
                           class="form-input @error('current_password') error @enderror" 
                           id="current_password" 
                           name="current_password" 
                           required
                           placeholder="Contraseña temporal asignada">
                    <button type="button" 
                            class="password-toggle" 
                            onclick="togglePassword('current_password')">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                @error('current_password')
                    <div class="error-message">{{ $message }}</div>
                @enderror
                <div class="help-text">
                    Ingrese la contraseña temporal que se le asignó al crear su usuario.
                </div>
            </div>

            <!-- Nueva Contraseña -->
            <div class="form-group">
                <label for="password" class="form-label">
                    Nueva Contraseña <span class="required">*</span>
                </label>
                <div class="input-group">
                    <input type="password" 
                           class="form-input @error('password') error @enderror" 
                           id="password" 
                           name="password" 
                           required
                           placeholder="Mínimo 8 caracteres"
                           oninput="checkPasswordStrength()">
                    <button type="button" 
                            class="password-toggle" 
                            onclick="togglePassword('password')">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                @error('password')
                    <div class="error-message">{{ $message }}</div>
                @enderror
                
                <div class="password-strength">
                    <div class="strength-header">
                        <span class="strength-text">Fortaleza:</span>
                        <span class="strength-text" id="strengthText">Muy débil</span>
                    </div>
                    <div class="strength-bar">
                        <div class="strength-fill" id="strengthBar"></div>
                    </div>
                    <div class="strength-requirements">
                        Mínimo 8 caracteres, una mayúscula y un número
                    </div>
                </div>
            </div>

            <!-- Confirmar Nueva Contraseña -->
            <div class="form-group">
                <label for="password_confirmation" class="form-label">
                    Confirmar Contraseña <span class="required">*</span>
                </label>
                <div class="input-group">
                    <input type="password" 
                           class="form-input" 
                           id="password_confirmation" 
                           name="password_confirmation" 
                           required
                           placeholder="Repita la nueva contraseña">
                    <button type="button" 
                            class="password-toggle" 
                            onclick="togglePassword('password_confirmation')">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <!-- Botón de Envío -->
            <button type="submit" class="first-time-btn">
                <i class="fas fa-save"></i>
                Guardar Nueva Contraseña
            </button>
        </form>
    </div>

    <script>
        // Cargar imagen alternativa si la principal falla
        const originalImage = 'https://i.pinimg.com/736x/47/2c/e7/472ce702a42826c727cf3cb88ae47f10.jpg';
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
        
        // Funciones para mostrar/ocultar contraseña
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const button = input.nextElementSibling;
            const icon = button.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'fas fa-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'fas fa-eye';
            }
        }
        
        // Validar fortaleza de contraseña
        function checkPasswordStrength() {
            const password = document.getElementById('password').value;
            const strengthBar = document.getElementById('strengthBar');
            const strengthText = document.getElementById('strengthText');
            
            let strength = 0;
            
            // Validaciones
            if (password.length >= 8) strength += 1;
            if (/[A-Z]/.test(password)) strength += 1;
            if (/[a-z]/.test(password)) strength += 1;
            if (/[0-9]/.test(password)) strength += 1;
            if (/[^A-Za-z0-9]/.test(password)) strength += 1;
            
            // Determinar texto, color y clase
            let text, strengthClass, textClass;
            
            switch(strength) {
                case 0:
                    text = 'Muy débil';
                    strengthClass = 'strength-0';
                    textClass = 'text-strength-0';
                    break;
                case 1:
                    text = 'Débil';
                    strengthClass = 'strength-1';
                    textClass = 'text-strength-1';
                    break;
                case 2:
                    text = 'Regular';
                    strengthClass = 'strength-2';
                    textClass = 'text-strength-2';
                    break;
                case 3:
                    text = 'Buena';
                    strengthClass = 'strength-3';
                    textClass = 'text-strength-3';
                    break;
                case 4:
                case 5:
                    text = 'Excelente';
                    strengthClass = 'strength-4';
                    textClass = 'text-strength-4';
                    break;
            }
            
            // Aplicar cambios
            strengthBar.className = 'strength-fill ' + strengthClass;
            strengthText.textContent = text;
            strengthText.className = 'strength-text ' + textClass;
        }
        
        // Validación del formulario para CAMBIAR CONTRASEÑA
        document.getElementById('passwordForm').addEventListener('submit', function(e) {
            const currentPassword = document.getElementById('current_password').value;
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;
            
            // Validar campos vacíos
            if (!currentPassword || !password || !confirmPassword) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Campos incompletos',
                    text: 'Por favor, complete todos los campos obligatorios.',
                    confirmButtonColor: '#ff9800',
                });
                return false;
            }
            
            // Validar que coincidan
            if (password !== confirmPassword) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Contraseñas no coinciden',
                    text: 'Por favor verifique que ambas contraseñas sean iguales.',
                    confirmButtonColor: '#f44336',
                });
                return false;
            }
            
            // Validar requisitos mínimos
            if (password.length < 8 || !/[A-Z]/.test(password) || !/[0-9]/.test(password)) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Contraseña débil',
                    html: 'La contraseña debe cumplir con:<br>' +
                          '• Mínimo 8 caracteres<br>' +
                          '• Al menos una letra mayúscula<br>' +
                          '• Al menos un número',
                    confirmButtonColor: '#f44336',
                });
                return false;
            }
            
            return true;
        });
        
        // Confirmación para CERRAR SESIÓN (Cambiar después)
        document.getElementById('logoutTopBtn').addEventListener('click', function(e) {
            e.preventDefault();
            
            Swal.fire({
                title: '¿Salir sin cambiar contraseña?',
                html: '¿Está seguro de salir sin cambiar la contraseña ahora?<br>' +
                      '<small style="color: #666;">Se le pedirá cambiar la contraseña en su próximo acceso.</small>',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí, salir',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#2e7d32',
                cancelButtonColor: '#6c757d',
                reverseButtons: true,
                backdrop: true,
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    // Enviar el formulario de logout
                    document.getElementById('logoutFormTop').submit();
                }
            });
        });
        
        // Mostrar mensaje de éxito si se redirige desde cambio exitoso
        @if(session('success'))
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: '¡Contraseña Actualizada!',
                html: `
                    <div style="text-align: left; line-height: 1.6;">
                        <p>✅ <strong>Contraseña actualizada exitosamente</strong></p>
                        <p>📝 {{ session('success') }}</p>
                        <p style="font-size: 14px; color: #666; margin-top: 15px;">
                            <i class="fas fa-info-circle"></i> Ahora puede iniciar sesión con su nueva contraseña
                        </p>
                    </div>
                `,
                confirmButtonText: 'Aceptar',
                confirmButtonColor: '#2e7d32',
                width: '500px',
                backdrop: true,
                allowOutsideClick: false,
                allowEscapeKey: false,
            });
        });
        @endif
        
        // Inicializar validación de fortaleza
        checkPasswordStrength();
    </script>
</body>
</html>