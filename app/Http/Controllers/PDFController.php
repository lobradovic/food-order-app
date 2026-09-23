<?php

namespace App\Http\Controllers;
use App\Models\Rezervacija;
use App\Models\StanjeNalepnica;
use Barryvdh\DomPDF\Facade\Pdf; 

use Illuminate\Http\Request;

class PDFController extends Controller
{
    private const PO_ARKU=40;

    public function generatePDF()
    {
        date_default_timezone_set('Europe/Berlin');

        $orders = Rezervacija::with(['user', 'stavkas.jelo'])->get();

        $summary = $orders
            ->flatMap(fn ($rezervacija) => $rezervacija->stavkas)
            ->groupBy(fn ($stavka) => $stavka->jelo?->naziv_jela ?? 'Nepoznato jelo')
            ->map(fn ($grupa) => $grupa->sum('kolicina'))
            ->sortDesc();

        $data = [
            'title' => 'Spisak narudzbina - ' . date('d.m.Y H:i'),
            'orders' => $orders,
            'summary' => $summary,
        ];

        $pdf = PDF::loadView('pdf.workOrders', $data);

        return $pdf->stream('narudzbenica.pdf');
    }

    public function stampajNalepnice()
    {
        $rezervacije = Rezervacija::with(['user', 'stavkas.jelo'])
            ->whereHas('status', fn ($q) => $q->whereNotIn('naziv_statusa', ['Otkazana', 'Zavrsena']))
            ->orderBy('termin')
            ->orderBy('created_at')
            ->get();

        // Ravna lista nalepnica — jedna po komadu, iz SVIH rezervacija, redom
        $stavke = [];
        foreach ($rezervacije as $rezervacija) {
            foreach ($rezervacija->stavkas as $stavka) {
                for ($i = 0; $i < $stavka->kolicina; $i++) {
                    $stavke[] = [
                        'ime' => $rezervacija->user?->name,
                        'jelo' => $stavka->jelo?->naziv_jela,
                        'napomena' => $rezervacija->napomena,
                    ];
                }
            }
        }

        if (empty($stavke)) {
            abort(404, 'Nema aktivnih porudžbina za štampu.');
        }

        $stanje = StanjeNalepnica::lockForUpdate()->firstOrFail();
        $pocetnaPozicija = $stanje->trenutna_pozicija;

        $novaPozicija = (($pocetnaPozicija - 1 + count($stavke)) % self::PO_ARKU) + 1;
        $stanje->update(['trenutna_pozicija' => $novaPozicija]);

        $ukupnoSlotova = $pocetnaPozicija - 1 + count($stavke);
        $brojArkova = (int) ceil($ukupnoSlotova / self::PO_ARKU) ?: 1;

        $arci = [];
        $indeksStavke = 0;

        for ($a = 0; $a < $brojArkova; $a++) {
            $slotovi = array_fill(0, self::PO_ARKU, null);
            $pocetnaNaArku = $a === 0 ? $pocetnaPozicija : 1;

            for ($pozicija = $pocetnaNaArku; $pozicija <= self::PO_ARKU && $indeksStavke < count($stavke); $pozicija++) {
                $slotovi[$pozicija - 1] = $stavke[$indeksStavke];
                $indeksStavke++;
            }

            $arci[] = $slotovi;
        }

        $pdf = PDF::loadView('pdf.nalepnice', ['arci' => $arci])
            ->setPaper('a4', 'portrait');

        return $pdf->stream('nalepnice.pdf');
    }
}
