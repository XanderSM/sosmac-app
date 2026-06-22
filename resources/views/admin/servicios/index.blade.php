@extends('admin.layouts.app')

@section('content')
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm"><i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger border-0 shadow-sm"><i class="bi bi-exclamation-triangle-fill me-2"></i>{{ $errors->first() }}</div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0 text-dark">Catálogo de Servicios</h3>
            <small class="text-muted fw-bold">Gestión de servicios y precios base</small>
        </div>
        
        <div class="d-flex gap-3">
            <form action="{{ route('servicios.index') }}" method="GET" class="d-flex align-items-center">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="buscar" class="form-control border-start-0 ps-0" placeholder="Buscar servicio..." value="{{ $buscar ?? '' }}">
                    <button class="btn btn-outline-secondary" type="submit">Buscar</button>
                </div>
            </form>

            <button class="btn btn-custom" data-bs-toggle="modal" data-bs-target="#modalNuevoServicio">
                <i class="bi bi-plus-lg me-2"></i>Nuevo Servicio
            </button>
        </div>
    </div>

    <div class="card-panel border-0 shadow-sm p-0">
        <table class="table table-hover mb-0 align-middle">
            <thead class="bg-light text-uppercase" style="font-size: 0.8rem; color: #8392ab;">
                <tr>
                    <th class="py-3 px-4">Servicio</th>
                    <th class="py-3">Descripción</th>
                    <th class="py-3 text-end">Precio Base (S/)</th>
                    <th class="py-3 text-center">Estado</th>
                    <th class="py-3 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($servicios as $servicio)
                <tr>
                    <td class="px-4 fw-bold text-dark">{{ $servicio->nombre }}</td>
                    <td class="text-muted small">{{ $servicio->descripcion ?? 'Sin descripción' }}</td>
                    <td class="text-end fw-bold text-primary">S/ {{ number_format($servicio->precio_base, 2) }}</td>
                    <td class="text-center">
                        @if($servicio->estado)
                            <span class="badge bg-success bg-opacity-10 text-success border border-success px-2 py-1">Activo</span>
                        @else
                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-2 py-1">Inactivo</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#modalEditar{{ $servicio->id }}">
                            <i class="bi bi-pencil-fill"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalEliminar{{ $servicio->id }}">
                            <i class="bi bi-trash-fill"></i>
                        </button>
                    </td>
                </tr>

                <div class="modal fade" id="modalEditar{{ $servicio->id }}" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow" style="border-radius: 16px;">
                            <div class="modal-header border-0 pb-0 px-4 pt-4">
                                <h5 class="modal-title fw-bold text-dark"><i class="bi bi-pencil-square text-primary me-2"></i>Editar Servicio</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body p-4 text-start">
                                <form action="{{ route('servicios.update', $servicio->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-3">
                                        <label class="form-label fw-bold small text-muted">NOMBRE DEL SERVICIO</label>
                                        <input type="text" class="form-control bg-light border-0" name="nombre" value="{{ $servicio->nombre }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold small text-muted">DESCRIPCIÓN</label>
                                        <textarea class="form-control bg-light border-0" name="descripcion" rows="3">{{ $servicio->descripcion }}</textarea>
                                    </div>
                                    <div class="row g-3 mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold small text-muted">PRECIO BASE (S/)</label>
                                            <input type="number" step="0.01" min="0" class="form-control bg-light border-0" name="precio_base" value="{{ $servicio->precio_base }}" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold small text-muted">ESTADO</label>
                                            <select class="form-select bg-light border-0" name="estado">
                                                <option value="1" {{ $servicio->estado ? 'selected' : '' }}>Activo</option>
                                                <option value="0" {{ !$servicio->estado ? 'selected' : '' }}>Inactivo</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-end gap-2 mt-4">
                                        <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-primary fw-bold">Actualizar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="modalEliminar{{ $servicio->id }}" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow" style="border-radius: 16px;">
                            <div class="modal-header border-0 pb-0 px-4 pt-4">
                                <h5 class="modal-title fw-bold text-danger"><i class="bi bi-exclamation-triangle-fill me-2"></i>Confirmar Eliminación</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body p-4 text-center">
                                <p class="mb-0">¿Estás seguro que deseas eliminar el servicio <strong>{{ $servicio->nombre }}</strong>?</p>
                                <p class="text-muted small mt-2">Esta acción no se puede deshacer.</p>
                                <form action="{{ route('servicios.destroy', $servicio->id) }}" method="POST" class="mt-4 d-flex justify-content-center gap-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-danger fw-bold">Sí, Eliminar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                @empty
                <tr>
                    <td colspan="5" class="text-center py-5 text-muted">Aún no hay servicios registrados en el catálogo.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="modalNuevoServicio" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow" style="border-radius: 16px;">
                <div class="modal-header border-0 pb-0 px-4 pt-4">
                    <h5 class="modal-title fw-bold text-dark"><i class="bi bi-card-checklist text-primary me-2"></i>Registrar Nuevo Servicio</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="{{ route('servicios.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">NOMBRE DEL SERVICIO</label>
                            <input type="text" class="form-control bg-light border-0" name="nombre" placeholder="Ej: Desinfección de Ambientes" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">DESCRIPCIÓN</label>
                            <textarea class="form-control bg-light border-0" name="descripcion" rows="3" placeholder="Detalles del servicio..."></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">PRECIO BASE (S/)</label>
                            <input type="number" step="0.01" min="0" class="form-control bg-light border-0" name="precio_base" placeholder="0.00" required>
                        </div>
                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-custom">Guardar Servicio</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection