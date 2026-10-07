<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class StoreIncidenciaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'categoria_id' => ['required', 'integer', 'exists:categorias,id'],
            'descripcion' => ['required', 'string', 'max:1000'],
            'latitud' => ['required', 'numeric', 'between:-90,90'],
            'longitud' => ['required', 'numeric', 'between:-180,180'],
            'foto' => ['nullable', File::image()->types(['jpg', 'jpeg', 'png'])->max('5mb')],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'categoria_id.required' => 'La categoría es obligatoria.',
            'categoria_id.exists' => 'La categoría seleccionada no existe.',
            'descripcion.required' => 'La descripción es obligatoria.',
            'descripcion.max' => 'La descripción no puede superar 1.000 caracteres.',
            'latitud.required' => 'La latitud es obligatoria.',
            'latitud.between' => 'La latitud debe estar entre -90 y 90.',
            'longitud.required' => 'La longitud es obligatoria.',
            'longitud.between' => 'La longitud debe estar entre -180 y 180.',
            'foto.image' => 'La foto debe ser una imagen.',
            'foto.mimes' => 'La foto debe ser JPEG o PNG.',
            'foto.max' => 'La foto no puede superar 5 MB.',
        ];
    }
}
