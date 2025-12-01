<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>VetCare - Sistema de Gestión Veterinaria</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .hero-section {
            background: linear-gradient(135deg, #4f46e5 0%, #6366f1 50%, #8b5cf6 100%);
            color: white;
            padding: 100px 0;
            position: relative;
            overflow: hidden;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff" fill-opacity="0.1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,154.7C960,171,1056,181,1152,165.3C1248,149,1344,107,1392,85.3L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
            background-size: cover;
        }
        
        .feature-card {
            transition: transform 0.3s, box-shadow 0.3s;
            border: none;
            border-radius: 15px;
            overflow: hidden;
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }
        
        .feature-icon {
            width: 80px;
            height: 80px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin: 0 auto 20px;
        }
        
        .btn-custom {
            padding: 12px 30px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .stats-section {
            background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
        }
        
        .stat-item {
            text-align: center;
            padding: 30px;
        }
        
        .stat-number {
            font-size: 3rem;
            font-weight: 800;
            background: linear-gradient(135deg, #4f46e5 0%, #8b5cf6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        footer {
            background: #1f2937;
            color: white;
        }
    </style>
</head>
<body>
    @php
        $totalMascotas = class_exists(\App\Models\Mascota::class) ? \App\Models\Mascota::count() : 0;
        $totalPropietarios = class_exists(\App\Models\Propietario::class) ? \App\Models\Propietario::count() : 0;
        $totalCitas = class_exists(\App\Models\Cita::class) ? \App\Models\Cita::count() : 0;
        $satisfaccion = '99%';
    @endphp
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold fs-4" href="/">
                <i class="bi bi-heart-pulse-fill text-primary"></i> VetCare
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Iniciar Sesión</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn btn-primary text-white px-4 ms-2" href="{{ route('register') }}">Registrarse</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-3 fw-bold mb-4">Gestiona tu clínica veterinaria con eficacia</h1>
                    <p class="lead mb-4">VetCare centraliza citas, historiales y facturación en una sola plataforma: menos papeleo, más tiempo para cuidar animales.</p>
       
                </div>
                <div class="col-lg-6 text-center d-none d-lg-block">
                    <i class="bi bi-hospital display-1" style="font-size: 15rem; opacity: 0.2;"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-3 stat-item">
                    <div class="stat-number">{{ number_format($totalMascotas) }}</div>
                    <h5 class="text-muted">Mascotas registradas</h5>
                </div>
                <div class="col-md-3 stat-item">
                    <div class="stat-number">{{ number_format($totalPropietarios) }}</div>
                    <h5 class="text-muted">Propietarios</h5>
                </div>
                <div class="col-md-3 stat-item">
                    <div class="stat-number">{{ number_format($totalCitas) }}</div>
                    <h5 class="text-muted">Citas totales</h5>
                </div>
                <div class="col-md-3 stat-item">
                    <div class="stat-number">{{ $satisfaccion }}</div>
                    <h5 class="text-muted">Satisfacción estimada</h5>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold mb-3">Funcionalidades Principales</h2>
                <p class="lead text-muted">Todo lo que necesitas en un solo sistema</p>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="card feature-card shadow-sm h-100">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon bg-primary bg-opacity-10">
                                <i class="bi bi-calendar-check text-primary"></i>
                            </div>
                            <h4 class="mb-3">Gestión de Citas</h4>
                            <p class="text-muted">Agenda, confirma y gestiona citas con calendarios por veterinario y ficha clínica asociada.</p>
                            @if(Route::has('citas.index'))
                                <a href="{{ route('citas.index') }}" class="btn btn-sm btn-outline-primary mt-3">Ir a Citas</a>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="card feature-card shadow-sm h-100">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon bg-success bg-opacity-10">
                                <i class="bi bi-heart-pulse text-success"></i>
                            </div>
                            <h4 class="mb-3">Historial Médico</h4>
                            <p class="text-muted">Registra diagnósticos y tratamientos por mascota, con generación de recetas y documentos en PDF.</p>
                            @if(Route::has('tratamientos.index'))
                                <a href="{{ route('tratamientos.index') }}" class="btn btn-sm btn-outline-primary mt-3">Ver Tratamientos</a>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="card feature-card shadow-sm h-100">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon bg-info bg-opacity-10">
                                <i class="bi bi-people text-info"></i>
                            </div>
                            <h4 class="mb-3">Gestión de Clientes</h4>
                            <p class="text-muted">Control de propietarios, historial de mascotas y acceso para clientes (mis mascotas, mis citas).</p>
                            @if(Route::has('propietarios.index'))
                                <a href="{{ route('propietarios.index') }}" class="btn btn-sm btn-outline-primary mt-3">Ver Propietarios</a>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="card feature-card shadow-sm h-100">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon bg-warning bg-opacity-10">
                                <i class="bi bi-graph-up text-warning"></i>
                            </div>
                            <h4 class="mb-3">Reportes y Estadísticas</h4>
                            <p class="text-muted">Panel con KPIs, gráficos y exportes a Excel/PDF listos para descargar.</p>
                            @if(Route::has('reportes.index'))
                                <a href="{{ route('reportes.index') }}" class="btn btn-sm btn-outline-primary mt-3">Ver Reportes</a>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="card feature-card shadow-sm h-100">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon bg-danger bg-opacity-10">
                                <i class="bi bi-shield-check text-danger"></i>
                            </div>
                            <h4 class="mb-3">Roles y Permisos</h4>
                            <p class="text-muted">Control de accesos y permisos para administrar quién puede ver y editar cada sección.</p>
                            @if(Route::has('admin.users.index'))
                                <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-primary mt-3">Gestionar Usuarios</a>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="card feature-card shadow-sm h-100">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon bg-purple bg-opacity-10" style="background-color: rgba(139, 92, 246, 0.1) !important;">
                                <i class="bi bi-cloud-arrow-down" style="color: #8b5cf6;"></i>
                            </div>
                            <h4 class="mb-3">Exportación de Datos</h4>
                            <p class="text-muted">Descarga listados y reportes en Excel o PDF para contabilidad y archivo.</p>
                            @if(Route::has('propietarios.export.excel'))
                                <a href="{{ route('propietarios.export.excel') }}" class="btn btn-sm btn-outline-primary mt-3">Exportar ejemplo</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-4">
                <h2 class="fw-bold">Lo que dicen clínicas reales</h2>
                <p class="text-muted">Historias reales de profesionales que optimizaron su trabajo con VetCare.</p>
            </div>

            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card p-4 shadow-sm">
                        <p class="mb-2">"Desde que usamos VetCare hemos reducido el tiempo administrativo y mejorado la comunicación con los propietarios."</p>
                        <p class="text-muted mb-0"><strong>Clínica San Bernardo</strong> — Cochabamba</p>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card p-4 shadow-sm">
                        <p class="mb-2">"Las exportaciones a Excel facilitan nuestros informes mensuales. El equipo está muy contento."</p>
                        <p class="text-muted mb-0"><strong>Centro Vet Andino</strong> — La Paz</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

   
    <!-- CTA Section -->
    <section class="py-5 bg-light">
        <div class="container text-center">
            <h2 class="display-5 fw-bold mb-3">Comienza hoy con VetCare</h2>
            <p class="lead text-muted mb-4">Únete a clínicas que simplificaron sus operaciones y mejoraron la atención.</p>
            @auth
                <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg btn-custom">
                    <i class="bi bi-speedometer2"></i> Ir al panel
                </a>
            @else
                <a href="{{ route('register') }}" class="btn btn-primary btn-lg btn-custom">
                    <i class="bi bi-person-plus"></i> Crear cuenta gratuita
                </a>
               
            @endauth
            <p class="text-muted small mt-3">Prueba gratuita disponible — sin tarjeta requerida.</p>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="bi bi-heart-pulse-fill"></i> VetCare</h5>
                    <p class="text-muted">Sistema de Gestión Veterinaria Completo</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0">&copy; {{ date('Y') }} VetCare. Todos los derechos reservados.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>