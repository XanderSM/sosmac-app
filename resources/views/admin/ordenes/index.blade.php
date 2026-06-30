@extends('admin.layouts.app')

@section('content')
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm"><i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}</div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0 text-dark">Programación de Servicios</h3>
            <small class="text-muted fw-bold">Asignación de trabajos en campo</small>
        </div>
        <button class="btn btn-custom" data-bs-toggle="modal" data-bs-target="#modalNuevaOrden">
            <i class="bi bi-calendar-plus me-2"></i>Programar Nueva Orden
        </button>
    </div>

    <div class="card-panel border-0 shadow-sm">
        <table class="table table-hover mb-0 align-middle">
            <thead class="bg-light text-uppercase" style="font-size: 0.8rem; color: #8392ab;">
                <tr>
                    <th class="py-3 px-4">Nro. Orden</th>
                    <th class="py-3">Cliente / Origen</th>
                    <th class="py-3">Fecha y Hora</th>
                    <th class="py-3">Técnico</th>
                    <th class="py-3 text-center">Estado</th>
                    <th class="py-3 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ordenes as $orden)
                <tr>
                    <td class="px-4 fw-bold text-primary">ORD-{{ str_pad($orden->id, 4, '0', STR_PAD_LEFT) }}</td>
                    <td>
                        <div class="fw-bold">{{ $orden->cotizacion->cliente->nombre_razon_social }}</div>
                        <small class="text-muted">Origen: COT-{{ str_pad($orden->cotizacion_id, 4, '0', STR_PAD_LEFT) }}</small>
                    </td>
                    <td>
                        <div><i class="bi bi-calendar text-primary me-2"></i>{{ \Carbon\Carbon::parse($orden->fecha_programada)->format('d/m/Y') }}</div>
                        <small class="text-muted"><i class="bi bi-clock me-2"></i>{{ \Carbon\Carbon::parse($orden->hora_programada)->format('h:i A') }}</small>
                    </td>
                    <td><span class="badge bg-secondary"><i class="bi bi-person-badge me-1"></i>{{ $orden->tecnico->name ?? 'Sin asignar' }}</span></td>
                    <td class="text-center">
                        @if($orden->estado == 'Completada') <span class="badge bg-success">Completada</span>
                        @elseif($orden->estado == 'En Ruta') <span class="badge bg-info text-dark">En Ruta</span>
                        @else <span class="badge bg-warning text-dark">Pendiente</span> @endif
                    </td>
                    <td class="text-center">
                        <a href="{{ route('ordenes.pdf_orden', $orden->id) }}" class="btn btn-sm btn-outline-warning me-1" title="Descargar Orden de Servicio">
                            <i class="bi bi-clipboard-check-fill"></i>
                        </a>
    
                        <a href="{{ route('ordenes.pdf_comprobante', $orden->id) }}" class="btn btn-sm btn-outline-primary me-1" title="Generar Boleta/Factura">
                            <i class="bi bi-receipt"></i>
                        </a>
    
                        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalEditar{{ $orden->id }}" title="Editar Programación">
                            <i class="bi bi-pencil-fill"></i>
                        </button>
                    </td>
                </tr>

                <div class="modal fade" id="modalEditar{{ $orden->id }}" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow" style="border-radius: 16px;">
                            <div class="modal-header border-0 pb-0 px-4 pt-4">
                                <h5 class="modal-title fw-bold text-dark"><i class="bi bi-pencil-square text-primary me-2"></i>Editar Orden ORD-{{ str_pad($orden->id, 4, '0', STR_PAD_LEFT) }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body p-4 text-start">
                                <form action="{{ route('ordenes.update', $orden->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-3">
                                        <label class="form-label fw-bold small text-muted">REASIGNAR TÉCNICO</label>
                                        <select class="form-select bg-light border-0" name="tecnico_id" required>
                                            @foreach($tecnicos as $tecnico)
                                                <option value="{{ $tecnico->id }}" {{ $orden->tecnico_id == $tecnico->id ? 'selected' : '' }}>{{ $tecnico->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="row g-3 mb-3">
                                        <div class="col-6">
                                            <label class="form-label fw-bold small text-muted">FECHA</label>
                                            <input type="date" class="form-control bg-light border-0" name="fecha_programada" value="{{ $orden->fecha_programada }}" required>
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label fw-bold small text-muted">HORA</label>
                                            <input type="time" class="form-control bg-light border-0" name="hora_programada" value="{{ $orden->hora_programada }}" required>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold small text-muted">ESTADO DE LA ORDEN</label>
                                        <select class="form-select bg-light border-0" name="estado" required>
                                            <option value="Pendiente" {{ $orden->estado == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                                            <option value="En Ruta" {{ $orden->estado == 'En Ruta' ? 'selected' : '' }}>En Ruta (Técnico en camino)</option>
                                            <option value="Completada" {{ $orden->estado == 'Completada' ? 'selected' : '' }}>Completada</option>
                                        </select>
                                    </div>
                                    <div class="d-flex justify-content-end gap-2 mt-4">
                                        <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-primary fw-bold">Actualizar Orden</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <tr><td colspan="6" class="text-center py-5 text-muted">No hay órdenes de servicio programadas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="modalNuevaOrden" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow" style="border-radius: 16px;">
                <div class="modal-header border-0 pb-0 px-4 pt-4">
                    <h5 class="modal-title fw-bold text-dark"><i class="bi bi-calendar-plus text-primary me-2"></i>Programar Servicio</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="{{ route('ordenes.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">COTIZACIÓN APROBADA (ORIGEN)</label>
                            <select class="form-select bg-light border-0" name="cotizacion_id" required>
                                <option value="" disabled selected>Seleccione la cotización base...</option>
                                @foreach($cotizaciones as $coti)
                                    <option value="{{ $coti->id }}">COT-{{ str_pad($coti->id, 4, '0', STR_PAD_LEFT) }} - {{ $coti->cliente->nombre_razon_social }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">ASIGNAR TÉCNICO</label>
                            <select class="form-select bg-light border-0" name="tecnico_id" required>
                                <option value="" disabled selected>Seleccione un técnico...</option>
                                @foreach($tecnicos as $tecnico)
                                    <option value="{{ $tecnico->id }}">{{ $tecnico->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label class="form-label fw-bold small text-muted">FECHA</label>
                                <input type="date" class="form-control bg-light border-0" name="fecha_programada" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-bold small text-muted">HORA DE LLEGADA</label>
                                <input type="time" class="form-control bg-light border-0" name="hora_programada" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">OBSERVACIONES INTERNAS</label>
                            <textarea class="form-control bg-light border-0" name="observaciones_admin" rows="2" placeholder="Instrucciones para el técnico..."></textarea>
                        </div>
                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-custom">Agendar Orden</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection