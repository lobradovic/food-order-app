<?php

namespace App\Http\Controllers;

use App\Http\Requests\RezervacijaStoreRequest;
use App\Models\DnevnaPonuda;
use App\Models\Jelo;
use App\Models\Rezervacija;
use App\Models\Status;
use Inertia\Inertia;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RezervacijaController extends Controller
{

    public function index()
    {
        return Inertia::render('Rezervacija/Index', [
            'rezervacije' => Rezervacija::with(['user', 'status', 'stavkas.jelo'])
                ->orderByDesc('datum')
                ->orderByDesc('termin')
                ->get(),
            'statusi' => Status::all(['id', 'naziv_statusa']),
            'title' => 'Rezervacije',
        ]);
    }

    public function store(RezervacijaStoreRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $dnevnaPonuda = DnevnaPonuda::where('datum', now()->toDateString())
            ->where('aktivna', true)
            ->with('jelos')
            ->first();

        $dnevnaPoId = $dnevnaPonuda?->jelos->keyBy('id') ?? collect();

        $rezervacija = Rezervacija::create([
            'datum' => now()->toDateString(),
            'termin' => $validated['termin'],
            'adresa' => $validated['adresa'],
            'napomena' => $validated['napomena'] ?? null,
            'user_id' => auth()->id(),
            'status_id' => Status::where('naziv_statusa', 'Evidentirana')->value('id'),
        ]);

        foreach ($validated['stavke'] as $stavka) {
            $jelo = Jelo::findOrFail($stavka['jelo_id']);
            $override = $dnevnaPoId->get($jelo->id);

            if ($override) {
                $cena = $override->pivot->cena;
            } elseif ($jelo->stalna_ponuda) {
                $cena = $jelo->cena;
            } else {
                abort(422, "{$jelo->naziv_jela} nije dostupno danas.");
            }

            $rezervacija->stavkas()->create([
                'jelo_id' => $jelo->id,
                'kolicina' => $stavka['kolicina'],
                'trenutna_cena' => $cena,
            ]);
        }

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => 'Porudžbina je poslata.',
        ]);

        return redirect()->route('home')->with('success', 'Porudžbina je poslata.');
    }

    public function promeniStatus(Request $request, Rezervacija $rezervacija): RedirectResponse
    {
        $validated = $request->validate([
            'status_id' => 'required|exists:statuses,id',
        ]);

        $rezervacija->update(['status_id' => $validated['status_id']]);

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => 'Porudžbina je poslata.',
        ]);
        
        return redirect()->route('rezervacija.index');
    }

    public function moje()
    {
        return Inertia::render('Rezervacija/Moje', [
            'rezervacije' => Rezervacija::with(['status', 'stavkas.jelo'])
                ->where('user_id', auth()->id())
                ->orderByDesc('datum')
                ->orderByDesc('termin')
                ->get(),
            'title' => 'Moje porudžbine',
        ]);
    }
}
