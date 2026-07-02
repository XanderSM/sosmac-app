<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>SOSMAC - Portal Técnico</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body { background-color: #f4f7f6; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; overflow-x: hidden; }
        
        /* ==========================================
           1. SIDEBAR ESCRITORIO
           ========================================== */
        .sidebar { background-color: #11235A; color: white; min-height: 100vh; width: 260px; position: fixed; top: 0; left: 0; z-index: 1000; display: flex; flex-direction: column; box-shadow: 4px 0 15px rgba(0,0,0,0.05); }
        .sidebar-brand { padding: 30px 20px; display: flex; align-items: center; gap: 15px; font-weight: 900; }
        .nav-link-desktop { color: rgba(255,255,255,0.8); padding: 14px 20px; margin: 5px 15px; border-radius: 10px; display: flex; align-items: center; gap: 15px; text-decoration: none; font-weight: 600; transition: all 0.3s ease; }
        .nav-link-desktop.active, .nav-link-desktop:hover { background-color: #1E5DDB; color: white; transform: translateX(5px); }
        .user-profile { display: flex; align-items: center; gap: 12px; padding: 20px; background-color: rgba(0,0,0,0.1); margin: 0 15px 15px 15px; border-radius: 12px; }
        .avatar { width: 40px; height: 40px; background: linear-gradient(45deg, #1E5DDB, #5bc0de); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; color: white; border: 2px solid rgba(255,255,255,0.2); }
        
        /* Contenedor Principal */
        .main-content { margin-left: 260px; padding: 40px; min-height: 100vh; }

        /* ==========================================
           2. APP MÓVIL (HEADER Y BOTTOM NAV)
           ========================================== */
        .mobile-header { display: none; background-color: #11235A; color: white; padding: 15px 20px; align-items: center; justify-content: space-between; position: fixed; top: 0; left: 0; width: 100%; z-index: 1000; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .mobile-bottom-nav { display: none; background-color: white; position: fixed; bottom: 0; left: 0; width: 100%; z-index: 1000; justify-content: space-around; padding: 12px 0; border-top: 1px solid #edf2f9; box-shadow: 0 -5px 20px rgba(0,0,0,0.05); }
        .mobile-nav-item { display: flex; flex-direction: column; align-items: center; color: #a8b2d1; text-decoration: none; font-size: 0.75rem; font-weight: 600; gap: 4px; background: none; border: none; padding: 0; }
        .mobile-nav-item.active { color: #1E5DDB; }
        .mobile-nav-item i { font-size: 1.3rem; }

        /* ==========================================
           3. ADAPTABILIDAD (MEDIA QUERIES)
           ========================================== */
        @media (max-width: 768px) {
            .sidebar { display: none; } /* Ocultar sidebar en móviles */
            .mobile-header { display: flex; } /* Mostrar header móvil */
            .mobile-bottom-nav { display: flex; } /* Mostrar navegación inferior */
            .main-content { margin-left: 0; padding: 80px 15px 90px 15px; } /* Ajustar márgenes para que no se oculte con los menús */
            .location-box { flex-direction: column; text-align: center; gap: 12px; }
            .location-box .btn { width: 100%; }
        }

        /* ==========================================
           4. TARJETAS Y COMPONENTES
           ========================================== */
        .card-job { background: white; border-radius: 16px; padding: 25px; border: 1px solid #edf2f9; box-shadow: 0 10px 30px rgba(0,0,0,0.03); margin-bottom: 25px; position: relative; overflow: hidden; }
        .card-job::before { content: ''; position: absolute; top: 0; left: 0; width: 5px; height: 100%; background-color: #5bc0de; }
        .tag-id { background-color: #e8f0fe; color: #1E5DDB; font-size: 0.75rem; font-weight: 800; padding: 6px 12px; border-radius: 8px; letter-spacing: 0.5px;}
        .location-box { background-color: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 12px; padding: 15px; display: flex; justify-content: space-between; align-items: center; margin: 20px 0; }
        
        .btn-terminar { background: linear-gradient(45deg, #11235A, #1E5DDB); color: white; font-weight: 800; border-radius: 10px; padding: 14px; width: 100%; border: none; letter-spacing: 1px; box-shadow: 0 5px 15px rgba(30,93,219,0.3); transition: all 0.3s; }
        .btn-terminar:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(30,93,219,0.4); }
        
        .panel-finalizar { display: none; margin-top: 25px; border-top: 2px dashed #edf2f9; padding-top: 25px; animation: fadeIn 0.4s ease; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
        
        .form-control, .form-select { border-radius: 10px; padding: 12px; border: 1px solid #cbd5e1; background-color: #f8fafc; }
        .form-control:focus, .form-select:focus { border-color: #1E5DDB; box-shadow: 0 0 0 4px rgba(30,93,219,0.1); background-color: white; }
    </style>
</head>
<body>

    <div class="mobile-header">
        <div class="d-flex align-items-center gap-2">
            <img src="{{ asset('img/logo.png') }}" alt="SOSMAC" style="width: 35px;">
            <div style="line-height: 1.1;">
                <div style="font-weight: 900; font-size: 1.1rem;">SOSMAC</div>
                <div style="font-size: 0.6rem; color: #5bc0de;">PORTAL TÉCNICO</div>
            </div>
        </div>
        <div class="avatar" style="width: 35px; height: 35px; font-size: 0.8rem;">TK</div>
    </div>

    <div class="sidebar">
        <div class="sidebar-brand">
            <img src="{{ asset('img/logo.png') }}" alt="Logo SOSMAC" style="width: 45px;">
            <div>
                <div style="line-height: 1.2; font-weight: 900; font-size: 1.3rem;">SOSMAC</div>
                <div style="font-size: 0.7rem; color: #5bc0de; letter-spacing: 1px;">SANEAMIENTO</div>
            </div>
        </div> 
        
        <div class="mt-4">
            <a href="#" class="nav-link-desktop active"><i class="bi bi-briefcase-fill"></i> Mis Trabajos</a>
        </div>
        
        <div class="mt-auto mb-4 border-top border-secondary pt-4">
            <div class="user-profile">
                <div class="avatar">TK</div>
                <div style="overflow: hidden;">
                    <div style="font-size: 0.85rem; font-weight: bold; white-space: nowrap; text-overflow: ellipsis;">{{ auth()->user()->name ?? 'Técnico Operativo' }}</div>
                    <div style="font-size: 0.7rem; color: #5bc0de;">ÁREA OPERATIVA</div>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="px-3">
                @csrf
                <button type="submit" class="nav-link-desktop border-0 w-100 text-start" style="background: none;"><i class="bi bi-box-arrow-left"></i> Cerrar Sesión</button>
            </form>
        </div>
    </div>

    <div class="mobile-bottom-nav">
        <a href="#" class="mobile-nav-item active">
            <i class="bi bi-briefcase-fill"></i>
            <span>Trabajos</span>
        </a>
        <form action="{{ route('logout') }}" method="POST" style="margin:0; padding:0;">
            @csrf
            <button type="submit" class="mobile-nav-item">
                <i class="bi bi-box-arrow-right"></i>
                <span>Salir</span>
            </button>
        </form>
    </div>

    <div class="main-content">
        
        <div class="mb-4">
            <h2 class="fw-bold text-dark mb-1">Mis Asignaciones</h2>
            <p class="text-muted small">Visualiza y gestiona tus servicios en campo.</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4" role="alert" style="background-color: #d1e7dd; color: #0f5132;">
                <i class="bi bi-check-circle-fill me-2"></i> <strong>¡Excelente!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @php
            // Filtramos las órdenes ANTES del bucle.
            // Si el técnico no tiene órdenes, o si todas las que tiene están "Completadas",
            // esto se considerará vacío y mostrará el mensaje correctamente.
            $ordenesPendientes = $ordenes->where('estado', '!=', 'Completada');
        @endphp

        @forelse($ordenesPendientes as $orden)
            <div class="card-job">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <span class="tag-id"><i class="bi bi-hash"></i> OS-{{ str_pad($orden->id, 4, '0', STR_PAD_LEFT) }}</span>
                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill"><i class="bi bi-clock-history"></i> {{ $orden->estado }}</span>
                </div>

                <h4 class="fw-bold text-dark mb-2">{{ $orden->cotizacion->titulo_proyecto ?? 'Servicio de Saneamiento' }}</h4>
                <p class="text-muted small mb-0"><i class="bi bi-calendar-event"></i> Programado para: <strong>{{ \Carbon\Carbon::parse($orden->fecha_programada)->format('d/m/Y') }}</strong> a las <strong>{{ $orden->hora_programada }}</strong></p>

                <div class="location-box">
                    <div class="d-flex align-items-center gap-3">
                        <div style="background: white; padding: 10px; border-radius: 50%; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                            <i class="bi bi-geo-alt-fill fs-5 text-danger"></i>
                        </div>
                        <div class="text-start">
                            <div class="fw-bold text-dark" style="font-size: 0.85rem;">Dirección del cliente</div>
                            <span class="text-muted small">{{ $orden->cotizacion->direccion_proyecto ?? 'Dirección no registrada' }}</span>
                        </div>
                    </div>
                    <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($orden->cotizacion->direccion_proyecto ?? '') }}" target="_blank" class="btn btn-outline-primary btn-sm fw-bold px-4 rounded-pill">
                        <i class="bi bi-cursor-fill me-1"></i> MAPA
                    </a>
                </div>

                <button type="button" class="btn-terminar" onclick="mostrarPanel({{ $orden->id }})" id="btn-show-{{ $orden->id }}">
                    <i class="bi bi-check2-square me-2"></i> REGISTRAR FIN DE SERVICIO
                </button>

                <div class="panel-finalizar" id="panel-{{ $orden->id }}">
                    <form action="{{ route('tecnico.ordenes.estado', $orden->id) }}" method="POST">
                        @csrf @method('PUT')
                        <input type="hidden" name="estado" value="Completada">
                        
                        <div class="row g-3 mb-4">
                            <div class="col-12 col-md-8">
                                <label class="form-label fw-bold text-muted small">MATERIAL UTILIZADO (OPCIONAL)</label>
                                <select class="form-select" name="producto_id">
                                    <option value="">-- No se registraron materiales extra --</option>
                                    @foreach($productos as $producto)
                                        <option value="{{ $producto->id }}">{{ $producto->nombre }} (Stock: {{ $producto->stock }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label fw-bold text-muted small">CANTIDAD GASTADA</label>
                                <input type="number" min="1" class="form-control" name="cantidad_usada" placeholder="Ej. 2">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold text-muted small">REPORTE DEL TÉCNICO <span class="text-danger">*</span></label>
                                <textarea class="form-control" rows="3" name="comentarios_adicionales" placeholder="Describe brevemente los trabajos realizados, áreas tratadas o incidencias..." required></textarea>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center gap-3">
                            <button type="button" class="btn btn-light text-secondary fw-bold w-50 py-3 rounded-3" onclick="ocultarPanel({{ $orden->id }})">CANCELAR</button>
                            <button type="submit" class="btn btn-success fw-bold w-50 py-3 rounded-3" style="box-shadow: 0 4px 10px rgba(25, 135, 84, 0.3);">CONFIRMAR FIN</button>
                        </div>
                    </form>
                </div>
            </div>
        @empty
            <div class="text-center p-5 bg-white rounded-4 border" style="margin-top: 10vh; box-shadow: 0 10px 30px rgba(0,0,0,0.02);">
                <div style="background-color: #f8fafc; width: 100px; height: 100px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px auto;">
                    <i class="bi bi-clipboard-check text-success" style="font-size: 3rem;"></i>
                </div>
                <h4 class="text-dark fw-bold mb-2">No hay trabajos por ahora</h4>
                <p class="text-muted mx-auto" style="max-width: 300px;">Estás al día con tus asignaciones. Tus nuevas órdenes de servicio aparecerán en esta pantalla.</p>
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