@extends('admin.layouts.app')

@push('styles')
<style>
    /* Tarjetas de Métricas */
    .stat-card { background: white; border-radius: 16px; padding: 25px; border: 1px solid #edf2f9; box-shadow: 0 4px 15px rgba(0,0,0,0.02); height: 100%; display: flex; justify-content: space-between; align-items: center; transition: transform 0.2s;}
    .stat-card:hover { transform: translateY(-3px); box-shadow: 0 8px 25px rgba(0,0,0,0.05); }
    .stat-title { font-size: 0.75rem; font-weight: 800; color: #8392ab; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 5px;}
    .stat-value { font-size: 1.8rem; font-weight: 900; color: #11235A; margin: 0;}
    .icon-box { width: 55px; height: 55px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; }
    
    .bg-green-light { background-color: #e6f8f3; color: #10b981; }
    .bg-blue-light { background-color: #e8f0fe; color: #1E5DDB; }
    .bg-orange-light { background-color: #fff3e0; color: #f59e0b; }
    .bg-purple-light { background-color: #f3e8ff; color: #8b5cf6; }
    
    /* Paneles de Gráficos y Reportes */
    .panel-box { background: white; border-radius: 16px; border: 1px solid #edf2f9; padding: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.02); height: 100%; }
    .panel-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .panel-title { font-size: 1rem; font-weight: 800; color: #11235A; text-transform: uppercase; letter-spacing: 0.5px; margin: 0; }
</style>
@endpush

@section('content')
    <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-0">Panel de Control Operativo</h2>
            <p class="text-muted fw-bold mb-0">Monitoreo comercial e inteligencia de negocio</p>
        </div>
        <div>
            <span class="badge bg-white text-dark shadow-sm px-3 py-2 fs-6 rounded-pill border border-light"><i class="bi bi-calendar3 me-2 text-primary"></i>{{ date('d / m / Y') }}</span>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-6 col-lg-3">
            <div class="stat-card">
                <div>
                    <div class="stat-title">Ingresos Aprobados</div>
                    <div class="stat-value text-success">S/ {{ number_format($ingresos ?? 0, 2) }}</div>
                </div>
                <div class="icon-box bg-green-light"><i class="bi bi-graph-up-arrow"></i></div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="stat-card">
                <div>
                    <div class="stat-title">Cotiz. Pendientes</div>
                    <div class="stat-value">{{ $cotizacionesPendientes ?? 0 }}</div>
                </div>
                <div class="icon-box bg-orange-light"><i class="bi bi-clock-history"></i></div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="stat-card">
                <div>
                    <div class="stat-title">Total Emitidas</div>
                    <div class="stat-value">{{ $totalCotizaciones ?? 0 }}</div>
                </div>
                <div class="icon-box bg-purple-light"><i class="bi bi-file-earmark-text"></i></div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="stat-card">
                <div>
                    <div class="stat-title">Clientes Registrados</div>
                    <div class="stat-value">{{ $totalClientes ?? 0 }}</div>
                </div>
                <div class="icon-box bg-blue-light"><i class="bi bi-people-fill"></i></div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        
        <div class="col-lg-5">
            <div class="panel-box">
                <div class="panel-header">
                    <h6 class="panel-title"><i class="bi bi-pie-chart-fill text-primary me-2"></i>Estado de Servicios (En Campo)</h6>
                </div>
                <div style="position: relative; height: 250px; width: 100%; display: flex; justify-content: center;">
                    <canvas id="ordenesChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="panel-box">
                <div class="panel-header">
                    <h6 class="panel-title"><i class="bi bi-file-earmark-bar-graph-fill text-success me-2"></i>Generador de Reportes Gerenciales</h6>
                </div>
                
                <p class="text-muted small mb-4">Selecciona el periodo y el tipo de información que deseas exportar para tu análisis operativo y contable.</p>

                <form action="{{ route('admin.reportes.exportar') }}" method="GET" target="_blank">
                    @csrf
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-muted">TIPO DE REPORTE</label>
                            <select class="form-select bg-light border-0" name="tipo_reporte" required>
                                <option value="ingresos">Reporte de Ingresos (Cotizaciones)</option>
                                <option value="operaciones">Reporte Operativo (Órdenes)</option>
                                <option value="inventario">Estado de Inventario</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-muted">RANGO / FRECUENCIA</label>
                            <select class="form-select bg-light border-0" name="rango" id="selectRango" onchange="toggleFechas()" required>
                                <option value="diario">Resumen Diario (Hoy)</option>
                                <option value="mensual">Resumen Mensual</option>
                                <option value="semestral">Resumen Semestral</option>
                                <option value="personalizado">Rango Personalizado...</option>
                            </select>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-success w-100 fw-bold"><i class="bi bi-file-earmark-pdf-fill me-2"></i>Exportar PDF</button>
                        </div>
                    </div>

                    <div class="row g-3" id="fechasPersonalizadas" style="display: none;">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">FECHA INICIO</label>
                            <input type="date" class="form-control bg-light border-0" name="fecha_inicio">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">FECHA FIN</label>
                            <input type="date" class="form-control bg-light border-0" name="fecha_fin">
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Mostrar/Ocultar campos de fechas personalizados
    function toggleFechas() {
        const rango = document.getElementById('selectRango').value;
        const divFechas = document.getElementById('fechasPersonalizadas');
        if (rango === 'personalizado') {
            divFechas.style.display = 'flex';
        } else {
            divFechas.style.display = 'none';
        }
    }

    // Inicializar Gráfico de Dona con Chart.js
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('ordenesChart').getContext('2d');
        
        // Obtenemos los datos pasados desde el controlador
        const pendientes = {{ $ordenesPendientes ?? 0 }};
        const enRuta = {{ $ordenesEnRuta ?? 0 }};
        const completadas = {{ $ordenesCompletadas ?? 0 }};

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Pendientes', 'En Ruta (Técnico)', 'Completadas'],
                datasets: [{
                    data: [pendientes, enRuta, completadas],
                    backgroundColor: [
                        '#f59e0b', // Naranja
                        '#3b82f6', // Azul
                        '#10b981'  // Verde
                    ],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { padding: 20, font: { family: "'Segoe UI', sans-serif", weight: 'bold' } }
                    }
                },
                cutout: '70%'
            }
        });
    });
</script>
@endpush