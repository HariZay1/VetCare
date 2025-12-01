<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar Sesión - VetCare</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #4f46e5 0%, #6366f1 50%, #8b5cf6 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        
        .login-header {
            background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        
        .login-body {
            padding: 2rem;
        }
        
        .form-control:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 0.2rem rgba(79, 70, 229, 0.25);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
            border: none;
            padding: 0.75rem;
            font-weight: 600;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(79, 70, 229, 0.3);
        }
        
        .demo-accounts {
            background: #f3f4f6;
            padding: 1.5rem;
            border-radius: 10px;
            margin-top: 1.5rem;
        }
        
        .demo-account-item {
            background: white;
            padding: 0.75rem;
            border-radius: 8px;
            margin-bottom: 0.5rem;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .demo-account-item:hover {
            transform: translateX(5px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="login-card">
                    <div class="row g-0">
                        <!-- Panel Izquierdo -->
                        <div class="col-md-6">
                            <div class="login-header" style="height: 100%; display: flex; flex-direction: column; justify-content: center;">
                                <i class="bi bi-heart-pulse-fill" style="font-size: 4rem;"></i>
                                <h1 class="mt-3 mb-2">VetCare</h1>
                                <p class="mb-0">Sistema de Gestión Veterinaria</p>
                            </div>
                        </div>

                        <!-- Panel Derecho - Formulario -->
                        <div class="col-md-6">
                            <div class="login-body">
                                <h3 class="mb-4">Iniciar Sesión</h3>

                                <!-- Session Status -->
                                @if (session('status'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        {{ session('status') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                @endif

                                <form method="POST" action="{{ route('login') }}">
                                    @csrf

                                    <!-- Email -->
                                    <div class="mb-3">
                                        <label for="email" class="form-label">
                                            <i class="bi bi-envelope"></i> Correo Electrónico
                                        </label>
                                        <input id="email" 
                                               type="email" 
                                               class="form-control @error('email') is-invalid @enderror" 
                                               name="email" 
                                               value="{{ old('email') }}" 
                                               required 
                                               autofocus 
                                               autocomplete="username"
                                               placeholder="tu@email.com">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Password -->
                                    <div class="mb-3">
                                        <label for="password" class="form-label">
                                            <i class="bi bi-lock"></i> Contraseña
                                        </label>
                                        <input id="password" 
                                               type="password" 
                                               class="form-control @error('password') is-invalid @enderror" 
                                               name="password" 
                                               required 
                                               autocomplete="current-password"
                                               placeholder="••••••••">
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Remember Me -->
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember_me">
                                        <label class="form-check-label" for="remember_me">
                                            Recordarme
                                        </label>
                                    </div>

                                    <!-- Submit Button -->
                                    <button type="submit" class="btn btn-primary w-100 mb-3">
                                        <i class="bi bi-box-arrow-in-right"></i> Iniciar Sesión
                                    </button>

                                    <!-- Links -->
                                    <div class="text-center">
                                        @if (Route::has('password.request'))
                                            <a href="{{ route('password.request') }}" class="text-decoration-none">
                                                ¿Olvidaste tu contraseña?
                                            </a>
                                        @endif
                                        
                                        @if (Route::has('register'))
                                            <div class="mt-2">
                                                <span class="text-muted">¿No tienes cuenta?</span>
                                                <a href="{{ route('register') }}" class="text-decoration-none fw-bold">
                                                    Regístrate aquí
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </form>

                                <!-- Cuentas de Demostración -->
                                <div class="demo-accounts">
                                    <h6 class="mb-2"><i class="bi bi-info-circle"></i> Cuentas de Prueba</h6>
                                    <small class="text-muted d-block mb-3">Haz clic para autocompletar</small>
                                    
                                    <div class="demo-account-item" onclick="fillDemo('admin@vetcare.com', 'admin123')">
                                        <strong class="text-primary"><i class="bi bi-shield-check"></i> Admin</strong>
                                        <small class="d-block text-muted">admin@vetcare.com / admin123</small>
                                    </div>
                                    
                                    <div class="demo-account-item" onclick="fillDemo('recepcion@vetcare.com', 'recepcion123')">
                                        <strong class="text-success"><i class="bi bi-person-badge"></i> Recepción</strong>
                                        <small class="d-block text-muted">recepcion@vetcare.com / recepcion123</small>
                                    </div>
                                    
                                    <div class="demo-account-item" onclick="fillDemo('veterinario@vetcare.com', 'veterinario123')">
                                        <strong class="text-info"><i class="bi bi-hospital"></i> Veterinario</strong>
                                        <small class="d-block text-muted">veterinario@vetcare.com / veterinario123</small>
                                    </div>
                                    
                                    <div class="demo-account-item" onclick="fillDemo('cliente@vetcare.com', 'cliente123')">
                                        <strong class="text-warning"><i class="bi bi-person"></i> Cliente</strong>
                                        <small class="d-block text-muted">cliente@vetcare.com / cliente123</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botón Volver -->
                <div class="text-center mt-3">
                    <a href="{{ url('/') }}" class="btn btn-light">
                        <i class="bi bi-arrow-left"></i> Volver al Inicio
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function fillDemo(email, password) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;
        }
    </script>
</body>
</html>