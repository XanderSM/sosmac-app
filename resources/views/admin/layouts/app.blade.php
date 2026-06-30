<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SOSMAC - Sistema de Gestión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    <style>
        /* TRANSICIÓN FLUIDA: Efecto Fade-in */
        body { background-color: #f4f7f6; font-family: 'Segoe UI', sans-serif; overflow-x: hidden; opacity: 0; transition: opacity 0.25s ease-in-out; }
        body.page-loaded { opacity: 1; }

        /* BARRA DE CARGA PREMIUM */
        #top-loading-bar { position: fixed; top: 0; left: 0; width: 0%; height: 3px; background: linear-gradient(90deg, #1E5DDB, #34d399); z-index: 9999; transition: width 0.4s ease, opacity 0.4s ease; }
        
        /* ESTILOS DEL MENÚ LATERAL */
        .sidebar { background-color: #11235A; color: white; min-height: 100vh; width: 250px; position: fixed; display: flex; flex-direction: column; z-index: 1000; top: 0; left: 0; }
        .sidebar-brand { padding: 30px 20px; display: flex; align-items: center; gap: 15px; font-weight: 900; font-size: 1.2rem; }
        .nav-link { color: #a8b2d1; padding: 12px 20px; margin: 5px 15px; border-radius: 8px; display: flex; align-items: center; gap: 15px; transition: all 0.3s; text-decoration: none; font-weight: 600;}
        .nav-link:hover, .nav-link.active { background-color: #1E5DDB; color: white; }
        .user-profile { display: flex; align-items: center; gap: 10px; margin-bottom: 15px; padding: 0 20px;}
        .user-profile .avatar { width: 35px; height: 35px; background-color: #1E5DDB; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; color: white; }
        
        .main-content { margin-left: 250px; padding: 40px; width: calc(100% - 250px); min-height: 100vh; }
        .card-panel { background: white; border-radius: 16px; padding: 25px; border: 1px solid #edf2f9; box-shadow: 0 5px 15px rgba(0,0,0,0.03); }
    </style>
    @stack('styles') 
</head>
<body>

    <!-- BARRA DE CARGA -->
    <div id="top-loading-bar"></div>

    <div class="sidebar">
        <div class="sidebar-brand">
            <img src="{{ asset('img/logo.png') }}" alt="Logo SOSMAC" style="width: 55px; height: auto; filter: drop-shadow(0 4px 6px rgba(0,0,0,0.4));">
            <div>
                <div style="line-height: 1.2; font-weight: 900; font-size: 1.2rem; letter-spacing: 1px;">SOSMAC</div>
                <div style="font-size: 0.65rem; font-weight: 600; color: #a8b2d1;">SANEAMIENTO</div>
            </div>
        </div>
        
        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><i class="bi bi-grid-fill"></i> Dashboard</a>
        <a href="{{ route('clientes.index') }}" class="nav-link {{ request()->routeIs('clientes.*') ? 'active' : '' }}"><i class="bi bi-people-fill"></i> Clientes</a>
        <a href="{{ route('inventario.index') }}" class="nav-link {{ request()->routeIs('inventario.*') ? 'active' : '' }}"><i class="bi bi-box-seam-fill"></i> Inventario</a>
        <a href="{{ route('servicios.index') }}" class="nav-link {{ request()->routeIs('servicios.*') ? 'active' : '' }}"><i class="bi bi-card-checklist"></i> Servicios</a>
        <a href="{{ route('cotizaciones.index') }}" class="nav-link {{ request()->routeIs('cotizaciones.*') ? 'active' : '' }}"><i class="bi bi-file-earmark-text-fill"></i> Cotizaciones</a>
        <a href="{{ route('ordenes.index') }}" class="nav-link {{ request()->routeIs('ordenes.*') ? 'active' : '' }}"><i class="bi bi-briefcase-fill"></i> Órdenes Serv.</a>
        <a href="{{ route('tecnicos.index') }}" class="nav-link {{ request()->routeIs('tecnicos.*') ? 'active' : '' }}"><i class="bi bi-tools"></i> Técnicos</a>
        
        <div class="mt-auto mb-4">
            <div class="user-profile">
                <div class="avatar">AD</div>
                <div>
                    <div style="font-size: 0.85rem; font-weight: bold; color: white;">Administrador</div>
                    <div style="font-size: 0.65rem; color: #a8b2d1;">SEDE TRUJILLO</div>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="px-3">
                @csrf
                <button type="submit" class="nav-link border-0 bg-transparent w-100 text-start" style="color: #ef4444 !important;"><i class="bi bi-box-arrow-left"></i> Salir del Sistema</button>
            </form>
        </div>
    </div>

    <div class="main-content">
        @yield('content')
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <script>
        // Lógica de Transiciones
        document.addEventListener("DOMContentLoaded", function() {
            document.body.classList.add('page-loaded');
            const bar = document.getElementById('top-loading-bar');
            bar.style.width = '100%';
            setTimeout(() => { bar.style.opacity = '0'; }, 400);
        });

        window.addEventListener("beforeunload", function() {
            const bar = document.getElementById('top-loading-bar');
            bar.style.opacity = '1';
            bar.style.width = '70%';
            document.body.style.opacity = '0';
        });
    </script>
    @stack('scripts') 
</body>
</html>