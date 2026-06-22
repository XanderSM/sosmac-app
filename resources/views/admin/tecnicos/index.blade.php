@extends('admin.layouts.app')

@section('content')
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm"><i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger border-0 shadow-sm">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> No se pudo registrar el técnico:
            <ul class="mb-0 mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0 text-dark">Gestión de Técnicos Operativos</h3>
            <small class="text-muted fw-bold">Personal autorizado para campo</small>
        </div>
        <button class="btn btn-custom" data-bs-toggle="modal" data-bs-target="#modalNuevoTecnico">
            <i class="bi bi-person-plus-fill me-2"></i>Registrar Técnico
        </button>
    </div>

    <div class="card-panel border-0 shadow-sm p-0">
        <table class="table table-hover mb-0 align-middle">
            <thead class="bg-light text-uppercase" style="font-size: 0.8rem; color: #8392ab;">
                <tr>
                    <th class="py-3 px-4">Nombre Completo</th>
                    <th class="py-3">Correo (Usuario)</th>
                    <th class="py-3 text-center">Rol</th>
                    <th class="py-3 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tecnicos as $tecnico)
                <tr>
                    <td class="px-4 fw-bold text-dark">{{ $tecnico->name }}</td>
                    <td>{{ $tecnico->email }}</td>
                    <td class="text-center"><span class="badge bg-primary">Técnico Operativo</span></td>
                    <td class="text-center">
                        <form action="{{ route('tecnicos.destroy', $tecnico->id) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar"><i class="bi bi-trash-fill"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center py-5 text-muted">No hay técnicos registrados en el sistema.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="modalNuevoTecnico" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow" style="border-radius: 16px;">
                <div class="modal-header border-0 pb-0 px-4 pt-4">
                    <h5 class="modal-title fw-bold text-dark"><i class="bi bi-person-plus text-primary me-2"></i>Registrar Técnico</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="{{ route('tecnicos.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">NOMBRE COMPLETO</label>
                            <input type="text" class="form-control bg-light border-0" name="name" value="{{ old('name') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">CORREO (USUARIO DE ACCESO)</label>
                            <input type="email" class="form-control bg-light border-0" name="email" value="{{ old('email') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">CONTRASEÑA TEMPORAL</label>
                            <input type="password" class="form-control bg-light border-0" name="password" required>
                        </div>
                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-custom">Guardar Técnico</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection