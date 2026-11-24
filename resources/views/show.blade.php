<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Detail poruchy #{{ $porucha->IDPorucha }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded shadow mb-6">
        <a href="{{ route('poruchy.index') }}" class="text-blue-600 hover:underline mb-4 inline-block">&larr; Zpět na přehled</a>

        <h1 class="text-2xl font-bold mb-4">Detail poruchy #{{ $porucha->IDPorucha }}</h1>

        <div class="grid grid-cols-2 gap-4 mb-6 bg-gray-50 p-4 rounded border">
            <div><strong>Status:</strong> {{ $porucha->Status_poruchy }}</div>
            <div><strong>Priorita:</strong> {{ $porucha->Priorita }}</div>
            <div><strong>Lokace:</strong> {{ $porucha->Lokace }}</div>
            <div><strong>Kategorie:</strong> {{ $porucha->kategorie->Nazev ?? 'Nespecifikováno' }}</div>
            <div><strong>Datum nahlášení:</strong> {{ $porucha->Datum_nahlaseni }}</div>
            <div><strong>Oznamovatel:</strong> {{ $porucha->uzivatel->Jmeno ?? '' }} {{ $porucha->uzivatel->Prijmeni ?? 'Neznámý' }}</div>
        </div>

        <h2 class="text-xl font-bold mb-3">Záznamy řešení</h2>
        @forelse($porucha->reseni as $r)
            <div class="border-l-4 border-blue-500 bg-gray-50 p-3 mb-3">
                <div class="text-xs text-gray-500 mb-1">
                    Zahájeno: {{ $r->Datum_zahajeni ?? 'Nezadáno' }} | Dokončeno: {{ $r->Datum_dokonceni ?? 'V řešení' }}
                </div>
                <p class="text-gray-800">{{ $r->Popis_reseni }}</p>
            </div>
        @empty
            <p class="text-gray-500 italic mb-4">K této poruše zatím nebylo zapsáno žádné řešení.</p>
        @endforelse

        <!-- Formulář pro zapsání řešení technika -->
        <hr class="my-6">
        <h3 class="text-lg font-bold mb-3">Přidat záznam o řešení</h3>
        <form action="{{ route('reseni.store', $porucha->IDPorucha) }}" method="POST">
            @csrf
            <div class="grid grid-cols-2 gap-4 mb-3">
                <div>
                    <label class="block text-sm font-medium">Datum zahájení</label>
                    <input type="datetime-local" name="Datum_zahajeni" class="w-full border p-2 rounded">
                </div>
                <div>
                    <label class="block text-sm font-medium">Datum dokončení</label>
                    <input type="datetime-local" name="Datum_dokonceni" class="w-full border p-2 rounded">
                </div>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium">Popis řešení</label>
                <textarea name="Popis_reseni" rows="3" class="w-full border p-2 rounded" required placeholder="Popište provedené úkony..."></textarea>
            </div>
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                Uložit řešení
            </button>
        </form>
    </div>
</body>
</html>
