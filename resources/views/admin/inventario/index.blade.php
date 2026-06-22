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
            <h3 class="fw-bold mb-0 text-dark">Gestión de Inventario</h3>
            <small class="text-muted fw-bold">Control de stock de químicos y materiales</small>
        </div>
        
        <div class="d-flex gap-3">
            <form action="{{ route('inventario.index') }}" method="GET" class="d-flex align-items-center">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="buscar" class="form-control border-start-0 ps-0" placeholder="Buscar producto..." value="{{ $buscar ?? '' }}">
                    <button class="btn btn-outline-secondary" type="submit">Buscar</button>
                </div>
            </form>

            <button class="btn btn-custom" data-bs-toggle="modal" data-bs-target="#modalNuevoProducto">
                <i class="bi bi-plus-lg me-2"></i>Nuevo Producto
            </button>
        </div>
    </div>

    <div class="card-panel border-0 shadow-sm p-0">
        <table class="table table-hover mb-0 align-middle">
            <thead class="bg-light text-uppercase" style="font-size: 0.8rem; color: #8392ab;">
                <tr>
                    <th class="py-3 px-4">Producto</th>
                    <th class="py-3">Categoría</th>
                    <th class="py-3 text-center">Stock Actual</th>
                    <th class="py-3 text-center">Mínimo</th>
                    <th class="py-3 text-center">Estado</th>
                    <th class="py-3 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($productos as $producto)
                <tr>
                    <td class="px-4 fw-bold text-dark">{{ $producto->nombre }}</td>
                    <td><span class="badge bg-secondary">{{ $producto->tipo }}</span></td>
                    <td class="text-center">
                        @if($producto->stock <= $producto->stock_minimo)
                            <span class="badge bg-warning text-dark px-3 py-2 fs-6" title="Requiere reposición">{{ $producto->stock }} {{ $producto->unidad_medida }} <i class="bi bi-exclamation-triangle-fill ms-1"></i></span>
                        @else
                            <span class="px-3 py-2 fs-6 fw-bold">{{ $producto->stock }} {{ $producto->unidad_medida }}</span>
                        @endif
                    </td>
                    <td class="text-center text-muted">{{ $producto->stock_minimo }}</td>
                    <td class="text-center">
                        @if($producto->estado)
                            <span class="badge bg-success bg-opacity-10 text-success border border-success px-2 py-1">Activo</span>
                        @else
                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-2 py-1">Inactivo</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#modalEditar{{ $producto->id }}">
                            <i class="bi bi-pencil-fill"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalEliminar{{ $producto->id }}">
                            <i class="bi bi-trash-fill"></i>
                        </button>
                    </td>
                </tr>

                <div class="modal fade" id="modalEditar{{ $producto->id }}" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow" style="border-radius: 16px;">
                            <div class="modal-header border-0 pb-0 px-4 pt-4">
                                <h5 class="modal-title fw-bold text-dark"><i class="bi bi-pencil-square text-primary me-2"></i>Editar Insumo</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body p-4 text-start">
                                <form action="{{ route('inventario.update', $producto->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-3">
                                        <label class="form-label fw-bold small text-muted">NOMBRE DEL PRODUCTO</label>
                                        <input type="text" class="form-control bg-light border-0" name="nombre" value="{{ $producto->nombre }}" required>
                                    </div>
                                    <div class="row g-3 mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold small text-muted">TIPO/CATEGORÍA</label>
                                            <select class="form-select bg-light border-0" name="tipo" required>
                                                <option value="Químico" {{ $producto->tipo == 'Químico' ? 'selected' : '' }}>Químico</option>
                                                <option value="Trampa" {{ $producto->tipo == 'Trampa' ? 'selected' : '' }}>Trampa</option>
                                                <option value="Material" {{ $producto->tipo == 'Material' ? 'selected' : '' }}>Material</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold small text-muted">UNIDAD DE MEDIDA</label>
                                            <select class="form-select bg-light border-0" name="unidad_medida" required>
                                                <option value="Litros" {{ $producto->unidad_medida == 'Litros' ? 'selected' : '' }}>Litros</option>
                                                <option value="Galones" {{ $producto->unidad_medida == 'Galones' ? 'selected' : '' }}>Galones</option>
                                                <option value="Unidades" {{ $producto->unidad_medida == 'Unidades' ? 'selected' : '' }}>Unidades</option>
                                                <option value="Kilos" {{ $producto->unidad_medida == 'Kilos' ? 'selected' : '' }}>Kilos</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row g-3 mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold small text-muted">STOCK ACTUAL</label>
                                            <input type="number" min="0" class="form-control bg-light border-0" name="stock" value="{{ $producto->stock }}" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold small text-muted">ALERTA MÍNIMA</label>
                                            <input type="number" min="0" class="form-control bg-light border-0" name="stock_minimo" value="{{ $producto->stock_minimo }}" required>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold small text-muted">ESTADO</label>
                                        <select class="form-select bg-light border-0" name="estado">
                                            <option value="1" {{ $producto->estado ? 'selected' : '' }}>Activo</option>
                                            <option value="0" {{ !$producto->estado ? 'selected' : '' }}>Inactivo</option>
                                        </select>
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

                <div class="modal fade" id="modalEliminar{{ $producto->id }}" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow" style="border-radius: 16px;">
                            <div class="modal-header border-0 pb-0 px-4 pt-4">
                                <h5 class="modal-title fw-bold text-danger"><i class="bi bi-exclamation-triangle-fill me-2"></i>Confirmar Eliminación</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body p-4 text-center">
                                <p class="mb-0">¿Estás seguro que deseas eliminar el producto <strong>{{ $producto->nombre }}</strong> del inventario?</p>
                                <p class="text-muted small mt-2">Esta acción no se puede deshacer.</p>
                                <form action="{{ route('inventario.destroy', $producto->id) }}" method="POST" class="mt-4 d-flex justify-content-center gap-2">
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
                    <td colspan="6" class="text-center py-5 text-muted">No se encontraron productos en el inventario.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="modalNuevoProducto" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow" style="border-radius: 16px;">
                <div class="modal-header border-0 pb-0 px-4 pt-4">
                    <h5 class="modal-title fw-bold text-dark"><i class="bi bi-box-seam text-primary me-2"></i>Registrar Insumo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="{{ route('inventario.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">NOMBRE DEL PRODUCTO</label>
                            <input type="text" class="form-control bg-light border-0" name="nombre" placeholder="Ej: Cipermetrina 20%" required>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted">TIPO/CATEGORÍA</label>
                                <select class="form-select bg-light border-0" name="tipo" required>
                                    <option value="Químico">Químico</option>
                                    <option value="Trampa">Trampa</option>
                                    <option value="Material">Material</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted">UNIDAD DE MEDIDA</label>
                                <select class="form-select bg-light border-0" name="unidad_medida" required>
                                    <option value="Litros">Litros</option>
                                    <option value="Galones">Galones</option>
                                    <option value="Unidades">Unidades</option>
                                    <option value="Kilos">Kilos</option>
                                </select>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted">STOCK INICIAL</label>
                                <input type="number" min="0" class="form-control bg-light border-0" name="stock" value="0" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted">ALERTA MÍNIMA</label>
                                <input type="number" min="0" class="form-control bg-light border-0" name="stock_minimo" value="5" required>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-custom">Guardar Producto</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection