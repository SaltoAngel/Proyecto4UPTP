<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar contraseña - {{ config('app.name', 'Laravel') }}</title>
    <link rel="shortcut icon" href="{{ asset('img/favicon.ico') }}" type="image/x-icon" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            background: linear-gradient(to right, rgba(45, 109, 48, 0.85), rgba(63, 145, 64, 0.75));
            z-index: -1;
        }
        
        .reset-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 450px;
            padding: 40px;
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
            margin-bottom: 35px; 
        }
        
        .reset-icon {
            width: 70px; 
            height: 70px; 
            margin: 0 auto 20px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 50%; 
            display: flex; 
            align-items: center;
            justify-content: center; 
            color: white;
            font-size: 28px;
            box-shadow: 0 8px 20px rgba(46, 125, 50, 0.3);
        }
        
        .reset-title { 
            font-size: 26px; 
            font-weight: 700; 
            color: var(--primary-color); 
            margin-bottom: 10px; 
        }
        
        .reset-subtitle { 
            color: #666; 
            font-size: 15px; 
            line-height: 1.6;
            max-width: 300px;
            margin: 0 auto;
        }
        
        .reset-form { 
            display: flex; 
            flex-direction: column; 
            gap: 25px; 
        }
        
        .form-group { 
            display: flex; 
            flex-direction: column; 
            gap: 10px; 
        }
        
        .form-label { 
            font-size: 15px; 
            font-weight: 600; 
            color: var(--dark-color); 
        }
        
        .form-input {
            padding: 14px 18px; 
            border: 2px solid #e0e0e0; 
            border-radius: 10px;
            font-size: 16px; 
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
            background-color: #fff5f5;
        }
        
        .form-input.success {
            border-color: #4caf50;
            background-color: #f0fff4;
        }
        
        .validation-feedback {
            font-size: 0.85rem;
            margin-top: 5px;
            display: block;
        }
        
        .validation-error {
            color: #f44336;
        }
        
        .validation-success {
            color: #4caf50;
        }
        
        .reset-btn {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white; 
            border: none; 
            padding: 16px; 
            border-radius: 10px;
            font-size: 16px; 
            font-weight: 600; 
            cursor: pointer;
            transition: var(--transition); 
            margin-top: 10px;
            font-family: inherit;
        }
        
        .reset-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(46, 125, 50, 0.3);
        }
        
        .reset-btn:disabled {
            background: #cccccc;
            cursor: not-allowed;
            transform: none !important;
            box-shadow: none !important;
        }
        
        /* Spinner de carga */
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
        
        /* Enlace para volver al login */
        .back-link {
            text-align: center; 
            margin-top: 25px; 
            padding-top: 25px;
            border-top: 1px solid #e0e0e0;
        }
        
        .back-link a {
            color: var(--primary-color); 
            text-decoration: none; 
            font-size: 15px;
            font-weight: 500; 
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .back-link a:hover { 
            color: var(--accent-color); 
            text-decoration: underline;
        }
        
        /* Alertas */
        .alert {
            padding: 14px 18px; 
            border-radius: 10px; 
            margin-bottom: 25px;
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
        
        ul {
            list-style: none;
            margin: 0;
            padding-left: 20px;
        }
        
        ul li {
            margin-bottom: 5px;
        }
        
        ul li:last-child {
            margin-bottom: 0;
        }
        
        @media (max-width: 576px) {
            .reset-container {
                padding: 30px 20px;
            }
            
            .reset-title { 
                font-size: 22px; 
            }
            
            .reset-subtitle { 
                font-size: 14px; 
            }
            
            .reset-icon {
                width: 60px; 
                height: 60px; 
                font-size: 24px;
            }
            
            .back-link a {
                font-size: 14px;
            }
        }
        
        /* Botón para volver atrás */
        .back-btn {
            position: absolute;
            top: 25px;
            left: 25px;
            background-color: rgba(255, 255, 255, 0.9);
            color: var(--primary-color);
            border: none;
            border-radius: 50px;
            padding: 12px 24px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: var(--transition);
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
            font-family: inherit;
            font-size: 15px;
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
                padding: 10px 18px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    
    <div class="reset-container">
        <div class="reset-header">
            <div class="reset-icon">
                <i class="fas fa-key"></i>
            </div>
            <h1 class="reset-title">Recuperar contraseña</h1>
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
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form class="reset-form" method="POST" action="{{ route('password.email') }}" id="resetForm">
            @csrf

            <div class="form-group">
                <label for="email" class="form-label">Correo electrónico</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    class="form-input @error('email') error @enderror" 
                    value="{{ old('email') }}" 
                    required 
                    autocomplete="email" 
                    autofocus
                    placeholder="ejemplo@correo.com"
                >
                <span id="email-error" class="validation-feedback validation-error" style="display: none;"></span>
            </div>

            <button type="submit" class="reset-btn" id="resetButton">
                <span id="buttonText">Enviar enlace de recuperación</span>
                <div class="spinner" id="spinner" style="display: none;"></div>
            </button>
        </form>

        <div class="back-link">
            <a href="{{ route('login') }}">
                <i class="fas fa-arrow-left"></i>
                Volver al inicio de sesión
            </a>
        </div>
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
        
        // Validaciones y manejo del formulario
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('resetForm');
            const emailInput = document.getElementById('email');
            const resetButton = document.getElementById('resetButton');
            const buttonText = document.getElementById('buttonText');
            const spinner = document.getElementById('spinner');
            
            // Función para validar email
            function isValidEmail(email) {
                const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return re.test(email);
            }
            
            // Función para mostrar error en campo
            function showFieldError(fieldId, message) {
                const field = document.getElementById(fieldId);
                const errorSpan = document.getElementById(`${fieldId}-error`);
                
                if (field && errorSpan) {
                    field.classList.add('error');
                    field.classList.remove('success');
                    errorSpan.textContent = message;
                    errorSpan.classList.remove('validation-success');
                    errorSpan.classList.add('validation-error');
                    errorSpan.style.display = 'block';
                }
            }
            
            // Función para limpiar error de campo
            function clearFieldError(fieldId) {
                const field = document.getElementById(fieldId);
                const errorSpan = document.getElementById(`${fieldId}-error`);
                
                if (field && errorSpan) {
                    field.classList.remove('error');
                    field.classList.remove('success');
                    errorSpan.style.display = 'none';
                    errorSpan.textContent = '';
                }
            }
            
            // Función para mostrar éxito en campo
            function showFieldSuccess(fieldId) {
                const field = document.getElementById(fieldId);
                const errorSpan = document.getElementById(`${fieldId}-error`);
                
                if (field && errorSpan) {
                    field.classList.remove('error');
                    field.classList.add('success');
                    errorSpan.style.display = 'none';
                }
            }
            
            // Validación en tiempo real del email
            emailInput.addEventListener('input', function() {
                const email = this.value.trim();
                
                if (!email) {
                    clearFieldError('email');
                } else if (!isValidEmail(email)) {
                    showFieldError('email', 'Por favor ingresa un email válido');
                } else {
                    showFieldSuccess('email');
                }
            });
            
            // Validación del formulario
            function validateForm() {
                const email = emailInput.value.trim();
                let isValid = true;
                
                if (!email) {
                    showFieldError('email', 'El correo electrónico es obligatorio');
                    isValid = false;
                } else if (!isValidEmail(email)) {
                    showFieldError('email', 'Por favor ingresa un email válido');
                    isValid = false;
                }
                
                return isValid;
            }
            
            // Función para mostrar/ocultar loading
            function setLoading(isLoading) {
                if (isLoading) {
                    resetButton.disabled = true;
                    buttonText.style.display = 'none';
                    spinner.style.display = 'inline-block';
                } else {
                    resetButton.disabled = false;
                    buttonText.style.display = 'inline-block';
                    spinner.style.display = 'none';
                }
            }
            
            // Manejar envío del formulario
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                if (!validateForm()) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Revisa el email',
                        text: 'Por favor ingresa un correo electrónico válido',
                        confirmButtonText: 'Entendido',
                        confirmButtonColor: '#2e7d32'
                    });
                    return;
                }
                
                setLoading(true);
                
                const formData = new FormData(this);
                
                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(async (response) => {
                    const contentType = response.headers.get('content-type') || '';
                    let data = null;
                    if (contentType.includes('application/json')) {
                        try { data = await response.json(); } catch (_) { data = null; }
                    }

                    setLoading(false);
                    
                    // Éxito si:
                    // - Respuesta JSON con success/status
                    // - O respuesta no JSON pero HTTP ok/redirect (Laravel redirige con session('status'))
                    const isJsonSuccess = data && (data.success || data.status || data.message);
                    const isHttpSuccess = response.ok || response.status === 302 || response.redirected;

                    if (isJsonSuccess || isHttpSuccess) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Enlace enviado!',
                            text: 'Revisa tu correo electrónico para restablecer tu contraseña',
                            confirmButtonText: 'Entendido',
                            confirmButtonColor: '#2e7d32'
                        }).then(() => {
                            // Limpiar formulario
                            emailInput.value = '';
                            clearFieldError('email');
                        });
                    } else {
                        let errorMessage = 'No pudimos enviar el enlace';
                        
                        if (response.status === 429) {
                            errorMessage = 'Has realizado demasiados intentos. Inténtalo nuevamente en unos minutos.';
                        } else if (data && data.errors && data.errors.email) {
                            errorMessage = 'Verifica que el correo esté registrado en el sistema';
                        } else if (data && data.message) {
                            errorMessage = data.message;
                        }
                        
                        Swal.fire({
                            icon: 'error',
                            title: 'No se pudo enviar',
                            text: errorMessage,
                            confirmButtonText: 'Intentar nuevamente',
                            confirmButtonColor: '#2e7d32'
                        });
                    }
                })
                .catch(error => {
                    setLoading(false);
                    console.error('Error:', error);
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Problema de conexión',
                        text: 'No pudimos conectar con el servidor. Revisa tu conexión a internet.',
                        confirmButtonText: 'Intentar nuevamente',
                        confirmButtonColor: '#2e7d32'
                    });
                });
            });
            
            // Mostrar alertas de sesión al cargar la página
            @if(session('status'))
                Swal.fire({
                    icon: 'success',
                    title: '¡Enlace enviado!',
                    text: '{{ session('status') }}',
                    confirmButtonText: 'Entendido',
                    confirmButtonColor: '#2e7d32'
                });
            @endif
            
            @if($errors->any())
                const errorMessages = [];
                @foreach($errors->all() as $error)
                    errorMessages.push('{{ $error }}');
                @endforeach
                
                Swal.fire({
                    icon: 'error',
                    title: 'Error al enviar',
                    text: 'Verifica que el correo esté registrado en el sistema',
                    confirmButtonText: 'Intentar nuevamente',
                    confirmButtonColor: '#2e7d32'
                });
            @endif
        });
    </script>
</body>
</html>