<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CitaRequest extends FormRequest
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
			'mascota_id' => 'required',
			'propietario_id' => 'required',
			'fecha_hora' => 'required',
			'motivo' => 'required|string',
			'estado' => 'required',
			'notas' => 'string',
			'diagnostico' => 'string',
			'receta' => 'string',
        ];
    }
}
