<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11pt;
            color: #1c1c1c;
        }

        h1 {
            font-size: 16pt;
            border-bottom: 2px solid #1c1c1c;
            padding-bottom: 8px;
            margin-bottom: 20px;
        }

        .porudzbina {
            margin-bottom: 20px;
            page-break-inside: avoid;
        }

        .porudzbina h3 {
            font-size: 13pt;
            margin: 0 0 4px 0;
        }

        .meta {
            font-size: 9pt;
            color: #555;
            margin-bottom: 6px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table.stavke th,
        table.stavke td {
            text-align: left;
            padding: 4px 6px;
            border-bottom: 1px solid #ddd;
            font-size: 10pt;
        }

        table.stavke th {
            border-bottom: 2px solid #1c1c1c;
            font-weight: bold;
        }

        .separator {
            border: none;
            border-top: 1px dashed #aaa;
            margin: 16px 0;
        }

        .summary-naslov {
            font-size: 14pt;
            margin-bottom: 10px;
        }

        table.summary th,
        table.summary td {
            padding: 5px 6px;
            font-size: 10pt;
        }

        table.summary thead th {
            border-bottom: 1px solid #1c1c1c;
            text-align: left;
        }

        table.summary tbody td {
            border-bottom: 1px solid #eee;
        }

        table.summary tfoot td {
            border-top: 1px solid #1c1c1c;
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>
    <h1>{{ $title }}</h1>

    @foreach ($orders as $o)
        <div class="porudzbina">
            <h3>{{ $o->user->name }}</h3>
            <div class="meta">
                Datum: {{ $o->datum }}
                @if ($o->termin) &middot; Termin: {{ $o->termin }}:00 @endif
                &middot; Adresa: {{ $o->adresa }}
                @if ($o->napomena) &middot; Napomena: {{ $o->napomena }} @endif
            </div>

            <table class="stavke">
                <thead>
                    <tr>
                        <th>Naziv artikla</th>
                        <th class="text-right">Količina</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($o->stavkas as $s)
                        <tr>
                            <td>{{ $s->jelo->naziv_jela }}</td>
                            <td class="text-right">{{ $s->kolicina }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @unless ($loop->last)
            <hr class="separator">
        @endunless
    @endforeach

    <div style="page-break-before: always;">
        <h2 class="summary-naslov">Pregled po jelima</h2>
        <table class="summary">
            <thead>
                <tr>
                    <th>Jelo</th>
                    <th class="text-right">Količina</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($summary as $nazivJela => $kolicina)
                    <tr>
                        <td>{{ $nazivJela }}</td>
                        <td class="text-right">{{ $kolicina }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td>Ukupno porcija</td>
                    <td class="text-right">{{ $summary->sum() }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</body>
</html>