<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ProFund Events - Nueva Cotización</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body { background-color: #f4f7f6; font-family: 'Segoe UI', sans-serif; overflow-x: hidden; }
        .sidebar { background-color: #11235A; color: white; min-height: 100vh; width: 250px; position: fixed; display: flex; flex-direction: column; z-index: 1000; top: 0; left: 0; }
        .sidebar-brand { padding: 30px 20px; display: flex; align-items: center; gap: 15px; font-weight: 900; font-size: 1.2rem; }
        .sidebar-brand .logo-icon { background: white; color: #11235A; padding: 10px; border-radius: 10px; font-size: 1.5rem; line-height: 1; }
        .nav-link { color: #a8b2d1; padding: 12px 20px; margin: 5px 15px; border-radius: 8px; display: flex; align-items: center; gap: 15px; transition: all 0.3s; text-decoration: none; font-weight: 600;}
        .nav-link:hover, .nav-link.active { background-color: #1E5DDB; color: white; }
        .nav-link i { font-size: 1.2rem; }
        .main-content { margin-left: 250px; padding: 40px; width: calc(100% - 250px); min-height: 100vh; }
        
        /* Estilos Premium para Formularios */
        .card-premium { background: white; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.04); border: 1px solid #edf2f9; margin-bottom: 25px; }
        .card-header-premium { background-color: #f8f9fc; border-bottom: 1px solid #edf2f9; padding: 15px 25px; border-radius: 12px 12px 0 0; font-weight: bold; color: #11235A; display: flex; align-items: center; gap: 10px; }
        .form-select, .form-control { border: 1px solid #dce1e9; border-radius: 8px; padding: 10px 15px; font-size: 0.95rem; }
        .form-select:focus, .form-control:focus { border-color: #1E5DDB; box-shadow: 0 0 0 0.25rem rgba(30, 93, 219, 0.1); }
        
        /* Tabla dinámica */
        .table-custom th { background-color: #f8f9fc; color: #8392ab; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.5px; border-bottom: 2px solid #edf2f9; padding: 15px; }
        .table-custom td { padding: 15px; vertical-align: middle; border-bottom: 1px solid #edf2f9; }
        
        /* Panel de Totales */
        .totals-panel { background: linear-gradient(145deg, #ffffff 0%, #f8f9fc 100%); border: 1px solid #edf2f9; border-radius: 12px; padding: 25px; }
        .total-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; color: #555; }
        .total-row.final { border-top: 2px dashed #dce1e9; padding-top: 15px; margin-top: 5px; margin-bottom: 0; color: #11235A; font-size: 1.4rem; font-weight: 900; }
        
        /* Botones */
        .btn-primary-custom { background-color: #1E5DDB; color: white; border-radius: 8px; padding: 12px 25px; font-weight: 600; border: none; transition: all 0.3s;}
        .btn-primary-custom:hover { background-color: #11235A; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(17, 35, 90, 0.2); }
        .btn-add { background-color: #e8f0fe; color: #1E5DDB; font-weight: 600; border-radius: 8px; border: 1px dashed #1E5DDB; padding: 8px 15px; }
        .btn-add:hover { background-color: #1E5DDB; color: white; }
    </style>
</head>
<body>

    <!-- Menú Lateral -->
    <div class="sidebar">
        <div class="sidebar-brand">
            <div class="logo-icon"><i class="bi bi-shield-check"></i></div>
            <div>
                <div style="line-height: 1.2;">ProFund</div>
                <div style="font-size: 0.7rem; font-weight: 500; color: #a8b2d1;">EVENTS SANEAMIENTO</div>
            </div>
        </div>
        <a href="/admin/dashboard" class="nav-link"><i class="bi bi-grid-fill"></i> Dashboard</a>
        <a href="/admin/clientes" class="nav-link"><i class="bi bi-people-fill"></i> Clientes</a>
        <a href="/admin/inventario" class="nav-link"><i class="bi bi-box-seam-fill"></i> Inventario</a>
        <a href="/admin/servicios" class="nav-link"><i class="bi bi-card-checklist"></i> Servicios</a>
        <a href="/admin/cotizaciones" class="nav-link active"><i class="bi bi-file-earmark-text-fill"></i> Cotizaciones</a>
        
        <div class="mt-auto mb-4 px-3">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="nav-link border-0 bg-transparent w-100 text-start text-danger"><i class="bi bi-box-arrow-left"></i> Salir del Sistema</button>
            </form>
        </div>
    </div>

    <!-- Contenido Principal -->
    <div class="main-content">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <a href="{{ route('cotizaciones.index') }}" class="text-decoration-none text-muted fw-bold small"><i class="bi bi-arrow-left me-1"></i> Volver a Cotizaciones</a>
                <h2 class="fw-bold text-dark mt-2 mb-0">Emitir Nueva Cotización</h2>
                <small class="text-muted fw-bold">Estructura un presupuesto formal comercial</small>
            </div>
            <div>
                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 fs-6 rounded-pill border border-primary"><i class="bi bi-calendar3 me-2"></i>{{ date('d / m / Y') }}</span>
            </div>
        </div>

        <form action="{{ route('cotizaciones.store') }}" method="POST">
            @csrf
            
            <!-- TARJETA 1: CLIENTE -->
            <div class="card-premium">
                <div class="card-header-premium">
                    <i class="bi bi-person-badge fs-5"></i> 1. Información del Cliente
                </div>
                <div class="p-4">
                    <div class="row">
                        <div class="col-md-8">
                            <label class="form-label fw-bold small text-muted">SELECCIONE UNA EMPRESA O PERSONA DE LA CARTERA</label>
                            <select class="form-select" name="cliente_id" required>
                                <option value="" selected disabled>-- Busque o seleccione un cliente comercial --</option>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id }}">[{{ $cliente->tipo_documento }}: {{ $cliente->documento }}] - {{ $cliente->nombre_razon_social }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TARJETA 2: SERVICIOS -->
            <div class="card-premium">
                <div class="card-header-premium d-flex justify-content-between align-items-center">
                    <div><i class="bi bi-card-list fs-5"></i> 2. Detalle de Servicios (Líneas de Presupuesto)</div>
                    <button type="button" class="btn btn-add" onclick="agregarFila()">
                        <i class="bi bi-plus-circle-fill me-1"></i> Añadir otra línea
                    </button>
                </div>
                <div class="p-0">
                    <div class="table-responsive">
                        <table class="table table-custom mb-0" id="tabla-detalles">
                            <thead>
                                <tr>
                                    <th style="width: 40%; padding-left: 25px;">Descripción del Servicio</th>
                                    <th style="width: 15%;" class="text-center">Cant.</th>
                                    <th style="width: 20%;" class="text-end">Precio Unit. (S/)</th>
                                    <th style="width: 20%;" class="text-end">Subtotal (S/)</th>
                                    <th style="width: 5%;" class="text-center">Quitar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Filas dinámicas -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TARJETA 3: RESUMEN Y TOTALES -->
            <div class="row justify-content-end mb-5">
                <div class="col-lg-5 col-md-6">
                    <div class="totals-panel shadow-sm">
                        <h6 class="fw-bold text-muted mb-4 text-uppercase letter-spacing-1"><i class="bi bi-calculator me-2"></i>Resumen Financiero</h6>
                        
                        <div class="total-row">
                            <span class="fw-bold">Valor de Venta (Subtotal)</span>
                            <span class="fw-bold text-dark fs-5" id="txt-subtotal">S/ 0.00</span>
                        </div>
                        
                        <div class="total-row">
                            <span class="fw-bold">IGV (18%)</span>
                            <span class="fw-bold text-dark fs-5" id="txt-igv">S/ 0.00</span>
                        </div>
                        
                        <div class="total-row final">
                            <span>TOTAL A PAGAR</span>
                            <span id="txt-total">S/ 0.00</span>
                        </div>

                        <div class="mt-4 pt-3 border-top d-flex gap-2">
                            <a href="{{ route('cotizaciones.index') }}" class="btn btn-light fw-bold w-100 py-2 border">Cancelar</a>
                            <button type="submit" class="btn btn-primary-custom w-100"><i class="bi bi-save me-2"></i>Guardar y Emitir</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Script de lógica matemática -->
    <script>
        const listaServicios = @json($servicios);
        let contadorFilas = 0;

        function agregarFila() {
            const tbody = document.querySelector('#tabla-detalles tbody');
            
            let opciones = '<option value="" selected disabled>-- Despliegue para seleccionar servicio --</option>';
            listaServicios.forEach(s => {
                opciones += `<option value="${s.id}" data-precio="${s.precio_base}">${s.nombre} (Referencia: S/ ${s.precio_base})</option>`;
            });

            const nuevaFila = document.createElement('tr');
            nuevaFila.id = `fila-${contadorFilas}`;
            nuevaFila.innerHTML = `
                <td style="padding-left: 25px;">
                    <select class="form-select fw-bold text-primary select-servicio" name="servicios[${contadorFilas}][id]" onchange="cargarPrecioOriginal(${contadorFilas})" required>
                        ${opciones}
                    </select>
                </td>
                <td>
                    <input type="number" class="form-control text-center input-cantidad fw-bold" name="servicios[${contadorFilas}][cantidad]" value="1" min="1" oninput="calcularTotales()" required>
                </td>
                <td>
                    <input type="number" step="0.01" class="form-control text-end input-precio fw-bold" name="servicios[${contadorFilas}][precio]" value="0.00" min="0" oninput="calcularTotales()" required>
                </td>
                <td class="text-end fw-bold text-dark fs-5 col-subtotal" style="vertical-align: middle;">S/ 0.00</td>
                <td class="text-center" style="vertical-align: middle;">
                    <button type="button" class="btn btn-sm btn-outline-danger border-0" onclick="removerFila(${contadorFilas})" title="Eliminar fila">
                        <i class="bi bi-trash3-fill fs-5"></i>
                    </button>
                </td>
            `;
            
            tbody.appendChild(nuevaFila);
            contadorFilas++;
            calcularTotales();
        }

        function cargarPrecioOriginal(index) {
            const fila = document.getElementById(`fila-${index}`);
            const select = fila.querySelector('.select-servicio');
            const precioInput = fila.querySelector('.input-precio');
            
            const opcionSeleccionada = select.options[select.selectedIndex];
            const precioBase = opcionSeleccionada.getAttribute('data-precio');
            
            if (precioBase) {
                precioInput.value = parseFloat(precioBase).toFixed(2);
            }
            calcularTotales();
        }

        function removerFila(index) {
            document.getElementById(`fila-${index}`).remove();
            calcularTotales();
        }

        function calcularTotales() {
            let subtotalAcumulado = 0;
            const filas = document.querySelectorAll('#tabla-detalles tbody tr');

            filas.forEach(fila => {
                const cantidadInput = fila.querySelector('.input-cantidad');
                const precioInput = fila.querySelector('.input-precio');
                const subtotalTd = fila.querySelector('.col-subtotal');

                let cantidad = parseInt(cantidadInput.value) || 0;
                let precio = parseFloat(precioInput.value) || 0;

                if (cantidad < 1) cantidad = 1;
                if (precio < 0) precio = 0;

                let subtotalItem = cantidad * precio;
                subtotalAcumulado += subtotalItem;

                subtotalTd.innerText = `S/ ${subtotalItem.toFixed(2)}`;
            });

            let igv = subtotalAcumulado * 0.18;
            let total = subtotalAcumulado + igv;

            document.getElementById('txt-subtotal').innerText = `S/ ${subtotalAcumulado.toFixed(2)}`;
            document.getElementById('txt-igv').innerText = `S/ ${igv.toFixed(2)}`;
            document.getElementById('txt-total').innerText = `S/ ${total.toFixed(2)}`;
        }

        document.addEventListener("DOMContentLoaded", function() {
            agregarFila(); // Carga la primera fila automáticamente
        });
    </script>
</body>
</html>