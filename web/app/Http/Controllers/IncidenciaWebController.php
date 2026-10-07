<?php

namespace App\Http\Controllers;

use App\Actions\CreateIncidenciaAction;
use App\Http\Requests\StoreIncidenciaRequest;
use App\Models\Categoria;
use App\Models\Incidencia;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class IncidenciaWebController extends Controller
{
    public function index(): View
    {
        $incidencias = Incidencia::query()
            ->with('categoria')
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(10);

        return view('incidencias.index', ['incidencias' => $incidencias]);
    }

    public function create(): View
    {
        return view('incidencias.create', [
            'categorias' => Categoria::query()->orderBy('nombre')->get(),
        ]);
    }

    public function store(
        StoreIncidenciaRequest $request,
        CreateIncidenciaAction $createIncidencia,
    ): RedirectResponse {
        $incidencia = $createIncidencia->handle(
            $request->safe()->only(['categoria_id', 'descripcion', 'latitud', 'longitud']),
            $request->file('foto'),
        );

        return redirect()
            ->route('alertas.index')
            ->with('success', "Alerta #{$incidencia->id} registrada correctamente.");
    }
}
