<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoriaResource;
use App\Models\Categoria;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CategoriaController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return CategoriaResource::collection(
            Categoria::query()->orderBy('nombre')->get(),
        );
    }
}
