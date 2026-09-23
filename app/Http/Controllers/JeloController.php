<?php

namespace App\Http\Controllers;


use App\Http\Requests\JeloStoreRequest;
use App\Http\Requests\JeloUpdateRequest;
use App\Models\Jelo;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class JeloController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Jelo/Index', [
            'jela' => Jelo::orderBy('naziv_jela')->get(),
            'title' => 'Jela',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(JeloStoreRequest $request):RedirectResponse
    {
        Jelo::create($request->validated());

        return redirect()->route('jelo.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(JeloUpdateRequest $request, Jelo $jelo):RedirectResponse
    {
        $jelo->update($request->validated());

        return redirect()->route('jelo.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Jelo $jelo)
    {
        $jelo->delete();

        return redirect->route('jelo.index');
    }
}
