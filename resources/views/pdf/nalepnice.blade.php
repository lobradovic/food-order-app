<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 0; }
        body { margin: 0; padding: 0; }
        table.grid {
            width: 210mm;
            border-collapse: collapse;
        }
        table.grid td {
            width: 52.5mm;
            height: 29.7mm;
            text-align: center;
            vertical-align: middle;
            font-family: Arial, sans-serif;
            font-size: 10pt;
            padding: 0;
        }
        .naslov { font-weight: bold; font-size: 11pt; }
        .jelo { font-weight: bold; }
        .napomena { font-size: 8pt; }
    </style>
</head>
<body>
    @foreach ($arci as $arkIndex => $slotovi)
        @if ($arkIndex > 0)
            <div style="page-break-before: always;"></div>
        @endif
        <table class="grid">
            @for ($red = 0; $red < 10; $red++)
                <tr>
                    @for ($kol = 0; $kol < 4; $kol++)
                        @php $stavka = $slotovi[$red * 4 + $kol]; @endphp
                        <td>
                            @if ($stavka)
                                <div class="naslov">{{ $stavka['ime'] }}</div>
                                <div class="jelo">{{ $stavka['jelo'] }}</div>
                                @if ($stavka['napomena'])
                                    <div class="napomena">{{ $stavka['napomena'] }}</div>
                                @endif
                            @endif
                        </td>
                    @endfor
                </tr>
            @endfor
        </table>
    @endforeach
</body>
</html>