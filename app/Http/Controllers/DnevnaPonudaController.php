<?php

namespace App\Http\Controllers;

use App\Http\Requests\DnevnaPonudaStoreRequest;
use App\Http\Requests\DnevnaPonudaUpdateRequest;
use App\Models\DnevnaPonuda;
use App\Models\Jelo;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class DnevnaPonudaController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('DnevnaPonuda/Index', [
            'ponude' => DnevnaPonuda::with('jelos')->orderByDesc('datum')->get(),
            'jelos' => Jelo::all(['id', 'naziv_jela', 'cena']),
        ]);
    }


    public function store(DnevnaPonudaStoreRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $ponuda = DnevnaPonuda::create([
            'datum' => $validated['datum'],
            'aktivna' => $validated['aktivna'] ?? true,
        ]);

        foreach ($validated['jela'] as $jelo) {
            $ponuda->jelos()->attach($jelo['id'], [
                'cena' => $jelo['cena'],
                'kolicina_dostupna' => $jelo['kolicina_dostupna'] ?? null,
            ]);
        }

        return redirect()->route('dnevna-ponuda.index');
    }


    public function update(DnevnaPonudaUpdateRequest $request, DnevnaPonuda $dnevnaPonuda): RedirectResponse
    {
        $validated = $request->validated();

        $dnevnaPonuda->update([
            'datum' => $validated['datum'],
            'aktivna' => $validated['aktivna'] ?? true,
        ]);

        $sync = [];
        foreach ($validated['jela'] as $jelo) {
            $sync[$jelo['id']] = [
                'cena' => $jelo['cena'],
                'kolicina_dostupna' => $jelo['kolicina_dostupna'] ?? null,
            ];
        }
        $dnevnaPonuda->jelos()->sync($sync);

        return redirect()->route('dnevna-ponuda.index');
    }

    public function destroy(DnevnaPonuda $dnevnaPonuda): RedirectResponse
    {
        if (!auth()->check() || auth()->user()->role->naziv_role !== 'Admin') {
            abort(403, 'Nemate dozvolu za pristup.');
        }

        $dnevnaPonuda->delete();

        return redirect()->route('dnevna-ponuda.index');
    }
}