<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DnevnaPonudaUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role->naziv_role === 'Admin';
    }

    public function rules(): array
    {
        return [
            'datum' => [
                'required',
                'date',
                Rule::unique('dnevna_ponudas', 'datum')->ignore($this->route('dnevnaPonuda')),
            ],
            'aktivna' => 'boolean',
            'jela' => 'required|array|min:1',
            'jela.*.id' => 'required|exists:jelos,id',
            'jela.*.cena' => 'required|integer|min:0',
            'jela.*.kolicina_dostupna' => 'nullable|integer|min:0',
        ];
    }
}