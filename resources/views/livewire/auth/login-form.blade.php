<div>
    <script>console.log('LoginForm component rendered');</script>
    <form wire:submit.prevent="login" class="login-form" id="loginForm">
        <div class="form-group">
            <label for="email">Correo electrónico</label>
            <div class="input-with-icon">
                <i class="fas fa-envelope"></i>
                <input
                    type="email"
                    id="email"
                    wire:model.live="email"
                    class="form-control @error('email') error @enderror"
                    placeholder="ejemplo@correo.com"
                    required
                    autocomplete="email"
                    autofocus
                >
            </div>
            @error('email')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">Contraseña</label>
            <div class="input-with-icon">
                <i class="fas fa-lock"></i>
                <input
                    type="password"
                    id="password"
                    wire:model.live="password"
                    class="form-control @error('password') error @enderror"
                    placeholder="Ingresa tu contraseña"
                    required
                    autocomplete="current-password"
                >
                <span class="toggle-password" id="togglePassword">
                    <i class="fas fa-eye"></i>
                </span>
            </div>
            @error('password')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-options">
            <div class="remember-me">
                <input type="checkbox" id="remember" wire:model.live="remember">
                <label for="remember">Recordar mis datos</label>
            </div>
            <a href="{{ route('password.request') }}" class="forgot-password">¿Olvidaste tu contraseña?</a>
        </div>

        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="login">
            <span wire:loading.remove wire:target="login">Iniciar sesión</span>
            <span wire:loading wire:target="login">Iniciando sesión...</span>
        </button>
    </form>

    <script>
        // Mostrar/ocultar contraseña
        document.addEventListener('livewire:loaded', function () {
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');

            if (togglePassword && passwordInput) {
                togglePassword.addEventListener('click', function() {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);

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
        });
    </script>
</div>