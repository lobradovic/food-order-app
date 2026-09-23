<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JeloUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role->naziv_role === 'Admin';
    }

    public function rules(): array
    {
        return [
            'naziv_jela' => 'required|string|max:255',
            'cena' => 'required|integer|min:0',
            'opis' => 'nullable|string',
            'stalna_ponuda' => 'boolean',
        ];
    }
}