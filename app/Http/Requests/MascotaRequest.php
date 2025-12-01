<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MascotaRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
			'propietario_id' => 'required',
			'nombre' => 'required|string',
			'especie' => 'required',
			'raza' => 'string',
			'color' => 'string',
			'foto' => 'string',
			'notas' => 'string',
			'activo' => 'required',
        ];
    }
}
