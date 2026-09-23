<?php

namespace App\Http\Controllers;

use App\Models\DnevnaPonuda;
use App\Models\Jelo;
use Inertia\Inertia;
use Inertia\Response;

class MeniController extends Controller
{
    public function index(): Response
    {
        $stalnaJela = Jelo::where('stalna_ponuda', true)->get()->map(fn ($j) => [
            'id' => $j->id,
            'naziv_jela' => $j->naziv_jela,
            'opis' => $j->opis,
            'cena' => $j->cena,
            'izvor' => 'stalna',
        ]);

        $dnevnaPonuda = DnevnaPonuda::where('datum', now()->toDateString())
            ->where('aktivna', true)
            ->with('jelos')
            ->first();

        $dnevnaJela = ($dnevnaPonuda?->jelos ?? collect())->map(fn ($j) => [
            'id' => $j->id,
            'naziv_jela' => $j->naziv_jela,
            'opis' => $j->opis,
            'cena' => $j->pivot->cena,
            'izvor' => 'dnevna',
        ]);

        return Inertia::render('Meni/Index', [
            'ponuda' => $stalnaJela->concat($dnevnaJela)->values(),
            'title' => 'Meni',
        ]);
    }
}