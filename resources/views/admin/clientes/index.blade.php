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
            <h3 class="fw-bold mb-0 text-dark">Cartera de Clientes</h3>
            <small class="text-muted fw-bold">Gestión y registro comercial</small>
        </div>
        
        <div class="d-flex gap-3">
            <form action="{{ route('clientes.index') }}" method="GET" class="d-flex align-items-center">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="buscar" class="form-control border-start-0 ps-0" placeholder="Buscar por DNI/Nombre..." value="{{ $buscar ?? '' }}">
                    <button class="btn btn-outline-secondary" type="submit">Buscar</button>
                </div>
            </form>

            <button class="btn btn-custom" data-bs-toggle="modal" data-bs-target="#modalNuevoCliente">
                <i class="bi bi-plus-lg me-2"></i>Nuevo Cliente
            </button>
        </div>
    </div>

    <div class="card-panel border-0 shadow-sm p-0">
        <table class="table table-hover mb-0 align-middle">
            <thead class="bg-light text-uppercase" style="font-size: 0.8rem; color: #8392ab;">
                <tr>
                    <th class="py-3 px-4">Documento</th>
                    <th class="py-3">Nombre / Razón Social</th>
                    <th class="py-3">Tipo</th>
                    <th class="py-3">Contacto</th>
                    <th class="py-3 text-center">Estado</th>
                    <th class="py-3 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($clientes as $cliente)
                <tr>
                    <td class="px-4">
                        <span class="badge bg-secondary mb-1">{{ $cliente->tipo_documento }}</span><br>
                        <span class="fw-bold text-dark">{{ $cliente->documento }}</span>
                    </td>
                    <td class="fw-bold text-dark">{{ $cliente->nombre_razon_social }}</td>
                    <td>{{ $cliente->tipo_cliente }}</td>
                    <td>
                        <div><i class="bi bi-telephone-fill text-muted me-2 small"></i>{{ $cliente->telefono ?? 'N/A' }}</div>
                        <div><i class="bi bi-envelope-fill text-muted me-2 small"></i>{{ $cliente->email ?? 'N/A' }}</div>
                    </td>
                    <td class="text-center">
                        @if($cliente->estado)
                            <span class="badge bg-success bg-opacity-10 text-success border border-success px-2 py-1">Activo</span>
                        @else
                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-2 py-1">Inactivo</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#modalEditar{{ $cliente->id }}">
                            <i class="bi bi-pencil-fill"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalEliminar{{ $cliente->id }}">
                            <i class="bi bi-trash-fill"></i>
                        </button>
                    </td>
                </tr>

                <div class="modal fade" id="modalEditar{{ $cliente->id }}" tabindex="-1">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content border-0 shadow" style="border-radius: 16px;">
                            <div class="modal-header border-0 pb-0 px-4 pt-4">
                                <h5 class="modal-title fw-bold text-dark"><i class="bi bi-pencil-square text-primary me-2"></i>Editar Cliente</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body p-4 text-start">
                                <form action="{{ route('clientes.update', $cliente->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold small text-muted">TIPO DE PERSONERÍA</label>
                                            <select class="form-select bg-light border-0" name="tipo_cliente" required>
                                                <option value="Empresa" {{ $cliente->tipo_cliente == 'Empresa' ? 'selected' : '' }}>Empresa (Jurídica)</option>
                                                <option value="Persona Natural" {{ $cliente->tipo_cliente == 'Persona Natural' ? 'selected' : '' }}>Persona Natural</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold small text-muted">DOCUMENTO</label>
                                            <div class="input-group">
                                                <select class="form-select bg-light border-0" name="tipo_documento" style="max-width: 100px;">
                                                    <option value="RUC" {{ $cliente->tipo_documento == 'RUC' ? 'selected' : '' }}>RUC</option>
                                                    <option value="DNI" {{ $cliente->tipo_documento == 'DNI' ? 'selected' : '' }}>DNI</option>
                                                </select>
                                                <input type="text" class="form-control bg-light border-0" name="documento" value="{{ $cliente->documento }}" required>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label fw-bold small text-muted">NOMBRE O RAZÓN SOCIAL</label>
                                            <input type="text" class="form-control bg-light border-0" name="nombre_razon_social" value="{{ $cliente->nombre_razon_social }}" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold small text-muted">TELÉFONO / CELULAR</label>
                                            <input type="text" class="form-control bg-light border-0" name="telefono" value="{{ $cliente->telefono }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold small text-muted">CORREO ELECTRÓNICO</label>
                                            <input type="email" class="form-control bg-light border-0" name="email" value="{{ $cliente->email }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold small text-muted">DIRECCIÓN</label>
                                            <input type="text" class="form-control bg-light border-0" name="direccion" value="{{ $cliente->direccion }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold small text-muted">ESTADO</label>
                                            <select class="form-select bg-light border-0" name="estado">
                                                <option value="1" {{ $cliente->estado ? 'selected' : '' }}>Activo</option>
                                                <option value="0" {{ !$cliente->estado ? 'selected' : '' }}>Inactivo</option>
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

                <div class="modal fade" id="modalEliminar{{ $cliente->id }}" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow" style="border-radius: 16px;">
                            <div class="modal-header border-0 pb-0 px-4 pt-4">
                                <h5 class="modal-title fw-bold text-danger"><i class="bi bi-exclamation-triangle-fill me-2"></i>Confirmar Eliminación</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body p-4 text-center">
                                <p class="mb-0">¿Estás seguro que deseas eliminar a <strong>{{ $cliente->nombre_razon_social }}</strong>?</p>
                                <p class="text-muted small mt-2">Esta acción no se puede deshacer.</p>
                                <form action="{{ route('clientes.destroy', $cliente->id) }}" method="POST" class="mt-4 d-flex justify-content-center gap-2">
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
                    <td colspan="6" class="text-center py-5 text-muted">Aún no hay clientes registrados en el sistema.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="modalNuevoCliente" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow" style="border-radius: 16px;">
                <div class="modal-header border-0 pb-0 px-4 pt-4">
                    <h5 class="modal-title fw-bold text-dark"><i class="bi bi-person-plus-fill text-primary me-2"></i>Registrar Nuevo Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="{{ route('clientes.store') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted">TIPO DE PERSONERÍA</label>
                                <select class="form-select bg-light border-0" name="tipo_cliente" id="tipo_cliente" onchange="ajustarDocumento()" required>
                                    <option value="Empresa">Empresa (Jurídica)</option>
                                    <option value="Persona Natural">Persona Natural</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted">DOCUMENTO</label>
                                <div class="input-group">
                                    <select class="form-select bg-light border-0" name="tipo_documento" id="tipo_documento" style="max-width: 100px;">
                                        <option value="RUC">RUC</option>
                                        <option value="DNI">DNI</option>
                                    </select>
                                    <input type="text" class="form-control bg-light border-0" name="documento" id="documento" placeholder="Ingrese nro..." required>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold small text-muted">NOMBRE O RAZÓN SOCIAL</label>
                                <input type="text" class="form-control bg-light border-0" name="nombre_razon_social" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted">TELÉFONO / CELULAR</label>
                                <input type="text" class="form-control bg-light border-0" name="telefono">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted">CORREO ELECTRÓNICO</label>
                                <input type="email" class="form-control bg-light border-0" name="email">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold small text-muted">DIRECCIÓN</label>
                                <input type="text" class="form-control bg-light border-0" name="direccion">
                            </div>
                        </div>
                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-custom">Guardar Cliente</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function ajustarDocumento() {
        const tipoCliente = document.getElementById('tipo_cliente').value;
        const tipoDoc = document.getElementById('tipo_documento');
        if(tipoCliente === 'Empresa') {
            tipoDoc.value = 'RUC';
        } else {
            tipoDoc.value = 'DNI';
        }
    }
</script>
@endpush