<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>SOSMAC - Portal Técnico</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body { background-color: #f4f7f6; font-family: 'Segoe UI', sans-serif; overflow-x: hidden; }
        
        /* Menú Lateral y Adaptabilidad */
        .sidebar { background-color: #11235A; color: white; min-height: 100vh; width: 250px; position: fixed; top: 0; left: 0; z-index: 1000; transition: 0.3s; }
        .main-content { margin-left: 250px; padding: 40px; min-height: 100vh; transition: 0.3s; }

        /* Ajuste para móviles */
        @media (max-width: 768px) {
            .sidebar { width: 100%; min-height: auto; position: relative; }
            .main-content { margin-left: 0; padding: 20px; width: 100%; }
            .location-box { flex-direction: column; text-align: center; gap: 10px; }
        }

        .sidebar-brand { padding: 30px 20px; display: flex; align-items: center; gap: 15px; font-weight: 900; }
        .nav-link { color: white; padding: 12px 20px; margin: 5px 15px; border-radius: 8px; display: flex; align-items: center; gap: 15px; text-decoration: none; font-weight: 600; background-color: #1E5DDB; }
        .user-profile { display: flex; align-items: center; gap: 10px; margin-bottom: 15px; padding: 0 20px; }
        .avatar { width: 35px; height: 35px; background-color: #1E5DDB; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; color: white; }
        
        /* Tarjeta de Trabajo */
        .card-job { background: white; border-radius: 16px; padding: 25px; border: 1px solid #edf2f9; box-shadow: 0 4px 15px rgba(0,0,0,0.02); margin-bottom: 25px; }
        .tag-id { background-color: #e8f0fe; color: #1E5DDB; font-size: 0.75rem; font-weight: bold; padding: 5px 10px; border-radius: 6px; }
        .location-box { border: 1px solid #edf2f9; border-radius: 12px; padding: 15px; display: flex; justify-content: space-between; align-items: center; margin: 20px 0; }
        .btn-terminar { background-color: #030544; color: white; font-weight: bold; border-radius: 8px; padding: 12px; width: 100%; border: none; }
        .panel-finalizar { display: none; margin-top: 20px; border-top: 1px dashed #ccc; padding-top: 20px; }
        .textarea-green { border: 2px solid #16023a; border-radius: 8px; }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="sidebar-brand">
            <img src="{{ asset('img/logo.png') }}" alt="Logo SOSMAC" style="width: 45px;">
            <div>
                <div style="line-height: 1.2; font-weight: 900;">SOSMAC</div>
                <div style="font-size: 0.65rem; color: #a8b2d1;">SANEAMIENTO</div>
            </div>
        </div> 
        <a href="#" class="nav-link"><i class="bi bi-briefcase-fill"></i> Mis Trabajos</a>
        
        <div class="mt-auto mb-4 border-top border-secondary pt-4">
            <div class="user-profile">
                <div class="avatar">TK</div>
                <div>
                    <div style="font-size: 0.85rem; font-weight: bold;">{{ auth()->user()->name ?? 'Técnico' }}</div>
                    <div style="font-size: 0.65rem; color: #a8b2d1;">SEDE TRUJILLO</div>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="px-3">
                @csrf
                <button type="submit" class="nav-link border-0 w-100 text-start" style="background: none;"><i class="bi bi-box-arrow-left"></i> Salir</button>
            </form>
        </div>
    </div>

    <div class="main-content">
        <div class="mb-4">
            <h2 class="fw-bold text-dark">Mis Trabajos</h2>
            <p class="text-muted">Portal del Técnico Operativo</p>
        </div>

        @forelse($ordenes as $orden)
            @if($orden->estado != 'Completada')
            <div class="card-job">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <span class="tag-id">ID: OS-{{ str_pad($orden->id, 4, '0', STR_PAD_LEFT) }}</span>
                    <span class="fw-bold">{{ \Carbon\Carbon::parse($orden->fecha_programada)->format('d/m/Y') }}</span>
                </div>

                <h4 class="fw-bold text-dark mb-4">{{ $orden->cotizacion->titulo_proyecto ?? 'Proyecto' }}</h4>

                <div class="location-box">
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-geo-alt fs-4 text-primary"></i>
                        <span>{{ $orden->cotizacion->direccion_proyecto ?? 'Sin dirección' }}</span>
                    </div>
                    <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($orden->cotizacion->direccion_proyecto ?? '') }}" target="_blank" class="btn btn-primary btn-sm mt-2 mt-md-0">
                        <i class="bi bi-cursor"></i> IR
                    </a>
                </div>

                <button type="button" class="btn-terminar" onclick="mostrarPanel({{ $orden->id }})" id="btn-show-{{ $orden->id }}">
                    TERMINAR SERVICIO
                </button>

                <div class="panel-finalizar" id="panel-{{ $orden->id }}">
                    <form action="{{ route('tecnico.ordenes.estado', $orden->id) }}" method="POST">
                        @csrf @method('PUT')
                        <textarea class="form-control textarea-green mb-3" rows="3" name="observaciones_tecnicas" placeholder="Observaciones técnicas..." required></textarea>
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn text-muted" onclick="ocultarPanel({{ $orden->id }})">CANCELAR</button>
                            <button type="submit" class="btn btn-success fw-bold">FINALIZAR</button>
                        </div>
                    </form>
                </div>
            </div>
            @endif
        @empty
            <div class="text-center mt-5 text-muted"><h5>Sin asignaciones pendientes.</h5></div>
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