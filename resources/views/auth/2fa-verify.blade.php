<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación de Seguridad - {{ config('app.name', 'Laravel') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Inter', 'Segoe UI', system-ui, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .verify-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 450px;
            padding: 40px 30px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            text-align: center;
        }
        .verify-icon {
            width: 80px; height: 80px; margin: 0 auto 20px;
            background: linear-gradient(135deg, #48bb78, #38a169);
            border-radius: 50%; display: flex; align-items: center;
            justify-content: center; color: white;
        }
        .verify-icon svg {
            width: 40px; height: 40px;
        }
        .verify-title { 
            font-size: 24px; font-weight: 700; color: #2d3748;
            margin-bottom: 10px;
        }
        .verify-subtitle {
            color: #718096; font-size: 14px; margin-bottom: 20px;
            line-height: 1.5;
        }
        .verification-reason {
            background: #f7fafc; padding: 12px 16px; border-radius: 10px;
            margin-bottom: 25px; border-left: 4px solid #667eea;
            text-align: left;
        }
        .verification-reason .title {
            font-weight: 600; color: #2d3748; margin-bottom: 4px;
        }
        .verification-reason .desc {
            font-size: 13px; color: #718096; line-height: 1.4;
        }
        .code-inputs {
            display: flex; gap: 10px; justify-content: center;
            margin-bottom: 30px;
        }
        .code-input {
            width: 50px; height: 60px; border: 2px solid #e2e8f0;
            border-radius: 10px; text-align: center; font-size: 24px;
            font-weight: bold; transition: all 0.3s ease;
        }
        .code-input:focus {
            outline: none; border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        .verify-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white; border: none; padding: 14px; border-radius: 10px;
            font-size: 16px; font-weight: 600; cursor: pointer;
            transition: all 0.3s ease; width: 100%; margin-bottom: 20px;
        }
        .verify-btn:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }
        .verify-btn:disabled {
            opacity: 0.6; cursor: not-allowed;
        }
        .resend-section {
            margin-top: 20px; padding-top: 20px;
            border-top: 1px solid #e2e8f0;
        }
        .resend-text {
            color: #718096; font-size: 14px; margin-bottom: 15px;
        }
        .resend-btn {
            background: transparent; color: #667eea; border: 2px solid #667eea;
            padding: 10px 20px; border-radius: 8px; font-size: 14px;
            font-weight: 600; cursor: pointer; transition: all 0.3s ease;
        }
        .resend-btn:hover:not(:disabled) {
            background: #667eea; color: white;
        }
        .resend-btn:disabled {
            opacity: 0.5; cursor: not-allowed;
        }
        .timer {
            color: #e53e3e; font-size: 14px; font-weight: 600;
            margin-top: 8px;
        }
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
        .info-badge {
            display: inline-block; background: #667eea; color: white;
            padding: 4px 12px; border-radius: 20px; font-size: 12px;
            font-weight: 600; margin-bottom: 15px;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .verify-container { animation: fadeIn 0.6s ease-out; }
        .security-info {
            font-size: 12px; color: #718096; margin-top: 10px;
            line-height: 1.4;
        }
    </style>
</head>
<body>
    <div class="verify-container">
        <div class="verify-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
        </div>

        <h1 class="verify-title">Verificación de Seguridad</h1>
        <p class="verify-subtitle">
            Hemos enviado un código de verificación de 6 dígitos a tu correo electrónico.
            Ingresa el código para completar el inicio de sesión.
        </p>
    


        @if ($errors->any())
            <div class="alert alert-error">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('2fa.verify') }}" id="verifyForm">
            @csrf
            
            <div class="code-inputs">
                <input type="text" name="code[]" class="code-input" maxlength="1" required autofocus>
                <input type="text" name="code[]" class="code-input" maxlength="1" required>
                <input type="text" name="code[]" class="code-input" maxlength="1" required>
                <input type="text" name="code[]" class="code-input" maxlength="1" required>
                <input type="text" name="code[]" class="code-input" maxlength="1" required>
                <input type="text" name="code[]" class="code-input" maxlength="1" required>
            </div>

            <input type="hidden" name="full_code" id="fullCode">

            <button type="submit" class="verify-btn" id="verifyBtn">
                Verificar Código
            </button>
            
            <!-- Información sobre el sistema de seguridad -->
        </form>

        <div class="resend-section">
            <p class="resend-text">¿No recibiste el código?</p>
            <form method="POST" action="{{ route('2fa.resend') }}">
                @csrf
                <button type="submit" class="resend-btn" id="resendBtn">
                    Reenviar Código
                </button>
            </form>
            <div class="timer" id="timer">Puedes reenviar en: <span id="countdown">60</span>s</div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('.code-input');
            const form = document.getElementById('verifyForm');
            const fullCodeInput = document.getElementById('fullCode');
            const verifyBtn = document.getElementById('verifyBtn');
            const resendBtn = document.getElementById('resendBtn');
            const countdownEl = document.getElementById('countdown');
            
            let countdown = 60;
            let timer;

            // Iniciar countdown para reenvío
            startCountdown();

            // Auto-focus y navegación entre inputs
            inputs.forEach((input, index) => {
                input.addEventListener('input', function(e) {
                    // Solo permitir números
                    this.value = this.value.replace(/[^0-9]/g, '');
                    
                    if (this.value.length === 1) {
                        if (index < inputs.length - 1) {
                            inputs[index + 1].focus();
                        } else {
                            verifyBtn.focus();
                        }
                    }
                    
                    updateFullCode();
                });

                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Backspace' && this.value === '' && index > 0) {
                        inputs[index - 1].focus();
                    }
                    
                    // Permitir navegación con flechas
                    if (e.key === 'ArrowLeft' && index > 0) {
                        inputs[index - 1].focus();
                        e.preventDefault();
                    }
                    if (e.key === 'ArrowRight' && index < inputs.length - 1) {
                        inputs[index + 1].focus();
                        e.preventDefault();
                    }
                });

                // Auto-seleccionar contenido al hacer focus
                input.addEventListener('focus', function() {
                    this.select();
                });

                // Pegar código completo
                input.addEventListener('paste', function(e) {
                    e.preventDefault();
                    const pastedText = e.clipboardData.getData('text');
                    const numbers = pastedText.replace(/[^0-9]/g, '').substring(0, 6);
                    
                    // Llenar inputs con los números pegados
                    numbers.split('').forEach((char, i) => {
                        if (inputs[i]) {
                            inputs[i].value = char;
                        }
                    });
                    
                    updateFullCode();
                    
                    // Focus en el siguiente input vacío o en el botón
                    const nextEmptyIndex = Array.from(inputs).findIndex(inp => !inp.value);
                    if (nextEmptyIndex !== -1) {
                        inputs[nextEmptyIndex].focus();
                    } else {
                        verifyBtn.focus();
                    }
                });
            });

            // Actualizar código completo
            function updateFullCode() {
                const code = Array.from(inputs).map(input => input.value).join('');
                fullCodeInput.value = code;
                verifyBtn.disabled = code.length !== 6;
            }

            // Countdown para reenvío
            function startCountdown() {
                resendBtn.disabled = true;
                countdown = 60;
                countdownEl.textContent = countdown;
                
                clearInterval(timer);
                timer = setInterval(() => {
                    countdown--;
                    countdownEl.textContent = countdown;
                    
                    if (countdown <= 0) {
                        clearInterval(timer);
                        resendBtn.disabled = false;
                        countdownEl.textContent = 'Listo para reenviar';
                        countdownEl.style.color = '#48bb78';
                    }
                }, 1000);
            }

            // Reenviar código
            resendBtn.addEventListener('click', function(e) {
                if (resendBtn.disabled) {
                    e.preventDefault();
                    return;
                }
                
                // Resetear countdown
                countdownEl.style.color = '#e53e3e';
                startCountdown();
                
                // Mostrar mensaje de confirmación
                const originalText = resendBtn.innerHTML;
                resendBtn.innerHTML = 'Reenviando...';
                resendBtn.disabled = true;
                
                setTimeout(() => {
                    resendBtn.innerHTML = originalText;
                }, 1500);
            });

            // Validar formulario
            form.addEventListener('submit', function(e) {
                const code = fullCodeInput.value;
                
                if (code.length !== 6 || !/^\d+$/.test(code)) {
                    e.preventDefault();
                    
                    // Resaltar inputs vacíos o inválidos
                    inputs.forEach(input => {
                        if (!input.value || !/^\d$/.test(input.value)) {
                            input.style.borderColor = '#e53e3e';
                            input.style.boxShadow = '0 0 0 3px rgba(229, 62, 62, 0.1)';
                        }
                    });
                    
                    // Focus en el primer input vacío
                    const firstEmpty = Array.from(inputs).find(input => !input.value);
                    if (firstEmpty) {
                        firstEmpty.focus();
                    } else {
                        inputs[0].focus();
                    }
                    
                    return;
                }
                
                // Deshabilitar botón para evitar múltiples envíos
                verifyBtn.disabled = true;
                verifyBtn.innerHTML = 'Verificando...';
                
                // Resetear estilos de inputs
                inputs.forEach(input => {
                    input.style.borderColor = '';
                    input.style.boxShadow = '';
                });
            });

            // Auto-submit cuando se completa el código
            inputs[inputs.length - 1].addEventListener('input', function() {
                const code = fullCodeInput.value;
                if (code.length === 6 && /^\d+$/.test(code)) {
                    // Pequeño delay para mejor UX
                    setTimeout(() => {
                        verifyBtn.click();
                    }, 300);
                }
            });

            // Focus en el primer input al cargar
            inputs[0].focus();
        });
    </script>
</body>
</html>