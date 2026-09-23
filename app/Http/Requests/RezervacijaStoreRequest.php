<?php

namespace App\Http\Requests;

use App\Rules\ValidanTermin;
use Illuminate\Foundation\Http\FormRequest;

class RezervacijaStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'adresa' => 'required|string|max:255',
            'napomena' => 'nullable|string|max:1000',
            'termin' => ['required', 'integer', new ValidanTermin()],
            'stavke' => 'required|array|min:1',
            'stavke.*.jelo_id' => 'required|exists:jelos,id',
            'stavke.*.kolicina' => 'required|integer|min:1',
        ];
    }
}