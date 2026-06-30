@extends('admin.layouts.app')

@push('styles')
<style>
    /* Estilos Ventana Gigante (Cotizador) */
    .modal-fullscreen .modal-content { background-color: #f8f9fc; border: none; }
    .topbar-cotizador { background: white; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #edf2f9; }
    .card-panel { background: white; border-radius: 16px; padding: 25px; border: 1px solid #edf2f9; margin-bottom: 20px; }
    .form-control, .form-select { border-radius: 8px; background-color: #f8f9fc; border: 1px solid #edf2f9; padding: 10px 15px; }
    .section-title { font-size: 0.8rem; font-weight: 800; color: #8392ab; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 15px; display: flex; align-items: center; gap: 10px; }
    
    /* Modificadores para Select2 */
    .select2-container--default .select2-selection--single { background-color: #f8f9fc; border: 1px solid #edf2f9; border-radius: 8px; height: 45px; display: flex; align-items: center; font-weight: bold; }
    .select2-container--default .select2-selection--single .select2-selection__arrow { height: 43px; }
    
    /* Panel Total Inversión */
    .panel-total { background-color: #0b1536; border-radius: 16px; padding: 30px; color: white; display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;}
    .total-number { font-size: 3rem; font-weight: 900; color: #34d399; margin: 0; line-height: 1;}
    .subtotal-text { color: #8392ab; font-size: 0.9rem; text-align: right;}
    .igv-text { color: #1E5DDB; font-size: 0.9rem; font-weight: bold; text-align: right;}
    
    /* Tablas Cotizador */
    .cat-header { color: #11235A; font-weight: 900; font-size: 0.9rem; margin-bottom: 15px; border-bottom: 2px solid #edf2f9; padding-bottom: 10px; display: flex; justify-content: space-between;}
    .table-cotizador th { font-size: 0.7rem; color: #8392ab; text-transform: uppercase; font-weight: 700; border: none; }
    .table-cotizador td { vertical-align: middle; border-bottom: 1px dashed #edf2f9; }
    .btn-analizar { background-color: #1E5DDB; color: white; font-weight: bold; border-radius: 8px; padding: 10px 20px; border: none; }
    .btn-generar { background-color: #10b981; color: white; font-weight: bold; border-radius: 8px; padding: 10px 20px; border: none; }
</style>
@endpush

@section('content')

    <!-- Alertas del Sistema -->
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm"><i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger border-0 shadow-sm"><i class="bi bi-exclamation-triangle-fill me-2"></i>{{ $errors->first() }}</div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0 text-dark">Historial de Cotizaciones</h3>
            <small class="text-muted fw-bold">Gestiona y descarga documentos emitidos</small>
        </div>
        <button class="btn btn-analizar" data-bs-toggle="modal" data-bs-target="#modalCotizador">
            <i class="bi bi-calculator me-2"></i>Abrir Cotizador
        </button>
    </div>

    <!-- TABLA PRINCIPAL DE COTIZACIONES -->
    <div class="card p-4 border-0 shadow-sm" style="border-radius: 16px;">
        <table class="table table-hover mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="py-3 px-4">Nro.</th>
                    <th class="py-3">Proyecto / Cliente</th>
                    <th class="py-3 text-end">Total (S/)</th>
                    <th class="py-3 text-center">Estado</th>
                    <th class="py-3 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cotizaciones as $coti)
                <tr>
                    <td class="px-4 fw-bold text-primary">C001-{{ str_pad($coti->id, 8, '0', STR_PAD_LEFT) }}</td>
                    <td>
                        <div class="fw-bold text-dark">{{ $coti->titulo_proyecto ?? 'S/T' }}</div>
                        <small class="text-muted">{{ $coti->cliente->nombre_razon_social }}</small>
                    </td>
                    <td class="text-end fw-bold text-primary">S/ {{ number_format($coti->total, 2) }}</td>
                    <td class="text-center">
                        @if($coti->estado == 'Aprobada') <span class="badge bg-success">Aprobada</span>
                        @elseif($coti->estado == 'Rechazada') <span class="badge bg-danger">Rechazada</span>
                        @else <span class="badge bg-warning text-dark">Pendiente</span> @endif
                    </td>
                    <td class="text-center">
                        <!-- Botón Descargar PDF -->
                        <a href="{{ route('cotizaciones.pdf', $coti->id) }}" class="btn btn-sm btn-outline-secondary me-1" title="Descargar PDF">
                            <i class="bi bi-file-earmark-pdf-fill"></i>
                        </a>
                        
                        @if($coti->estado == 'Pendiente')
                        <!-- Botón Aprobar -->
                        <form action="{{ route('cotizaciones.estado', $coti->id) }}" method="POST" class="d-inline">
                            @csrf @method('PUT')
                            <input type="hidden" name="estado" value="Aprobada">
                            <button type="submit" class="btn btn-sm btn-outline-success me-1" title="Aprobar"><i class="bi bi-check-lg"></i></button>
                        </form>
                        
                        <!-- Botón Rechazar -->
                        <form action="{{ route('cotizaciones.estado', $coti->id) }}" method="POST" class="d-inline">
                            @csrf @method('PUT')
                            <input type="hidden" name="estado" value="Rechazada">
                            <button type="submit" class="btn btn-sm btn-outline-warning me-1" title="Rechazar"><i class="bi bi-x-lg"></i></button>
                        </form>
                        @endif

                        <!-- Botón Eliminar (Solo aparece si fue rechazada) -->
                        @if($coti->estado == 'Rechazada')
                        <form action="{{ route('cotizaciones.destroy', $coti->id) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar" onclick="return confirm('¿Seguro que deseas eliminar esta cotización rechazada? Esta acción no se puede deshacer.')">
                                <i class="bi bi-trash-fill"></i>
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center py-5 text-muted">Aún no hay cotizaciones. Abre el cotizador para iniciar.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- ========================================== -->
    <!-- VENTANA GIGANTE: COTIZADOR MANUAL          -->
    <!-- ========================================== -->
    <div class="modal fade" id="modalCotizador" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                
                <!-- Cabecera del Cotizador -->
                <div class="topbar-cotizador shadow-sm">
                    <div class="d-flex align-items-center gap-3">
                        <button type="button" class="btn btn-light rounded-circle" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></button>
                        <div class="bg-dark text-white p-2 rounded"><i class="bi bi-calculator"></i></div>
                        <div>
                            <h5 class="fw-bold mb-0 text-dark">Cotizador Comercial</h5>
                            <small class="text-muted fw-bold" style="font-size: 0.7rem;">FORMATO OFICIAL SANEAMIENTO</small>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-generar" onclick="document.getElementById('formCotizacion').submit()"><i class="bi bi-file-earmark-pdf me-2"></i>GENERAR Y GUARDAR DOCUMENTO</button>
                    </div>
                </div>

                <!-- Cuerpo del Cotizador -->
                <div class="modal-body p-4 p-md-5" style="background-color: #f4f7f6;">
                    <form id="formCotizacion" action="{{ route('cotizaciones.store') }}" method="POST">
                        @csrf
                        <div class="row g-4">
                            
                            <!-- COLUMNA IZQUIERDA: DATOS PRINCIPALES -->
                            <div class="col-lg-4">
                                <div class="card-panel">
                                    <div class="section-title"><i class="bi bi-info-circle"></i> DATOS PRINCIPALES</div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label text-muted small fw-bold">BUSCAR CLIENTE (RUC / DNI / NOMBRE)</label>
                                        <div class="input-group">
                                            <select class="form-select w-100" id="buscador-clientes" name="cliente_id" required>
                                                <option value="" disabled selected>Escriba aquí para buscar...</option>
                                                @foreach(\App\Models\Cliente::all() as $cliente)
                                                    <option value="{{ $cliente->id }}">{{ $cliente->documento }} - {{ $cliente->nombre_razon_social }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label text-muted small fw-bold">TÍTULO PROYECTO</label>
                                        <input type="text" class="form-control fw-bold" name="titulo_proyecto" placeholder="Ej: CONSORCIO MET - 03 CAMPAMENTO" required>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label text-muted small fw-bold">DIRECCIÓN</label>
                                        <input type="text" class="form-control" name="direccion_proyecto" required>
                                    </div>
                                </div>

                                <div class="card-panel">
                                    <div class="section-title"><i class="bi bi-cash-stack"></i> CUENTAS BANCARIAS</div>
                                    <input type="text" class="form-control mb-2 text-muted" value="0011 - 0248 - 0200147412 - 27" readonly>
                                    <input type="text" class="form-control mb-2 text-muted" value="011-248 - 000200147412 - 27" readonly>
                                    <input type="text" class="form-control text-muted" value="00761 150863" readonly>
                                </div>
                            </div>

                            <!-- COLUMNA DERECHA: SERVICIOS Y TOTALES -->
                            <div class="col-lg-8">
                                
                                <!-- Tablas de Servicios -->
                                <div class="card-panel pb-2">
                                    <div class="cat-header">
                                        <span>1. SERVICIOS OPERATIVOS</span>
                                        <button type="button" class="btn btn-sm btn-link text-decoration-none fw-bold" onclick="agregarFila()">+ AGREGAR FILA</button>
                                    </div>
                                    <table class="table table-cotizador" id="tabla-detalles">
                                        <thead>
                                            <tr>
                                                <th style="width: 40%">ÁREA A TRATAR (SERVICIO)</th>
                                                <th class="text-center">UND</th>
                                                <th class="text-center">APLIC</th>
                                                <th class="text-center">SERV</th>
                                                <th class="text-end">PRECIO</th>
                                                <th class="text-end">IMPORTE</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Filas Dinámicas -->
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Panel Oscuro Total -->
                                <div class="panel-total shadow-lg">
                                    <div>
                                        <div class="text-info fw-bold mb-1" style="font-size: 0.8rem; letter-spacing: 1px;">TOTAL DE LA INVERSIÓN</div>
                                        <h1 class="total-number" id="txt-total">S/ 0.00</h1>
                                    </div>
                                    <div>
                                        <div class="subtotal-text">Subtotal: <span id="txt-subtotal">S/ 0.00</span></div>
                                        <div class="igv-text">IGV 18%: <span id="txt-igv">S/ 0.00</span></div>
                                    </div>
                                </div>

                                <!-- Textareas inferiores RESTAURADOS -->
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="card-panel h-100">
                                            <div class="section-title mb-2"><i class="bi bi-clipboard-data"></i> ÁREAS A TRATAR</div>
                                            <textarea class="form-control border-0 bg-light fw-bold" name="notas_areas" rows="4" placeholder="CAMPAMENTO: CONSISTE EN LA FUMIGACIÓN..."></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card-panel h-100">
                                            <div class="section-title mb-2"><i class="bi bi-box-seam"></i> NOTAS / MATERIALES</div>
                                            <textarea class="form-control border-0 bg-light fw-bold" name="notas_materiales" rows="4" placeholder="Malathion, Deltamax 2.5%..."></textarea>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    const listaServicios = @json(\App\Models\Servicio::where('estado', true)->get());
    let contadorFilas = 0;

    // Iniciar la barra de búsqueda avanzada
    $(document).ready(function() {
        $('#buscador-clientes').select2({
            dropdownParent: $('#modalCotizador'),
            placeholder: "Buscar por DNI, RUC o Nombre...",
            allowClear: true,
            language: {
                noResults: function() { return "No se encontraron clientes"; }
            }
        });
    });

    function agregarFila() {
        const tbody = document.querySelector('#tabla-detalles tbody');
        let opciones = '<option value="">Seleccione...</option>';
        listaServicios.forEach(s => { opciones += `<option value="${s.id}" data-precio="${s.precio_base}">${s.nombre}</option>`; });

        const tr = document.createElement('tr');
        tr.id = `fila-${contadorFilas}`;
        tr.innerHTML = `
            <td><select class="form-select form-select-sm fw-bold border-0 select-servicio" name="servicios[${contadorFilas}][id]" onchange="cargarPrecio(${contadorFilas})" required>${opciones}</select></td>
            <td><input type="text" class="form-control form-control-sm text-center border-0" value="GLB" readonly></td>
            <td><input type="number" class="form-control form-control-sm text-center border-0 input-aplic" name="servicios[${contadorFilas}][aplic]" value="1" min="1" oninput="calcular()"></td>
            <td><input type="number" class="form-control form-control-sm text-center border-0 input-serv" name="servicios[${contadorFilas}][serv]" value="1" min="1" oninput="calcular()"></td>
            <td><input type="number" step="0.01" class="form-control form-control-sm text-end border-0 fw-bold text-primary input-precio" name="servicios[${contadorFilas}][precio]" value="0.00" min="0" oninput="calcular()"></td>
            <td class="text-end fw-bold align-middle col-importe" style="padding-right: 15px;">
                <div class="d-flex justify-content-end align-items-center gap-3">
                    <span>S/ 0.00</span>
                    <button type="button" class="btn btn-sm text-danger p-0 border-0" onclick="document.getElementById('fila-${contadorFilas}').remove(); calcular();"><i class="bi bi-trash-fill"></i></button>
                </div>
            </td>
        `;
        tbody.appendChild(tr);
        contadorFilas++;
    }

    function cargarPrecio(idx) {
        const fila = document.getElementById(`fila-${idx}`);
        const select = fila.querySelector('.select-servicio');
        fila.querySelector('.input-precio').value = parseFloat(select.options[select.selectedIndex].getAttribute('data-precio')).toFixed(2);
        calcular();
    }

    function calcular() {
        let subtotal = 0;
        document.querySelectorAll('#tabla-detalles tbody tr').forEach(fila => {
            let aplic = parseInt(fila.querySelector('.input-aplic').value) || 1;
            let serv = parseInt(fila.querySelector('.input-serv').value) || 1;
            let precio = parseFloat(fila.querySelector('.input-precio').value) || 0;
            
            if(aplic < 1) aplic = 1; if(serv < 1) serv = 1; if(precio < 0) precio = 0;
            
            let importe = (aplic * serv) * precio;
            subtotal += importe;
            fila.querySelector('.col-importe span').innerText = 'S/ ' + importe.toFixed(2);
        });

        let igv = subtotal * 0.18;
        let total = subtotal + igv;

        document.getElementById('txt-subtotal').innerText = `S/ ${subtotal.toFixed(2)}`;
        document.getElementById('txt-igv').innerText = `S/ ${igv.toFixed(2)}`;
        document.getElementById('txt-total').innerText = `S/ ${total.toFixed(2)}`;
    }
    
    document.addEventListener("DOMContentLoaded", function() { agregarFila(); });
</script>
@endpush