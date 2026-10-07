@extends('adminlte::page')

@section('title', 'Panel de alertas')

@section('content_header')
    <x-adminlte-content-header title="Panel de alertas" :breadcrumbs="[['label' => 'Alertas']]" />
@stop

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    @endif

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card text-bg-primary shadow-sm h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-uppercase small opacity-75">Alertas registradas</div>
                        <div class="display-6 fw-semibold">{{ $incidencias->total() }}</div>
                    </div>
                    <i class="bi bi-megaphone display-4 opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-md-8 d-flex align-items-center justify-content-md-end">
            <a href="{{ route('alertas.create') }}" class="btn btn-danger btn-lg">
                <i class="bi bi-plus-circle me-2"></i>Registrar nueva alerta
            </a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title mb-0"><i class="bi bi-list-ul me-2"></i>Incidencias recientes</h3>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Categoría</th>
                            <th scope="col">Descripción</th>
                            <th scope="col">Ubicación</th>
                            <th scope="col">Estado</th>
                            <th scope="col">Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($incidencias as $incidencia)
                            <tr>
                                <td class="fw-semibold">#{{ $incidencia->id }}</td>
                                <td>
                                    <span class="badge text-bg-{{ $incidencia->categoria->color }}">
                                        <i class="{{ $incidencia->categoria->icono }} me-1"></i>
                                        {{ $incidencia->categoria->nombre }}
                                    </span>
                                </td>
                                <td>{{ $incidencia->descripcion }}</td>
                                <td class="text-nowrap">
                                    <small>{{ $incidencia->latitud }}, {{ $incidencia->longitud }}</small>
                                </td>
                                <td><span class="badge text-bg-info">{{ ucfirst($incidencia->estado->value) }}</span></td>
                                <td class="text-nowrap">{{ $incidencia->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-body-secondary">
                                    <i class="bi bi-inbox display-5 d-block mb-2"></i>
                                    Todavía no hay alertas registradas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($incidencias->hasPages())
            <div class="card-footer">
                {{ $incidencias->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
@stop

@section('footer')
    <strong>Alerta Ciudadana</strong> · Iteración 0 · Andrés
@stop
