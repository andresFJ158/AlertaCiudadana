<?php

namespace App\Http\Controllers\Api;

use App\Actions\CreateIncidenciaAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreIncidenciaRequest;
use App\Http\Resources\IncidenciaResource;
use App\Models\Incidencia;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class IncidenciaController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $incidencias = Incidencia::query()
            ->with('categoria')
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get();

        return IncidenciaResource::collection($incidencias);
    }

    public function store(
        StoreIncidenciaRequest $request,
        CreateIncidenciaAction $createIncidencia,
    ): JsonResponse
    {
        $incidencia = $createIncidencia->handle(
            $request->safe()->only(['categoria_id', 'descripcion', 'latitud', 'longitud']),
            $request->file('foto'),
        );

        return (new IncidenciaResource($incidencia->load('categoria')))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Incidencia $incidencia): IncidenciaResource
    {
        return new IncidenciaResource($incidencia->load('categoria'));
    }
}
