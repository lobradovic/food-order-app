<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DnevnaPonudaController;
use App\Http\Controllers\JeloController;
use App\Http\Controllers\MeniController;
use App\Http\Controllers\RezervacijaController;
use App\Http\Controllers\PDFController;
use Inertia\Inertia;

//Route::inertia('/', 'Welcome')->name('home');
Route::get('/', [MeniController::class, 'index'])->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
});

Route::middleware(['auth','admin'])->group(function(){
    Route::resource('dnevna-ponuda', DnevnaPonudaController::class)
        ->parameters(['dnevna-ponuda' => 'dnevnaPonuda'])
        ->only(['index', 'store', 'update', 'destroy']);
    Route::resource('jelo', JeloController::class)
        ->only(['index', 'store', 'update', 'destroy']);
    Route::get('/rezervacija', [RezervacijaController::class, 'index'])->name('rezervacija.index');
    Route::patch('/rezervacija/{rezervacija}/status', [RezervacijaController::class, 'promeniStatus'])
        ->name('rezervacija.status');
    Route::get('pdf/orders', [PDFController::class, 'generatePDF'])->name('pdf.orders');
    Route::get('pdf/nalepnice', [PDFController::class, 'stampajNalepnice'])
        ->middleware(['auth', 'admin'])
        ->name('nalepnice.stampaj');
});
Route::post('/rezervacija', [RezervacijaController::class, 'store'])->middleware('auth')->name('rezervacija.store');

Route::get('/korpa', fn () => Inertia::render('Korpa/Index', ['title' => 'Korpa']))->name('korpa.index');
//Route::get('/meni', [MeniController::class, 'index'])->name('meni.index');

Route::get('/moje-porudzbine', [RezervacijaController::class, 'moje'])
->middleware('auth')
->name('rezervacija.moje');

require __DIR__.'/settings.php';
