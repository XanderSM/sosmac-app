<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SOSMAC - Portal Técnico</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body { background-color: #f4f7f6; font-family: 'Segoe UI', sans-serif; overflow-x: hidden; }
        
        /* Menú Lateral Técnico */
        .sidebar { background-color: #11235A; color: white; min-height: 100vh; width: 250px; position: fixed; display: flex; flex-direction: column; top: 0; left: 0; z-index: 1000; }
        .sidebar-brand { padding: 30px 20px; display: flex; align-items: center; gap: 15px; font-weight: 900; font-size: 1.2rem; }
        .sidebar-brand .logo-icon { background: white; color: #11235A; padding: 10px; border-radius: 10px; font-size: 1.5rem; line-height: 1; }
        .nav-link { color: white; padding: 12px 20px; margin: 5px 15px; border-radius: 8px; display: flex; align-items: center; gap: 15px; text-decoration: none; font-weight: 600; background-color: #1E5DDB; }
        .user-profile { display: flex; align-items: center; gap: 10px; margin-bottom: 15px; padding: 0 20px;}
        .user-profile .avatar { width: 35px; height: 35px; background-color: #1E5DDB; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; color: white; }
        
        .main-content { margin-left: 250px; padding: 40px; width: calc(100% - 250px); min-height: 100vh; }
        
        /* Tarjeta de Trabajo */
        .card-job { background: white; border-radius: 16px; padding: 30px; border: 1px solid #edf2f9; box-shadow: 0 4px 15px rgba(0,0,0,0.02); margin-bottom: 25px; }
        .tag-id { background-color: #e8f0fe; color: #1E5DDB; font-size: 0.75rem; font-weight: bold; padding: 5px 10px; border-radius: 6px; }
        .location-box { border: 1px solid #edf2f9; border-radius: 12px; padding: 15px; display: flex; justify-content: space-between; align-items: center; margin: 20px 0; }
        .btn-ir { background-color: #3b82f6; color: white; font-weight: bold; border-radius: 8px; padding: 8px 25px; border: none; }
        .btn-terminar { background-color: #059669; color: white; font-weight: bold; border-radius: 8px; padding: 12px; width: 100%; border: none; transition: 0.3s; }
        .btn-terminar:hover { background-color: #047857; }
        
        /* Panel Desplegable */
        .panel-finalizar { display: none; margin-top: 20px; border-top: 1px dashed #ccc; padding-top: 20px; }
        .textarea-green { border: 2px solid #10b981; border-radius: 8px; box-shadow: none !important; }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="sidebar-brand">
            <img src="{{ asset('img/logo.png') }}" alt="Logo SOSMAC" style="width: 55px; height: auto; filter: drop-shadow(0 4px 6px rgba(0,0,0,0.4));">
            <div>
                <div style="line-height: 1.2; font-weight: 900; font-size: 1.2rem; letter-spacing: 1px;">SOSMAC</div>
                <div style="font-size: 0.65rem; font-weight: 600; color: #a8b2d1;">SANEAMIENTO</div>
            </div>
        </div> 
        <a href="#" class="nav-link"><i class="bi bi-briefcase-fill"></i> Mis Trabajos</a>
        
        <div class="mt-auto mb-4 border-top border-secondary pt-4">
            <div class="user-profile">
                <div class="avatar">TK</div>
                <div>
                    <div style="font-size: 0.85rem; font-weight: bold; color: white;">{{ auth()->user()->name ?? 'Técnico Operativo' }}</div>
                    <div style="font-size: 0.65rem; color: #a8b2d1;">SEDE TRUJILLO</div>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="px-3">
                @csrf
                <button type="submit" class="nav-link border-0 bg-transparent w-100 text-start text-white" style="background-color: transparent !important;"><i class="bi bi-box-arrow-left"></i> Salir del Sistema</button>
            </form>
        </div>
    </div>

    <div class="main-content">
        <div class="mb-4">
            <h2 class="fw-bold text-dark" style="color: #11235A !important;">Mis Trabajos</h2>
            <p class="text-muted mb-0">Portal del Técnico Operativo</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm"><i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}</div>
        @endif

        @forelse($ordenes as $orden)
            @if($orden->estado != 'Completada')
            <div class="card-job">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <span class="tag-id">ID: OS-{{ str_pad($orden->id, 4, '0', STR_PAD_LEFT) }}</span>
                    <div class="text-end">
                        <small class="text-muted fw-bold d-block" style="font-size: 0.7rem;">FECHA</small>
                        <span class="fw-bold text-dark">{{ \Carbon\Carbon::parse($orden->fecha_programada)->format('Y-m-d') }}</span>
                    </div>
                </div>

                <h4 class="fw-bold text-dark mb-4">{{ $orden->cotizacion->titulo_proyecto ?? $orden->cotizacion->cliente->nombre_razon_social }}</h4>

                <div class="location-box">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width: 40px; height: 40px; border-radius: 50%; border: 1px solid #edf2f9; display: flex; align-items: center; justify-content: center; color: #3b82f6;">
                            <i class="bi bi-geo-alt"></i>
                        </div>
                        <div>
                            <small class="text-muted fw-bold d-block" style="font-size: 0.7rem;">UBICACIÓN</small>
                            <span class="fw-bold text-dark">{{ $orden->cotizacion->direccion_proyecto ?? $orden->cotizacion->cliente->direccion ?? 'Sin dirección' }}</span>
                        </div>
                    </div>
                    <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($orden->cotizacion->direccion_proyecto ?? $orden->cotizacion->cliente->direccion) }}" target="_blank" class="btn-ir text-decoration-none">
                        <i class="bi bi-cursor me-1"></i> IR
                    </a>
                </div>

                <button type="button" class="btn-terminar" onclick="mostrarPanel({{ $orden->id }})" id="btn-show-{{ $orden->id }}">
                    <i class="bi bi-check-circle me-2"></i> TERMINAR SERVICIO
                </button>

                <div class="panel-finalizar" id="panel-{{ $orden->id }}">
                    <form action="{{ route('tecnico.ordenes.estado', $orden->id) }}" method="POST">
                        @csrf @method('PUT')
                        <label class="form-label fw-bold text-muted" style="font-size: 0.8rem;">OBSERVACIONES TÉCNICAS</label>
                        <textarea class="form-control textarea-green mb-3" rows="3" name="observaciones_tecnicas" placeholder="Ej: Se aplicó cipermetrina 20% en rincones..." required></textarea>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <button type="button" class="btn fw-bold text-muted" onclick="ocultarPanel({{ $orden->id }})">CANCELAR</button>
                            <button type="submit" class="btn" style="background-color: #059669; color: white; font-weight: bold; border-radius: 8px; padding: 10px 40px;">CONFIRMAR FINALIZACIÓN</button>
                        </div>
                    </form>
                </div>
            </div>
            @endif
        @empty
            <div class="text-center mt-5 text-muted">
                <i class="bi bi-tools fs-1 d-block mb-3"></i>
                <h5 class="fw-bold">Sin asignaciones</h5>
                <p>No tienes trabajos programados.</p>
            </div>
        @endforelse
    </div>

    <script>
        function mostrarPanel(id) {
            document.getElementById('btn-show-' + id).style.display = 'none';
            document.getElementById('panel-' + id).style.display = 'block';
        }
        function ocultarPanel(id) {
            document.getElementById('panel-' + id).style.display = 'none';
            document.getElementById('btn-show-' + id).style.display = 'block';
        }
    </script>
</body>
</html>